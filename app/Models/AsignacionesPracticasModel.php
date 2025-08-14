<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionesPracticasModel extends Model
{
    protected $table = 'TAB_ASIGNACIONES_PRACTICAS';
    protected $primaryKey = 'ID_ASIGNACION_PRACTICA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_TIPO_PRACTICA', 'ID_USUARIO', 'ID_ESTADO_PRACTICAS',
        'FECHA_INICIO', 'FECHA_FIN', 'HORA_TOTAL', 'DESCRIPCION', 'CRONOGRAMA'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'HORA_TOTAL' => 'required|integer',
        'DESCRIPCION' => 'required',
        'CRONOGRAMA' => 'required|max_length[255]'
    ];

    // Relación con usuario y tipo de práctica
    public function getAsignacionCompleta($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_ASIGNACIONES_PRACTICAS.*, TAB_TIPOS_PRACTICAS.PRACTICA, TAB_ESTADO_PRACTICAS.ESTADO, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_TIPOS_PRACTICAS', 'TAB_ASIGNACIONES_PRACTICAS.ID_TIPO_PRACTICA = TAB_TIPOS_PRACTICAS.ID_TIPO_PRACTICA')
            ->join('TAB_ESTADO_PRACTICAS', 'TAB_ASIGNACIONES_PRACTICAS.ID_ESTADO_PRACTICAS = TAB_ESTADO_PRACTICAS.ID_ESTADO_PRACTICAS')
            ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA');
        
        if ($id) {
            $builder->where('TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}