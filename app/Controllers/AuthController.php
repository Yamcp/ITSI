<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class AuthController extends BaseController 
{
    public function index()
    {
        // Si el usuario ya está logueado, redirigir según su rol
        if (session()->get('logged_in')) {
            return $this->redirigirSegunRol(session()->get('rol'));
        }
        return view('auth/login');
    }

    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuariosModel();

        $usuario = $this->request->getPost('usuario');
        $contrasena = $this->request->getPost('password');

        // Validación básica
        if (empty($usuario) || empty($contrasena)) {
            $session->setFlashdata('msg', 'Por favor complete todos los campos');
            return redirect()->to('/')->withInput();
        }

        $resultado = $usuarioModel->verificarUsuario($usuario, $contrasena);

        if ($resultado['status']) {
            $userData = $resultado['usuario'];
            
            // Verificar que el usuario esté activo
            if ($userData['estado'] != '1') {
                $session->setFlashdata('msg', 'Usuario inactivo. Contacte al administrador');
                return redirect()->to('/')->withInput();
            }
            
            $ses_data = [
                'id_usuario' => $userData['id'],
                'usuario' => $userData['username'],
                'nombre' => $userData['nombre'],
                'apellido' => $userData['apellido'],
                'rol' => (int)$userData['rol'], // Asegurar que sea entero
                'estado' => $userData['estado'],
                'logged_in' => TRUE
            ];

            $session->set($ses_data);

            // Log para debugging (remover en producción)
            log_message('info', 'Usuario autenticado: ' . $usuario . ' con rol: ' . $userData['rol']);

            // Redirigir según el rol del usuario
            return $this->redirigirSegunRol((int)$userData['rol']);
            
        } else {
            $session->setFlashdata('msg', 'Usuario o contraseña incorrectos');
            return redirect()->to('/')->withInput();
        }
    }

    /**
     * Redirige al usuario según su rol
     */
    private function redirigirSegunRol($rol)
    {
        switch ((int)$rol) {
            case 1: // Administrador
                return redirect()->to('/admin/dashboard');
            case 2: // Docente/Instructor
                return redirect()->to('/docente/dashboard');
            case 3: // Estudiante
                return redirect()->to('/estudiante/dashboard');
            default:
                // Si el rol no está definido, cerrar sesión por seguridad
                session()->setFlashdata('msg', 'Rol de usuario no válido');
                return $this->cerrarSesion();
        }
    }

    public function cerrarSesion()
    {
        $session = session();
        
        // Limpiar datos específicos de sesión
        $session->remove([
            'id_usuario',
            'usuario',
            'nombre',
            'apellido',
            'rol',
            'estado',
            'logged_in'
        ]);
        
        // Destruir la sesión completa
        $session->destroy();
        
        // Redirigir al login con mensaje
        return redirect()->to('/')
            ->with('msg', 'Sesión cerrada correctamente');
    }

    // Método para verificar si el usuario está logueado
    protected function verificarSesion()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return null; // Importante retornar null si la sesión es válida
    }

    // Método para verificar permisos por rol
    protected function verificarRol($rolesPermitidos = [])
    {
        $rolUsuario = session()->get('rol');
        
        if (!in_array($rolUsuario, $rolesPermitidos)) {
            return redirect()->to('/acceso-denegado');
        }
        return null;
    }
}