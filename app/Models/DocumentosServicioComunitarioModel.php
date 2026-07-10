<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosServicioComunitarioModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_DOCUMENTO_SERVICIO';
    protected $allowedFields = [
        'ID_SERVICIO_COMUNITARIO',
        'ID_TIPO_DOCUMENTO',
        'ID_ESTADO_REVISION',
        'NOMBRE_ARCHIVO',
        'NOMBRE_ORIGINAL',
        'TIPO_ARCHIVO',
        'TAMANO_ARCHIVO',
        'RUTA_ARCHIVO',
        'FECHA_SUBIDA',
        'OBSERVACIONES',
        'OBSERVACIONES_REVISOR',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'ID_SERVICIO_COMUNITARIO' => 'required|integer',
        'ID_TIPO_DOCUMENTO' => 'required|integer',
        'NOMBRE_ARCHIVO' => 'required|max_length[255]',
        'TIPO_ARCHIVO' => 'required|max_length[100]',
        'FECHA_SUBIDA' => 'required|valid_date',
        'ID_ESTADO_REVISION' => 'permit_empty|integer',
        'OBSERVACIONES' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'ID_SERVICIO_COMUNITARIO' => [
            'required' => 'El servicio comunitario es requerido',
            'integer' => 'El servicio comunitario debe ser un número entero'
        ],
        'ID_TIPO_DOCUMENTO' => [
            'required' => 'El tipo de documento es requerido',
            'integer' => 'El tipo de documento debe ser un número entero'
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
            'valid_date' => 'La fecha de subida debe ser una fecha válida'
        ],
        'OBSERVACIONES' => [
            'max_length' => 'Las observaciones no pueden exceder 500 caracteres'
        ]
    ];

    /**
     * Obtener documentos por servicio comunitario
     */
    public function getDocumentosPorServicio($idServicio)
    {
        $query = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
            ->select('
                dsc.*,
                tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                tds.CODIGO as CODIGO_DOCUMENTO,
                tds.DESCRIPCION as DESCRIPCION_DOCUMENTO,
                er.ESTADO as ESTADO_REVISION
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->where('dsc.ID_SERVICIO_COMUNITARIO', (int) $idServicio)
            ->orderBy('tds.ORDEN', 'ASC')
            ->get();

        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Obtener documentos por estado de revisión
     */
    public function getDocumentosPorEstado($estado)
    {
        $builder = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
            ->select('
                dsc.*,
                tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                sc.PROYECTO_SOCIAL,
                dp.NOMBRE,
                dp.APELLIDO,
                er.ESTADO as ESTADO_REVISION
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->join('TAB_SERVICIO_COMUNITARIO sc', 'dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO', 'left')
            ->join('TAB_ESTUDIANTES e', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
            ->orderBy('dsc.FECHA_SUBIDA', 'DESC');

        if (is_numeric($estado)) {
            $builder->where('dsc.ID_ESTADO_REVISION', (int) $estado);
        } else {
            $builder->where('er.ESTADO', (string) $estado);
        }

        $query = $builder->get();
        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes($limite = 10)
    {
        $query = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
            ->select('
                dsc.*,
                tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                sc.PROYECTO_SOCIAL,
                dp.NOMBRE,
                dp.APELLIDO,
                er.ESTADO as ESTADO_REVISION
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->join('TAB_SERVICIO_COMUNITARIO sc', 'dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO', 'left')
            ->join('TAB_ESTUDIANTES e', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
            ->orderBy('dsc.FECHA_SUBIDA', 'DESC')
            ->limit((int) $limite)
            ->get();

        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticasDocumentos()
    {
        $query = $this->db->table($this->table . ' dsc')
            ->select('
                COUNT(*) as total,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as rechazados
            ')
            ->get();

        if ($query === false) {
            return [
                'total' => 0,
                'pendientes' => 0,
                'aprobados' => 0,
                'rechazados' => 0,
            ];
        }

        return $query->getRowArray() ?: [
            'total' => 0,
            'pendientes' => 0,
            'aprobados' => 0,
            'rechazados' => 0,
        ];
    }

    /**
     * Obtener documentos con información completa.
     * @param int|null $idEstudiante Si se indica, solo documentos de servicio comunitario de ese estudiante.
     */
    public function getDocumentosCompletos($idEstudiante = null)
    {
        try {
            $builder = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
                ->select('
                    dsc.*,
                    tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                    tds.CODIGO as CODIGO_DOCUMENTO,
                    tds.DESCRIPCION as DESCRIPCION_DOCUMENTO,
                    er.ESTADO as ESTADO_REVISION,
                    sc.PROYECTO_SOCIAL,
                    sc.COMUNIDAD_BENEFICIADA,
                    sc.ID_ESTUDIANTE,
                    dp.NOMBRE as NOMBRE_ESTUDIANTE,
                    dp.APELLIDO as APELLIDO_ESTUDIANTE,
                    dp.CEDULA,
                    CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE
                ')
                ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
                ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
                ->join('TAB_SERVICIO_COMUNITARIO sc', 'dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO', 'left')
                ->join('TAB_ESTUDIANTES e', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
                ->join('TAB_DATOS_PERSONAS dp', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
                ->orderBy('dsc.FECHA_SUBIDA', 'DESC');

            if ($idEstudiante !== null && (int) $idEstudiante > 0) {
                $builder->where('sc.ID_ESTUDIANTE', (int) $idEstudiante);
            }

            $query = $builder->get();
            if ($query === false) {
                $error = $this->db->error();
                log_message('error', 'getDocumentosCompletos servicio SQL: ' . ($error['message'] ?? 'query failed'));
                return [];
            }

            return $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'getDocumentosCompletos servicio: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener tipos de documentos disponibles
     */
    public function getTiposDocumentos()
    {
        $tiposModel = new \App\Models\TiposDocumentosServicioComunitarioModel();

        return $tiposModel->getAllTipos();
    }

    /**
     * Verificar si un servicio ya tiene un tipo específico de documento
     */
    public function verificarDocumentoExistente($idServicio, $idTipoDocumento)
    {
        return $this->where('ID_SERVICIO_COMUNITARIO', $idServicio)
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->countAllResults() > 0;
    }

    /**
     * Progreso por tipo de documento PSC para el estudiante (todos sus servicios comunitarios).
     */
    public function getProgresoEstudianteServicio(int $idUsuario): array
    {
        $est = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();

        if (!$est) {
            return [];
        }

        $servicios = $this->db->table('TAB_SERVICIO_COMUNITARIO')
            ->select('ID_SERVICIO_COMUNITARIO')
            ->where('ID_ESTUDIANTE', $est['ID_ESTUDIANTE'])
            ->get()
            ->getResultArray();

        $ids = array_map('intval', array_column($servicios, 'ID_SERVICIO_COMUNITARIO'));
        if ($ids === []) {
            return [];
        }

        $inList = implode(',', $ids);
        $fields = $this->db->getFieldNames($this->table);
        $hasObsRev = is_array($fields) && in_array('OBSERVACIONES_REVISOR', $fields, true);

        $builder = $this->db->table('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO t');
        $builder->select(
            't.ID_TIPO_DOCUMENTO_SERVICIO AS ID_TIPO_DOCUMENTO_SERVICIO, t.ID_TIPO_DOCUMENTO_SERVICIO AS ID_TIPO_DOCUMENTO, t.CODIGO, t.NOMBRE AS TIPO_DOCUMENTO_NOMBRE, MAX(d.ID_DOCUMENTO_SERVICIO) AS ID_DOCUMENTO_SERVICIO, MAX(d.ID_DOCUMENTO_SERVICIO) AS ID_DOCUMENTO_PRACTICA, MAX(d.FECHA_SUBIDA) AS FECHA_SUBIDA',
            false
        );
        $builder->select(
            "CASE MAX(d.ID_ESTADO_REVISION)
                WHEN 1 THEN 'Pendiente'
                WHEN 2 THEN 'En Revisión'
                WHEN 3 THEN 'Aprobado'
                WHEN 4 THEN 'Rechazado'
                WHEN 5 THEN 'Requiere Corrección'
                ELSE NULL END AS ESTADO_REVISION",
            false
        );
        if ($hasObsRev) {
            $builder->select('MAX(d.OBSERVACIONES_REVISOR) AS OBSERVACIONES_REVISOR', false);
        }
        $builder->join(
            $this->table . ' d',
            'd.ID_TIPO_DOCUMENTO = t.ID_TIPO_DOCUMENTO_SERVICIO AND d.ID_SERVICIO_COMUNITARIO IN (' . $inList . ')',
            'left',
            false
        );
        $builder->where('t.ACTIVO', 1);
        $builder->groupBy('t.ID_TIPO_DOCUMENTO_SERVICIO, t.CODIGO, t.NOMBRE, t.ORDEN');
        $builder->orderBy('t.ORDEN', 'ASC');
        $builder->orderBy('t.CODIGO', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Comprueba si el documento pertenece a un servicio del estudiante (por ID_USUARIO).
     */
    public function documentoPerteneceAEstudiante(int $idDocumento, int $idUsuario): bool
    {
        $doc = $this->find($idDocumento);
        if (!$doc || empty($doc['ID_SERVICIO_COMUNITARIO'])) {
            return false;
        }

        $row = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('sc.ID_SERVICIO_COMUNITARIO', (int) $doc['ID_SERVICIO_COMUNITARIO'])
            ->where('u.ID_USUARIO', $idUsuario)
            ->countAllResults();

        return $row > 0;
    }

    /**
     * Obtener documentos pendientes de revisión
     */
    public function getDocumentosPendientes()
    {
        $query = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
            ->select('
                dsc.*,
                tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                sc.PROYECTO_SOCIAL,
                dp.NOMBRE,
                dp.APELLIDO,
                er.ESTADO as ESTADO_REVISION
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->join('TAB_SERVICIO_COMUNITARIO sc', 'dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO', 'left')
            ->join('TAB_ESTUDIANTES e', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
            ->where('dsc.ID_ESTADO_REVISION', 1)
            ->orderBy('dsc.FECHA_SUBIDA', 'ASC')
            ->get();

        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Obtener documentos por rango de fechas
     */
    public function getDocumentosPorRangoFechas($fechaInicio, $fechaFin)
    {
        $query = $this->db->table('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc')
            ->select('
                dsc.*,
                tds.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                sc.PROYECTO_SOCIAL,
                dp.NOMBRE,
                dp.APELLIDO,
                er.ESTADO as ESTADO_REVISION
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds', 'dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->join('TAB_SERVICIO_COMUNITARIO sc', 'dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO', 'left')
            ->join('TAB_ESTUDIANTES e', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
            ->where('DATE(dsc.FECHA_SUBIDA) >=', $fechaInicio)
            ->where('DATE(dsc.FECHA_SUBIDA) <=', $fechaFin)
            ->orderBy('dsc.FECHA_SUBIDA', 'DESC')
            ->get();

        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Obtener resumen de documentos por tipo
     */
    public function getResumenPorTipo()
    {
        $query = $this->db->table($this->table . ' dsc')
            ->select('
                dsc.ID_TIPO_DOCUMENTO,
                tdsc.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                COUNT(*) as total,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN dsc.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as rechazados
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tdsc', 'dsc.ID_TIPO_DOCUMENTO = tdsc.ID_TIPO_DOCUMENTO_SERVICIO', 'left')
            ->groupBy('dsc.ID_TIPO_DOCUMENTO, tdsc.NOMBRE')
            ->orderBy('total', 'DESC')
            ->get();

        return $query === false ? [] : $query->getResultArray();
    }

    /**
     * Eliminar documento y archivo asociado
     */
    public function eliminarDocumento($id)
    {
        $documento = $this->find($id);
        if (!$documento) {
            return false;
        }
        
        // Eliminar archivo físico
        $rutaArchivo = WRITEPATH . 'uploads/documentos-servicio-comunitario/' . $documento['NOMBRE_ARCHIVO'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }
        
        // Eliminar registro de la base de datos
        return $this->delete($id);
    }

    /**
     * Actualizar estado de revisión
     */
    public function actualizarEstadoRevision($id, $estado, $observaciones = null)
    {
        $data = [];
        if (is_numeric($estado)) {
            $data['ID_ESTADO_REVISION'] = (int) $estado;
        } else {
            $estadoRow = $this->db->table('TAB_ESTADOS_REVISIONES')
                ->select('ID_ESTADO_REVISION')
                ->where('ESTADO', (string) $estado)
                ->get()
                ->getRowArray();
            if ($estadoRow) {
                $data['ID_ESTADO_REVISION'] = (int) $estadoRow['ID_ESTADO_REVISION'];
            }
        }

        if ($observaciones) {
            $data['OBSERVACIONES_REVISOR'] = $observaciones;
        }

        if ($data === []) {
            return false;
        }

        return $this->update($id, $data);
    }
}
