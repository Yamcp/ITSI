<?php

namespace App\Models;

use CodeIgniter\Model;

class ActividadesEducacionModel extends Model
{
    protected $table = 'TAB_ACTIVIDADES_EDUCACION';
    protected $primaryKey = 'ID_ACTIVIDAD_EDUCACION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_AREA_TEMATICA', 'ID_INSTRUCTOR', 'ID_TIPO_MODALIDAD', 'ID_TIPO_ACTIVIDAD',
        'ID_USUARIO', 'NOMBRE_ACTIVIDAD', 'DESCRIPCION', 'OBJETIVOS', 'DURACION_HORAS',
        'FECHA_INICIO', 'FECHA_FIN', 'LUGAR', 'HORARIO', 'INCLUYE_CERTIFICADO',
        'PROGRAMA_DETALLADO'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'NOMBRE_ACTIVIDAD' => 'required|max_length[200]',
        'DESCRIPCION' => 'required',
        'OBJETIVOS' => 'required',
        'DURACION_HORAS' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'LUGAR' => 'required|max_length[150]',
        'HORARIO' => 'required|max_length[100]'
    ];
}