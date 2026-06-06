<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\RedirectResponse;

class CuentaDocenteController extends BaseController
{
    protected $usuariosModel;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
    }

    public function index(): string|RedirectResponse
    {
        // Obtener ID del usuario de la sesión
        $userId = session('id_usuario');

        if (!$userId) {
            return redirect()->to('auth/login');
        }

        // Obtener información básica del usuario
        $usuario = $this->usuariosModel->find($userId);

        $data = [
            'title' => 'Mi Cuenta - Cambio de Contraseña',
            'usuario' => $usuario
        ];

        return view('docente/cuenta/cuentaDocente', $data);
    }

    public function cambiarPassword(): RedirectResponse
    {
        // Obtener ID del usuario de la sesión
        $userId = session('id_usuario');

        if (!$userId) {
            return redirect()->to('auth/login');
        }

        // Validar datos del formulario
        $rules = [
            'password_actual' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'La contraseña actual es obligatoria',
                    'min_length' => 'La contraseña actual debe tener al menos 6 caracteres'
                ]
            ],
            'password_nuevo' => [
                'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_-])[A-Za-z\d@$!%*?&_-]/]',
                'errors' => [
                    'required' => 'La nueva contraseña es obligatoria',
                    'min_length' => 'La nueva contraseña debe tener al menos 8 caracteres',
                    'regex_match' => 'La nueva contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial'
                ]
            ],
            'password_confirmar' => [
                'rules' => 'required|matches[password_nuevo]',
                'errors' => [
                    'required' => 'La confirmación de contraseña es obligatoria',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener datos del formulario
        $passwordActual = $this->request->getPost('password_actual');
        $passwordNuevo = $this->request->getPost('password_nuevo');

        // Verificar que la contraseña actual sea correcta
        $usuario = $this->usuariosModel->find($userId);

        // Verificar contraseña (soporta tanto hash como texto plano para transición)
        $passwordValid = false;
        if ($usuario) {
            // Primero intentar verificar como hash
            if (password_verify($passwordActual, $usuario['CONTRASENA'])) {
                $passwordValid = true;
            }
            // Si falla, verificar como texto plano (para usuarios existentes)
            else if ($passwordActual === $usuario['CONTRASENA']) {
                $passwordValid = true;
            }
        }

        if (!$passwordValid) {
            return redirect()->back()->withInput()->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar la contraseña
        $passwordHash = password_hash($passwordNuevo, PASSWORD_DEFAULT);

        $datosUsuario = [
            'CONTRASENA' => $passwordHash
        ];

        if ($this->usuariosModel->update($userId, $datosUsuario)) {
            session()->set('requiere_cambio_password', false);
            return redirect()->back()->with('success', 'Contraseña actualizada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la contraseña');
        }
    }
}