<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciasPracticasModel extends Model
{
    protected $table = 'TAB_ASISTENCIAS_PRACTICAS';
    protected $primaryKey = 'ID_ASISTENCIA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA', 'FECHA_ASISTENCIA', 'HORA_ENTRADA',
        'HORA_SALIDA', 'ACTIVIDADES_DIA', 'FECHA_REGISTRO', 'OBSERVACIONES'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'FECHA_ASISTENCIA' => 'valid_date',
        'HORA_ENTRADA' => 'required',
        'HORA_SALIDA' => 'required',
        'ACTIVIDADES_DIA' => 'required'
    ];

    // Método para calcular horas trabajadas
    public function calcularHorasTrabajadas($horaEntrada, $horaSalida)
    {
        $entrada = new \DateTime($horaEntrada);
        $salida = new \DateTime($horaSalida);
        $diff = $entrada->diff($salida);
        return $diff->h + ($diff->i / 60);
    }
}