<?php

namespace App\Models;

use CodeIgniter\Model;

class ActividadesPracticasModel extends Model
{
    protected $table = 'actividades_practicas';
    protected $primaryKey = 'ID_ACTIVIDAD';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_ESTUDIANTE',
        'ID_PRACTICA',
        'TIPO_PRACTICA',
        'FECHA_ACTIVIDAD',
        'HORA_ENTRADA',
        'HORA_SALIDA',
        'ACTIVIDADES_REALIZADAS',
        'OBSERVACIONES',
        'FECHA_REGISTRO',
        'ESTADO'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_REGISTRO';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_ESTUDIANTE' => 'required|integer',
        'ID_PRACTICA' => 'required|integer',
        'TIPO_PRACTICA' => 'required|in_list[preprofesional,servicio]',
        'FECHA_ACTIVIDAD' => 'required|valid_date',
        'HORA_ENTRADA' => 'required',
        'HORA_SALIDA' => 'required',
        'ACTIVIDADES_REALIZADAS' => 'required|min_length[10]|max_length[1000]'
    ];

    protected $validationMessages = [
        'ID_ESTUDIANTE' => [
            'required' => 'El ID del estudiante es obligatorio',
            'integer' => 'El ID del estudiante debe ser un número entero'
        ],
        'ID_PRACTICA' => [
            'required' => 'El ID de la práctica es obligatorio',
            'integer' => 'El ID de la práctica debe ser un número entero'
        ],
        'TIPO_PRACTICA' => [
            'required' => 'El tipo de práctica es obligatorio',
            'in_list' => 'El tipo de práctica debe ser preprofesional o servicio'
        ],
        'FECHA_ACTIVIDAD' => [
            'required' => 'La fecha de la actividad es obligatoria',
            'valid_date' => 'La fecha debe ser válida'
        ],
        'HORA_ENTRADA' => [
            'required' => 'La hora de entrada es obligatoria'
        ],
        'HORA_SALIDA' => [
            'required' => 'La hora de salida es obligatoria'
        ],
        'ACTIVIDADES_REALIZADAS' => [
            'required' => 'Las actividades realizadas son obligatorias',
            'min_length' => 'Las actividades deben tener al menos 10 caracteres',
            'max_length' => 'Las actividades no pueden exceder 1000 caracteres'
        ]
    ];

    /**
     * Obtener actividades de un estudiante específico
     */
    public function getActividadesPorEstudiante($estudianteId, $tipoPractica = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        
        if ($tipoPractica) {
            $builder->where('TIPO_PRACTICA', $tipoPractica);
        }
        
        $builder->orderBy('FECHA_ACTIVIDAD', 'DESC');
        $builder->orderBy('HORA_ENTRADA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener actividades de una práctica específica
     */
    public function getActividadesPorPractica($practicaId, $tipoPractica)
    {
        $builder = $this->db->table($this->table);
        $builder->where('ID_PRACTICA', $practicaId);
        $builder->where('TIPO_PRACTICA', $tipoPractica);
        $builder->orderBy('FECHA_ACTIVIDAD', 'DESC');
        $builder->orderBy('HORA_ENTRADA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Calcular horas totales trabajadas por un estudiante
     */
    public function calcularHorasTotales($estudianteId, $tipoPractica = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('SUM(TIMESTAMPDIFF(HOUR, CONCAT(FECHA_ACTIVIDAD, " ", HORA_ENTRADA), CONCAT(FECHA_ACTIVIDAD, " ", HORA_SALIDA))) as total_horas');
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        
        if ($tipoPractica) {
            $builder->where('TIPO_PRACTICA', $tipoPractica);
        }
        
        $result = $builder->get()->getRow();
        return $result->total_horas ?? 0;
    }

    /**
     * Obtener estadísticas de actividades por período
     */
    public function getEstadisticasActividades($fechaInicio, $fechaFin, $estudianteId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_actividades,
            SUM(TIMESTAMPDIFF(HOUR, CONCAT(FECHA_ACTIVIDAD, " ", HORA_ENTRADA), CONCAT(FECHA_ACTIVIDAD, " ", HORA_SALIDA))) as total_horas,
            TIPO_PRACTICA
        ');
        $builder->where('FECHA_ACTIVIDAD >=', $fechaInicio);
        $builder->where('FECHA_ACTIVIDAD <=', $fechaFin);
        
        if ($estudianteId) {
            $builder->where('ID_ESTUDIANTE', $estudianteId);
        }
        
        $builder->groupBy('TIPO_PRACTICA');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener actividades recientes de un estudiante
     */
    public function getActividadesRecientes($estudianteId, $limite = 5)
    {
        $builder = $this->db->table($this->table);
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        $builder->orderBy('FECHA_ACTIVIDAD', 'DESC');
        $builder->orderBy('HORA_ENTRADA', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si existe actividad duplicada en la misma fecha
     */
    public function existeActividadDuplicada($estudianteId, $fechaActividad, $horaEntrada, $horaSalida)
    {
        $builder = $this->db->table($this->table);
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        $builder->where('FECHA_ACTIVIDAD', $fechaActividad);
        $builder->where('HORA_ENTRADA', $horaEntrada);
        $builder->where('HORA_SALIDA', $horaSalida);
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener resumen de actividades por mes
     */
    public function getResumenMensual($estudianteId, $año, $mes)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_actividades,
            SUM(TIMESTAMPDIFF(HOUR, CONCAT(FECHA_ACTIVIDAD, " ", HORA_ENTRADA), CONCAT(FECHA_ACTIVIDAD, " ", HORA_SALIDA))) as total_horas,
            TIPO_PRACTICA
        ');
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        $builder->where('YEAR(FECHA_ACTIVIDAD)', $año);
        $builder->where('MONTH(FECHA_ACTIVIDAD)', $mes);
        $builder->groupBy('TIPO_PRACTICA');
        
        return $builder->get()->getResultArray();
    }
}
