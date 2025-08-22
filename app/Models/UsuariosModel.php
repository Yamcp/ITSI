<?php

namespace App\Models;
use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'TAB_USUARIOS';
    protected $primaryKey = 'ID_USUARIO';
    protected $allowedFields = ['ID_DATO_PERSONA', 'USUARIO', 'CONTRASENA', 'ESTADO'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    // Validaciones
    protected $validationRules = [
        'USUARIO' => 'required|min_length[3]|max_length[50]|is_unique[TAB_USUARIOS.USUARIO,ID_USUARIO,{ID_USUARIO}]',
        'CONTRASENA' => 'required|min_length[6]',
        'ESTADO' => 'required|in_list[A,I]',
        'ID_DATO_PERSONA' => 'required|integer'
    ];

    protected $validationMessages = [
        'USUARIO' => [
            'required' => 'El nombre de usuario es obligatorio',
            'is_unique' => 'Este nombre de usuario ya existe',
            'min_length' => 'El usuario debe tener al menos 3 caracteres'
        ],
        'CONTRASENA' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ]
    ];

    // Hash de contraseña automático
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['CONTRASENA'])) {
            $data['data']['CONTRASENA'] = password_hash($data['data']['CONTRASENA'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    // Función auxiliar para verificar rol
    public function tieneRol($idUsuario, $roles)
    {
        $rolModel = new RolesModel();
        $tipoRolModel = new TiposRolesModel();
        
        if (!is_array($roles)) {
            $roles = [$roles]; // Convertir a array si es un solo rol
        }
        
        $tiposRoles = $tipoRolModel->whereIn('ROL', $roles)->findAll();
        $rolIds = array_column($tiposRoles, 'ID_TIPOS_ROLES');
        
        $asignacion = $rolModel
            ->where('ID_USUARIO', $idUsuario)
            ->whereIn('ID_TIPOS_ROLES', $rolIds)
            ->first();
            
        return !empty($asignacion);
    }

    // Obtener datos completos del usuario con su perfil
    public function getConPerfil($idUsuario)
    {
        $builder = $this->db->table('TAB_USUARIOS u')
            ->select('u.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.FOTO_URL, r.ID_TIPOS_ROLES, tr.ROL')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA', 'left')
            ->join('TAB_ROLES r', 'r.ID_USUARIO = u.ID_USUARIO', 'left')
            ->join('TAB_TIPOS_ROLES tr', 'tr.ID_TIPOS_ROLES = r.ID_TIPOS_ROLES', 'left')
            ->where('u.ID_USUARIO', $idUsuario);
            
        return $builder->get()->getRowArray();
    }
    
    // Obtener todos los usuarios activos con sus roles
    public function getUsuariosConRoles()
    {
        $builder = $this->db->table('TAB_USUARIOS u')
            ->select('u.ID_USUARIO, u.USUARIO, u.ESTADO, dp.NOMBRE, dp.APELLIDO, dp.EMAIL, tr.ROL')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA', 'left')
            ->join('TAB_ROLES r', 'r.ID_USUARIO = u.ID_USUARIO', 'left')
            ->join('TAB_TIPOS_ROLES tr', 'tr.ID_TIPOS_ROLES = r.ID_TIPOS_ROLES', 'left')
            ->where('u.ESTADO', 'A');
            
        return $builder->get()->getResultArray();
    }
}