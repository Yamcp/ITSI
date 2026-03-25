<?php

namespace App\Controllers\coord;

use App\Models\UsuariosModel;
use App\Controllers\BaseController;

class PerfilCoordController extends BaseController
{
    protected $db;

    public function __construct() 
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $session = session();

        // Obtener datos del usuario actual
        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();

        // Consultar información completa del usuario con JOIN a otras tablas
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return redirect()->to(base_url('coord/perfil'))->with('error', 'No se pudo cargar la información del perfil');
        }

        $data = [
            'title' => 'Mi Perfil | ITSI',
            'usuario' => $usuario,
            'validation' => null
        ];

        return view('coord/perfil/perfilCoord', $data);
    }

    public function update()
    {
        $session = session();
        
        // Log para depuración
        log_message('info', 'PerfilCoordController::update - Iniciando actualización de perfil');
        log_message('info', 'Sesión logged_in: ' . ($session->get('logged_in') ? 'true' : 'false'));
        log_message('info', 'ID Usuario: ' . $session->get('id_usuario'));

        if (!$session->get('logged_in')) {
            log_message('error', 'Usuario no autenticado, redirigiendo a login');
            return redirect()->to(base_url('/'));
        }

        // Verificar CSRF token
        if (!$this->request->is('post')) {
            log_message('error', 'Método no permitido: ' . $this->request->getMethod());
            return redirect()->back()->with('error', 'Método no permitido');
        }

        // Validación de datos del formulario (solo campos editables)
        $rules = [
            'celular' => 'permit_empty|min_length[9]|max_length[10]|numeric',
            'direccion' => 'permit_empty|min_length[3]|max_length[255]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'genero' => 'permit_empty|in_list[Masculino,Femenino,No binario]',
            'estado_civil' => 'permit_empty|in_list[Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre]',
            'nacionalidad' => 'permit_empty|min_length[2]|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Errores de validación en perfil: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('validation', $this->validator)->with('error', 'Por favor, corrija los errores en el formulario');
        }

        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();

        // Obtener información actual del usuario
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Preparar datos personales para actualizar (solo campos editables)
        $datosPersona = [
            'CELULAR' => $this->request->getPost('celular') ?: null,
            'DIRECCION' => $this->request->getPost('direccion') ?: null,
            'EMAIL' => $this->request->getPost('email') ?: null,
            'GENERO' => $this->request->getPost('genero') ?: null,
            'ESTADO_CIVIL' => $this->request->getPost('estado_civil') ?: null,
            'NACIONALIDAD' => $this->request->getPost('nacionalidad') ?: null
        ];
        
        // Log de datos para depuración
        log_message('info', 'Datos a actualizar - Usuario ID: ' . $userId . ' - Datos: ' . json_encode($datosPersona));

        // Solo actualizar información personal (no contraseñas)
        $datosUsuario = [];

        // Actualizar perfil usando el nuevo método
        $resultado = $usuarioModel->actualizarPerfil($userId, $datosPersona, $datosUsuario);
        
        if ($resultado) {
            return redirect()->to(base_url('coord/perfil'))->with('success', 'Perfil actualizado correctamente');
        } else {
            // Obtener el último error de la base de datos
            $dbError = $this->db->error();
            $errorMessage = 'Error al actualizar los datos';
            
            if (!empty($dbError['message'])) {
                $errorMessage .= ': ' . $dbError['message'];
            }
            
            log_message('error', 'Error actualizando perfil - Usuario ID: ' . $userId . ' - Error DB: ' . json_encode($dbError));
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    public function uploadImage()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();
        
        // Obtener información actual del usuario
        $usuario = $usuarioModel->getUserProfile($userId);
        
        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        // Validar que se haya subido un archivo
        $file = $this->request->getFile('foto_perfil');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se ha seleccionado un archivo válido'
            ]);
        }

        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Formato de archivo no válido. Solo se permiten JPG, PNG y GIF'
            ]);
        }

        // Validar tamaño (2MB máximo)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El archivo es demasiado grande. Máximo 2MB'
            ]);
        }

        try {
            // Generar nombre único para el archivo
            $newName = 'perfil_' . $userId . '_' . time() . '.' . $file->getExtension();
            
            // Mover archivo al directorio de perfiles
            if ($file->move(ROOTPATH . 'public/uploads/perfiles', $newName)) {
                // Eliminar imagen anterior si existe
                if (!empty($usuario['FOTO_URL']) && file_exists(ROOTPATH . 'public/uploads/perfiles/' . $usuario['FOTO_URL'])) {
                    unlink(ROOTPATH . 'public/uploads/perfiles/' . $usuario['FOTO_URL']);
                }
                
                // Actualizar base de datos
                $datosPersona = ['FOTO_URL' => $newName];
                $resultado = $usuarioModel->actualizarPerfil($userId, $datosPersona, []);
                
                if ($resultado) {
                    $session->set('foto_perfil', $newName);
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Imagen actualizada correctamente',
                        'image_url' => base_url('uploads/perfiles/' . $newName)
                    ]);
                } else {
                    // Si falla la actualización, eliminar el archivo subido
                    unlink(ROOTPATH . 'public/uploads/perfiles/' . $newName);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al actualizar la base de datos'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al subir el archivo'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error subiendo imagen: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar la foto de perfil del usuario
     */
    public function deleteImage()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        try {
            $fotoUrl = $usuario['FOTO_URL'] ?? null;

            if (!empty($fotoUrl) && file_exists(ROOTPATH . 'public/uploads/perfiles/' . $fotoUrl)) {
                unlink(ROOTPATH . 'public/uploads/perfiles/' . $fotoUrl);
            }

            $datosPersona = ['FOTO_URL' => ''];
            $resultado = $usuarioModel->actualizarPerfil($userId, $datosPersona, []);

            if ($resultado) {
                $session->set('foto_perfil', null);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Foto de perfil eliminada correctamente'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el perfil'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error eliminando imagen de perfil: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }
}
