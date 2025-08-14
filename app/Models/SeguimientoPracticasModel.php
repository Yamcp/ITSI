<?php

namespace App\Models;

use CodeIgniter\Model;

class SeguimientoPracticasModel extends Model
{
    protected $table      = 'TAB_SEGUIMIENTO_PRACTICAS';
    protected $primaryKey = 'ID_SEGUIMIENTO';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA', 'HORAS_CUMPLIDAS', 'ACTIVIDADES_REALIZADAS',
        'OBSERVACIONES', 'ARCHIVO_REPORTE'
    ];
}