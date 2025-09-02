<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosPracticasModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_PRACTICAS';
    protected $primaryKey = 'ID_DOCUMENTO_PRACTICA';
    protected $allowedFields = [
        'ID_ESTADO_REVISION',
        'ID_TIPO_DOCUMENTO', 
        'ID_USUARIO',
        'NOMBRE_ARCHIVO',
        'TIPO',
        'FECHA_SUBIDA',
        'OBSERVACIONES',
        'OBSERVACIONES_REVISOR'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_ESTADO_REVISION' => 'required|integer',
        'ID_TIPO_DOCUMENTO' => 'required|integer',
        'ID_USUARIO' => 'required|integer',
        'NOMBRE_ARCHIVO' => 'required|max_length[255]',
        'TIPO' => 'required|max_length[100]',
        'FECHA_SUBIDA' => 'required|valid_date',
        'OBSERVACIONES' => 'permit_empty|max_length[500]',
        'OBSERVACIONES_REVISOR' => 'permit_empty|max_length[500]'
    ];
    
    protected $validationMessages = [
        'ID_ESTADO_REVISION' => [
            'required' => 'El estado de revisión es requerido',
            'integer' => 'El estado de revisión debe ser un número entero'
        ],
        'ID_TIPO_DOCUMENTO' => [
            'required' => 'El tipo de documento es requerido',
            'integer' => 'El tipo de documento debe ser un número entero'
        ],
        'ID_USUARIO' => [
            'required' => 'El usuario es requerido',
            'integer' => 'El usuario debe ser un número entero'
        ],
        'NOMBRE_ARCHIVO' => [
            'required' => 'El nombre del archivo es requerido',
            'max_length' => 'El nombre del archivo no puede exceder 255 caracteres'
        ],
        'TIPO' => [
            'required' => 'El tipo es requerido',
            'max_length' => 'El tipo no puede exceder 100 caracteres'
        ],
        'FECHA_SUBIDA' => [
            'required' => 'La fecha de subida es requerida',
            'valid_date' => 'La fecha de subida debe ser una fecha válida'
        ],
        'OBSERVACIONES' => [
            'max_length' => 'Las observaciones no pueden exceder 500 caracteres'
        ],
        'OBSERVACIONES_REVISOR' => [
            'max_length' => 'Las observaciones del revisor no pueden exceder 500 caracteres'
        ]
    ];
    
    /**
     * Obtener documentos por usuario
     */
    public function getDocumentosPorUsuario($idUsuario)
    {
        return $this->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_TIPOS_DOCUMENTOS_PRACTICAS.NOMBRE as TIPO_DOCUMENTO_NOMBRE')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS', 'TAB_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO')
            ->where('TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO', $idUsuario)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
            ->findAll();
    }
    
    /**
     * Obtener documentos por estado de revisión
     */
    public function getDocumentosPorEstado($idEstado)
    {
        return $this->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->where('TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION', $idEstado)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
            ->findAll();
    }
    
    /**
     * Obtener documentos por tipo
     */
    public function getDocumentosPorTipo($tipo)
    {
        return $this->where('TIPO', $tipo)
            ->orderBy('FECHA_SUBIDA', 'DESC')
            ->findAll();
    }
    
    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes($limite = 10)
    {
        return $this->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
            ->limit($limite)
            ->findAll();
    }
    
    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticasDocumentos()
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            COUNT(*) as total_documentos,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as rechazados
        ');
        
        return $builder->get()->getRowArray();
    }
    
    /**
     * Obtener documentos con información completa
     */
    public function getDocumentosCompletos()
    {
        return $this->select('
            TAB_DOCUMENTOS_PRACTICAS.*,
            TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION,
            TAB_TIPOS_DOCUMENTOS_PRACTICAS.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
            TAB_DATOS_PERSONAS.NOMBRE as NOMBRE_USUARIO,
            TAB_DATOS_PERSONAS.APELLIDO as APELLIDO_USUARIO,
            TAB_DATOS_PERSONAS.CEDULA
        ')
        ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
        ->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS', 'TAB_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO')
        ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
        ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
        ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
        ->findAll();
    }
    
    /**
     * Verificar si un usuario ya subió un tipo específico de documento
     */
    public function verificarDocumentoExistente($idUsuario, $tipo)
    {
        return $this->where('ID_USUARIO', $idUsuario)
            ->where('TIPO', $tipo)
            ->countAllResults() > 0;
    }
    
    /**
     * Obtener documentos pendientes de revisión
     */
    public function getDocumentosPendientes()
    {
        return $this->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->where('TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION', 1)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'ASC')
            ->findAll();
    }
    
    /**
     * Obtener documentos por rango de fechas
     */
    public function getDocumentosPorRangoFechas($fechaInicio, $fechaFin)
    {
        return $this->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->where('DATE(TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA) >=', $fechaInicio)
            ->where('DATE(TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA) <=', $fechaFin)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
            ->findAll();
    }
    
    /**
     * Obtener resumen de documentos por tipo
     */
    public function getResumenPorTipo()
    {
        $builder = $this->db->table($this->table . ' dp');
        $builder->select('
            dp.TIPO,
            COUNT(*) as total,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as rechazados
        ')
        ->groupBy('dp.TIPO')
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
        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }
        
        // Eliminar registro de la base de datos
        return $this->delete($id);
    }
}
