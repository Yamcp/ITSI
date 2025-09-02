<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticasPreprofesionalesModel extends Model
{
    protected $table = 'TAB_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_PRACTICA_PREPROFESIONAL';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA',
        'ID_ESTUDIANTE',
        'ID_INSTRUCTOR',
        'ID_INSTITUCION_CONVENIO',
        'AREA_ESPECIALIZACION',
        'PROYECTO_ESPECIFICO',
        'HORAS_PRACTICAS',
        'FECHA_INICIO',
        'FECHA_FIN',
        'ESTADO_PRACTICA',
        'EVALUACION_FINAL',
        'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_ASIGNACION_PRACTICA' => 'required|integer',
        'ID_ESTUDIANTE' => 'required|integer',
        'ID_INSTRUCTOR' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'HORAS_PRACTICAS' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'ESTADO_PRACTICA' => 'required|in_list[Pendiente,En Progreso,Completada,Cancelada]'
    ];
}
