<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;
use App\Services\EstudianteAsistenciaService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DocumentosPracticasEstudianteController extends BaseController
{
    protected $documentosModel;
    protected $tiposDocumentosModel;
    protected $estadosRevisionesModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        if (!session()->get('logged_in') || (int) session()->get('rol') !== 4) {
            redirect()->to('/')->send();
            exit;
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
        $idUsuario = (int) session()->get('id_usuario');

        $tipos = $this->tiposDocumentosModel->getAllTipos();
        $pendAsist = EstudianteAsistenciaService::pendientesAsistenciaHoy($idUsuario);
        $itemsPp = array_values(array_filter(
            $pendAsist['items'],
            static fn (array $i): bool => ($i['tipo'] ?? '') === 'preprofesional'
        ));
        $itemsPpActivas = EstudianteAsistenciaService::itemsPreprofesionalesEnProgreso($idUsuario);
        $tienePpActiva = EstudianteAsistenciaService::tienePracticaPreprofesionalEnProgreso($idUsuario);
        $resumenPpDia = EstudianteAsistenciaService::resumenPreprofesionalDia($idUsuario, $pendAsist['fecha']);
        $asistenciaBarPct = ($resumenPpDia['en_progreso'] ?? 0) > 0
            ? (int) round(100 * ($resumenPpDia['registradas_hoy'] ?? 0) / $resumenPpDia['en_progreso'])
            : 0;
        $practicasDocumentacion = $this->obtenerPracticasDocumentacionEstudiante($idUsuario);
        $asistenciaHorasPp = EstudianteAsistenciaService::horasPreprofesionalesEnProgreso($idUsuario);

        $data = [
            'title' => 'Documentos de Prácticas Preprofesionales',
            'tipos_documentos' => $tipos,
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'progreso' => $this->getProgresoEstudiante($idUsuario),
            'estadisticas' => $this->getEstadisticasEstudiante($idUsuario, count($tipos)),
            'total_tipos_documentos' => count($tipos),
            'asistencia_items' => $itemsPp,
            'asistencia_items_activa' => $itemsPpActivas,
            'asistencia_fecha' => $pendAsist['fecha'],
            'asistencia_tiene_activa' => $tienePpActiva,
            'asistencia_mostrar_tarjeta' => false,
            'asistencia_franja_superior' => ($resumenPpDia['en_progreso'] ?? 0) > 0,
            'asistencia_resumen_pp' => $resumenPpDia,
            'asistencia_bar_pct' => $asistenciaBarPct,
            'asistencia_modal_automatico' => false,
            'asistencia_titulo_tarjeta' => 'Asistencia — prácticas preprofesionales',
            'asistencia_doc_info_sin_progreso' => $practicasDocumentacion !== []
                && ($resumenPpDia['en_progreso'] ?? 0) === 0,
            'asistencia_horas_pp' => $asistenciaHorasPp,
            'practicas_documentacion' => $practicasDocumentacion,
        ];

        return view('estudiante/documentos/documentos_practicas', $data);
    }

    /**
     * Prácticas del estudiante con entidad convenio y nombre del instructor (para pantalla de documentos).
     *
     * @return list<array<string, mixed>>
     */
    private function obtenerPracticasDocumentacionEstudiante(int $idUsuario): array
    {
        try {
            $db = \Config\Database::connect();

            $est = $db->table('TAB_ESTUDIANTES e')
                ->select('e.ID_ESTUDIANTE')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('u.ID_USUARIO', $idUsuario)
                ->get()
                ->getRowArray();

            if (empty($est['ID_ESTUDIANTE'])) {
                return [];
            }

            $idEst = (int) $est['ID_ESTUDIANTE'];

            // Usar siempre TAB_INSTITUCIONES_CONVENIOS (MySQL resuelve mayúsculas/minúsculas). No usar tableExists():
            // en servidores con tablas en minúsculas, listTables() no coincide y el fallback "instituciones_convenios" rompe la consulta.
            // Tutor docente vía TAB_DOCENTES_TUTORES → datos persona
            return $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.ID_PRACTICA_PREPROFESIONAL, ic.NOMBRE as INSTITUCION_NOMBRE, CONCAT(COALESCE(dpdt.NOMBRE,\'\'), \' \', COALESCE(dpdt.APELLIDO,\'\')) as SUPERVISOR_NOMBRE', false)
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO', 'left')
                ->join('TAB_DOCENTES_TUTORES dt', 'dt.ID_DOCENTE_TUTOR = pp.ID_DOCENTE_TUTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dpdt', 'dpdt.ID_DATO_PERSONA = dt.ID_DATO_PERSONA', 'left')
                ->where('pp.ID_ESTUDIANTE', $idEst)
                ->orderBy('pp.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'obtenerPracticasDocumentacionEstudiante: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Subir documento de práctica
     */
    public function subirDocumento()
    {
        $idUsuario = session()->get('id_usuario');
        
        $rules = [
            'tipo_documento' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf]',
            'entidad_receptora' => 'permit_empty|max_length[255]',
            'docente_tutor' => 'permit_empty|max_length[255]',
            'observaciones' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $msg = 'Datos de entrada inválidos.';
            if (isset($errors['archivo'])) {
                $msg = 'Solo se permiten archivos PDF con un tamaño máximo de 10 MB.';
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => $msg,
                'errors' => $errors
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
                ];

                if ($this->documentosModel->insert($datos)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente. Será revisado por el coordinador.',
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

        if (!$this->documentosModel->documentoPerteneceAUsuario((int) $id, $idUsuario)) {
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

        if (!$this->documentosModel->documentoPerteneceAUsuario((int) $id, $idUsuario)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para eliminar este documento'
            ]);
        }

        // Aprobado = ID 3 en TAB_ESTADOS_REVISIONES
        if (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 3) {
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
    private function getEstadisticasEstudiante($idUsuario, int $totalTiposConfigurados = 12)
    {
        $documentos = $this->documentosModel->getDocumentosPorEstudiante($idUsuario);

        $total = count($documentos);
        $aprobados = 0;
        $pendientes = 0;
        $rechazados = 0;
        $en_revision = 0;
        $requiere = 0;

        foreach ($documentos as $documento) {
            $est = $documento['ESTADO_REVISION'] ?? '';
            if ($est === 'Aprobado' || (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 3)) {
                $aprobados++;
            } elseif ($est === 'Pendiente' || (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 1)) {
                $pendientes++;
            } elseif ($est === 'Rechazado' || (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 4)) {
                $rechazados++;
            } elseif ($est === 'En Revisión' || (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 2)) {
                $en_revision++;
            } elseif ($est === 'Requiere Corrección' || (!empty($documento['ID_ESTADO_REVISION']) && (int) $documento['ID_ESTADO_REVISION'] === 5)) {
                $requiere++;
            }
        }

        $den = $totalTiposConfigurados > 0 ? $totalTiposConfigurados : 12;

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados,
            'en_revision' => $en_revision,
            'requiere_correccion' => $requiere,
            'porcentaje_completado' => $den > 0 ? round(($aprobados / $den) * 100, 1) : 0,
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
