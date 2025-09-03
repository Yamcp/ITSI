<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosServicioComunitarioModel;

class DocumentosServicioComunitarioAdminController extends BaseController
{
    protected $documentosModel;
    protected $usuariosModel;
    protected $estadosRevisionesModel;
    protected $tiposDocumentosModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentosServicioComunitarioModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
        $this->tiposDocumentosModel = new TiposDocumentosServicioComunitarioModel();
    }

    /**
     * Mostrar la vista de gestión de documentos de servicio comunitario
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Documentos de Servicio Comunitario',
            'documentos' => $this->getDocumentosCompletos(),
            'estadisticas' => $this->getEstadisticas(),
            'tiposDocumentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'estudiantes' => $this->getEstudiantes()
        ];

        return view('admin/documentos/documentos_servicio_comunitario', $data);
    }

    /**
     * Obtener documentos para AJAX
     */
    public function obtenerDocumentos()
    {
        try {
            $documentos = $this->getDocumentosCompletos();
            
            return $this->response->setJSON([
                'success' => true,
                'documentos' => $documentos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener documentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Procesar subida de documento
     */
    public function store()
    {
        $rules = [
            'id_tipo_documento' => 'required|integer|is_natural_no_zero',
            'id_servicio' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,51200]|ext_in[archivo,pdf,doc,docx,jpg,jpeg,png,zip,rar]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Manejar subida de archivo
            $archivo = $this->request->getFile('archivo');
            $uploadPath = WRITEPATH . 'uploads/documentos-servicio/';

            // Asegurar que el directorio exista
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $this->generarNombreArchivo($archivo);
                $archivo->move($uploadPath, $nombreArchivo);

                $datos = [
                    'ID_ESTADO_REVISION' => 1, // Estado inicial: Pendiente
                    'ID_TIPO_DOCUMENTO_SERVICIO' => $this->request->getPost('id_tipo_documento'),
                    'ID_USUARIO' => $this->request->getPost('id_servicio'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                    'TIPO' => $archivo->getClientMimeType(),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                    'OBSERVACIONES' => $this->request->getPost('observaciones') ?? ''
                ];

                if ($this->documentosModel->insert($datos)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al guardar el documento en la base de datos'
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
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver documento
     */
    public function ver($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Descargar documento
     */
    public function descargar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($filePath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no encontrado'
            ]);
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Eliminar documento
     */
    public function eliminar($id)
    {
        try {
            $documento = $this->documentosModel->find($id);
            
            if (!$documento) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Documento no encontrado'
                ]);
            }

            // Eliminar archivo físico
            $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Eliminar registro de la base de datos
            if ($this->documentosModel->delete($id)) {
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
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar estado del documento
     */
    public function cambiarEstado($id)
    {
        $rules = [
            'estado' => 'required|in_list[Pendiente,En Revisión,Aprobado,Rechazado,Requiere Corrección]',
            'observaciones' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Obtener ID del estado
            $estadoNombre = $this->request->getPost('estado');
            $estado = $this->estadosRevisionesModel->where('ESTADO', $estadoNombre)->first();
            
            if (!$estado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido'
                ]);
            }

            $datos = [
                'ID_ESTADO_REVISION' => $estado['ID_ESTADO_REVISION'],
                'OBSERVACIONES_REVISOR' => $this->request->getPost('observaciones') ?? ''
            ];

            if ($this->documentosModel->update($id, $datos)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente'
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
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener documentos completos con toda la información
     */
    public function getDocumentosCompletos()
    {
        try {
            return $this->documentosModel->getDocumentosCompletos();
        } catch (\Exception $e) {
            // Si hay error, devolver array vacío para evitar errores en la vista
            return [];
        }
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticas()
    {
        try {
            $total = $this->documentosModel->countAllResults();
            $aprobados = $this->documentosModel->where('ID_ESTADO_REVISION', 3)->countAllResults(); // Aprobado
            $pendientes = $this->documentosModel->where('ID_ESTADO_REVISION', 1)->countAllResults(); // Pendiente
            $rechazados = $this->documentosModel->where('ID_ESTADO_REVISION', 4)->countAllResults(); // Rechazado

            return [
                'total' => $total,
                'aprobados' => $aprobados,
                'pendientes' => $pendientes,
                'rechazados' => $rechazados
            ];
        } catch (\Exception $e) {
            // Si hay error, devolver estadísticas de ejemplo
            return [
                'total' => 0,
                'aprobados' => 0,
                'pendientes' => 0,
                'rechazados' => 0
            ];
        }
    }

    /**
     * Obtener lista de estudiantes
     */
    public function getEstudiantes()
    {
        try {
            return $this->usuariosModel
                ->select('TAB_USUARIOS.ID_USUARIO, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO, TAB_DATOS_PERSONAS.CEDULA')
                ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
                ->join('TAB_ROLES', 'TAB_USUARIOS.ID_ROL = TAB_ROLES.ID_ROL')
                ->where('TAB_ROLES.NOMBRE_ROL', 'Estudiante')
                ->findAll();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Generar nombre único para archivo
     */
    private function generarNombreArchivo($archivo)
    {
        $extension = $archivo->getClientExtension();
        $nombreBase = pathinfo($archivo->getClientName(), PATHINFO_FILENAME);
        $nombreBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreBase);
        
        return $nombreBase . '_' . time() . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Mostrar vista de reportes
     */
    public function reportes()
    {
        $filtros = $this->request->getGet();
        
        $data = [
            'title' => 'Reportes de Documentos de Servicio Comunitario',
            'documentos' => $this->getDocumentosCompletos(),
            'estadisticas' => $this->getEstadisticas(),
            'tiposDocumentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'filtros' => $filtros
        ];

        return view('admin/documentos/reportes_servicio_comunitario', $data);
    }
}
