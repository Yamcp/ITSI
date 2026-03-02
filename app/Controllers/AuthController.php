<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use Config\Database;

class AuthController extends BaseController 
{
    public function index()
    {
        // Si el usuario ya está logueado, redirigir según su rol
        if (session()->get('logged_in')) {
            return $this->redirigirSegunRol(session()->get('rol'));
        }
        
        // Verificar si existe cookie de recordarme
        $rememberToken = $this->request->getCookie('remember_token');
        if ($rememberToken) {
            // Aquí podrías verificar el token en la base de datos
            // Por ahora, simplemente redirigimos al dashboard principal
            // En una implementación más segura, deberías:
            // 1. Verificar el token en la base de datos
            // 2. Obtener los datos del usuario asociado al token
            // 3. Crear la sesión con esos datos
            log_message('info', 'Token de recordarme encontrado: ' . $rememberToken);
        }
        
        return view('auth/login');
    }

    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuariosModel();

        $usuario = $this->request->getPost('usuario');
        $contrasena = $this->request->getPost('password');
        $recordarme = $this->request->getPost('rememberMe');

        // Validación básica
        if (empty($usuario) || empty($contrasena)) {
            $session->setFlashdata('error', 'Por favor complete todos los campos');
            return redirect()->to('/')->withInput();
        }

        $resultado = $usuarioModel->verificarUsuario($usuario, $contrasena);

        if ($resultado['status']) {
            $userData = $resultado['usuario'];
            
            // Verificar que el usuario esté activo
            if ($userData['estado'] != '1') {
                $session->setFlashdata('error', 'Usuario inactivo. Contacte al administrador');
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

            // Intentar obtener el período académico actual y guardarlo en sesión
            try {
                $db = Database::connect();

                // Primero intentamos usar la vista V_PERIODO_ACADEMICO_ACTUAL si existe
                $builder = $db->table('V_PERIODO_ACADEMICO_ACTUAL');
                $periodo = $builder->get(1)->getRowArray();

                // Si no hay resultados en la vista, probamos directamente sobre la tabla de períodos
                if (!$periodo) {
                    $builder = $db->table('TAB_PERIODOS_ACADEMICOS');
                    $builder->where('ESTADO', 'Activo');
                    $builder->orderBy('FECHA_INICIO', 'DESC');
                    $periodo = $builder->get(1)->getRowArray();
                }

                if ($periodo) {
                    $ses_data['periodo_academico_id'] = $periodo['ID_PERIODO_ACADEMICO'] ?? null;
                    $ses_data['periodo_academico_nombre'] = $periodo['NOMBRE_PERIODO'] ?? null;
                    $ses_data['periodo_academico_anio'] = $periodo['AÑO_ACADEMICO'] ?? ($periodo['ANIO_ACADEMICO'] ?? null);
                    $ses_data['periodo_academico_rango'] = ($periodo['FECHA_INICIO'] ?? '') . ' - ' . ($periodo['FECHA_FIN'] ?? '');
                }
            } catch (\Throwable $e) {
                // En caso de error, solo registramos el problema y continuamos sin interrumpir el login
                log_message('error', 'Error al obtener período académico actual: ' . $e->getMessage());
            }

            $session->set($ses_data);

            // Manejar la opción "Recordarme"
            if ($recordarme) {
                // Crear token de autenticación persistente
                $token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60); // 30 días
                
                // Guardar token en cookie
                $cookie = [
                    'name' => 'remember_token',
                    'value' => $token,
                    'expire' => $expires,
                    'httponly' => true,
                    'secure' => false, // Cambiar a true en HTTPS
                    'samesite' => 'Lax'
                ];
                
                $this->response->setCookie($cookie);
                
                // Guardar token en base de datos (opcional, para mayor seguridad)
                // Aquí podrías guardar el token en una tabla de tokens de sesión
                log_message('info', 'Token de recordarme creado para usuario: ' . $usuario);
            }

            // Log para debugging (remover en producción)
            log_message('info', 'Usuario autenticado: ' . $usuario . ' con rol: ' . $userData['rol']);

            // Redirigir según el rol del usuario
            return $this->redirigirSegunRol((int)$userData['rol']);
            
        } else {
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
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
        
        // Log para debugging
        log_message('info', 'Iniciando proceso de cierre de sesión para usuario: ' . ($session->get('usuario') ?? 'desconocido'));
        
        // Limpiar datos específicos de sesión
        $session->remove([
            'id_usuario',
            'usuario',
            'nombre',
            'apellido',
            'rol',
            'estado',
                'logged_in',
                'foto_perfil',
                'periodo_academico_id',
                'periodo_academico_nombre',
                'periodo_academico_anio',
                'periodo_academico_rango'
        ]);
        
        // Destruir la sesión completa
        $session->destroy();
        
        // Limpiar cookie de recordarme si existe
        if ($this->request->getCookie('remember_token')) {
            $this->response->deleteCookie('remember_token');
            log_message('info', 'Cookie de recordarme eliminada');
        }
        
        // Limpiar todas las cookies relacionadas con la sesión
        $this->response->deleteCookie('ci_session');
        
        log_message('info', 'Sesión cerrada exitosamente');
        
        // Redirigir al login con mensaje
        return redirect()->to('/')
            ->with('success', 'Sesión cerrada correctamente');
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