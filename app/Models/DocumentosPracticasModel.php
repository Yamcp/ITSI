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
        'ID_ESTADO_REVISION',
        'OBSERVACIONES',
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
        'ID_ESTADO_REVISION' => 'permit_empty|integer|in_list[1,2,3,4,5]',
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
            dp.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION,
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            u.ID_USUARIO,
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
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTUDIANTES est', 'pp.ID_ESTUDIANTE = est.ID_ESTUDIANTE', 'left');
        $builder->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = est.ID_DATO_PERSONA', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'est.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
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
            dp.ID_ESTADO_REVISION,
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
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');

        // Filtrar por prácticas del estudiante (la tabla usa ID_PRACTICA_PREPROFESIONAL, no ID_USUARIO)
        $estudiante = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();
        if ($estudiante) {
            $idsPracticas = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
                ->select('ID_PRACTICA_PREPROFESIONAL')
                ->where('ID_ESTUDIANTE', $estudiante['ID_ESTUDIANTE'])
                ->get()
                ->getResultArray();
            $ids = array_column($idsPracticas, 'ID_PRACTICA_PREPROFESIONAL');
            if (!empty($ids)) {
                $builder->whereIn('dp.ID_PRACTICA_PREPROFESIONAL', $ids);
            } else {
                $builder->where('1 = 0'); // sin prácticas, sin documentos
            }
        } else {
            $builder->where('1 = 0');
        }

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
            dp.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION
        ');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
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
            dp.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->where('dp.ID_ESTADO_REVISION', (int) $idEstadoRevision);
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
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as rechazados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 5 THEN 1 ELSE 0 END) as requiere_correccion
        ');
        
        $result = $builder->get()->getRowArray();
        return $result ?: [
            'total' => 0,
            'pendientes' => 0,
            'aprobados' => 0,
            'rechazados' => 0,
            'en_revision' => 0,
            'requiere_correccion' => 0,
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
            COUNT(dp.ID_DOCUMENTO_PREPROFESIONAL) as total_documentos,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as rechazados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 5 THEN 1 ELSE 0 END) as requiere_correccion
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
            dp.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            dp_persona.NOMBRE as NOMBRE_USUARIO,
            dp_persona.APELLIDO as APELLIDO_USUARIO
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');
        $builder->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTUDIANTES est', 'pp.ID_ESTUDIANTE = est.ID_ESTUDIANTE', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'est.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
        $builder->orderBy('dp.FECHA_SUBIDA', 'DESC');
        $builder->limit($limite);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un estudiante ya tiene un documento de un tipo específico.
     * La tabla usa ID_PRACTICA_PREPROFESIONAL (no ID_USUARIO). Se obtienen las prácticas del estudiante y se filtran documentos por ellas.
     */
    public function verificarDocumentoExistente($idUsuario, $idTipoDocumento)
    {
        $estudiante = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();
        if (!$estudiante) {
            return null;
        }
        $idsPracticas = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->select('ID_PRACTICA_PREPROFESIONAL')
            ->where('ID_ESTUDIANTE', $estudiante['ID_ESTUDIANTE'])
            ->get()
            ->getResultArray();
        $ids = array_column($idsPracticas, 'ID_PRACTICA_PREPROFESIONAL');
        if (empty($ids)) {
            return null;
        }
        return $this->whereIn('ID_PRACTICA_PREPROFESIONAL', $ids)
                    ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
                    ->first();
    }

    /**
     * Obtener ID de la primera práctica preprofesional del estudiante (por ID_USUARIO).
     * Usado al subir documento cuando no se envía id_practica en el formulario.
     */
    public function getIdPrimeraPracticaEstudiante($idUsuario)
    {
        $row = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();
        if (!$row) {
            return null;
        }
        $practica = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->select('ID_PRACTICA_PREPROFESIONAL')
            ->where('ID_ESTUDIANTE', $row['ID_ESTUDIANTE'])
            ->orderBy('ID_PRACTICA_PREPROFESIONAL', 'ASC')
            ->limit(1)
            ->get()
            ->getRowArray();
        return $practica ? (int) $practica['ID_PRACTICA_PREPROFESIONAL'] : null;
    }

    /**
     * Obtener progreso de documentos por estudiante.
     * La tabla usa ID_PRACTICA_PREPROFESIONAL (no ID_USUARIO). Se obtienen las prácticas del estudiante y se filtran documentos por ellas.
     */
    public function getProgresoEstudiante($idUsuario)
    {
        // Obtener ID_ESTUDIANTE del usuario (via TAB_USUARIOS / TAB_ESTUDIANTES por ID_DATO_PERSONA)
        $estudiante = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();

        if (!$estudiante) {
            return [];
        }

        $idsPracticas = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->select('ID_PRACTICA_PREPROFESIONAL')
            ->where('ID_ESTUDIANTE', $estudiante['ID_ESTUDIANTE'])
            ->get()
            ->getResultArray();
        $ids = array_column($idsPracticas, 'ID_PRACTICA_PREPROFESIONAL');
        if (empty($ids)) {
            $ids = [0]; // evita IN () vacío; no habrá documentos
        }

        $selectFields = "
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            MAX(dp.ID_DOCUMENTO_PREPROFESIONAL) as ID_DOCUMENTO_PREPROFESIONAL,
            MAX(dp.FECHA_SUBIDA) as FECHA_SUBIDA,
            CASE MAX(dp.ID_ESTADO_REVISION)
                WHEN 1 THEN 'Pendiente'
                WHEN 2 THEN 'En Revisión'
                WHEN 3 THEN 'Aprobado'
                WHEN 4 THEN 'Rechazado'
                WHEN 5 THEN 'Requiere Corrección'
                ELSE 'Pendiente'
            END as ESTADO_REVISION
        ";

        if ($this->columnExists('OBSERVACIONES_REVISOR')) {
            $selectFields .= ', MAX(dp.OBSERVACIONES_REVISOR) as OBSERVACIONES_REVISOR';
        }

        // Base en tipos (un solo alias `dp` en el JOIN; no combinar table('... dp') + from() o MySQL error 1066)
        $builder = $this->db->table('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp');
        $builder->select($selectFields);
        $builder->join($this->table . ' dp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL AND dp.ID_PRACTICA_PREPROFESIONAL IN (' . implode(',', array_map('intval', $ids)) . ')', 'left');
        $builder->groupBy('tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL, tdp.CODIGO, tdp.NOMBRE');
        $builder->orderBy('tdp.CODIGO', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * El documento pertenece a una práctica del estudiante (por ID_USUARIO de sesión).
     */
    public function documentoPerteneceAUsuario(int $idDocumento, int $idUsuario): bool
    {
        $doc = $this->find($idDocumento);
        if (!$doc || empty($doc['ID_PRACTICA_PREPROFESIONAL'])) {
            return false;
        }

        $est = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();

        if (!$est) {
            return false;
        }

        $n = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ID_PRACTICA_PREPROFESIONAL', (int) $doc['ID_PRACTICA_PREPROFESIONAL'])
            ->where('ID_ESTUDIANTE', $est['ID_ESTUDIANTE'])
            ->countAllResults();

        return $n > 0;
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
            dp.ID_ESTADO_REVISION,
            er.ESTADO as ESTADO_REVISION,
            tdp.CODIGO as CODIGO_DOCUMENTO,
            tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE
        ');
        $builder->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left');

        // Aplicar filtros
        if (!empty($filtros['tipo_documento'])) {
            $builder->where('dp.ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
        }

        if (!empty($filtros['estado'])) {
            $est = $filtros['estado'];
            if (is_numeric($est) || (is_string($est) && ctype_digit($est))) {
                $builder->where('dp.ID_ESTADO_REVISION', (int) $est);
            } else {
                $builder->where('er.ESTADO', $est);
            }
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
        $builder->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL', 'left');
        $builder->join('TAB_ESTUDIANTES est', 'pp.ID_ESTUDIANTE = est.ID_ESTUDIANTE', 'left');
        $builder->join('TAB_DATOS_PERSONAS dp_persona', 'est.ID_DATO_PERSONA = dp_persona.ID_DATO_PERSONA', 'left');
        $builder->where('dp.ID_ESTADO_REVISION', 1); // Pendiente
        $builder->where('DATE(dp.FECHA_SUBIDA) <=', $fechaVencimiento);
        $builder->orderBy('dp.FECHA_SUBIDA', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}