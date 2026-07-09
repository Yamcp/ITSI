<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciasServicioComunitarioModel extends Model
{
    protected $table = 'TAB_ASISTENCIAS_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_ASISTENCIA_SERVICIO';
    protected $allowedFields = [
        'ID_SERVICIO_COMUNITARIO',
        'FECHA_ASISTENCIA',
        'HORA_ENTRADA',
        'HORA_SALIDA',
        'ACTIVIDADES_DIA',
        'BENEFICIARIOS_ATENDIDOS',
        'FECHA_REGISTRO',
        'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_SERVICIO_COMUNITARIO' => 'required|integer',
        'FECHA_ASISTENCIA' => 'required|valid_date',
        'HORA_ENTRADA' => 'required',
        'HORA_SALIDA' => 'required',
        'ACTIVIDADES_DIA' => 'required',
        'OBSERVACIONES' => 'permit_empty'
    ];
}
