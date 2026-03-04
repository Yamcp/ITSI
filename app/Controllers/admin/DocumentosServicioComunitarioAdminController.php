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
        try {
            // Verificar si hay tipos de documentos, si no, crear los predefinidos
            $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
            
            if (empty($tiposDocumentos)) {
                // Intentar crear los tipos predefinidos
                $resultado = $this->tiposDocumentosModel->crearTiposPredefinidos();
                if ($resultado) {
                    $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
                } else {
                    // Si falla, crear manualmente los tipos básicos
                    $this->crearTiposBasicos();
                    $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
                }
            }

            // Verificar si hay documentos, si no, crear algunos de ejemplo
            $documentos = $this->getDocumentosCompletos();
            if (empty($documentos)) {
                $this->crearDocumentosEjemplo();
            }

            $data = [
                'title' => 'Gestión de Documentos de Servicio Comunitario',
                'documentos' => $this->getDocumentosCompletos(),
                'estadisticas' => $this->getEstadisticas(),
                'tiposDocumentos' => $tiposDocumentos,
                'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
                'estudiantes' => $this->getEstudiantes(),
                'qr_servicio_url' => $this->getQrServicioUrl(),
            ];

            return view('admin/documentos/documentos_servicio_comunitario', $data);
            
        } catch (\Exception $e) {
            // En caso de error, mostrar vista con datos mínimos
            $data = [
                'title' => 'Gestión de Documentos de Servicio Comunitario',
                'documentos' => [],
                'estadisticas' => ['Aprobados' => 0, 'pendientes' => 0, 'requiere_correccion' => 0, 'rechazados' => 0],
                'tiposDocumentos' => [],
                'estados_revision' => [],
                'estudiantes' => [],
                'qr_servicio_url' => $this->getQrServicioUrl(),
            ];
            
            return view('admin/documentos/documentos_servicio_comunitario', $data);
        }
    }

    /**
     * Obtener URL del QR de servicio comunitario. Se refleja en el perfil del estudiante.
     */
    private function getQrServicioUrl()
    {
        $qrFile = WRITEPATH . 'uploads/qr/servicio_comunitario.png';
        if (file_exists($qrFile) && is_readable($qrFile)) {
            return base_url('qr/servicio');
        }
        return base_url('sistema/assets/images/practicas/formatos-servicio-comunitario-qr.png');
    }

    /**
     * Subir / actualizar imagen QR de formatos de servicio comunitario.
     * Se refleja en el perfil del estudiante (Servicio Comunitario).
     */
    public function subirQr()
    {
        $file = $this->request->getFile('qr_imagen');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Seleccione una imagen válida (PNG o JPG).'
            ]);
        }
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo se permiten imágenes PNG o JPG.'
            ]);
        }
        $qrDir = WRITEPATH . 'uploads/qr/';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
        $file->move($qrDir, 'servicio_comunitario.png');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Código QR actualizado. Se verá en el perfil del estudiante.',
            'url' => $this->getQrServicioUrl()
        ]);
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

    /**
     * Crear documentos de ejemplo para demostración
     */
    private function crearDocumentosEjemplo()
    {
        // Solo crear ejemplos si no hay documentos
        $documentosExistentes = $this->documentosModel->countAllResults();
        if ($documentosExistentes > 0) {
            return;
        }

        // Obtener tipos de documentos
        $tipos = $this->tiposDocumentosModel->getAllTipos();
        if (empty($tipos)) {
            return;
        }

        // Datos de ejemplo
        $documentosEjemplo = [
            [
                'ID_SERVICIO_COMUNITARIO' => 1,
                'ID_TIPO_DOCUMENTO' => $tipos[0]['ID_TIPO_DOCUMENTO_SERVICIO'],
                'NOMBRE_ARCHIVO' => 'plan_trabajo_ejemplo.pdf',
                'TIPO_ARCHIVO' => 'application/pdf',
                'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                'ESTADO_REVISION' => 'Pendiente',
                'OBSERVACIONES' => 'Documento de ejemplo para demostración'
            ],
            [
                'ID_SERVICIO_COMUNITARIO' => 1,
                'ID_TIPO_DOCUMENTO' => $tipos[1]['ID_TIPO_DOCUMENTO_SERVICIO'],
                'NOMBRE_ARCHIVO' => 'cronograma_actividades_ejemplo.pdf',
                'TIPO_ARCHIVO' => 'application/pdf',
                'FECHA_SUBIDA' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'ESTADO_REVISION' => 'Aprobado',
                'OBSERVACIONES' => 'Cronograma aprobado correctamente'
            ],
            [
                'ID_SERVICIO_COMUNITARIO' => 2,
                'ID_TIPO_DOCUMENTO' => $tipos[0]['ID_TIPO_DOCUMENTO_SERVICIO'],
                'NOMBRE_ARCHIVO' => 'plan_trabajo_estudiante2.pdf',
                'TIPO_ARCHIVO' => 'application/pdf',
                'FECHA_SUBIDA' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'ESTADO_REVISION' => 'Requiere Corrección',
                'OBSERVACIONES' => 'Faltan detalles en el plan de trabajo'
            ]
        ];

        // Insertar documentos de ejemplo
        foreach ($documentosEjemplo as $documento) {
            $this->documentosModel->insert($documento);
        }
    }

    /**
     * Crear tipos básicos de documentos de servicio comunitario
     */
    private function crearTiposBasicos()
    {
        $tiposBasicos = [
            [
                'CODIGO' => 'PSC-001',
                'NOMBRE' => 'Oficio de Asignación de Tutor',
                'DESCRIPCION' => 'Documento oficial que designa al docente tutor responsable del servicio comunitario',
                'ORDEN' => 1,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-002',
                'NOMBRE' => 'Oficio a Entidad Receptora',
                'DESCRIPCION' => 'Carta formal a la institución solicitando oportunidad de servicio comunitario',
                'ORDEN' => 2,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-003',
                'NOMBRE' => 'Carta de Aceptación',
                'DESCRIPCION' => 'Carta oficial de aceptación de la entidad receptora',
                'ORDEN' => 3,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-004',
                'NOMBRE' => 'Solicitud Institucional',
                'DESCRIPCION' => 'Solicitud al Rector para aprobación institucional',
                'ORDEN' => 4,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-005',
                'NOMBRE' => 'Certificado de Culminación',
                'DESCRIPCION' => 'Certificado de horas completadas de servicio comunitario',
                'ORDEN' => 5,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ]
        ];

        // Insertar tipos básicos
        foreach ($tiposBasicos as $tipo) {
            try {
                $this->tiposDocumentosModel->insert($tipo);
            } catch (\Exception $e) {
                // Continuar con el siguiente si hay error
                continue;
            }
        }
    }
}
