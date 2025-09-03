<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosPracticasModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_DOCUMENTO_PREPROFESIONAL';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_PRACTICA_PREPROFESIONAL',
        'ID_TIPO_DOCUMENTO',
        'NOMBRE_ARCHIVO',
        'TIPO_ARCHIVO',
        'FECHA_SUBIDA',
        'ESTADO_REVISION',
        'OBSERVACIONES'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_PRACTICA_PREPROFESIONAL' => 'required|integer|is_natural_no_zero',
        'ID_TIPO_DOCUMENTO' => 'required|integer|is_natural_no_zero',
        'NOMBRE_ARCHIVO' => 'required|max_length[255]',
        'TIPO_ARCHIVO' => 'required|max_length[100]',
        'FECHA_SUBIDA' => 'required|valid_date',
        'ESTADO_REVISION' => 'permit_empty|max_length[50]',
        'OBSERVACIONES' => 'permit_empty'
    ];

    protected $validationMessages = [
        'ID_PRACTICA_PREPROFESIONAL' => [
            'required' => 'La práctica preprofesional es requerida',
            'integer' => 'La práctica debe ser un número entero',
            'is_natural_no_zero' => 'La práctica debe ser un número positivo'
        ],
        'ID_TIPO_DOCUMENTO' => [
            'required' => 'El tipo de documento es requerido',
            'integer' => 'El tipo debe ser un número entero',
            'is_natural_no_zero' => 'El tipo debe ser un número positivo'
        ],
        'NOMBRE_ARCHIVO' => [
            'required' => 'El nombre del archivo es requerido',
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
        ],
        'TIPO_ARCHIVO' => [
            'required' => 'El tipo de archivo es requerido',
            'max_length' => 'El tipo de archivo no puede exceder 100 caracteres'
        ],
        'FECHA_SUBIDA' => [
            'required' => 'La fecha de subida es requerida',
            'valid_date' => 'Debe proporcionar una fecha válida'
        ],
        'ESTADO_REVISION' => [
            'max_length' => 'El estado de revisión no puede exceder 50 caracteres'
        ],
        'OBSERVACIONES' => [
            'max_length' => 'Las observaciones no pueden exceder el límite permitido'
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
     * Verificar si una columna existe en la tabla
     */
    private function columnExists($columnName)
    {
        $query = $this->db->query("
            SELECT COUNT(*) as count 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = '{$this->table}' 
              AND COLUMN_NAME = '{$columnName}'
        ");
        $result = $query->getRow();
        return $result->count > 0;
    }

    /**
     * Obtener documentos completos con información relacionada
     */
    public function getDocumentosCompletos()
    {
        $builder = $this->db->table($this->table . ' dp');
        
        // Construir SELECT dinámicamente basado en las columnas existentes
        $selectFields = '
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            er.ID_ESTADO_REVISION,
            dp.ESTADO_REVISION,
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            u.ID_USUARIO,
,
            dp_persona.EMAIL as EMAIL_USUARIO
        ';
        
        // Agregar columnas opcionales solo si existen
        if ($this->columnExists('OBSERVACIONES_REVISOR')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.OBSERVACIONES_REVISOR,', $selectFields);
        }
        
        if ($this->columnExists('ENTIDAD_RECEPTORA')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.ENTIDAD_RECEPTORA,', $selectFields);
        }
        
        if ($this->columnExists('DOCENTE_TUTOR')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.DOCENTE_TUTOR,', $selectFields);
        }
        
        if ($this->columnExists('PRIORIDAD')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.PRIORIDAD,', $selectFields);
        }
        
        if ($this->columnExists('FECHA_CREACION')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.FECHA_CREACION,', $selectFields);
        }
        
        if ($this->columnExists('FECHA_ACTUALIZACION')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.FECHA_ACTUALIZACION,', $selectFields);
        }
        
        $builder->select($selectFields);
        // No hay tabla de estados, usar campo directo
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos por estudiante
     */
    public function getDocumentosPorEstudiante($idUsuario)
    {
        $builder = $this->db->table($this->table . ' dp');
        
        // Construir SELECT dinámicamente
        $selectFields = '
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            dp.ESTADO_REVISION,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE
        ';
        
        // Agregar columnas opcionales solo si existen
        if ($this->columnExists('OBSERVACIONES_REVISOR')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.OBSERVACIONES_REVISOR,', $selectFields);
        }
        
        if ($this->columnExists('ENTIDAD_RECEPTORA')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.ENTIDAD_RECEPTORA,', $selectFields);
        }
        
        if ($this->columnExists('DOCENTE_TUTOR')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.DOCENTE_TUTOR,', $selectFields);
        }
        
        if ($this->columnExists('PRIORIDAD')) {
            $selectFields = str_replace('dp.OBSERVACIONES,', 'dp.OBSERVACIONES, dp.PRIORIDAD,', $selectFields);
        }
        
        $builder->select($selectFields);
        // No hay tabla de estados, usar campo directo
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->where('dp.ID_USUARIO', $idUsuario);
        $builder->orderBy('tdp.CODIGO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos por tipo
     */
    public function getDocumentosPorTipo($idTipoDocumento)
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,

            dp.ESTADO_REVISION,

        ');
        // No hay tabla de estados, usar campo directo
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante
        $builder->where('dp.ID_TIPO_DOCUMENTO', $idTipoDocumento);
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos por estado de revisión
     */
    public function getDocumentosPorEstado($idEstadoRevision)
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,

            dp.ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,

        ');
        // No hay tabla de estados, usar campo directo
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante
        $builder->where('dp.ESTADO_REVISION', $idEstadoRevision);
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticas()
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as rechazados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as en_revision
        ');
        
        $result = $builder->get()->getRowArray();
        return $result ?: [
            'total' => 0,
            'pendientes' => 0,
            'aprobados' => 0,
            'rechazados' => 0,
            'en_revision' => 0
        ];
    }

    /**
     * Obtener estadísticas por tipo de documento
     */
    public function getEstadisticasPorTipo()
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            COUNT(dp.ID_DOCUMENTO_PRACTICA) as total_documentos,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as rechazados
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'right');
        $builder->groupBy('tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL, tdp.CODIGO, tdp.NOMBRE');
        $builder->orderBy('tdp.CODIGO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes($limite = 10)
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            dp.ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO
        ');
        // No hay tabla de estados, usar campo directo
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un estudiante ya tiene un documento de un tipo específico
     */
    public function verificarDocumentoExistente($idUsuario, $idTipoDocumento)
    {
        return $this->where('ID_PRACTICA_PREPROFESIONAL', $idUsuario)
                    ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
                    ->first();
    }

    /**
     * Obtener progreso de documentos por estudiante
     */
    public function getProgresoEstudiante($idUsuario)
    {
        $builder = $this->db->table($this->table . ' dp');
        
        // Construir SELECT dinámicamente
        $selectFields = '
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.FECHA_SUBIDA,
            er.ESTADO as ESTADO_REVISION
        ';
        
        // Agregar OBSERVACIONES_REVISOR solo si la columna existe
        if ($this->columnExists('OBSERVACIONES_REVISOR')) {
            $selectFields .= ', dp.OBSERVACIONES_REVISOR';
        }
        
        $builder->select($selectFields);
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'right');
        // No hay tabla de estados, usar campo directo
        $builder->where('dp.ID_USUARIO', $idUsuario);
        $builder->orWhere('dp.ID_USUARIO IS NULL');
        $builder->orderBy('tdp.CODIGO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Buscar documentos con filtros
     */
    public function buscarDocumentos($filtros = [])
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,

            dp.ESTADO_REVISION,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,

        ');
        // No hay tabla de estados, usar campo directo
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante

        // Aplicar filtros
        if (!empty($filtros['tipo_documento'])) {
            $builder->where('dp.ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('dp.ESTADO_REVISION', $filtros['estado']);
        }
        
        if (!empty($filtros['estudiante'])) {
            $builder->where('dp.ID_PRACTICA_PREPROFESIONAL', $filtros['estudiante']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('DATE(dp.FECHA_SUBIDA) >=', $filtros['fecha_desde']);
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('DATE(dp.FECHA_SUBIDA) <=', $filtros['fecha_hasta']);
        }
        
        if (!empty($filtros['entidad_receptora'])) {
            $builder->like('dp.ENTIDAD_RECEPTORA', $filtros['entidad_receptora']);
        }
        
        if (!empty($filtros['docente_tutor'])) {
            $builder->like('dp.DOCENTE_TUTOR', $filtros['docente_tutor']);
        }

        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos por prioridad
     */
    public function getDocumentosPorPrioridad($prioridad)
    {
        return $this->where('PRIORIDAD', $prioridad)
                    ->orderBy('FECHA_SUBIDA', 'DESC')
                    ->findAll();
    }

    /**
     * Actualizar estado de revisión
     */
    public function actualizarEstadoRevision($id, $idEstadoRevision, $observaciones = null)
    {
        $datos = [
            'ID_ESTADO_REVISION' => $idEstadoRevision,
            'FECHA_ACTUALIZACION' => date('Y-m-d H:i:s')
        ];
        
        if ($observaciones !== null && $this->columnExists('OBSERVACIONES_REVISOR')) {
            $datos['OBSERVACIONES_REVISOR'] = $observaciones;
        }
        
        return $this->update($id, $datos);
    }

    /**
     * Obtener documentos vencidos (pendientes por más de 30 días)
     */
    public function getDocumentosVencidos()
    {
        $fechaVencimiento = date('Y-m-d', strtotime('-30 days'));
        
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.ID_DOCUMENTO_PREPROFESIONAL,
            dp.NOMBRE_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.EMAIL as EMAIL_USUARIO
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        // No hay tabla de usuarios en documentos, usar práctica
        // Usar join con prácticas para obtener datos del estudiante
        $builder->where('dp.ID_ESTADO_REVISION', 1); // Pendiente
        $builder->where('DATE(dp.FECHA_SUBIDA) <=', $fechaVencimiento);
        $builder->orderBy('dp.FECHA_SUBIDA', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}