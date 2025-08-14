<?php

namespace App\Models;

use CodeIgniter\Model;

class InstructoresModel extends Model
{
    protected $table = 'TAB_INSTRUCTORES';
    protected $primaryKey = 'ID_INSTRUCTOR';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_TIPO_INSTRUCTOR', 'ID_DATO_PERSONA', 'ID_EMPLEADO',
        'ESPECIALIDAD', 'TITULO_PROFESIONAL'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'ESPECIALIDAD' => 'required',
        'TITULO_PROFESIONAL' => 'required|max_length[200]'
    ];

    // Relación completa con datos personales
    public function getInstructorCompleto($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_INSTRUCTORES.*, TAB_DATOS_PERSONAS.*, TAB_TIPO_INSTRUCTORES.TIPO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_INSTRUCTORES.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_TIPO_INSTRUCTORES', 'TAB_INSTRUCTORES.ID_TIPO_INSTRUCTOR = TAB_TIPO_INSTRUCTORES.ID_TIPO_INSTRUCTOR');
        
        if ($id) {
            $builder->where('TAB_INSTRUCTORES.ID_INSTRUCTOR', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}