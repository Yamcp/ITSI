<?php

namespace App\Models;

use CodeIgniter\Model;

class SeguimientosPracticasPreprofesionalesModel extends Model
{
    protected $table = 'TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_SEGUIMIENTO_PREPROFESIONAL';
    protected $allowedFields = [
        'ID_PRACTICA_PREPROFESIONAL',
        'HORAS_CUMPLIDAS',
        'ACTIVIDADES_REALIZADAS',
        'COMPETENCIAS_DESARROLLADAS',
        'OBSERVACIONES',
        'ARCHIVO_REPORTE',
        'FECHA_REPORTE'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_PRACTICA_PREPROFESIONAL' => 'required|integer',
        'HORAS_CUMPLIDAS' => 'required|integer',
        'ACTIVIDADES_REALIZADAS' => 'required',
        'OBSERVACIONES' => 'required',
        'ARCHIVO_REPORTE' => 'required'
    ];
}
