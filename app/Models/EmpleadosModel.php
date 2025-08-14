<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadosModel extends Model
{
    protected $table = 'TAB_EMPLEADOS';
    protected $primaryKey = 'ID_EMPLEADO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_DEPARTAMENTO', 'ID_DATO_PERSONA', 'ID_TIPO_CONTRATO',
        'CARGO', 'FECHA_INGRESO'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'CARGO' => 'required|max_length[100]',
        'FECHA_INGRESO' => 'required|valid_date'
    ];

    // Relación completa con datos personales y departamento
    public function getEmpleadoCompleto($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_EMPLEADOS.*, TAB_DATOS_PERSONAS.*, TAB_DEPARTAMENTOS.NOMBRE as DEPARTAMENTO, TAB_TIPO_CONTRATO.TIPO_CONTRATO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_EMPLEADOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_DEPARTAMENTOS', 'TAB_EMPLEADOS.ID_DEPARTAMENTO = TAB_DEPARTAMENTOS.ID_DEPARTAMENTO')
            ->join('TAB_TIPO_CONTRATO', 'TAB_EMPLEADOS.ID_TIPO_CONTRATO = TAB_TIPO_CONTRATO.ID_TIPO_CONTRATO');
        
        if ($id) {
            $builder->where('TAB_EMPLEADOS.ID_EMPLEADO', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}