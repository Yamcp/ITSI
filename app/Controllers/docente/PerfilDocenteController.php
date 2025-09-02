<?php

namespace App\Controllers\docente;

use App\Models\UsuariosModel;
use App\Controllers\BaseController;

class PerfilDocenteController extends BaseController
{
    public function __construct() {}

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('docente/perfil');
        }

        $session = session();

        // Obtener datos del usuario actual
        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();

        // Consultar información completa del usuario con JOIN a otras tablas
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return redirect()->to(base_url('docente/perfil'))->with('error', 'No se pudo cargar la información del perfil');
        }

        $data = [
            'title' => 'Mi Perfil | ITSI | Docente',
            'usuario' => $usuario,
            'validation' => null
        ];

        return view('docente/perfil/perfilDocente', $data);
    }

    public function update()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('docente/perfil'));
        }

        // Validación de datos del formulario (solo información personal)
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'apellido' => 'required|min_length[3]|max_length[100]',
            'celular' => 'permit_empty|min_length[10]|max_length[10]',
            'direccion' => 'permit_empty|min_length[5]',
            'email' => 'permit_empty|valid_email',
            'genero' => 'permit_empty|in_list[Masculino,Femenino,No binario]',
            'estado_civil' => 'permit_empty|in_list[Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión Libre]',
            'nacionalidad' => 'permit_empty|min_length[3]|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userId = $session->get('id_usuario');
        $usuarioModel = new UsuariosModel();

        // Obtener información actual del usuario
        $usuario = $usuarioModel->getUserProfile($userId);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Preparar datos personales para actualizar
        $datosPersona = [
            'NOMBRE' => $this->request->getPost('nombre'),
            'APELLIDO' => $this->request->getPost('apellido'),
            'CELULAR' => $this->request->getPost('celular') ?: null,
            'DIRECCION' => $this->request->getPost('direccion') ?: null,
            'EMAIL' => $this->request->getPost('email') ?: null,
            'GENERO' => $this->request->getPost('genero') ?: null,
            'ESTADO_CIVIL' => $this->request->getPost('estado_civil') ?: null,
            'NACIONALIDAD' => $this->request->getPost('nacionalidad') ?: null
        ];

        // Si se proporciona fecha de ingreso, incluirla
        if ($this->request->getPost('fecha_ingreso')) {
            $datosPersona['FECHA_INGRESO'] = $this->request->getPost('fecha_ingreso');
        }

        // Solo actualizar información personal (no contraseñas)
        $datosUsuario = [];

        // Actualizar perfil usando el nuevo método
        if ($usuarioModel->actualizarPerfil($userId, $datosPersona, $datosUsuario)) {
            return redirect()->to(base_url('docente/perfil'))->with('success', 'Perfil actualizado correctamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar los datos');
        }
    }
}
