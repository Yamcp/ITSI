<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionesPracticasModel extends Model
{
    protected $table = 'evaluaciones_practicas';
    protected $primaryKey = 'ID_EVALUACION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_DOCENTE',
        'ID_ESTUDIANTE',
        'ID_PRACTICA',
        'TIPO_PRACTICA',
        'CRITERIO_EVALUACION',
        'CALIFICACION',
        'COMENTARIOS',
        'RECOMENDACIONES',
        'FECHA_EVALUACION',
        'FECHA_LIMITE',
        'ESTADO',
        'OBSERVACIONES'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_DOCENTE' => 'required|integer',
        'ID_ESTUDIANTE' => 'required|integer',
        'CRITERIO_EVALUACION' => 'required|max_length[100]',
        'CALIFICACION' => 'required|decimal|greater_than_equal_to[1]|less_than_equal_to[10]',
        'FECHA_EVALUACION' => 'required|valid_date',
        'ESTADO' => 'required|in_list[Pendiente,Completada,Cancelada]'
    ];

    protected $validationMessages = [
        'ID_DOCENTE' => [
            'required' => 'El ID del docente es obligatorio',
            'integer' => 'El ID del docente debe ser un número entero'
        ],
        'ID_ESTUDIANTE' => [
            'required' => 'El ID del estudiante es obligatorio',
            'integer' => 'El ID del estudiante debe ser un número entero'
        ],
        'CRITERIO_EVALUACION' => [
            'required' => 'El criterio de evaluación es obligatorio',
            'max_length' => 'El criterio no puede exceder 100 caracteres'
        ],
        'CALIFICACION' => [
            'required' => 'La calificación es obligatoria',
            'decimal' => 'La calificación debe ser un número decimal',
            'greater_than_equal_to' => 'La calificación debe ser mayor o igual a 1',
            'less_than_equal_to' => 'La calificación debe ser menor o igual a 10'
        ],
        'FECHA_EVALUACION' => [
            'required' => 'La fecha de evaluación es obligatoria',
            'valid_date' => 'La fecha debe ser válida'
        ],
        'ESTADO' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser Pendiente, Completada o Cancelada'
        ]
    ];

    /**
     * Obtener evaluaciones de un docente
     */
    public function getEvaluacionesPorDocente($docenteId, $estado = null)
    {
        $builder = $this->db->table($this->table . ' ep');
        $builder->select('ep.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, u.NOMBRE as DOCENTE_NOMBRE');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE');
        $builder->join('usuarios u', 'u.ID_USUARIO = ep.ID_DOCENTE');
        $builder->where('ep.ID_DOCENTE', $docenteId);
        
        if ($estado) {
            $builder->where('ep.ESTADO', $estado);
        }
        
        $builder->orderBy('ep.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener evaluaciones de un estudiante
     */
    public function getEvaluacionesPorEstudiante($estudianteId)
    {
        $builder = $this->db->table($this->table . ' ep');
        $builder->select('ep.*, u.NOMBRE as DOCENTE_NOMBRE');
        $builder->join('usuarios u', 'u.ID_USUARIO = ep.ID_DOCENTE');
        $builder->where('ep.ID_ESTUDIANTE', $estudianteId);
        $builder->orderBy('ep.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener evaluaciones pendientes de un docente
     */
    public function getEvaluacionesPendientes($docenteId)
    {
        return $this->getEvaluacionesPorDocente($docenteId, 'Pendiente');
    }

    /**
     * Obtener evaluaciones completadas de un docente
     */
    public function getEvaluacionesCompletadas($docenteId)
    {
        return $this->getEvaluacionesPorDocente($docenteId, 'Completada');
    }

    /**
     * Calcular promedio de calificaciones de un estudiante
     */
    public function calcularPromedioEstudiante($estudianteId)
    {
        $builder = $this->db->table($this->table);
        $builder->selectAvg('CALIFICACION', 'promedio');
        $builder->where('ID_ESTUDIANTE', $estudianteId);
        $builder->where('ESTADO', 'Completada');
        
        $result = $builder->get()->getRow();
        return round($result->promedio ?? 0, 2);
    }

    /**
     * Obtener estadísticas de evaluaciones por docente
     */
    public function getEstadisticasPorDocente($docenteId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_evaluaciones,
            SUM(CASE WHEN ESTADO = "Completada" THEN 1 ELSE 0 END) as completadas,
            SUM(CASE WHEN ESTADO = "Pendiente" THEN 1 ELSE 0 END) as pendientes,
            AVG(CASE WHEN ESTADO = "Completada" THEN CALIFICACION ELSE NULL END) as promedio_calificaciones
        ');
        $builder->where('ID_DOCENTE', $docenteId);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener evaluaciones próximas a vencer
     */
    public function getEvaluacionesProximasVencer($dias = 3)
    {
        $builder = $this->db->table($this->table . ' ep');
        $builder->select('ep.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, u.NOMBRE as DOCENTE_NOMBRE');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE');
        $builder->join('usuarios u', 'u.ID_USUARIO = ep.ID_DOCENTE');
        $builder->where('ep.ESTADO', 'Pendiente');
        $builder->where('ep.FECHA_LIMITE <= DATE_ADD(NOW(), INTERVAL ' . $dias . ' DAY)');
        $builder->orderBy('ep.FECHA_LIMITE', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener evaluaciones por criterio
     */
    public function getEvaluacionesPorCriterio($criterio, $docenteId = null)
    {
        $builder = $this->db->table($this->table . ' ep');
        $builder->select('ep.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, u.NOMBRE as DOCENTE_NOMBRE');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE');
        $builder->join('usuarios u', 'u.ID_USUARIO = ep.ID_DOCENTE');
        $builder->where('ep.CRITERIO_EVALUACION', $criterio);
        
        if ($docenteId) {
            $builder->where('ep.ID_DOCENTE', $docenteId);
        }
        
        $builder->orderBy('ep.FECHA_EVALUACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener resumen de evaluaciones por período
     */
    public function getResumenPorPeriodo($fechaInicio, $fechaFin, $docenteId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_evaluaciones,
            SUM(CASE WHEN ESTADO = "Completada" THEN 1 ELSE 0 END) as completadas,
            SUM(CASE WHEN ESTADO = "Pendiente" THEN 1 ELSE 0 END) as pendientes,
            AVG(CASE WHEN ESTADO = "Completada" THEN CALIFICACION ELSE NULL END) as promedio_calificaciones,
            CRITERIO_EVALUACION
        ');
        $builder->where('FECHA_EVALUACION >=', $fechaInicio);
        $builder->where('FECHA_EVALUACION <=', $fechaFin);
        
        if ($docenteId) {
            $builder->where('ID_DOCENTE', $docenteId);
        }
        
        $builder->groupBy('CRITERIO_EVALUACION');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Marcar evaluación como completada
     */
    public function marcarCompletada($evaluacionId, $calificacion, $comentarios = null, $recomendaciones = null)
    {
        $data = [
            'ESTADO' => 'Completada',
            'CALIFICACION' => $calificacion,
            'FECHA_EVALUACION' => date('Y-m-d H:i:s')
        ];
        
        if ($comentarios) {
            $data['COMENTARIOS'] = $comentarios;
        }
        
        if ($recomendaciones) {
            $data['RECOMENDACIONES'] = $recomendaciones;
        }
        
        return $this->update($evaluacionId, $data);
    }

    /**
     * Obtener evaluaciones con calificaciones bajas
     */
    public function getEvaluacionesCalificacionBaja($calificacionMinima = 6)
    {
        $builder = $this->db->table($this->table . ' ep');
        $builder->select('ep.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, u.NOMBRE as DOCENTE_NOMBRE');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE');
        $builder->join('usuarios u', 'u.ID_USUARIO = ep.ID_DOCENTE');
        $builder->where('ep.ESTADO', 'Completada');
        $builder->where('ep.CALIFICACION <', $calificacionMinima);
        $builder->orderBy('ep.CALIFICACION', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
