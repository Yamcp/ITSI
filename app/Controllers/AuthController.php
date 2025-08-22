<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class LoginController extends BaseController 
{
    public function index()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuariosModel();

        $usuario = $this->request->getPost('usuario');
        $contrasena = $this->request->getPost('password');

        $resultado = $usuarioModel->verificarUsuario($usuario, $contrasena);

        if ($resultado['status']) {
            $userData = $resultado['usuario'];
            
            $ses_data = [
                'id_usuario' => $userData['id'],
                'usuario' => $userData['username'],
                'nombre' => $userData['nombre'],
                'apellido' => $userData['apellido'],
                'rol' => $userData['rol'],
                'estado' => $userData['estado'],
                'logged_in' => TRUE
            ];

            $session->set($ses_data);

            // Redirigir según el rol del usuario
            switch ($userData['rol']) {
                case 1: // Administrador
                    return redirect()->to('/admin/dashboard');
                case 2: // Docente
                    return redirect()->to('/docente/dashboard');
                case 3: // Estudiante
                    return redirect()->to('/estudiante/dashboard');
                default:
                    return redirect()->to('/dashboard');
            }
        } else {
            $session->setFlashdata('msg', 'Usuario o contraseña incorrectos');
            return redirect()->to('/')->withInput();
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

    // Método para verificar si el usuario está logueado (útil como filtro)
    protected function verificarSesion()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
    }
}