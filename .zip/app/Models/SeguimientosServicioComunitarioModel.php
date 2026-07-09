<?php

namespace App\Models;

use CodeIgniter\Model;

class SeguimientosServicioComunitarioModel extends Model
{
    protected $table = 'TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_SEGUIMIENTO_SERVICIO';
    protected $allowedFields = [
        'ID_SERVICIO_COMUNITARIO',
        'HORAS_CUMPLIDAS',
        'ACTIVIDADES_REALIZADAS',
        'BENEFICIARIOS_ATENDIDOS',
        'OBSERVACIONES',
        'ARCHIVO_REPORTE',
        'FECHA_REPORTE'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_SERVICIO_COMUNITARIO' => 'required|integer',
        'HORAS_CUMPLIDAS' => 'required|integer',
        'ACTIVIDADES_REALIZADAS' => 'required',
        'OBSERVACIONES' => 'required',
        'ARCHIVO_REPORTE' => 'required'
    ];
}
