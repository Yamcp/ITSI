<?php

namespace App\Models;

use CodeIgniter\Model;

class PracticasPreprofesionalesModel extends Model
{
    protected $table = 'TAB_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_PRACTICA_PREPROFESIONAL';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA',
        'ID_ESTUDIANTE',
        'ID_INSTRUCTOR',
        'ID_INSTITUCION_CONVENIO',
        'AREA_ESPECIALIZACION',
        'PROYECTO_ESPECIFICO',
        'HORAS_PRACTICAS',
        'FECHA_INICIO',
        'FECHA_FIN',
        'ESTADO_PRACTICA',
        'EVALUACION_FINAL',
        'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_ESTUDIANTE' => 'required|integer',
        'ID_INSTRUCTOR' => 'permit_empty|integer',
        'ID_INSTITUCION_CONVENIO' => 'permit_empty|integer',
        'HORAS_PRACTICAS' => 'required|integer|greater_than[0]',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'ESTADO_PRACTICA' => 'required|in_list[Pendiente,En Progreso,Completada,Cancelada]'
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
        'HORAS_PRACTICAS' => [
            'required' => 'Las horas de práctica son obligatorias',
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
        'ESTADO_PRACTICA' => [
            'required' => 'El estado de la práctica es obligatorio',
            'in_list' => 'El estado debe ser: Pendiente, En Progreso, Completada o Cancelada'
        ]
    ];

    /**
     * Obtener prácticas preprofesionales con información del estudiante
     */
    public function getPracticasConEstudiante($docenteId = null)
    {
        $builder = $this->db->table($this->table . ' pp');
        $builder->select('pp.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION');
        $builder->join('estudiantes e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE');
        $builder->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA');
        $builder->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO');
        
        if ($docenteId) {
            $builder->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        
        $builder->orderBy('pp.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener prácticas preprofesionales de un estudiante específico
     */
    public function getPracticasPorEstudiante($estudianteId)
    {
        return $this->where('ID_ESTUDIANTE', $estudianteId)->findAll();
    }

    /**
     * Obtener estadísticas de prácticas preprofesionales
     */
    public function getEstadisticas($docenteId = null)
    {
        $builder = $this->db->table($this->table);
        
        if ($docenteId) {
            $builder->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        
        $total = $builder->countAllResults();
        
        $activas = $this->db->table($this->table)
            ->where('ESTADO_PRACTICA', 'En Progreso');
        if ($docenteId) {
            $activas->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $activas = $activas->countAllResults();
        
        $completadas = $this->db->table($this->table)
            ->where('ESTADO_PRACTICA', 'Completada');
        if ($docenteId) {
            $completadas->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $completadas = $completadas->countAllResults();
        
        $pendientes = $this->db->table($this->table)
            ->where('ESTADO_PRACTICA', 'Pendiente');
        if ($docenteId) {
            $pendientes->where('ID_DOCENTE_SUPERVISOR', $docenteId);
        }
        $pendientes = $pendientes->countAllResults();
        
        return [
            'total' => $total,
            'activas' => $activas,
            'completadas' => $completadas,
            'pendientes' => $pendientes
        ];
    }

    /**
     * Calcular progreso de una práctica preprofesional
     */
    public function calcularProgreso($practicaId)
    {
        $practica = $this->find($practicaId);
        if (!$practica) {
            return 0;
        }
        
        // Obtener horas cumplidas desde seguimiento (TAB_SEGUIMIENTO_* tiene HORAS_CUMPLIDAS)
        $horasCumplidas = $this->db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES')
            ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
            ->where('ID_PRACTICA_PREPROFESIONAL', $practicaId)
            ->get()
            ->getRow();
        
        $totalHoras = (int) ($horasCumplidas->total_horas ?? 0);
        $horasRequeridas = $practica['HORAS_PRACTICAS'];
        
        if ($horasRequeridas > 0) {
            return round(($totalHoras / $horasRequeridas) * 100, 2);
        }
        
        return 0;
    }
}
