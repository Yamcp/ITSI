<?php

namespace App\Models;

use CodeIgniter\Model;

class ConveniosModel extends Model
{
    protected $table = 'TAB_CONVENIOS';
    protected $primaryKey = 'ID_CONVENIO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_TIPO_CONVENIO', 'ID_INSTITUCION_CONVENIO', 'FECHA_INICIO',
        'FECHA_FIN', 'OBSERVACIONES', 'ARCHIVO_CONVENIO', 'RENOVABLE'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'OBSERVACIONES' => 'required',
        'ARCHIVO_CONVENIO' => 'required|max_length[255]'
    ];

    // Relación con instituciones y tipos
    public function getConvenioCompleto($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_CONVENIOS.*, TAB_INSTITUCIONES_CONVENIOS.NOMBRE as INSTITUCION, TAB_TIPOS_CONVENIOS.CONVENIO as TIPO_CONVENIO')
            ->join('TAB_INSTITUCIONES_CONVENIOS', 'TAB_CONVENIOS.ID_INSTITUCION_CONVENIO = TAB_INSTITUCIONES_CONVENIOS.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_CONVENIOS', 'TAB_CONVENIOS.ID_TIPO_CONVENIO = TAB_TIPOS_CONVENIOS.ID_TIPO_CONVENIO');
        
        if ($id) {
            $builder->where('TAB_CONVENIOS.ID_CONVENIO', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}