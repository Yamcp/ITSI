<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class OneDriveController extends BaseController
{
    private $clientId = 'TU_CLIENT_ID';
    private $clientSecret = 'TU_CLIENT_SECRET';
    private $tenantId = 'TU_TENANT_ID';
    private $redirectUri;
    private $graphApiUrl = 'https://graph.microsoft.com/v1.0';

    public function __construct()
    {
        $this->redirectUri = base_url('admin/onedrive/callback');
    }

    /**
     * Inicia el flujo de autenticación con Microsoft
     */
    public function connect()
    {
        return redirect()->to($this->getAuthUrl());
    }

    /**
     * Maneja el callback de Microsoft con el código de autorización
     */
    public function callback()
    {
        $code = $this->request->getVar('code');
        $error = $this->request->getVar('error');

        // Si hay error, redirige de vuelta
        if ($error) {
            session()->setFlashdata('error', 'Error en autenticación: ' . $error);
            return redirect()->to('admin/backup');
        }

        if (!$code) {
            session()->setFlashdata('error', 'Código de autorización no recibido');
            return redirect()->to('admin/backup');
        }

        try {
            $token = $this->getAccessToken($code);
            
            if (!$token || !isset($token['access_token'])) {
                session()->setFlashdata('error', 'Error al obtener token de acceso');
                return redirect()->to('admin/backup');
            }

            // Almacena tokens en sesión
            session()->set('onedrive_token', $token['access_token']);
            session()->set('onedrive_refresh_token', $token['refresh_token'] ?? null);
            session()->set('onedrive_expires_at', time() + ($token['expires_in'] ?? 3600));

            session()->setFlashdata('success', 'Conexión exitosa con OneDrive');
            return redirect()->to('admin/backup');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error en la autenticación: ' . $e->getMessage());
            return redirect()->to('admin/backup');
        }
    }

    /**
     * Verifica si el usuario está conectado a OneDrive
     */
    public function checkConnection()
    {
        $connected = session()->has('onedrive_token');
        return $this->response->setJSON(['connected' => $connected]);
    }

    /**
     * Desconecta el usuario de OneDrive
     */
    public function disconnect()
    {
        session()->remove('onedrive_token');
        session()->remove('onedrive_refresh_token');
        session()->remove('onedrive_expires_at');

        return redirect()->to('admin/backup')->with('success', 'Desconectado de OneDrive');
    }

    /**
     * Obtiene la URL de autorización
     */
    private function getAuthUrl()
    {
        $scope = urlencode('Files.ReadWrite.All offline_access');
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/authorize?" .
               "client_id={$this->clientId}&" .
               "redirect_uri=" . urlencode($this->redirectUri) . "&" .
               "response_type=code&" .
               "scope={$scope}&" .
               "response_mode=query";
        return $url;
    }

    /**
     * Intercambia el código de autorización por un token de acceso
     */
    private function getAccessToken($code)
    {
        $tokenUrl = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $postData = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            throw new \Exception('Error al obtener token: ' . $response);
        }

        return json_decode($response, true);
    }

    /**
     * Sube un archivo a OneDrive
     */
    public function uploadFileToOneDrive($localFilePath, $oneDriveFileName)
    {
        // Verifica si el archivo local existe
        if (!file_exists($localFilePath)) {
            throw new \Exception('Archivo local no encontrado: ' . $localFilePath);
        }

        // Obtiene el token de acceso
        $accessToken = session()->get('onedrive_token');
        if (!$accessToken) {
            throw new \Exception('No conectado a OneDrive');
        }

        // Refresca el token si ha expirado
        if ($this->isTokenExpired()) {
            $this->refreshAccessToken();
            $accessToken = session()->get('onedrive_token');
        }

        // Prepara la URL de carga
        $fileName = urlencode($oneDriveFileName);
        $uploadUrl = "{$this->graphApiUrl}/me/drive/root:/Backups/{$fileName}:/content";

        // Lee el contenido del archivo
        $fileContent = file_get_contents($localFilePath);

        // Realiza la solicitud PUT a Microsoft Graph
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $uploadUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $fileContent,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/octet-stream'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 300
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!in_array($httpCode, [200, 201])) {
            throw new \Exception('Error al subir archivo a OneDrive: ' . $response);
        }

        return json_decode($response, true);
    }

    /**
     * Refresca el token de acceso si ha expirado
     */
    private function refreshAccessToken()
    {
        $refreshToken = session()->get('onedrive_refresh_token');
        if (!$refreshToken) {
            throw new \Exception('No hay refresh token disponible');
        }

        $tokenUrl = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $postData = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token'
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            throw new \Exception('Error al refrescar token: ' . $response);
        }

        $token = json_decode($response, true);
        session()->set('onedrive_token', $token['access_token']);
        session()->set('onedrive_expires_at', time() + ($token['expires_in'] ?? 3600));
    }

    /**
     * Verifica si el token ha expirado
     */
    private function isTokenExpired()
    {
        $expiresAt = session()->get('onedrive_expires_at');
        return $expiresAt ? time() >= $expiresAt : true;
    }
}
