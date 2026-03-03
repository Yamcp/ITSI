<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\TiposDocumentosServicioComunitarioModel;
use App\Models\ServicioComunitarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentosAdminController extends BaseController
{
    protected $documentosServicioModel;
    protected $tiposDocumentosModel;
    protected $servicioComunitarioModel;

    public function __construct()
    {
        // Verificar autenticación y rol de administrador
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        $this->documentosServicioModel = new DocumentosServicioComunitarioModel();
        $this->tiposDocumentosModel = new TiposDocumentosServicioComunitarioModel();
        $this->servicioComunitarioModel = new ServicioComunitarioModel();
    }

    public function index()
    {
        // Cargar la vista principal de documentos
        return view('admin/documentos/documentos_views');
    }

    public function documentosPracticas()
    {
        // Cargar la vista de documentos de prácticas
        return view('admin/documentos/documentos_practicas');
    }

    public function documentosServicioComunitario()
    {
        try {
            // Obtener estadísticas de documentos
            $estadisticas = $this->documentosServicioModel->getEstadisticasDocumentos();
            
            // Obtener tipos de documentos disponibles
            $tiposDocumentos = $this->tiposDocumentosModel->getTiposOrdenados();
            
            // Obtener documentos recientes
            $documentosRecientes = $this->documentosServicioModel->getDocumentosRecientes(20);
            
            $data = [
                'estadisticas' => $estadisticas ?: ['total' => 0, 'pendientes' => 0, 'aprobados' => 0, 'rechazados' => 0],
                'tiposDocumentos' => $tiposDocumentos ?: [],
                'documentosRecientes' => $documentosRecientes ?: []
            ];
            
            return view('admin/documentos/documentos_servicio_comunitario', $data);
        } catch (\Exception $e) {
            // En caso de error, mostrar datos vacíos
            $data = [
                'estadisticas' => ['total' => 0, 'pendientes' => 0, 'aprobados' => 0, 'rechazados' => 0],
                'tiposDocumentos' => [],
                'documentosRecientes' => []
            ];
            
            return view('admin/documentos/documentos_servicio_comunitario', $data);
        }
    }

    public function subirDocumento()
    {
        // Lógica para subir documentos
        if ($this->request->getMethod() === 'post') {
            $archivo = $this->request->getFile('archivo');
            
            if ($archivo->isValid() && !$archivo->hasMoved()) {
                // Validar tipo y tamaño del archivo
                $tipoPermitido = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
                $extension = $archivo->getExtension();
                
                if (!in_array(strtolower($extension), $tipoPermitido)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Tipo de archivo no permitido'
                    ]);
                }
                
                // Validar tamaño (máximo 10 MB)
                if ($archivo->getSize() > 10 * 1024 * 1024) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'El archivo excede el tamaño máximo permitido (10 MB)'
                    ]);
                }
                
                // Generar nombre único para el archivo
                $nuevoNombre = uniqid() . '_' . $archivo->getName();
                
                // Mover archivo a la carpeta de uploads
                $archivo->move(WRITEPATH . 'uploads/documentos', $nuevoNombre);
                
                // Obtener datos del formulario
                $tipoDocumento = $this->request->getPost('tipo_documento');
                $estudiante = $this->request->getPost('estudiante');
                $entidadReceptora = $this->request->getPost('entidad_receptora');
                $docenteTutor = $this->request->getPost('docente_tutor');
                $estadoRevision = $this->request->getPost('estado_revision');
                $prioridad = $this->request->getPost('prioridad');
                $observaciones = $this->request->getPost('observaciones');
                
                // Aquí puedes guardar la información en la base de datos
                // Por ahora solo retornamos éxito con los datos recibidos
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Documento subido exitosamente',
                    'filename' => $nuevoNombre,
                    'data' => [
                        'tipo_documento' => $tipoDocumento,
                        'estudiante' => $estudiante,
                        'entidad_receptora' => $entidadReceptora,
                        'docente_tutor' => $docenteTutor,
                        'estado_revision' => $estadoRevision,
                        'prioridad' => $prioridad,
                        'observaciones' => $observaciones
                    ]
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el archivo'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }

    public function obtenerDocumentos()
    {
        // Lógica para obtener lista de documentos
        // Aquí puedes implementar la consulta a la base de datos
        
        $documentos = [
            [
                'id' => 1,
                'nombre' => 'Informe_Final_Practica.pdf',
                'categoria' => 'practicas',
                'tipo' => 'informe',
                'tamaño' => '2.5 MB',
                'estado' => 'aprobado',
                'fecha_subida' => '2025-08-30',
                'usuario' => 'Yamilex Campues'
            ],
            [
                'id' => 2,
                'nombre' => 'Plan_Trabajo.docx',
                'categoria' => 'academicos',
                'tipo' => 'plan_trabajo',
                'tamaño' => '1.8 MB',
                'estado' => 'pendiente',
                'fecha_subida' => '2025-08-29',
                'usuario' => 'Ana Yandun'
            ]
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    public function eliminarDocumento($id = null)
    {
        // Lógica para eliminar documentos
        if ($id) {
            // Aquí puedes implementar la eliminación del archivo y registro de la BD
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de documento requerido'
        ]);
    }

    public function descargarDocumento($id = null)
    {
        // Lógica para descargar documentos
        if ($id) {
            // Aquí puedes implementar la lógica de descarga
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Descarga iniciada'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de documento requerido'
        ]);
    }

    public function crearCarpeta()
    {
        // Lógica para crear carpetas
        if ($this->request->getMethod() === 'post') {
            $nombre = $this->request->getPost('nombre_carpeta');
            $descripcion = $this->request->getPost('descripcion_carpeta');
            $categoria = $this->request->getPost('categoria_carpeta');
            
            if ($nombre) {
                // Aquí puedes implementar la creación de la carpeta en la BD
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Carpeta creada exitosamente'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nombre de carpeta requerido'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }

    // ========== MÉTODOS ESPECÍFICOS PARA SERVICIO COMUNITARIO ==========

    /**
     * Obtener documentos de servicio comunitario
     */
    public function obtenerDocumentosServicio()
    {
        try {
            $documentos = $this->documentosServicioModel->getDocumentosCompletos();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $documentos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener documentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Subir documento de servicio comunitario
     */
    public function subirDocumentoServicio()
    {
        if ($this->request->getMethod() === 'post') {
            try {
                $archivo = $this->request->getFile('archivo');
                $idServicio = $this->request->getPost('id_servicio');
                $idTipoDocumento = $this->request->getPost('id_tipo_documento');
                $observaciones = $this->request->getPost('observaciones');
                
                // Validaciones
                if (!$archivo || !$archivo->isValid()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Archivo no válido'
                    ]);
                }
                
                if (!$idServicio || !$idTipoDocumento) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Datos requeridos faltantes'
                    ]);
                }
                
                // Validar tipo de archivo
                $tiposPermitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
                $extension = $archivo->getExtension();
                
                if (!in_array(strtolower($extension), $tiposPermitidos)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Tipo de archivo no permitido'
                    ]);
                }
                
                // Validar tamaño (máximo 10 MB)
                if ($archivo->getSize() > 10 * 1024 * 1024) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'El archivo excede el tamaño máximo permitido (10 MB)'
                    ]);
                }
                
                // Verificar si ya existe un documento de este tipo para este servicio
                if ($this->documentosServicioModel->verificarDocumentoExistente($idServicio, $idTipoDocumento)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Ya existe un documento de este tipo para este servicio'
                    ]);
                }
                
                // Crear directorio si no existe
                $uploadPath = WRITEPATH . 'uploads/documentos-servicio-comunitario/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Generar nombre único para el archivo
                $nuevoNombre = uniqid() . '_' . $archivo->getName();
                
                // Mover archivo
                if ($archivo->move($uploadPath, $nuevoNombre)) {
                    // Guardar en base de datos
                    $datos = [
                        'ID_SERVICIO_COMUNITARIO' => $idServicio,
                        'ID_TIPO_DOCUMENTO' => $idTipoDocumento,
                        'NOMBRE_ARCHIVO' => $nuevoNombre,
                        'TIPO_ARCHIVO' => $extension,
                        'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                        'ESTADO_REVISION' => 'Pendiente',
                        'OBSERVACIONES' => $observaciones
                    ];
                    
                    if ($this->documentosServicioModel->insert($datos)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Documento subido exitosamente',
                            'filename' => $nuevoNombre
                        ]);
                    } else {
                        // Eliminar archivo si falla la inserción
                        unlink($uploadPath . $nuevoNombre);
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Error al guardar en base de datos'
                        ]);
                    }
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al subir el archivo'
                    ]);
                }
                
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }

    /**
     * Descargar documento de servicio comunitario
     */
    public function descargarDocumentoServicio($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de documento requerido'
            ]);
        }
        
        try {
            $documento = $this->documentosServicioModel->find($id);
            
            if (!$documento) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Documento no encontrado'
                ]);
            }
            
            $rutaArchivo = WRITEPATH . 'uploads/documentos-servicio-comunitario/' . $documento['NOMBRE_ARCHIVO'];
            
            if (!file_exists($rutaArchivo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Archivo no encontrado en el servidor'
                ]);
            }
            
            return $this->response->download($rutaArchivo, null);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al descargar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar documento de servicio comunitario
     */
    public function eliminarDocumentoServicio($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de documento requerido'
            ]);
        }
        
        try {
            if ($this->documentosServicioModel->eliminarDocumento($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Documento eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el documento'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar estado de revisión de documento
     */
    public function cambiarEstadoDocumentoServicio()
    {
        if ($this->request->getMethod() === 'post') {
            try {
                $id = $this->request->getPost('id');
                $estado = $this->request->getPost('estado');
                $observaciones = $this->request->getPost('observaciones');
                
                if (!$id || !$estado) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Datos requeridos faltantes'
                    ]);
                }
                
                $estadosValidos = ['Pendiente', 'Aprobado', 'Rechazado'];
                if (!in_array($estado, $estadosValidos)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Estado no válido'
                    ]);
                }
                
                if ($this->documentosServicioModel->actualizarEstadoRevision($id, $estado, $observaciones)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Estado actualizado exitosamente'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al actualizar el estado'
                    ]);
                }
                
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }

    /**
     * Obtener estadísticas de documentos de servicio comunitario
     */
    public function obtenerEstadisticasServicio()
    {
        try {
            $estadisticas = $this->documentosServicioModel->getEstadisticasDocumentos();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar reporte de documentos de servicio comunitario
     */
    public function generarReporteServicio()
    {
        try {
            $fechaInicio = $this->request->getGet('fecha_inicio');
            $fechaFin = $this->request->getGet('fecha_fin');
            $estado = $this->request->getGet('estado');
            
            $documentos = [];
            
            if ($fechaInicio && $fechaFin) {
                $documentos = $this->documentosServicioModel->getDocumentosPorRangoFechas($fechaInicio, $fechaFin);
            } elseif ($estado) {
                $documentos = $this->documentosServicioModel->getDocumentosPorEstado($estado);
            } else {
                $documentos = $this->documentosServicioModel->getDocumentosCompletos();
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $documentos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar reporte: ' . $e->getMessage()
            ]);
        }
    }
}
