<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionesEnlacesModel extends Model
{
    protected $table = 'TAB_EVALUACIONES_ENLACES';
    protected $primaryKey = 'ID_EVALUACION_ENLACE';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_ACTIVIDAD_EDUCACION',
        'ID_USUARIO_CREADOR',
        'NOMBRE_EVALUACION',
        'TIPO_EVALUACION',
        'ENLACE_FORMULARIO',
        'DESCRIPCION',
        'FECHA_VENCIMIENTO',
        'ESTADO',
        'NUMERO_RESPUESTAS',
        'ACTIVO',
        'FECHA_ACTUALIZACION'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_ACTIVIDAD_EDUCACION' => 'required|integer',
        'NOMBRE_EVALUACION' => 'required|max_length[200]',
        'TIPO_EVALUACION' => 'required|max_length[50]',
        'ENLACE_FORMULARIO' => 'required|max_length[500]|valid_url',
        'FECHA_VENCIMIENTO' => 'required|valid_date',
        'ESTADO' => 'required|in_list[activo,inactivo,borrador]'
    ];

    protected $validationMessages = [
        'ID_ACTIVIDAD_EDUCACION' => [
            'required' => 'El curso es requerido',
            'integer' => 'El ID del curso debe ser un número entero'
        ],
        'NOMBRE_EVALUACION' => [
            'required' => 'El nombre de la evaluación es requerido',
            'max_length' => 'El nombre no puede exceder 200 caracteres'
        ],
        'TIPO_EVALUACION' => [
            'required' => 'El tipo de evaluación es requerido',
            'max_length' => 'El tipo no puede exceder 50 caracteres'
        ],
        'ENLACE_FORMULARIO' => [
            'required' => 'El enlace del formulario es requerido',
            'max_length' => 'El enlace no puede exceder 500 caracteres',
            'valid_url' => 'Debe proporcionar un enlace válido'
        ],
        'FECHA_VENCIMIENTO' => [
            'required' => 'La fecha de vencimiento es requerida',
            'valid_date' => 'Debe proporcionar una fecha válida'
        ],
        'ESTADO' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser: activo, inactivo o borrador'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtener todas las evaluaciones con información relacionada
     */
    public function obtenerEvaluacionesCompletas()
    {
        $builder = $this->db->table($this->table . ' e');
        $builder->select('
            e.ID_EVALUACION_ENLACE,
            e.ID_ACTIVIDAD_EDUCACION,
            e.NOMBRE_EVALUACION,
            e.TIPO_EVALUACION,
            e.ENLACE_FORMULARIO,
            e.DESCRIPCION,
            e.FECHA_CREACION,
            e.FECHA_VENCIMIENTO,
            e.ESTADO,
            e.NUMERO_RESPUESTAS,
            e.ACTIVO,
            a.NOMBRE_ACTIVIDAD,
            u.USUARIO as USUARIO_CREADOR
        ');
        $builder->join('TAB_ACTIVIDADES_EDUCACION a', 'a.ID_ACTIVIDAD_EDUCACION = e.ID_ACTIVIDAD_EDUCACION', 'left');
        $builder->join('TAB_USUARIOS u', 'u.ID_USUARIO = e.ID_USUARIO_CREADOR', 'left');
        $builder->where('e.ACTIVO', true);
        $builder->orderBy('e.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener una evaluación específica con información relacionada
     */
    public function obtenerEvaluacionCompleta($id)
    {
        $builder = $this->db->table($this->table . ' e');
        $builder->select('
            e.ID_EVALUACION_ENLACE,
            e.ID_ACTIVIDAD_EDUCACION,
            e.NOMBRE_EVALUACION,
            e.TIPO_EVALUACION,
            e.ENLACE_FORMULARIO,
            e.DESCRIPCION,
            e.FECHA_CREACION,
            e.FECHA_VENCIMIENTO,
            e.ESTADO,
            e.NUMERO_RESPUESTAS,
            e.ACTIVO,
            a.NOMBRE_ACTIVIDAD,
            u.USUARIO as USUARIO_CREADOR
        ');
        $builder->join('TAB_ACTIVIDADES_EDUCACION a', 'a.ID_ACTIVIDAD_EDUCACION = e.ID_ACTIVIDAD_EDUCACION', 'left');
        $builder->join('TAB_USUARIOS u', 'u.ID_USUARIO = e.ID_USUARIO_CREADOR', 'left');
        $builder->where('e.ID_EVALUACION_ENLACE', $id);
        $builder->where('e.ACTIVO', true);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener evaluaciones por estado
     */
    public function obtenerPorEstado($estado)
    {
        return $this->where('ESTADO', $estado)
                   ->where('ACTIVO', true)
                   ->orderBy('FECHA_CREACION', 'DESC')
                   ->findAll();
    }

    /**
     * Obtener evaluaciones por tipo
     */
    public function obtenerPorTipo($tipo)
    {
        return $this->where('TIPO_EVALUACION', $tipo)
                   ->where('ACTIVO', true)
                   ->orderBy('FECHA_CREACION', 'DESC')
                   ->findAll();
    }

    /**
     * Obtener evaluaciones vencidas
     */
    public function obtenerVencidas()
    {
        return $this->where('FECHA_VENCIMIENTO <', date('Y-m-d'))
                   ->where('ESTADO', 'activo')
                   ->where('ACTIVO', true)
                   ->orderBy('FECHA_VENCIMIENTO', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener estadísticas de evaluaciones
     */
    public function obtenerEstadisticas()
    {
        $total = $this->where('ACTIVO', true)->countAllResults();
        $activas = $this->where('ESTADO', 'activo')->where('ACTIVO', true)->countAllResults();
        $inactivas = $this->where('ESTADO', 'inactivo')->where('ACTIVO', true)->countAllResults();
        $borrador = $this->where('ESTADO', 'borrador')->where('ACTIVO', true)->countAllResults();
        
        // Total de respuestas
        $totalRespuestas = $this->selectSum('NUMERO_RESPUESTAS')
                              ->where('ACTIVO', true)
                              ->get()
                              ->getRow();
        
        $promedioRespuestas = $total > 0 ? round($totalRespuestas->NUMERO_RESPUESTAS / $total, 1) : 0;

        return [
            'total' => $total,
            'activas' => $activas,
            'inactivas' => $inactivas,
            'borrador' => $borrador,
            'total_respuestas' => $totalRespuestas->NUMERO_RESPUESTAS ?? 0,
            'promedio_respuestas' => $promedioRespuestas
        ];
    }

    /**
     * Actualizar número de respuestas
     */
    public function actualizarRespuestas($id, $numeroRespuestas)
    {
        return $this->update($id, ['NUMERO_RESPUESTAS' => $numeroRespuestas]);
    }

    /**
     * Cambiar estado de evaluación
     */
    public function cambiarEstado($id, $nuevoEstado)
    {
        return $this->update($id, ['ESTADO' => $nuevoEstado]);
    }

    /**
     * Obtener evaluaciones con filtros
     */
    public function obtenerConFiltros($filtros = [])
    {
        $builder = $this->db->table($this->table . ' e');
        $builder->select('
            e.ID_EVALUACION_ENLACE,
            e.NOMBRE_EVALUACION,
            e.TIPO_EVALUACION,
            e.ENLACE_FORMULARIO,
            e.DESCRIPCION,
            e.FECHA_CREACION,
            e.FECHA_VENCIMIENTO,
            e.ESTADO,
            e.NUMERO_RESPUESTAS,
            e.ACTIVO,
            a.NOMBRE_ACTIVIDAD
        ');
        $builder->join('TAB_ACTIVIDADES_EDUCACION a', 'a.ID_ACTIVIDAD_EDUCACION = e.ID_ACTIVIDAD_EDUCACION', 'left');
        $builder->where('e.ACTIVO', true);

        // Aplicar filtros
        if (!empty($filtros['tipo'])) {
            $builder->where('e.TIPO_EVALUACION', $filtros['tipo']);
        }

        if (!empty($filtros['estado'])) {
            $builder->where('e.ESTADO', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $builder->where('e.FECHA_CREACION >=', $filtros['fecha_desde'] . ' 00:00:00');
        }

        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('e.FECHA_CREACION <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }

        $builder->orderBy('e.FECHA_CREACION', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener evaluaciones disponibles para docentes
     * Solo muestra evaluaciones activas y no vencidas
     */
    public function obtenerEvaluacionesParaDocentes()
    {
        $builder = $this->db->table($this->table . ' e');
        $builder->select('
            e.ID_EVALUACION_ENLACE,
            e.NOMBRE_EVALUACION,
            e.TIPO_EVALUACION,
            e.ENLACE_FORMULARIO,
            e.DESCRIPCION,
            e.FECHA_CREACION,
            e.FECHA_VENCIMIENTO,
            e.ESTADO,
            e.NUMERO_RESPUESTAS,
            a.NOMBRE_ACTIVIDAD
        ');
        $builder->join('TAB_ACTIVIDADES_EDUCACION a', 'a.ID_ACTIVIDAD_EDUCACION = e.ID_ACTIVIDAD_EDUCACION', 'left');
        $builder->where('e.ESTADO', 'activo');
        $builder->where('e.ACTIVO', true);
        $builder->where('e.FECHA_VENCIMIENTO >=', date('Y-m-d'));
        $builder->orderBy('e.FECHA_VENCIMIENTO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener evaluaciones disponibles para estudiantes
     * Solo muestra evaluaciones activas y no vencidas
     */
    public function obtenerEvaluacionesParaEstudiantes()
    {
        $builder = $this->db->table($this->table . ' e');
        $builder->select('
            e.ID_EVALUACION_ENLACE,
            e.NOMBRE_EVALUACION,
            e.TIPO_EVALUACION,
            e.ENLACE_FORMULARIO,
            e.DESCRIPCION,
            e.FECHA_CREACION,
            e.FECHA_VENCIMIENTO,
            e.ESTADO,
            e.NUMERO_RESPUESTAS,
            a.NOMBRE_ACTIVIDAD
        ');
        $builder->join('TAB_ACTIVIDADES_EDUCACION a', 'a.ID_ACTIVIDAD_EDUCACION = e.ID_ACTIVIDAD_EDUCACION', 'left');
        $builder->where('e.ESTADO', 'activo');
        $builder->where('e.ACTIVO', true);
        $builder->where('e.FECHA_VENCIMIENTO >=', date('Y-m-d'));
        $builder->orderBy('e.FECHA_VENCIMIENTO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas para docentes
     */
    public function obtenerEstadisticasParaDocentes()
    {
        $total = $this->where('ESTADO', 'activo')
                      ->where('ACTIVO', true)
                      ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
                      ->countAllResults();
        
        $activas = $this->where('ESTADO', 'activo')
                        ->where('ACTIVO', true)
                        ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
                        ->countAllResults();
        
        $pendientes = $total; // Para docentes, todas las activas son pendientes de completar

        return [
            'total' => $total,
            'activas' => $activas,
            'pendientes' => $pendientes
        ];
    }

    /**
     * Obtener estadísticas para estudiantes
     */
    public function obtenerEstadisticasParaEstudiantes()
    {
        $total = $this->where('ESTADO', 'activo')
                      ->where('ACTIVO', true)
                      ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
                      ->countAllResults();
        
        $activas = $this->where('ESTADO', 'activo')
                        ->where('ACTIVO', true)
                        ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
                        ->countAllResults();
        
        $pendientes = $total; // Para estudiantes, todas las activas son pendientes de completar

        return [
            'total' => $total,
            'activas' => $activas,
            'pendientes' => $pendientes
        ];
    }

    /**
     * Obtener evaluaciones próximas a vencer (7 días o menos)
     */
    public function obtenerProximasAVencer($dias = 7)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->where('ESTADO', 'activo')
                   ->where('ACTIVO', true)
                   ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
                   ->where('FECHA_VENCIMIENTO <=', $fechaLimite)
                   ->orderBy('FECHA_VENCIMIENTO', 'ASC')
                   ->findAll();
    }
}
