<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'TAB_USUARIOS';
    protected $primaryKey = 'ID_USUARIO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_DATO_PERSONA', 'USUARIO', 'CONTRASENA', 'ESTADO'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'USUARIO' => 'required|max_length[20]|is_unique[TAB_USUARIOS.USUARIO]',
        'CONTRASENA' => 'required|max_length[60]',
        'ESTADO' => 'required|max_length[1]'
    ];

    // Relación con datos personas
    public function getUsuarioConDatos($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_USUARIOS.*, TAB_DATOS_PERSONAS.*')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA');
        
        if ($id) {
            $builder->where('TAB_USUARIOS.ID_USUARIO', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}