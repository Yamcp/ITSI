<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudiantesModel extends Model
{
    protected $table = 'TAB_ESTUDIANTES';
    protected $primaryKey = 'ID_ESTUDIANTE';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_TIPO_ESTADO', 'ID_ASIGNATURA', 'ID_DATO_PERSONA',
        'SEMESTRE_ACTUAL', 'PROMEDIO_GENERAL', 'MATERIAS_APROBADAS'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'SEMESTRE_ACTUAL' => 'required|integer',
        'PROMEDIO_GENERAL' => 'required|decimal',
        'MATERIAS_APROBADAS' => 'required|integer'
    ];

    // Relación completa con datos personales y carrera
    public function getEstudianteCompleto($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('TAB_ESTUDIANTES.*, TAB_DATOS_PERSONAS.*, TAB_ASIGNATURAS.NOMBRE as ASIGNATURA, TAB_CARRERAS.NOMBRE as CARRERA, TAB_TIPOS_ESTADOS.ESTADO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_ESTUDIANTES.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_ASIGNATURAS', 'TAB_ESTUDIANTES.ID_ASIGNATURA = TAB_ASIGNATURAS.ID_ASIGNATURA')
            ->join('TAB_CARRERAS', 'TAB_ASIGNATURAS.ID_CARRERA = TAB_CARRERAS.ID_CARRERA')
            ->join('TAB_TIPOS_ESTADOS', 'TAB_ESTUDIANTES.ID_TIPO_ESTADO = TAB_TIPOS_ESTADOS.ID_TIPO_ESTADO');
        
        if ($id) {
            $builder->where('TAB_ESTUDIANTES.ID_ESTUDIANTE', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
}