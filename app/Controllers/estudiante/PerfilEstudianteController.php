<?php

namespace App\Controllers\admin;

use App\Models\UsuariosModel;
use App\Models\DatosPersonasModel;
use App\Controllers\BaseController;

class PerfilAdminController extends BaseController
{
    public function __construct() {}

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('admin/perfil');
        }

        $session = session();

        // Obtener datos del usuario actual
        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();

        // Consultar información completa del usuario con JOIN a otras tablas
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return redirect()->to(base_url('admin/perfil'))->with('error', 'No se pudo cargar la información del perfil');
        }

        $data = [
            'title' => 'Mi Perfil | ITSI',
            'usuario' => $usuario,
            'validation' => null
        ];

        return view('admin/perfil/perfil', $data);
    }

    public function update()
    {
        if (!session()->get('logged_in')) {
        return redirect()->to('/login');
        }

        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('admin/perfil'));
        }

        // Validación de datos del formulario
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'apellido' => 'required|min_length[3]|max_length[100]',
            'cedula' => 'required|exact_length[10]|numeric',
            'celular' => 'required|min_length[10]|max_length[10]|numeric',
            'direccion' => 'required|min_length[5]',
            'usuario' => 'required|min_length[3]|max_length[20]|alpha_numeric'
        ];

        // Si se está intentando cambiar la contraseña
        if ($this->request->getPost('password_nuevo')) {
            $rules['password_actual'] = 'required';
            $rules['password_nuevo'] = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password_nuevo]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();
        $datoPersonaModel = new DatosPersonasModel();

        // Obtener información actual del usuario
        $usuario = $usuarioModel->find($userId);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Verificar si el nombre de usuario ya existe (si se cambió)
        if ($this->request->getPost('usuario') != $usuario['USUARIO']) {
            $existingUser = $usuarioModel->where('USUARIO', $this->request->getPost('usuario'))
                ->where('ID_USUARIO !=', $userId)
                ->first();

            if ($existingUser) {
                return redirect()->back()->withInput()->with('error', 'El nombre de usuario ya está en uso');
            }
        }

        // Actualizar datos de usuario
        $usuarioData = [
            'USUARIO' => $this->request->getPost('usuario')
        ];

        // Verificar si se está cambiando la contraseña
        if ($this->request->getPost('password_nuevo')) {
            // Verificar contraseña actual
            if (!password_verify($this->request->getPost('password_actual'), $usuario['CONTRASENA'])) {
                return redirect()->back()->withInput()->with('error', 'La contraseña actual es incorrecta');
            }

            $usuarioData['CONTRASENA'] = password_hash($this->request->getPost('password_nuevo'), PASSWORD_DEFAULT);
        }

        // Actualizar datos personales
        $personaData = [
            'NOMBRE' => $this->request->getPost('nombre'),
            'APELLIDO' => $this->request->getPost('apellido'),
            'CEDULA' => $this->request->getPost('cedula'),
            'CELULAR' => $this->request->getPost('celular'),
            'DIRECCION' => $this->request->getPost('direccion')
        ];

        // Ejecutar actualización dentro de una transacción
        $db = \Config\Database::connect();
        $db->transStart();

        $usuarioModel->update($userId, $usuarioData);
        $datoPersonaModel->update($usuario['ID_DATO_PERSONA'], $personaData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar los datos');
        }

        // Actualizar datos de sesión si es necesario
        if ($this->request->getPost('usuario') != $usuario['USUARIO']) {
            $session->set('username', $this->request->getPost('usuario'));
        }

        return redirect()->to(base_url('admin/perfil'))->with('success', 'Perfil actualizado correctamente');
    }
}
