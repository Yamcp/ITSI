<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\TiposDocumentosPracticasModel;
use App\Models\EstadosRevisionesModel;

class DocumentosPracticasEstudianteController extends BaseController
{
    protected $documentosModel;
    protected $tiposDocumentosModel;
    protected $estadosRevisionesModel;

    public function __construct()
    {
        // Verificar autenticación y rol de estudiante
        if (!session()->get('logged_in') || session()->get('rol') != 3) {
            return redirect()->to('/');
        }
        
        $this->documentosModel = new DocumentosPracticasModel();
        $this->tiposDocumentosModel = new TiposDocumentosPracticasModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
    }

    /**
     * Vista principal de documentos de prácticas para estudiantes
     */
    public function index()
    {
        $idUsuario = session()->get('id_usuario');
        
        $data = [
            'title' => 'Documentos de Prácticas Preprofesionales',
            'tipos_documentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'progreso' => $this->getProgresoEstudiante($idUsuario),
            'estadisticas' => $this->getEstadisticasEstudiante($idUsuario)
        ];

        return view('estudiante/documentos/documentos_practicas', $data);
    }

    /**
     * Subir documento de práctica
     */
    public function subirDocumento()
    {
        $idUsuario = session()->get('id_usuario');
        
        $rules = [
            'tipo_documento' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,51200]|ext_in[archivo,pdf,doc,docx,jpg,jpeg,png,mp4,avi,zip,rar]',
            'entidad_receptora' => 'permit_empty|max_length[255]',
            'docente_tutor' => 'permit_empty|max_length[255]',
            'observaciones' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Verificar si ya existe un documento de este tipo para el estudiante
            $documentoExistente = $this->documentosModel->verificarDocumentoExistente(
                $idUsuario, 
                $this->request->getPost('tipo_documento')
            );

            if ($documentoExistente) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya tienes un documento de este tipo subido. Si necesitas actualizarlo, elimina el anterior primero.'
                ]);
            }

            // Manejar subida de archivo
            $archivo = $this->request->getFile('archivo');
            $uploadPath = WRITEPATH . 'uploads/documentos-practicas/';

            // Asegurar que el directorio exista
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($archivo->isValid() && !$archivo->hasMoved()) {
                $idPractica = $this->request->getPost('id_practica');
                if (empty($idPractica)) {
                    $idPractica = $this->documentosModel->getIdPrimeraPracticaEstudiante($idUsuario);
                }
                if (empty($idPractica)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No tienes una práctica preprofesional registrada. Regístrala primero.'
                    ]);
                }

                $nombreArchivo = $this->generarNombreArchivo($archivo, $idUsuario);
                $archivo->move($uploadPath, $nombreArchivo);

                $datos = [
                    'ID_PRACTICA_PREPROFESIONAL' => (int) $idPractica,
                    'ID_ESTADO_REVISION' => 1, // Estado inicial: Pendiente
                    'ID_TIPO_DOCUMENTO' => $this->request->getPost('tipo_documento'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                    'TIPO_ARCHIVO' => $archivo->getClientMimeType() ?: $archivo->getClientName(),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                    'OBSERVACIONES' => $this->request->getPost('observaciones') ?? '',
                    'ENTIDAD_RECEPTORA' => $this->request->getPost('entidad_receptora') ?? '',
                    'DOCENTE_TUTOR' => $this->request->getPost('docente_tutor') ?? ''
                ];

                if ($this->documentosModel->insert($datos)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente. Será revisado por el administrador.',
                        'data' => [
                            'id' => $this->documentosModel->getInsertID(),
                            'nombre' => $archivo->getClientName(),
                            'fecha' => date('d/m/Y H:i')
                        ]
                    ]);
                } else {
                    // Si falla la inserción, eliminar el archivo subido
                    unlink($uploadPath . $nombreArchivo);
                    throw new \Exception('Error al guardar en la base de datos');
                }
            } else {
                throw new \Exception('Archivo no válido o error al subir el archivo');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver mis documentos subidos
     */
    public function misDocumentos()
    {
        $idUsuario = session()->get('id_usuario');
        
        $data = [
            'title' => 'Mis Documentos de Prácticas',
            'documentos' => $this->documentosModel->getDocumentosPorEstudiante($idUsuario),
            'tipos_documentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados()
        ];

        return view('estudiante/documentos/mis_documentos', $data);
    }

    /**
     * Ver progreso de documentos
     */
    public function verProgreso()
    {
        $idUsuario = session()->get('id_usuario');
        
        $data = [
            'title' => 'Progreso de Documentos',
            'progreso' => $this->getProgresoEstudiante($idUsuario),
            'estadisticas' => $this->getEstadisticasEstudiante($idUsuario)
        ];

        return view('estudiante/documentos/progreso', $data);
    }

    /**
     * Descargar documento
     */
    public function descargarDocumento($id)
    {
        $idUsuario = session()->get('id_usuario');
        $documento = $this->documentosModel->find($id);

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        // Verificar que el documento pertenece al estudiante
        if ($documento['ID_USUARIO'] != $idUsuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No tienes permisos para acceder a este documento');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];

        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }

    /**
     * Eliminar documento
     */
    public function eliminarDocumento($id)
    {
        $idUsuario = session()->get('id_usuario');
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        // Verificar que el documento pertenece al estudiante
        if ($documento['ID_USUARIO'] != $idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para eliminar este documento'
            ]);
        }

        // Verificar que el documento no esté aprobado
        if ($documento['ID_ESTADO_REVISION'] == 2) { // Aprobado
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes eliminar un documento que ya ha sido aprobado'
            ]);
        }

        // Eliminar archivo físico
        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
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
    }

    /**
     * Obtener progreso del estudiante
     */
    private function getProgresoEstudiante($idUsuario)
    {
        return $this->documentosModel->getProgresoEstudiante($idUsuario);
    }

    /**
     * Obtener estadísticas del estudiante
     */
    private function getEstadisticasEstudiante($idUsuario)
    {
        $documentos = $this->documentosModel->getDocumentosPorEstudiante($idUsuario);
        
        $total = count($documentos);
        $aprobados = 0;
        $pendientes = 0;
        $rechazados = 0;
        $en_revision = 0;

        foreach ($documentos as $documento) {
            switch ($documento['ESTADO_REVISION']) {
                case 'Aprobado':
                    $aprobados++;
                    break;
                case 'Pendiente':
                    $pendientes++;
                    break;
                case 'Rechazado':
                    $rechazados++;
                    break;
                case 'En Revisión':
                    $en_revision++;
                    break;
            }
        }

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados,
            'en_revision' => $en_revision,
            'porcentaje_completado' => $total > 0 ? round(($aprobados / 12) * 100, 2) : 0
        ];
    }

    /**
     * Generar nombre único para el archivo
     */
    private function generarNombreArchivo($archivo, $idUsuario)
    {
        $extension = $archivo->getClientExtension();
        $timestamp = date('YmdHis');
        $random = bin2hex(random_bytes(4));
        return "estudiante_{$idUsuario}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * API para obtener tipos de documentos disponibles
     */
    public function apiTiposDocumentos()
    {
        $tipos = $this->tiposDocumentosModel->getAllTipos();
        return $this->response->setJSON([
            'success' => true,
            'data' => $tipos
        ]);
    }

    /**
     * API para obtener progreso del estudiante
     */
    public function apiProgreso()
    {
        $idUsuario = session()->get('id_usuario');
        $progreso = $this->getProgresoEstudiante($idUsuario);
        $estadisticas = $this->getEstadisticasEstudiante($idUsuario);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'progreso' => $progreso,
                'estadisticas' => $estadisticas
            ]
        ]);
    }

    /**
     * API para obtener mis documentos
     */
    public function apiMisDocumentos()
    {
        $idUsuario = session()->get('id_usuario');
        $documentos = $this->documentosModel->getDocumentosPorEstudiante($idUsuario);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }
}
