<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciasPracticasPreprofesionalesModel extends Model
{
    protected $table = 'TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_ASISTENCIA_PREPROFESIONAL';
    protected $allowedFields = [
        'ID_PRACTICA_PREPROFESIONAL',
        'FECHA_ASISTENCIA',
        'HORA_ENTRADA',
        'HORA_SALIDA',
        'ACTIVIDADES_DIA',
        'COMPETENCIAS_DESARROLLADAS',
        'FECHA_REGISTRO',
        'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_PRACTICA_PREPROFESIONAL' => 'required|integer',
        'FECHA_ASISTENCIA' => 'required|valid_date',
        'HORA_ENTRADA' => 'required',
        'HORA_SALIDA' => 'required',
        'ACTIVIDADES_DIA' => 'required',
        'OBSERVACIONES' => 'permit_empty'
    ];
}
