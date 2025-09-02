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
        'NOMBRE_ARCHIVO',
        'TIPO_ARCHIVO',
        'FECHA_SUBIDA',
        'ESTADO_REVISION',
        'OBSERVACIONES'
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
     * Obtener documentos con información completa
     */
    public function getDocumentosCompletos()
    {
        return $this->select('
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
        ->orderBy('TAB_DOCUMENTOS_SERVICIO_COMUNITARIO.FECHA_SUBIDA', 'DESC')
        ->findAll();
    }

    /**
     * Obtener tipos de documentos disponibles
     */
    public function getTiposDocumentos()
    {
        $tiposModel = new \App\Models\TiposDocumentosServicioComunitarioModel();
        return $tiposModel->orderBy('ORDEN', 'ASC')->findAll();
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
