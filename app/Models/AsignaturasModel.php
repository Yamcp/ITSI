<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignaturasModel extends Model
{
    protected $table = 'TAB_ASIGNATURAS';
    protected $primaryKey = 'ID_ASIGNATURA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_CARRERA', 'NOMBRE', 'SEMESTRE', 'CREDITOS'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'NOMBRE' => 'required|max_length[100]',
        'SEMESTRE' => 'required|integer',
        'CREDITOS' => 'required|integer'
    ];

    // Relación con carreras
    public function getAsignaturasConCarrera($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_ASIGNATURAS.*, TAB_CARRERAS.NOMBRE as NOMBRE_CARRERA')
            ->join('TAB_CARRERAS', 'TAB_ASIGNATURAS.ID_CARRERA = TAB_CARRERAS.ID_CARRERA');
        
        if ($id) {
            $builder->where('TAB_ASIGNATURAS.ID_ASIGNATURA', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}