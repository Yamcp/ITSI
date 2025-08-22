<?php

namespace App\Models;
use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table = 'TAB_ROLES';
    protected $primaryKey = 'ID_ROL';
    protected $allowedFields = ['ID_USUARIO', 'ID_TIPOS_ROLES'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_USUARIO' => 'required|integer',
        'ID_TIPOS_ROLES' => 'required|integer'
    ];
    
    // Verificar si ya existe asignación para no duplicar
    protected $beforeInsert = ['verificarUnico'];
    
    protected function verificarUnico(array $data)
    {
        if (isset($data['data']['ID_USUARIO']) && isset($data['data']['ID_TIPOS_ROLES'])) {
            $existe = $this->where('ID_USUARIO', $data['data']['ID_USUARIO'])
                         ->where('ID_TIPOS_ROLES', $data['data']['ID_TIPOS_ROLES'])
                         ->first();
            
            if ($existe) {
                return false; // Evitar inserción duplicada
            }
        }
        return $data;
    }
    
    // Obtener roles por usuario
    public function getRolesPorUsuario($idUsuario) 
    {
        $builder = $this->db->table('TAB_ROLES r')
            ->select('r.ID_ROL, r.ID_TIPOS_ROLES, tr.ROL')
            ->join('TAB_TIPOS_ROLES tr', 'tr.ID_TIPOS_ROLES = r.ID_TIPOS_ROLES')
            ->where('r.ID_USUARIO', $idUsuario);
            
        return $builder->get()->getResultArray();
    }
}