<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiciosComunitariosModel extends Model
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
        'ID_ESTUDIANTE' => 'required|integer',
        'ID_DOCENTE_SUPERVISOR' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'HORAS_SERVICIO' => 'required|integer|greater_than[0]',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'ESTADO_SERVICIO' => 'required|in_list[Pendiente,En Progreso,Completado,Cancelado]'
    ];

    protected $validationMessages = [
        'ID_ESTUDIANTE' => [
            'required' => 'El estudiante es obligatorio',
            'integer' => 'El ID del estudiante debe ser un número entero'
        ],
        'ID_DOCENTE_SUPERVISOR' => [
            'required' => 'El docente supervisor es obligatorio',
            'integer' => 'El ID del docente debe ser un número entero'
        ],
        'ID_INSTITUCION_CONVENIO' => [
            'required' => 'La institución es obligatoria',
            'integer' => 'El ID de la institución debe ser un número entero'
        ],
        'HORAS_SERVICIO' => [
            'required' => 'Las horas de servicio son obligatorias',
            'integer' => 'Las horas deben ser un número entero',
            'greater_than' => 'Las horas deben ser mayor a 0'
        ],
        'FECHA_INICIO' => [
            'required' => 'La fecha de inicio es obligatoria',
            'valid_date' => 'La fecha de inicio debe ser válida'
        ],
        'FECHA_FIN' => [
            'required' => 'La fecha de fin es obligatoria',
            'valid_date' => 'La fecha de fin debe ser válida'
        ],
        'ESTADO_SERVICIO' => [
            'required' => 'El estado del servicio es obligatorio',
            'in_list' => 'El estado debe ser: Pendiente, En Progreso, Completado o Cancelado'
        ]
    ];

    /**
     * Obtener servicios comunitarios con información del estudiante
     */
    public function getServiciosConEstudiante($docenteId = null)
    {
        $builder = $this->db->table($this->table . ' sc');
        $builder->select('sc.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE');
        $builder->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA');
        $builder->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO');
        
        if ($docenteId) {
            $builder->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        
        $builder->orderBy('sc.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener servicios comunitarios de un estudiante específico
     */
    public function getServiciosPorEstudiante($estudianteId)
    {
        return $this->where('ID_ESTUDIANTE', $estudianteId)->findAll();
    }

    /**
     * Obtener estadísticas de servicios comunitarios
     */
    public function getEstadisticas($docenteId = null)
    {
        $builder = $this->db->table($this->table);
        
        if ($docenteId) {
            $builder->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        
        $total = $builder->countAllResults();
        
        $activos = $this->db->table($this->table)
            ->where('ESTADO_SERVICIO', 'En Progreso');
        if ($docenteId) {
            $activos->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $activos = $activos->countAllResults();
        
        $completados = $this->db->table($this->table)
            ->where('ESTADO_SERVICIO', 'Completado');
        if ($docenteId) {
            $completados->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $completados = $completados->countAllResults();
        
        $pendientes = $this->db->table($this->table)
            ->where('ESTADO_SERVICIO', 'Pendiente');
        if ($docenteId) {
            $pendientes->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $pendientes = $pendientes->countAllResults();
        
        return [
            'total' => $total,
            'activos' => $activos,
            'completados' => $completados,
            'pendientes' => $pendientes
        ];
    }

    /**
     * Obtener servicios comunitarios próximos a vencer
     */
    public function getServiciosProximosVencer($dias = 7, $docenteId = null)
    {
        $builder = $this->db->table($this->table . ' sc');
        $builder->select('sc.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE');
        $builder->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO');
        $builder->where('sc.ESTADO_SERVICIO', 'En Progreso');
        $builder->where('sc.FECHA_FIN <= DATE_ADD(NOW(), INTERVAL ' . $dias . ' DAY)');
        $builder->where('sc.FECHA_FIN >= NOW()');
        
        if ($docenteId) {
            $builder->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        
        $builder->orderBy('sc.FECHA_FIN', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Calcular progreso de un servicio comunitario
     */
    public function calcularProgreso($servicioId)
    {
        $servicio = $this->find($servicioId);
        if (!$servicio) {
            return 0;
        }
        
        // Obtener horas cumplidas desde seguimiento (TAB_SEGUIMIENTO_* tiene HORAS_CUMPLIDAS)
        $horasCumplidas = $this->db->table('TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO')
            ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
            ->where('ID_SERVICIO_COMUNITARIO', $servicioId)
            ->get()
            ->getRow();
        
        $totalHoras = (int) ($horasCumplidas->total_horas ?? 0);
        $horasRequeridas = $servicio['HORAS_SERVICIO'];
        
        if ($horasRequeridas > 0) {
            return round(($totalHoras / $horasRequeridas) * 100, 2);
        }
        
        return 0;
    }

    /**
     * Obtener servicios comunitarios con progreso
     */
    public function getServiciosConProgreso($docenteId = null)
    {
        $servicios = $this->getServiciosConEstudiante($docenteId);
        
        foreach ($servicios as &$servicio) {
            $servicio['PROGRESO'] = $this->calcularProgreso($servicio['ID_SERVICIO_COMUNITARIO']);
        }
        
        return $servicios;
    }
}
