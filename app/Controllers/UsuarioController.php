<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\DatosPersonasModel;
use App\Models\RolesModel;

class UsuarioController extends BaseController
{
    protected $usuarioModel;
    protected $datosPersonaModel;
    protected $rolModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->usuarioModel = new UsuariosModel();
        $this->datosPersonaModel = new DatosPersonasModel();
        $this->rolModel = new RolesModel();
    }

    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        try {
            $usuarios = $this->usuarioModel
                ->select('u.*, dp.NOMBRE, dp.APELLIDO, dp.EMAIL, dp.CEDULA, dp.ACTIVO')
                ->from('TAB_USUARIOS u')
                ->join('TAB_DATOS_PERSONAS dp', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuarios,
                'message' => 'Usuarios obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener un usuario específico
     */
    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de usuario requerido'
            ])->setStatusCode(400);
        }

        try {
            $usuario = $this->usuarioModel
                ->select('u.*, dp.*, GROUP_CONCAT(tr.ROL) as roles')
                ->from('TAB_USUARIOS u')
                ->join('TAB_DATOS_PERSONAS dp', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
                ->join('TAB_ROLES r', 'u.ID_USUARIO = r.ID_USUARIO', 'left')
                ->join('TAB_TIPOS_ROLES tr', 'r.ID_TIPOS_ROLES = tr.ID_TIPOS_ROLES', 'left')
                ->where('u.ID_USUARIO', $id)
                ->groupBy('u.ID_USUARIO')
                ->first();

            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuario,
                'message' => 'Usuario obtenido correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'cedula' => 'required|exact_length[10]|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
            'celular' => 'required|exact_length[10]',
            'email' => 'required|valid_email|is_unique[TAB_DATOS_PERSONAS.EMAIL]',
            'direccion' => 'required',
            'genero' => 'required|in_list[Masculino,Femenino,Otro]',
            'estado_civil' => 'required|in_list[Soltero,Casado,Divorciado,Viudo,Union Libre]',
            'nacionalidad' => 'required|max_length[50]',
            'usuario' => 'required|min_length[4]|max_length[20]|is_unique[TAB_USUARIOS.USUARIO]',
            'contrasena' => 'required|min_length[6]',
            'roles' => 'required'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Crear datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'EMAIL' => $this->request->getPost('email'),
                'GENERO' => $this->request->getPost('genero'),
                'ESTADO_CIVIL' => $this->request->getPost('estado_civil'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad'),
                'FECHA_INGRESO' => date('Y-m-d'),
                'ACTIVO' => true,
                'FOTO_URL' => $this->request->getPost('foto_url') ?? ''
            ];

            $idDatoPersona = $this->datosPersonaModel->insert($datosPersona);

            // Crear usuario
            $usuario = [
                'ID_DATO_PERSONA' => $idDatoPersona,
                'USUARIO' => $this->request->getPost('usuario'),
                'CONTRASENA' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT),
                'ESTADO' => 'A'
            ];

            $idUsuario = $this->usuarioModel->insert($usuario);

            // Asignar roles
            $roles = $this->request->getPost('roles');
            if (is_array($roles)) {
                foreach ($roles as $rolId) {
                    $this->rolModel->insert([
                        'ID_USUARIO' => $idUsuario,
                        'ID_TIPOS_ROLES' => $rolId
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => ['id' => $idUsuario],
                'message' => 'Usuario creado correctamente'
            ])->setStatusCode(201);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Actualizar un usuario existente
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de usuario requerido'
            ])->setStatusCode(400);
        }

        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ])->setStatusCode(404);
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'celular' => 'required|exact_length[10]',
            'direccion' => 'required',
            'genero' => 'required|in_list[Masculino,Femenino,Otro]',
            'estado_civil' => 'required|in_list[Soltero,Casado,Divorciado,Viudo,Union Libre]',
            'estado' => 'required|in_list[A,I]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getRawInput())) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Actualizar datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getRawInput()['nombre'],
                'APELLIDO' => $this->request->getRawInput()['apellido'],
                'CELULAR' => $this->request->getRawInput()['celular'],
                'DIRECCION' => $this->request->getRawInput()['direccion'],
                'GENERO' => $this->request->getRawInput()['genero'],
                'ESTADO_CIVIL' => $this->request->getRawInput()['estado_civil'],
                'ACTIVO' => $this->request->getRawInput()['estado'] === 'A'
            ];

            $this->datosPersonaModel->update($usuario['ID_DATO_PERSONA'], $datosPersona);

            // Actualizar usuario
            $usuarioData = [
                'ESTADO' => $this->request->getRawInput()['estado']
            ];

            if (isset($this->request->getRawInput()['contrasena']) && !empty($this->request->getRawInput()['contrasena'])) {
                $usuarioData['CONTRASENA'] = password_hash($this->request->getRawInput()['contrasena'], PASSWORD_DEFAULT);
            }

            $this->usuarioModel->update($id, $usuarioData);

            $db->transComplete();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar un usuario
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de usuario requerido'
            ])->setStatusCode(400);
        }

        try {
            $usuario = $this->usuarioModel->find($id);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ])->setStatusCode(404);
            }

            // Soft delete: cambiar estado a inactivo
            $this->usuarioModel->update($id, ['ESTADO' => 'I']);
            $this->datosPersonaModel->update($usuario['ID_DATO_PERSONA'], ['ACTIVO' => false]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Login de usuario
     */
    public function login()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'usuario' => 'required',
            'contrasena' => 'required'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario y contraseña son requeridos'
            ])->setStatusCode(400);
        }

        try {
            $usuario = $this->usuarioModel
                ->select('u.*, dp.NOMBRE, dp.APELLIDO, dp.EMAIL')
                ->from('TAB_USUARIOS u')
                ->join('TAB_DATOS_PERSONAS dp', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
                ->where('u.USUARIO', $this->request->getPost('usuario'))
                ->where('u.ESTADO', 'A')
                ->where('dp.ACTIVO', true)
                ->first();

            if (!$usuario || !password_verify($this->request->getPost('contrasena'), $usuario['CONTRASENA'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ])->setStatusCode(401);
            }

            // Obtener roles del usuario
            $roles = $this->rolModel
                ->select('tr.ROL')
                ->from('TAB_ROLES r')
                ->join('TAB_TIPOS_ROLES tr', 'r.ID_TIPOS_ROLES = tr.ID_TIPOS_ROLES')
                ->where('r.ID_USUARIO', $usuario['ID_USUARIO'])
                ->findColumn('ROL');

            // Crear sesión
            session()->set([
                'user_id' => $usuario['ID_USUARIO'],
                'user_name' => $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'],
                'user_email' => $usuario['EMAIL'],
                'user_roles' => $roles,
                'logged_in' => true
            ]);

            unset($usuario['CONTRASENA']); // No devolver la contraseña

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'usuario' => $usuario,
                    'roles' => $roles
                ],
                'message' => 'Login exitoso'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en el login: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Logout de usuario
     */
    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    }
}