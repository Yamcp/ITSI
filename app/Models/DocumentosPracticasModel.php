<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosPracticasModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_PRACTICAS';
    protected $primaryKey = 'ID_DOCUMENTO_PRACTICA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_ESTADO_REVISION',
        'ID_TIPO_DOCUMENTO',
        'ID_USUARIO',
        'NOMBRE_ARCHIVO',
        'TIPO',
        'FECHA_SUBIDA',
        'OBSERVACIONES',
        'OBSERVACIONES_REVISOR',
        'ENTIDAD_RECEPTORA',
        'DOCENTE_TUTOR',
        'PRIORIDAD'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_ESTADO_REVISION' => 'required|integer|is_natural_no_zero',
        'ID_TIPO_DOCUMENTO' => 'required|integer|is_natural_no_zero',
        'ID_USUARIO' => 'required|integer|is_natural_no_zero',
        'NOMBRE_ARCHIVO' => 'required|max_length[255]',
        'TIPO' => 'required|max_length[100]',
        'FECHA_SUBIDA' => 'required|valid_date',
        'OBSERVACIONES' => 'permit_empty|max_length[1000]',
        'OBSERVACIONES_REVISOR' => 'permit_empty|max_length[1000]',
        'ENTIDAD_RECEPTORA' => 'permit_empty|max_length[255]',
        'DOCENTE_TUTOR' => 'permit_empty|max_length[255]',
        'PRIORIDAD' => 'permit_empty|in_list[baja,media,alta,urgente]'
    ];

    protected $validationMessages = [
        'ID_ESTADO_REVISION' => [
            'required' => 'El estado de revisión es requerido',
            'integer' => 'El estado debe ser un número entero',
            'is_natural_no_zero' => 'El estado debe ser un número positivo'
        ],
        'ID_TIPO_DOCUMENTO' => [
            'required' => 'El tipo de documento es requerido',
            'integer' => 'El tipo debe ser un número entero',
            'is_natural_no_zero' => 'El tipo debe ser un número positivo'
        ],
        'ID_USUARIO' => [
            'required' => 'El usuario es requerido',
            'integer' => 'El usuario debe ser un número entero',
            'is_natural_no_zero' => 'El usuario debe ser un número positivo'
        ],
        'NOMBRE_ARCHIVO' => [
            'required' => 'El nombre del archivo es requerido',
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
        ],
        'TIPO' => [
            'required' => 'El tipo de archivo es requerido',
            'max_length' => 'El tipo no puede exceder 100 caracteres'
        ],
        'FECHA_SUBIDA' => [
            'required' => 'La fecha de subida es requerida',
            'valid_date' => 'Debe proporcionar una fecha válida'
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            er.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION,
            tdp.ID_TIPO_DOCUMENTO,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            u.ID_USUARIO,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.CEDULA as CEDULA_USUARIO,
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
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            er.ESTADO as ESTADO_REVISION,
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
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            dp.OBSERVACIONES_REVISOR,
            dp.ENTIDAD_RECEPTORA,
            dp.DOCENTE_TUTOR,
            dp.PRIORIDAD,
            er.ESTADO as ESTADO_REVISION,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.CEDULA as CEDULA_USUARIO
        ');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            dp.OBSERVACIONES_REVISOR,
            dp.ENTIDAD_RECEPTORA,
            dp.DOCENTE_TUTOR,
            dp.PRIORIDAD,
            er.ESTADO as ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.CEDULA as CEDULA_USUARIO
        ');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
        $builder->where('dp.ID_ESTADO_REVISION', $idEstadoRevision);
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
            tdp.ID_TIPO_DOCUMENTO,
            tdp.CODIGO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            COUNT(dp.ID_DOCUMENTO_PRACTICA) as total_documentos,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as rechazados
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'right');
        $builder->groupBy('tdp.ID_TIPO_DOCUMENTO, tdp.CODIGO, tdp.NOMBRE');
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            er.ESTADO as ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO
        ');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un estudiante ya tiene un documento de un tipo específico
     */
    public function verificarDocumentoExistente($idUsuario, $idTipoDocumento)
    {
        return $this->where('ID_USUARIO', $idUsuario)
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
            tdp.ID_TIPO_DOCUMENTO,
            tdp.CODIGO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp.ID_DOCUMENTO_PRACTICA,
            dp.FECHA_SUBIDA,
            er.ESTADO as ESTADO_REVISION
        ';
        
        // Agregar OBSERVACIONES_REVISOR solo si la columna existe
        if ($this->columnExists('OBSERVACIONES_REVISOR')) {
            $selectFields .= ', dp.OBSERVACIONES_REVISOR';
        }
        
        $builder->select($selectFields);
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'right');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.TIPO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            dp.OBSERVACIONES_REVISOR,
            dp.ENTIDAD_RECEPTORA,
            dp.DOCENTE_TUTOR,
            dp.PRIORIDAD,
            er.ESTADO as ESTADO_REVISION,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.CEDULA as CEDULA_USUARIO
        ');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');

        // Aplicar filtros
        if (!empty($filtros['tipo_documento'])) {
            $builder->where('dp.ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('dp.ID_ESTADO_REVISION', $filtros['estado']);
        }
        
        if (!empty($filtros['estudiante'])) {
            $builder->where('dp.ID_USUARIO', $filtros['estudiante']);
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
            dp.ID_DOCUMENTO_PRACTICA,
            dp.NOMBRE_ARCHIVO,
            dp.FECHA_SUBIDA,
            dp.OBSERVACIONES,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO,
            dp_persona.EMAIL as EMAIL_USUARIO
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO', 'left');
        $builder->join('TAB_USUARIOS u', 'dp.ID_USUARIO = u.ID_USUARIO', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'u.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
        $builder->where('dp.ID_ESTADO_REVISION', 1); // Pendiente
        $builder->where('DATE(dp.FECHA_SUBIDA) <=', $fechaVencimiento);
        $builder->orderBy('dp.FECHA_SUBIDA', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}