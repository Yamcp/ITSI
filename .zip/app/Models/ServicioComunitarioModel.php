<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioComunitarioModel extends Model
{
    protected $table = 'TAB_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_SERVICIO_COMUNITARIO';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA',
        'ID_ESTUDIANTE',
        'ID_INSTRUCTOR',
        'ID_INSTITUCION_CONVENIO',
        'PROYECTO_SOCIAL',
        'COMUNIDAD_BENEFICIADA',
        'HORAS_SERVICIO',
        'FECHA_INICIO',
        'FECHA_FIN',
        'ESTADO_SERVICIO',
        'IMPACTO_SOCIAL',
        'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_ASIGNACION_PRACTICA' => 'required|integer',
        'ID_ESTUDIANTE' => 'required|integer',
        'ID_INSTRUCTOR' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'HORAS_SERVICIO' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'ESTADO_SERVICIO' => 'required|in_list[Pendiente,En Progreso,Completado,Cancelado]'
    ];
}
