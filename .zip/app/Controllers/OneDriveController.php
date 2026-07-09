<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class OneDriveController extends BaseController
{
    private $clientId = 'TU_CLIENT_ID'; // Reemplazar con tu client_id
    private $clientSecret = 'TU_CLIENT_SECRET'; // Reemplazar con tu client_secret
    private $tenantId = 'TU_TENANT_ID'; // Reemplazar con tu tenant_id
    private $redirectUri = ''; // Se llena en el constructor

    public function __construct()
    {
        $this->redirectUri = base_url('admin/onedrive/callback');
    }

    /**
     * Redirige al usuario a Microsoft para autenticación
     */
    public function connect()
    {
        $authUrl = $this->getAuthUrl();
        return redirect()->to($authUrl);
    }

    /**
     * Obtiene la URL de autorización para redirigir al usuario a Microsoft
     */
    private function getAuthUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'Files.ReadWrite.All offline_access',
            'response_mode' => 'query'
        ];

        return 'https://login.microsoftonline.com/' . $this->tenantId . '/oauth2/v2.0/authorize?' . http_build_query($params);
    }

    /**
     * Maneja el callback de autorización de Microsoft
     */
    public function callback()
    {
        $code = $this->request->getVar('code');
        $error = $this->request->getVar('error');

        if ($error) {
            return redirect()->to('admin/backup')->with('error', 'Error de autenticación: ' . $error);
        }

        if (!$code) {
            return redirect()->to('admin/backup')->with('error', 'Código de autorización no recibido');
        }

        // Intercambiar código por token
        $token = $this->getAccessToken($code);

        if ($token) {
            // Guardar token en sesión o base de datos
            session()->set('onedrive_token', $token['access_token']);
            session()->set('onedrive_refresh_token', $token['refresh_token']);
            session()->set('onedrive_expires_at', time() + $token['expires_in']);

            return redirect()->to('admin/backup')->with('success', 'Conexión con OneDrive realizada exitosamente');
        }

        return redirect()->to('admin/backup')->with('error', 'No se pudo obtener el token de acceso');
    }

    /**
     * Verifica si el usuario está conectado a OneDrive
     */
    public function checkConnection()
    {
        $this->response->setContentType('application/json');
        $connected = session()->has('onedrive_token');
        return $this->response->setJSON(['connected' => $connected]);
    }

    /**
     * Intercambia un código de autorización por un token de acceso
     */
    private function getAccessToken($code)
    {
        $tokenUrl = 'https://login.microsoftonline.com/' . $this->tenantId . '/oauth2/v2.0/token';

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
            'scope' => 'Files.ReadWrite.All offline_access'
        ];

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data ?? null;
    }

    /**
     * Sube un archivo a OneDrive
     */
    public function uploadFileToOneDrive($localFilePath, $oneDriveFileName)
    {
        $accessToken = session()->get('onedrive_token');

        if (!$accessToken) {
            return ['success' => false, 'message' => 'No hay conexión con OneDrive'];
        }

        // Verificar si el token ha expirado
        if (session()->get('onedrive_expires_at') < time()) {
            $accessToken = $this->refreshAccessToken();
            if (!$accessToken) {
                return ['success' => false, 'message' => 'No se pudo renovar el token de OneDrive'];
            }
        }

        // Leer contenido del archivo
        if (!file_exists($localFilePath)) {
            return ['success' => false, 'message' => 'Archivo local no encontrado'];
        }

        $fileContents = file_get_contents($localFilePath);
        $fileSize = filesize($localFilePath);

        // URL de carga a OneDrive
        $uploadUrl = 'https://graph.microsoft.com/v1.0/me/drive/root:/Backups/' . urlencode($oneDriveFileName) . ':/content';

        $ch = curl_init($uploadUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/octet-stream',
            'Content-Length: ' . $fileSize
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $data = json_decode($response, true);
            return ['success' => true, 'message' => 'Archivo subido exitosamente', 'data' => $data];
        }

        return ['success' => false, 'message' => 'Error al subir archivo: ' . $response];
    }

    /**
     * Renueva el token de acceso usando el refresh token
     */
    private function refreshAccessToken()
    {
        $refreshToken = session()->get('onedrive_refresh_token');

        if (!$refreshToken) {
            return null;
        }

        $tokenUrl = 'https://login.microsoftonline.com/' . $this->tenantId . '/oauth2/v2.0/token';

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
            'scope' => 'Files.ReadWrite.All offline_access'
        ];

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($data && isset($data['access_token'])) {
            session()->set('onedrive_token', $data['access_token']);
            session()->set('onedrive_expires_at', time() + $data['expires_in']);
            return $data['access_token'];
        }

        return null;
    }

    /**
     * Desconecta de OneDrive (limpia los tokens de sesión)
     */
    public function disconnect()
    {
        session()->remove('onedrive_token');
        session()->remove('onedrive_refresh_token');
        session()->remove('onedrive_expires_at');

        return redirect()->to('admin/backup')->with('success', 'Desconectado de OneDrive');
    }

    /**
     * Verifica si el usuario está conectado a OneDrive
     */
    public function isConnected()
    {
        return session()->has('onedrive_token');
    }
}
