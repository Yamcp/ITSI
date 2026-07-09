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
        'TIPO_ARCHIVO',
        'FECHA_SUBIDA',
        'ESTADO_REVISION',
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
        'ESTADO_REVISION' => 'required|in_list[Pendiente,Aprobado,Rechazado]',
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
        'ESTADO_REVISION' => [
            'required' => 'El estado de revisión es requerido',
            'in_list' => 'El estado de revisión debe ser: Pendiente, Aprobado o Rechazado'
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
        return $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.CODIGO as CODIGO_DOCUMENTO,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.DESCRIPCION as DESCRIPCION_DOCUMENTO
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->where('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO', $idServicio)
        ->orderBy('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ORDEN', 'ASC')
        ->findAll();
    }

    /**
     * Obtener documentos por estado de revisión
     */
    public function getDocumentosPorEstado($estado)
    {
        return $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_SERVICIO_COMUNITARIO.PROYECTO_SOCIAL,
            TAB_DATOS_PERSONAS.NOMBRE,
            TAB_DATOS_PERSONAS.APELLIDO
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->join('TAB_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO = TAB_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO')
        ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SERVICIO_COMUNITARIO.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
        ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->where('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ESTADO_REVISION', $estado)
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'DESC')
        ->findAll();
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes($limite = 10)
    {
        return $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_SERVICIO_COMUNITARIO.PROYECTO_SOCIAL,
            TAB_DATOS_PERSONAS.NOMBRE,
            TAB_DATOS_PERSONAS.APELLIDO
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->join('TAB_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO = TAB_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO')
        ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SERVICIO_COMUNITARIO.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
        ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'DESC')
        ->limit($limite)
        ->findAll();
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticasDocumentos()
    {
        $builder = $this->db->table($this->table . ' dsc');
        $builder->select('
            COUNT(*) as total,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Pendiente" THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Aprobado" THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Rechazado" THEN 1 ELSE 0 END) as rechazados
        ');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener documentos con información completa.
     * @param int|null $idEstudiante Si se indica, solo documentos de servicio comunitario de ese estudiante.
     */
    public function getDocumentosCompletos($idEstudiante = null)
    {
        $builder = $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.CODIGO as CODIGO_DOCUMENTO,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.DESCRIPCION as DESCRIPCION_DOCUMENTO,
            TAB_SERVICIO_COMUNITARIO.PROYECTO_SOCIAL,
            TAB_SERVICIO_COMUNITARIO.COMUNIDAD_BENEFICIADA,
            TAB_DATOS_PERSONAS.NOMBRE as NOMBRE_ESTUDIANTE,
            TAB_DATOS_PERSONAS.APELLIDO as APELLIDO_ESTUDIANTE,
            TAB_DATOS_PERSONAS.CEDULA
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->join('TAB_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO = TAB_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO')
        ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SERVICIO_COMUNITARIO.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
        ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'DESC');

        if ($idEstudiante !== null && (int) $idEstudiante > 0) {
            $builder->where('TAB_SERVICIO_COMUNITARIO.ID_ESTUDIANTE', (int) $idEstudiante);
        }

        return $builder->findAll();
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
        return $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_SERVICIO_COMUNITARIO.PROYECTO_SOCIAL,
            TAB_DATOS_PERSONAS.NOMBRE,
            TAB_DATOS_PERSONAS.APELLIDO
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->join('TAB_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO = TAB_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO')
        ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SERVICIO_COMUNITARIO.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
        ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->where('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ESTADO_REVISION', 'Pendiente')
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'ASC')
        ->findAll();
    }

    /**
     * Obtener documentos por rango de fechas
     */
    public function getDocumentosPorRangoFechas($fechaInicio, $fechaFin)
    {
        return $this->select('
            TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.*,
            TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_SERVICIO_COMUNITARIO.PROYECTO_SOCIAL,
            TAB_DATOS_PERSONAS.NOMBRE,
            TAB_DATOS_PERSONAS.APELLIDO
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_TIPO_DOCUMENTO_SERVICIO')
        ->join('TAB_SERVICIO_COMUNITARIO', 'TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO = TAB_SERVICIO_COMUNITARIO.ID_SERVICIO_COMUNITARIO')
        ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SERVICIO_COMUNITARIO.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
        ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->where('DATE(TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA) >=', $fechaInicio)
        ->where('DATE(TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA) <=', $fechaFin)
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'DESC')
        ->findAll();
    }

    /**
     * Obtener resumen de documentos por tipo
     */
    public function getResumenPorTipo()
    {
        $builder = $this->db->table($this->table . ' dsc');
        $builder->select('
            dsc.ID_TIPO_DOCUMENTO,
            tdsc.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            COUNT(*) as total,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Pendiente" THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Aprobado" THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dsc.ESTADO_REVISION = "Rechazado" THEN 1 ELSE 0 END) as rechazados
        ')
        ->join('TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tdsc', 'dsc.ID_TIPO_DOCUMENTO = tdsc.ID_TIPO_DOCUMENTO_SERVICIO')
        ->groupBy('dsc.ID_TIPO_DOCUMENTO, tdsc.NOMBRE')
        ->orderBy('total', 'DESC');
        
        return $builder->get()->getResultArray();
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
        $data = ['ESTADO_REVISION' => $estado];
        if ($observaciones) {
            $data['OBSERVACIONES'] = $observaciones;
        }
        
        return $this->update($id, $data);
    }
}
