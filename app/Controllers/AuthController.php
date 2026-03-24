<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RecuperacionContrasenaModel;
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

    /**
     * Muestra el formulario para recuperar contraseña
     */
    public function recuperarContrasena()
    {
        if (session()->get('logged_in')) {
            return $this->redirigirSegunRol(session()->get('rol'));
        }
        return view('auth/recuperar_contrasena', ['token' => null]);
    }

    /**
     * Procesa la solicitud de recuperación de contraseña.
     * Disponible para cualquier usuario del sistema (administrador, docente, estudiante).
     */
    public function solicitarRecuperacion()
    {
        $session = session();
        $emailOUsuario = $this->request->getPost('email_o_usuario');

        if (empty(trim($emailOUsuario ?? ''))) {
            $session->setFlashdata('error', 'Ingrese su correo electrónico o usuario.');
            return redirect()->to('auth/recuperar-contrasena')->withInput();
        }

        $usuarioModel = new UsuariosModel();
        $usuario = $usuarioModel->buscarPorEmailOUsuario($emailOUsuario);

        if ($usuario && !empty($usuario['EMAIL'])) {
            $recuperacionModel = new RecuperacionContrasenaModel();
            $token = $recuperacionModel->crearToken((int) $usuario['ID_USUARIO']);
            if ($token) {
                $enlace = site_url('auth/restablecer-contrasena?token=' . $token);
                $enviado = $this->enviarCorreoRecuperacion($usuario, $token);
                if (!$enviado) {
                    log_message('error', 'No se pudo enviar el correo de recuperación a: ' . $usuario['EMAIL']);
                    $session->setFlashdata('enlace_recuperacion', $enlace);
                    $session->setFlashdata('error_email', true);
                }
            }
        } elseif ($usuario && empty(trim($usuario['EMAIL'] ?? ''))) {
            $session->setFlashdata('error', 'Este usuario no tiene correo registrado. Contacte al administrador para que agregue su email en el sistema.');
            return redirect()->to('auth/recuperar-contrasena')->withInput();
        }

        $session->setFlashdata('success', 'Si el correo o usuario está registrado, recibirás instrucciones para restablecer tu contraseña. Revisa tu bandeja de entrada y la carpeta de spam.');
        return redirect()->to('auth/recuperar-contrasena');
    }

    /**
     * Envía el correo con el enlace para restablecer la contraseña
     */
    private function enviarCorreoRecuperacion(array $usuario, string $token): bool
    {
        $config = $this->obtenerConfigEmail();
        if (empty(trim($config['fromEmail'] ?? ''))) {
            log_message('error', 'Recuperación de contraseña: configure fromEmail en app/Config/Email.php');
            return false;
        }

        $enlace = site_url('auth/restablecer-contrasena?token=' . $token);
        $nombre = trim(($usuario['NOMBRE'] ?? '') . ' ' . ($usuario['APELLIDO'] ?? ''));
        if ($nombre === '') {
            $nombre = $usuario['USUARIO'] ?? 'Usuario';
        }

        $mensaje = $this->generarMensajeRecuperacion($nombre, $enlace);

        try {
            $email = \Config\Services::email();
            $email->setFrom($config['fromEmail'], $config['fromName'] ?: 'Sistema de Vinculación');
            $email->setTo($usuario['EMAIL']);
            $email->setSubject('Recuperar contraseña - Sistema de Vinculación');
            $email->setMailType('html');
            $email->setMessage($mensaje);

            if (!$email->send()) {
                $this->logErrorEmail($email, $usuario['EMAIL']);
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Error enviando correo recuperación: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la configuración de correo (desde Config o .env)
     */
    private function obtenerConfigEmail(): array
    {
        $config = config('Email');
        $fromEmail = $this->getEnvOrConfig('email.fromEmail', $config->fromEmail ?? '');
        $fromName  = $this->getEnvOrConfig('email.fromName', $config->fromName ?? 'Sistema de Vinculación');
        return [
            'fromEmail' => is_string($fromEmail) ? trim($fromEmail) : '',
            'fromName'  => is_string($fromName) ? trim($fromName) : 'Sistema de Vinculación',
        ];
    }

    private function getEnvOrConfig(string $key, string $fallback): string
    {
        $v = getenv($key);
        if ($v !== false && $v !== '') {
            return $v;
        }
        $v = getenv(strtoupper(str_replace('.', '_', $key)));
        if ($v !== false && $v !== '') {
            return $v;
        }
        return $fallback;
    }

    /**
     * Registra en el log el motivo del fallo del envío de correo
     */
    private function logErrorEmail($email, string $destinatario): void
    {
        $debug = 'send() devolvió false';
        if (method_exists($email, 'printDebugger')) {
            ob_start();
            $email->printDebugger();
            $debug = ob_get_clean() ?: $debug;
        }
        log_message('error', 'Email recuperación a ' . $destinatario . ': ' . $debug);
    }

    private function generarMensajeRecuperacion(string $nombre, string $enlace): string
    {
        return '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
            body{font-family:Arial,sans-serif;line-height:1.6;color:#333;margin:0;padding:0;}
            .container{max-width:600px;margin:0 auto;padding:20px;}
            .header{background:linear-gradient(135deg,#2196F3,#BBDEFB);color:#fff;padding:25px;text-align:center;border-radius:10px 10px 0 0;}
            .content{background:#f8f9fa;padding:25px;border-radius:0 0 10px 10px;}
            .btn{display:inline-block;background:#2196F3;color:#fff!important;padding:12px 24px;text-decoration:none;border-radius:6px;margin:15px 0;}
            .footer{text-align:center;margin-top:20px;color:#666;font-size:13px;}
            .aviso{color:#856404;background:#fff3cd;padding:10px;border-radius:5px;margin-top:15px;font-size:13px;}
        </style></head><body><div class="container">
            <div class="header"><h1>Sistema de Vinculación</h1><h2>Recuperar contraseña</h2></div>
            <div class="content">
                <p>Hola <strong>' . htmlspecialchars($nombre) . '</strong>,</p>
                <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el siguiente enlace (válido por 1 hora):</p>
                <p style="text-align:center;"><a href="' . htmlspecialchars($enlace) . '" class="btn">Restablecer mi contraseña</a></p>
                <p>Si el botón no funciona, copia y pega esta URL en tu navegador:</p>
                <p style="word-break:break-all;font-size:12px;">' . htmlspecialchars($enlace) . '</p>
                <div class="aviso">Si no solicitaste este correo, ignóralo. Tu contraseña no cambiará.</div>
            </div>
            <div class="footer">&copy; ' . date('Y') . ' Departamento de Vinculación</div>
        </div></body></html>';
    }

    /**
     * Muestra el formulario para restablecer contraseña (con token en URL).
     * Válido para cualquier rol (admin, docente, estudiante).
     */
    public function restablecerContrasena()
    {
        if (session()->get('logged_in')) {
            return $this->redirigirSegunRol(session()->get('rol'));
        }

        $token = $this->request->getGet('token');
        if (empty($token)) {
            return redirect()->to('auth/recuperar-contrasena')->with('error', 'Enlace inválido o expirado.');
        }

        $recuperacionModel = new RecuperacionContrasenaModel();
        $idUsuario = $recuperacionModel->validarToken($token);
        if (!$idUsuario) {
            return redirect()->to('auth/recuperar-contrasena')->with('error', 'El enlace ha expirado o ya fue utilizado. Solicita uno nuevo.');
        }

        return view('auth/recuperar_contrasena', ['token' => $token]);
    }

    /**
     * Procesa el cambio de contraseña con el token (cualquier rol).
     */
    public function restablecerContrasenaPost()
    {
        $session = session();
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordConfirmar = $this->request->getPost('password_confirmar');

        if (empty($token)) {
            $session->setFlashdata('error', 'Enlace inválido.');
            return redirect()->to('auth/recuperar-contrasena');
        }

        $recuperacionModel = new RecuperacionContrasenaModel();
        $idUsuario = $recuperacionModel->validarToken($token);
        if (!$idUsuario) {
            $session->setFlashdata('error', 'El enlace ha expirado o ya fue utilizado.');
            return redirect()->to('auth/recuperar-contrasena');
        }

        if (strlen($password) < 8) {
            $session->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres.');
            return redirect()->to('auth/restablecer-contrasena?token=' . urlencode($token))->withInput();
        }

        if ($password !== $passwordConfirmar) {
            $session->setFlashdata('error', 'Las contraseñas no coinciden.');
            return redirect()->to('auth/restablecer-contrasena?token=' . urlencode($token))->withInput();
        }

        $usuarioModel = new UsuariosModel();
        $usuarioModel->actualizarPasswordHash($idUsuario, $password);
        $recuperacionModel->marcarUsado($token);

        $session->setFlashdata('success', 'Tu contraseña se ha actualizado correctamente. Ya puedes iniciar sesión.');
        return redirect()->to('/');
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
            $requiereCambioPassword = !empty($userData['requiere_cambio_password']);
            
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
                'foto_perfil' => $userData['foto_perfil'] ?? null,
                'logged_in' => TRUE,
                'requiere_cambio_password' => $requiereCambioPassword
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
                    $builder->orderBy('AÑO_INICIO', 'DESC');
                    $builder->orderBy('MES_INICIO', 'DESC');
                    $periodo = $builder->get(1)->getRowArray();
                }

                if ($periodo) {
                    $ses_data['periodo_academico_id'] = $periodo['ID_PERIODO_ACADEMICO'] ?? null;
                    $ses_data['periodo_academico_anio'] = $periodo['AÑO_ACADEMICO'] ?? $periodo['AÑO_INICIO'] ?? ($periodo['ANIO_ACADEMICO'] ?? null);

                    $etiquetaMesAnio = formatear_periodo_academico_mes_anio($periodo);
                    if ($etiquetaMesAnio !== null) {
                        $ses_data['periodo_academico_nombre'] = $etiquetaMesAnio;
                        $ses_data['periodo_academico_rango'] = '';
                    } else {
                        $nombre = $periodo['NOMBRE_PERIODO'] ?? null;
                        if ($nombre === null && isset($periodo['MES_INICIO'], $periodo['AÑO_INICIO'], $periodo['MES_FIN'], $periodo['AÑO_FIN'])) {
                            $mi = str_pad((string) $periodo['MES_INICIO'], 2, '0', STR_PAD_LEFT);
                            $mf = str_pad((string) $periodo['MES_FIN'], 2, '0', STR_PAD_LEFT);
                            $nombre = "{$mi}/{$periodo['AÑO_INICIO']} - {$mf}/{$periodo['AÑO_FIN']}";
                        }
                        $ses_data['periodo_academico_nombre'] = $nombre;
                        if (!empty($periodo['FECHA_INICIO']) && !empty($periodo['FECHA_FIN'])) {
                            $ses_data['periodo_academico_rango'] = $periodo['FECHA_INICIO'] . ' - ' . $periodo['FECHA_FIN'];
                        } else {
                            $mi = str_pad((string) ($periodo['MES_INICIO'] ?? ''), 2, '0', STR_PAD_LEFT);
                            $mf = str_pad((string) ($periodo['MES_FIN'] ?? ''), 2, '0', STR_PAD_LEFT);
                            $ai = $periodo['AÑO_INICIO'] ?? '';
                            $af = $periodo['AÑO_FIN'] ?? '';
                            $ses_data['periodo_academico_rango'] = "{$mi}/{$ai} - {$mf}/{$af}";
                        }
                    }
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
            log_message('info', 'Usuario autenticado: ' . $usuario . ' con rol: ' . $userData['rol'] . ' - requiere cambio password: ' . ($requiereCambioPassword ? 'sí' : 'no'));

            // Si el usuario tiene contraseña en texto plano (usuarios antiguos),
            // redirigir primero a la vista de cambio de contraseña según su rol.
            if ($requiereCambioPassword) {
                switch ((int)$userData['rol']) {
                    case 1: // Administrador
                        return redirect()->to('/admin/cuenta')
                            ->with('info', 'Por seguridad, por favor cambia tu contraseña inicial antes de continuar.');
                    case 2: // Docente/Instructor
                        return redirect()->to('/docente/cuenta')
                            ->with('info', 'Por seguridad, por favor cambia tu contraseña inicial antes de continuar.');
                    case 3: // Estudiante
                        return redirect()->to('/estudiante/cuenta')
                            ->with('info', 'Por seguridad, por favor cambia tu contraseña inicial antes de continuar.');
                }
            }

            // Si no requiere cambio, redirigir según el rol del usuario
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
                'requiere_cambio_password',
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