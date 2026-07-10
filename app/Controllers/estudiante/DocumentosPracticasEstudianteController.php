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
                $db = \Config\Database::connect();
                $idSolicitado = $this->leerIdEnteroPost('id_practica');
                $idPractica = $this->resolverIdPracticaEstudiante((int) $idUsuario, $idSolicitado);
                if ($idPractica <= 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $idSolicitado > 0
                            ? 'La práctica seleccionada no es válida para tu usuario.'
                            : 'No tienes una práctica preprofesional registrada. Solicita la asignación a vinculación primero.',
                    ]);
                }

                // Revalidar existencia real justo antes del INSERT (misma conexión).
                $practicaOk = $db->query(
                    'SELECT ID_PRACTICA_PREPROFESIONAL FROM TAB_PRACTICAS_PREPROFESIONALES WHERE ID_PRACTICA_PREPROFESIONAL = ? LIMIT 1',
                    [$idPractica]
                )->getRowArray();
                if (empty($practicaOk)) {
                    log_message('error', 'Subida docs PP: ID_PRACTICA={id} no existe en TAB_PRACTICAS_PREPROFESIONALES (usuario={user}, post={post})', [
                        'id' => $idPractica,
                        'user' => $idUsuario,
                        'post' => $idSolicitado,
                    ]);

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se encontró una práctica preprofesional válida en el sistema. Pide a vinculación que revise tu asignación.',
                    ]);
                }
                $idPractica = (int) $this->valorFila($practicaOk, 'ID_PRACTICA_PREPROFESIONAL');

                $idTipoDocumento = $this->leerIdEnteroPost('tipo_documento');
                if ($idTipoDocumento <= 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Tipo de documento no válido.'
                    ]);
                }

                $tipoOk = $db->query(
                    'SELECT ID_TIPO_DOCUMENTO_PREPROFESIONAL FROM TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES WHERE ID_TIPO_DOCUMENTO_PREPROFESIONAL = ? LIMIT 1',
                    [$idTipoDocumento]
                )->getRowArray();
                if (empty($tipoOk)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'El tipo de documento no existe o fue desactivado.',
                    ]);
                }

                $nombreOriginal = $archivo->getClientName();
                $tamanoArchivo = (int) $archivo->getSize();
                $mimeType = $archivo->getClientMimeType() ?: 'application/pdf';
                if (strlen($mimeType) > 100) {
                    $mimeType = 'application/pdf';
                }

                $nombreArchivo = $this->generarNombreArchivo($archivo, $idUsuario);
                $archivo->move($uploadPath, $nombreArchivo);

                // Solo columnas seguras; ID de práctica ya verificado en BD.
                $datos = [
                    'ID_PRACTICA_PREPROFESIONAL' => $idPractica,
                    'ID_ESTADO_REVISION' => 1,
                    'ID_TIPO_DOCUMENTO' => $idTipoDocumento,
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                    'TIPO_ARCHIVO' => $mimeType,
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                    'OBSERVACIONES' => (string) ($this->request->getPost('observaciones') ?? ''),
                ];

                $camposOpcionales = [
                    'NOMBRE_ORIGINAL' => $nombreOriginal,
                    'TAMANO_ARCHIVO' => $tamanoArchivo,
                    'RUTA_ARCHIVO' => '/uploads/documentos-practicas/',
                    'VERSION' => 1,
                    'ACTIVO' => 1,
                ];
                foreach ($camposOpcionales as $columna => $valor) {
                    if ($db->fieldExists($columna, 'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES')) {
                        $datos[$columna] = $valor;
                    }
                }

                // INSERT SQL directo con el ID ya verificado en la misma conexión.
                $columnas = array_keys($datos);
                $placeholders = implode(', ', array_fill(0, count($columnas), '?'));
                $sql = 'INSERT INTO TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES ('
                    . implode(', ', $columnas)
                    . ') VALUES (' . $placeholders . ')';

                try {
                    $resultado = $db->query($sql, array_values($datos));
                    // Con DBDebug=false, una consulta fallida no lanza excepción: solo
                    // retorna false. Hay que comprobarlo explícitamente para no reportar
                    // éxito cuando el INSERT nunca se guardó.
                    if ($resultado === false || (int) $db->affectedRows() < 1) {
                        throw new \RuntimeException('DB query returned false or affected 0 rows');
                    }

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente. Será revisado por el coordinador.',
                        'data' => [
                            'id' => (int) $db->insertID(),
                            'nombre' => $nombreOriginal,
                            'fecha' => date('d/m/Y H:i'),
                        ],
                    ]);
                } catch (\Throwable $insertEx) {
                    @unlink($uploadPath . $nombreArchivo);
                    $dbError = $db->error();
                    log_message('error', 'Error al guardar documento prácticas. Ex: {ex}. DB: {db}. Data: {data}', [
                        'ex' => $insertEx->getMessage(),
                        'db' => json_encode($dbError, JSON_UNESCAPED_UNICODE),
                        'data' => json_encode($datos, JSON_UNESCAPED_UNICODE),
                    ]);

                    $msgDb = (string) (($dbError['message'] ?? '') !== '' ? $dbError['message'] : $insertEx->getMessage());
                    // TEMPORAL: se muestra el detalle técnico de MySQL en pantalla porque
                    // el hosting (InfinityFree, capa gratuita) no da acceso a writable/logs.
                    // Quitar este sufijo una vez diagnosticado el problema en producción.
                    $detalleTecnico = ' [Detalle técnico: código ' . ($dbError['code'] ?? '?') . ' — ' . $msgDb . ']';

                    if (stripos($msgDb, 'foreign key') !== false || stripos($msgDb, 'CONSTRAINT') !== false) {
                        // Identificar la FK real en vez de asumir siempre que es la práctica:
                        // esta tabla tiene 4 llaves foráneas (práctica, tipo, estado, revisor).
                        preg_match('/CONSTRAINT `?([A-Za-z0-9_]+)`?/i', $msgDb, $m);
                        $constraint = strtoupper($m[1] ?? '');

                        $mensajesPorFk = [
                            'FK_DOCS_PREPROFESIONALES_PRACTICA' => 'No se pudo vincular el archivo a tu práctica (ID ' . $idPractica . '). Verifica con vinculación que tu práctica preprofesional esté creada correctamente.',
                            'FK_DOCS_PREPROFESIONALES_TIPO' => 'El tipo de documento seleccionado (ID ' . $idTipoDocumento . ') no existe en la base de datos de este entorno.',
                            'FK_DOCS_PREPROFESIONALES_ESTADO' => 'El estado de revisión inicial no existe en la base de datos de este entorno.',
                            'FK_DOCS_PREPROFESIONALES_REVISOR' => 'El revisor asignado no existe en la base de datos de este entorno.',
                        ];

                        throw new \Exception(
                            ($mensajesPorFk[$constraint]
                                ?? ('No se pudo vincular el archivo (restricción: ' . ($constraint !== '' ? $constraint : 'desconocida') . ').'))
                            . $detalleTecnico
                        );
                    }

                    throw new \Exception(($msgDb !== '' ? $msgDb : 'Error al guardar en la base de datos') . $detalleTecnico);
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
     * Lee un entero positivo desde POST (soporta arrays/duplicados del FormData).
     */
    private function leerIdEnteroPost(string $campo): int
    {
        $raw = $this->request->getPost($campo);
        if (is_array($raw)) {
            $raw = end($raw);
        }
        $id = (int) $raw;

        return $id > 0 ? $id : 0;
    }

    /**
     * Obtiene un valor de fila tolerando mayúsculas/minúsculas en el nombre de columna.
     *
     * @param array<string, mixed> $fila
     */
    private function valorFila(array $fila, string $columna): mixed
    {
        if (array_key_exists($columna, $fila)) {
            return $fila[$columna];
        }
        $lower = strtolower($columna);
        foreach ($fila as $k => $v) {
            if (strtolower((string) $k) === $lower) {
                return $v;
            }
        }

        return null;
    }

    /**
     * Resuelve un ID_PRACTICA_PREPROFESIONAL real del estudiante autenticado.
     * Acepta el ID de práctica o, por compatibilidad, el ID_ASIGNACION_PRACTICA.
     */
    private function resolverIdPracticaEstudiante(int $idUsuario, int $idSolicitado = 0): int
    {
        $db = \Config\Database::connect();

        $est = $db->query(
            'SELECT e.ID_ESTUDIANTE
             FROM TAB_ESTUDIANTES e
             INNER JOIN TAB_USUARIOS u ON u.ID_DATO_PERSONA = e.ID_DATO_PERSONA
             WHERE u.ID_USUARIO = ?
             LIMIT 1',
            [$idUsuario]
        )->getRowArray();

        $idEst = (int) $this->valorFila($est ?? [], 'ID_ESTUDIANTE');
        if ($idEst <= 0) {
            return 0;
        }

        $practicas = $db->query(
            'SELECT ID_PRACTICA_PREPROFESIONAL, ID_ASIGNACION_PRACTICA
             FROM TAB_PRACTICAS_PREPROFESIONALES
             WHERE ID_ESTUDIANTE = ?
             ORDER BY FECHA_INICIO DESC, ID_PRACTICA_PREPROFESIONAL DESC',
            [$idEst]
        )->getResultArray();

        if ($practicas === []) {
            return 0;
        }

        $porPractica = [];
        $porAsignacion = [];
        foreach ($practicas as $p) {
            $idP = (int) $this->valorFila($p, 'ID_PRACTICA_PREPROFESIONAL');
            if ($idP <= 0) {
                continue;
            }
            $porPractica[$idP] = $idP;
            $idAsig = (int) $this->valorFila($p, 'ID_ASIGNACION_PRACTICA');
            if ($idAsig > 0) {
                $porAsignacion[$idAsig] = $idP;
            }
        }

        if ($porPractica === []) {
            return 0;
        }

        if ($idSolicitado > 0) {
            if (isset($porPractica[$idSolicitado])) {
                return $idSolicitado;
            }
            if (isset($porAsignacion[$idSolicitado])) {
                return $porAsignacion[$idSolicitado];
            }

            return 0;
        }

        return (int) reset($porPractica);
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
