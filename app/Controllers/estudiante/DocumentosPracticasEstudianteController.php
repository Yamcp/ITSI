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
            $rows = $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.ID_PRACTICA_PREPROFESIONAL, ic.NOMBRE as INSTITUCION_NOMBRE, CONCAT(COALESCE(dpdt.NOMBRE,\'\'), \' \', COALESCE(dpdt.APELLIDO,\'\')) as SUPERVISOR_NOMBRE', false)
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO', 'left')
                ->join('TAB_DOCENTES_TUTORES dt', 'dt.ID_DOCENTE_TUTOR = pp.ID_DOCENTE_TUTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dpdt', 'dpdt.ID_DATO_PERSONA = dt.ID_DATO_PERSONA', 'left')
                ->where('pp.ID_ESTUDIANTE', $idEst)
                ->orderBy('pp.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();

            $out = [];
            foreach ($rows as $row) {
                $idP = (int) $this->valorFila($row, 'ID_PRACTICA_PREPROFESIONAL');
                if ($idP <= 0) {
                    continue;
                }
                $out[] = [
                    'ID_PRACTICA_PREPROFESIONAL' => $idP,
                    'INSTITUCION_NOMBRE' => (string) ($this->valorFila($row, 'INSTITUCION_NOMBRE') ?? ''),
                    'SUPERVISOR_NOMBRE' => trim((string) ($this->valorFila($row, 'SUPERVISOR_NOMBRE') ?? '')),
                ];
            }

            return $out;
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
        $idUsuario = (int) session()->get('id_usuario');

        $rules = [
            'tipo_documento' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf]',
            'entidad_receptora' => 'permit_empty|max_length[255]',
            'docente_tutor' => 'permit_empty|max_length[255]',
            'observaciones' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $msg = isset($errors['archivo'])
                ? 'Solo se permiten archivos PDF con un tamaño máximo de 10 MB.'
                : 'Datos de entrada inválidos.';

            return $this->response->setJSON([
                'success' => false,
                'message' => $msg,
                'errors' => $errors,
            ]);
        }

        try {
            $idTipoDocumento = $this->leerIdEnteroPost('tipo_documento');
            if ($idTipoDocumento <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de documento no válido.',
                ]);
            }

            if ($this->documentosModel->verificarDocumentoExistente($idUsuario, $idTipoDocumento)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ya tienes un documento de este tipo subido. Si necesitas actualizarlo, elimina el anterior primero.',
                ]);
            }

            $archivo = $this->request->getFile('archivo');
            $uploadPath = WRITEPATH . 'uploads/documentos-practicas/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
                throw new \Exception('Archivo no válido o error al subir el archivo');
            }

            $db = \Config\Database::connect();
            /** @var \mysqli|null $mysqli */
            $mysqli = $db->connID ?? null;
            if (!($mysqli instanceof \mysqli)) {
                throw new \Exception('No hay conexión MySQLi disponible para guardar el documento.');
            }

            $idEstudiante = $this->obtenerIdEstudiantePorUsuario($mysqli, $idUsuario);
            if ($idEstudiante <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró el perfil de estudiante para tu usuario.',
                ]);
            }

            // Tabla exacta referenciada por la FK (evita desfase mayúsculas/minúsculas).
            $tablaPractica = $this->nombreTablaReferenciadaFk(
                $mysqli,
                'FK_DOCS_PREPROFESIONALES_PRACTICA',
                'TAB_PRACTICAS_PREPROFESIONALES'
            );
            $tablaDocs = $this->nombreTablaReal(
                $mysqli,
                'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES'
            );
            $tablaTipos = $this->nombreTablaReferenciadaFk(
                $mysqli,
                'FK_DOCS_PREPROFESIONALES_TIPO',
                'TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES'
            );
            $tablaEstados = $this->nombreTablaReferenciadaFk(
                $mysqli,
                'FK_DOCS_PREPROFESIONALES_ESTADO',
                'TAB_ESTADOS_REVISIONES'
            );

            // Si la práctica está en otra variante de nombre, copiarla a la tabla de la FK.
            $this->sincronizarPracticaHaciaTablaFk($mysqli, $tablaPractica, $idEstudiante);

            $idPractica = $this->obtenerIdPracticaEnTabla($mysqli, $tablaPractica, $idEstudiante);
            $idEstudianteParaInsert = $idEstudiante;

            // Compatibilidad: algunas filas antiguas guardaron ID_USUARIO en ID_ESTUDIANTE.
            if ($idPractica <= 0 && $idUsuario > 0 && $idUsuario !== $idEstudiante) {
                $this->sincronizarPracticaHaciaTablaFk($mysqli, $tablaPractica, $idUsuario);
                $idPracticaAlt = $this->obtenerIdPracticaEnTabla($mysqli, $tablaPractica, $idUsuario);
                if ($idPracticaAlt > 0) {
                    $idPractica = $idPracticaAlt;
                    $idEstudianteParaInsert = $idUsuario;
                    log_message('warning', 'Práctica encontrada por ID_USUARIO={u} en lugar de ID_ESTUDIANTE={e}', [
                        'u' => $idUsuario,
                        'e' => $idEstudiante,
                    ]);
                }
            }

            if ($idPractica <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No tienes una práctica preprofesional en la tabla vinculada por la FK ('
                        . $tablaPractica . '). Pide a vinculación que cree tu asignación de prácticas.',
                    'debug' => [
                        'id_estudiante' => $idEstudiante,
                        'id_usuario' => $idUsuario,
                        'tabla_practica_fk' => $tablaPractica,
                    ],
                ]);
            }

            if (!$this->existeIdEnTabla($mysqli, $tablaTipos, 'ID_TIPO_DOCUMENTO_PREPROFESIONAL', $idTipoDocumento)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El tipo de documento #' . $idTipoDocumento . ' no existe.',
                ]);
            }

            $idEstadoRevision = $this->obtenerIdEstadoPendienteMysqli($mysqli, $tablaEstados);
            if ($idEstadoRevision <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No hay estados de revisión en ' . $tablaEstados . '. Un administrador debe cargarlos.',
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
            $fechaSubida = date('Y-m-d H:i:s');
            $observaciones = (string) ($this->request->getPost('observaciones') ?? '');

            // Asegurar fila de práctica en la tabla exacta de la FK (repara desfase de mayúsculas).
            $idPractica = $this->asegurarPracticaEnTablaFk(
                $mysqli,
                $tablaPractica,
                $idEstudianteParaInsert,
                $idPractica
            );

            if ($idPractica <= 0 || !$this->existeIdEnTabla($mysqli, $tablaPractica, 'ID_PRACTICA_PREPROFESIONAL', $idPractica)) {
                @unlink($uploadPath . $nombreArchivo);

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo asegurar la práctica #' . $idPractica
                        . ' dentro de `' . $tablaPractica . '` (tabla de la FK). '
                        . 'Abre /estudiante/documentos-practicas/diagnostico y comparte el JSON, '
                        . 'o pide a vinculación que recree tu práctica.',
                    'debug' => [
                        'id_practica' => $idPractica,
                        'id_estudiante' => $idEstudianteParaInsert,
                        'tabla_practica_fk' => $tablaPractica,
                    ],
                ]);
            }

            $cols = ['ID_PRACTICA_PREPROFESIONAL', 'ID_ESTADO_REVISION', 'ID_TIPO_DOCUMENTO', 'NOMBRE_ARCHIVO', 'TIPO_ARCHIVO', 'FECHA_SUBIDA', 'OBSERVACIONES'];
            $vals = [$idPractica, $idEstadoRevision, $idTipoDocumento, $nombreArchivo, $mimeType, $fechaSubida, $observaciones];
            $tipos = 'iiissss';

            $opcionales = [
                'NOMBRE_ORIGINAL' => ['s', $nombreOriginal],
                'TAMANO_ARCHIVO' => ['i', $tamanoArchivo],
                'RUTA_ARCHIVO' => ['s', '/uploads/documentos-practicas/'],
                'VERSION' => ['i', 1],
                'ACTIVO' => ['i', 1],
            ];
            foreach ($opcionales as $col => $meta) {
                if ($this->columnaExisteEnTabla($mysqli, $tablaDocs, $col)) {
                    $cols[] = $col;
                    $tipos .= $meta[0];
                    $vals[] = $meta[1];
                }
            }

            $placeholders = implode(', ', array_fill(0, count($cols), '?'));
            $sql = 'INSERT INTO `' . str_replace('`', '``', $tablaDocs) . '` ('
                . implode(', ', $cols) . ') VALUES (' . $placeholders . ')';

            try {
                // En algunos hosts InnoDB la FK falla por desfase de nombre de tabla aunque el ID exista.
                // Ya verificamos práctica/tipo/estado: desactivar checks solo para este INSERT.
                $mysqli->query('SET FOREIGN_KEY_CHECKS=0');
                $nuevoId = $this->ejecutarInsertValoresNativo($mysqli, $sql, $tipos, $vals);
                $mysqli->query('SET FOREIGN_KEY_CHECKS=1');

                if ($nuevoId <= 0) {
                    $nuevoId = $this->buscarDocumentoPorNombre($mysqli, $tablaDocs, $nombreArchivo);
                }

                if ($nuevoId <= 0) {
                    @unlink($uploadPath . $nombreArchivo);

                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'El INSERT no creó el documento. Revisa el diagnóstico de tablas.',
                        'debug' => [
                            'id_practica' => $idPractica,
                            'id_tipo' => $idTipoDocumento,
                            'id_estado' => $idEstadoRevision,
                            'tabla_practica' => $tablaPractica,
                            'tabla_docs' => $tablaDocs,
                        ],
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Documento subido exitosamente. Será revisado por el coordinador.',
                    'data' => [
                        'id' => $nuevoId,
                        'nombre' => $nombreOriginal,
                        'fecha' => date('d/m/Y H:i'),
                        'id_practica' => $idPractica,
                    ],
                ]);
            } catch (\Throwable $insertEx) {
                @$mysqli->query('SET FOREIGN_KEY_CHECKS=1');
                @unlink($uploadPath . $nombreArchivo);
                $msgDb = $insertEx->getMessage();
                log_message('error', 'Subida docs PP falló: {ex} | est={est} prac={p} tipo={t} estado={e} tablas={tb}', [
                    'ex' => $msgDb,
                    'est' => $idEstudiante,
                    'p' => $idPractica,
                    't' => $idTipoDocumento,
                    'e' => $idEstadoRevision,
                    'tb' => $tablaPractica . '/' . $tablaDocs,
                ]);

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al subir el documento: ' . $this->mensajeAmigableFkDocumento(
                        $msgDb,
                        $idPractica,
                        $idTipoDocumento,
                        $idEstadoRevision
                    ),
                    'debug' => [
                        'id_practica' => $idPractica,
                        'id_tipo' => $idTipoDocumento,
                        'id_estado' => $idEstadoRevision,
                        'id_estudiante' => $idEstudiante,
                        'tabla_practica_fk' => $tablaPractica,
                        'mysql' => $msgDb,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage(),
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

    private function obtenerIdEstudiantePorUsuario(\mysqli $mysqli, int $idUsuario): int
    {
        $sql = 'SELECT e.ID_ESTUDIANTE
                FROM TAB_ESTUDIANTES e
                INNER JOIN TAB_USUARIOS u ON u.ID_DATO_PERSONA = e.ID_DATO_PERSONA
                WHERE u.ID_USUARIO = ?
                LIMIT 1';
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return (int) ($row['ID_ESTUDIANTE'] ?? $row['id_estudiante'] ?? 0);
    }

    private function nombreTablaReferenciadaFk(\mysqli $mysqli, string $constraint, string $fallback): string
    {
        $sql = 'SELECT REFERENCED_TABLE_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND CONSTRAINT_NAME = ?
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                LIMIT 1';
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $constraint);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $stmt->close();
            $name = (string) ($row['REFERENCED_TABLE_NAME'] ?? '');
            if ($name !== '') {
                return $name;
            }
        }

        return $this->nombreTablaReal($mysqli, $fallback);
    }

    private function nombreTablaReal(\mysqli $mysqli, string $preferida): string
    {
        $preferida = trim($preferida);
        $patterns = array_values(array_unique([
            $preferida,
            strtoupper($preferida),
            strtolower($preferida),
        ]));

        $encontradas = [];
        foreach ($patterns as $t) {
            $safe = $mysqli->real_escape_string($t);
            $r = $mysqli->query("SHOW TABLES LIKE '{$safe}'");
            if (!$r) {
                continue;
            }
            while ($row = $r->fetch_array()) {
                if (!empty($row[0])) {
                    $encontradas[(string) $row[0]] = (string) $row[0];
                }
            }
        }

        if ($encontradas === []) {
            return $preferida;
        }

        // 1) Coincidencia exacta
        if (isset($encontradas[$preferida])) {
            return $preferida;
        }
        // 2) En producción las tablas reales están en MAYÚSCULAS (TAB_...)
        $upper = strtoupper($preferida);
        if (isset($encontradas[$upper])) {
            return $upper;
        }
        foreach ($encontradas as $name) {
            if (strpos($name, 'TAB_') === 0) {
                return $name;
            }
        }
        // 3) Evitar el duplicado vacío en minúsculas si hay otra opción
        foreach ($encontradas as $name) {
            if (strpos($name, 'tab_') !== 0) {
                return $name;
            }
        }

        return (string) reset($encontradas);
    }

    /**
     * Si la práctica del estudiante está en una variante de nombre distinta a la de la FK, la copia.
     */
    private function sincronizarPracticaHaciaTablaFk(\mysqli $mysqli, string $tablaFk, int $idEstudiante): void
    {
        if ($this->obtenerIdPracticaEnTabla($mysqli, $tablaFk, $idEstudiante) > 0) {
            return;
        }

        $alternas = array_values(array_unique([
            'TAB_PRACTICAS_PREPROFESIONALES',
            'tab_practicas_preprofesionales',
            strtolower($tablaFk),
            strtoupper($tablaFk),
        ]));

        foreach ($alternas as $origen) {
            if (strcasecmp($origen, $tablaFk) === 0) {
                continue;
            }
            $safeOrigen = $mysqli->real_escape_string($origen);
            $r = $mysqli->query("SHOW TABLES LIKE '{$safeOrigen}'");
            if (!$r || $r->num_rows === 0) {
                continue;
            }
            $realOrigen = (string) ($r->fetch_array()[0] ?? '');
            if ($realOrigen === '' || strcasecmp($realOrigen, $tablaFk) === 0) {
                continue;
            }

            $idOrigen = $this->obtenerIdPracticaEnTabla($mysqli, $realOrigen, $idEstudiante);
            if ($idOrigen <= 0) {
                continue;
            }

            // Copiar columnas clave a la tabla que usa la FK.
            $tf = '`' . str_replace('`', '``', $tablaFk) . '`';
            $to = '`' . str_replace('`', '``', $realOrigen) . '`';
            $sql = "INSERT IGNORE INTO {$tf} (
                        ID_PRACTICA_PREPROFESIONAL, ID_PERIODO_ACADEMICO, ID_ASIGNACION_PRACTICA,
                        ID_ESTUDIANTE, ID_DOCENTE_TUTOR, ID_INSTITUCION_CONVENIO,
                        AREA_ESPECIALIZACION, PROYECTO_ESPECIFICO, HORAS_PRACTICAS,
                        FECHA_INICIO, FECHA_FIN, ESTADO_PRACTICA, ID_ESTADO_PREPROFESIONAL,
                        EVALUACION_FINAL, OBSERVACIONES
                    )
                    SELECT
                        ID_PRACTICA_PREPROFESIONAL, ID_PERIODO_ACADEMICO, ID_ASIGNACION_PRACTICA,
                        ID_ESTUDIANTE, ID_DOCENTE_TUTOR, ID_INSTITUCION_CONVENIO,
                        AREA_ESPECIALIZACION, PROYECTO_ESPECIFICO, HORAS_PRACTICAS,
                        FECHA_INICIO, FECHA_FIN, ESTADO_PRACTICA, ID_ESTADO_PREPROFESIONAL,
                        EVALUACION_FINAL, OBSERVACIONES
                    FROM {$to}
                    WHERE ID_ESTUDIANTE = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('i', $idEstudiante);
                @$stmt->execute();
                $stmt->close();
            }

            // Fallback: copiar todas las columnas si el esquema coincide.
            if ($this->obtenerIdPracticaEnTabla($mysqli, $tablaFk, $idEstudiante) <= 0) {
                $sqlAll = "INSERT IGNORE INTO {$tf} SELECT * FROM {$to} WHERE ID_ESTUDIANTE = ?";
                $stmtAll = $mysqli->prepare($sqlAll);
                if ($stmtAll) {
                    $stmtAll->bind_param('i', $idEstudiante);
                    @$stmtAll->execute();
                    $stmtAll->close();
                }
            }

            if ($this->obtenerIdPracticaEnTabla($mysqli, $tablaFk, $idEstudiante) > 0) {
                log_message('notice', 'Práctica sincronizada de {src} hacia {dst} para estudiante {est}', [
                    'src' => $realOrigen,
                    'dst' => $tablaFk,
                    'est' => $idEstudiante,
                ]);

                return;
            }
        }
    }

    private function obtenerIdPracticaEnTabla(\mysqli $mysqli, string $tabla, int $idEstudiante): int
    {
        $t = '`' . str_replace('`', '``', $tabla) . '`';
        $sql = "SELECT ID_PRACTICA_PREPROFESIONAL
                FROM {$t}
                WHERE ID_ESTUDIANTE = ?
                ORDER BY FECHA_INICIO DESC, ID_PRACTICA_PREPROFESIONAL DESC
                LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param('i', $idEstudiante);
        if (!$stmt->execute()) {
            $stmt->close();

            return 0;
        }
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return (int) ($row['ID_PRACTICA_PREPROFESIONAL'] ?? $row['id_practica_preprofesional'] ?? 0);
    }

    private function existeIdEnTabla(\mysqli $mysqli, string $tabla, string $columna, int $id): bool
    {
        $t = '`' . str_replace('`', '``', $tabla) . '`';
        $c = '`' . str_replace('`', '``', $columna) . '`';
        $sql = "SELECT 1 AS ok FROM {$t} WHERE {$c} = ? LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $ok = $res && $res->fetch_assoc();
        $stmt->close();

        return (bool) $ok;
    }

    private function obtenerIdEstadoPendienteMysqli(\mysqli $mysqli, string $tablaEstados): int
    {
        $t = '`' . str_replace('`', '``', $tablaEstados) . '`';
        $sql = "SELECT ID_ESTADO_REVISION FROM {$t}
                WHERE ESTADO = 'Pendiente' OR LOWER(ESTADO) = 'pendiente'
                ORDER BY ID_ESTADO_REVISION ASC LIMIT 1";
        $r = $mysqli->query($sql);
        if ($r && ($row = $r->fetch_assoc())) {
            return (int) ($row['ID_ESTADO_REVISION'] ?? 0);
        }

        $r = $mysqli->query("SELECT ID_ESTADO_REVISION FROM {$t} ORDER BY ID_ESTADO_REVISION ASC LIMIT 1");
        if ($r && ($row = $r->fetch_assoc())) {
            return (int) ($row['ID_ESTADO_REVISION'] ?? 0);
        }

        // Sembrar estados mínimos si la tabla está vacía.
        $estados = [
            ['Pendiente', 'Documento pendiente de revisión', '#ffc107', 1],
            ['En Revisión', 'Documento en revisión', '#17a2b8', 2],
            ['Aprobado', 'Documento aprobado', '#28a745', 3],
            ['Rechazado', 'Documento rechazado', '#dc3545', 4],
            ['Requiere Corrección', 'Documento requiere correcciones', '#fd7e14', 5],
        ];
        $ins = $mysqli->prepare(
            "INSERT INTO {$t} (ESTADO, DESCRIPCION, COLOR, ORDEN, ACTIVO) VALUES (?, ?, ?, ?, 1)"
        );
        if ($ins) {
            foreach ($estados as $e) {
                $ins->bind_param('sssi', $e[0], $e[1], $e[2], $e[3]);
                @$ins->execute();
            }
            $ins->close();
        }

        $r = $mysqli->query("SELECT ID_ESTADO_REVISION FROM {$t} WHERE ESTADO = 'Pendiente' LIMIT 1");
        if ($r && ($row = $r->fetch_assoc())) {
            return (int) ($row['ID_ESTADO_REVISION'] ?? 0);
        }

        return 0;
    }

    private function columnaExisteEnTabla(\mysqli $mysqli, string $tabla, string $columna): bool
    {
        $sql = 'SELECT 1 AS ok
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND LOWER(TABLE_NAME) = LOWER(?)
                  AND LOWER(COLUMN_NAME) = LOWER(?)
                LIMIT 1';
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $tabla, $columna);
        $stmt->execute();
        $res = $stmt->get_result();
        $ok = $res && $res->fetch_assoc();
        $stmt->close();

        return (bool) $ok;
    }

    /**
     * @param list<mixed> $valores
     */
    private function ejecutarInsertSelectNativo(\mysqli $mysqli, string $sql, string $tiposBind, array $valores): int
    {
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('No se pudo preparar el INSERT: ' . $mysqli->error);
        }

        if (!$stmt->bind_param($tiposBind, ...$valores)) {
            $err = $stmt->error;
            $stmt->close();
            throw new \Exception('No se pudo enlazar parámetros: ' . $err);
        }

        if (!$stmt->execute()) {
            $err = $stmt->error !== '' ? $stmt->error : $mysqli->error;
            $errno = (int) ($stmt->errno ?: $mysqli->errno);
            $stmt->close();
            throw new \Exception(($errno > 0 ? "[{$errno}] " : '') . $err);
        }

        $affected = (int) $stmt->affected_rows;
        $id = (int) $mysqli->insert_id;
        $stmt->close();

        if ($affected < 1) {
            return 0;
        }

        // Si el host no reporta insert_id, el caller confirma por NOMBRE_ARCHIVO.
        return $id > 0 ? $id : -1;
    }

    private function buscarDocumentoPorNombre(\mysqli $mysqli, string $tablaDocs, string $nombreArchivo): int
    {
        $t = '`' . str_replace('`', '``', $tablaDocs) . '`';
        $sql = "SELECT ID_DOCUMENTO_PREPROFESIONAL FROM {$t}
                WHERE NOMBRE_ARCHIVO = ?
                ORDER BY ID_DOCUMENTO_PREPROFESIONAL DESC LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param('s', $nombreArchivo);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        return (int) ($row['ID_DOCUMENTO_PREPROFESIONAL'] ?? $row['id_documento_preprofesional'] ?? 0);
    }

    /**
     * Garantiza que el ID de práctica exista en la tabla exacta referenciada por la FK.
     * Si solo está en una variante de nombre, la inserta en la tabla de la FK.
     */
    private function asegurarPracticaEnTablaFk(\mysqli $mysqli, string $tablaFk, int $idEstudiante, int $idPracticaHint): int
    {
        if ($idPracticaHint > 0 && $this->existeIdEnTabla($mysqli, $tablaFk, 'ID_PRACTICA_PREPROFESIONAL', $idPracticaHint)) {
            return $idPracticaHint;
        }

        $idEnFk = $this->obtenerIdPracticaEnTabla($mysqli, $tablaFk, $idEstudiante);
        if ($idEnFk > 0) {
            return $idEnFk;
        }

        // Buscar la práctica en cualquier variante de nombre y copiarla.
        $this->sincronizarPracticaHaciaTablaFk($mysqli, $tablaFk, $idEstudiante);
        $idEnFk = $this->obtenerIdPracticaEnTabla($mysqli, $tablaFk, $idEstudiante);
        if ($idEnFk > 0) {
            return $idEnFk;
        }

        // Último recurso: crear una fila mínima en la tabla FK si encontramos datos en otra tabla.
        $origen = $this->buscarFilaPracticaEnCualquierTabla($mysqli, $idEstudiante);
        if ($origen === null && $idPracticaHint > 0) {
            $origen = $this->buscarFilaPracticaPorIdEnCualquierTabla($mysqli, $idPracticaHint);
        }
        if ($origen === null) {
            return 0;
        }

        $tf = '`' . str_replace('`', '``', $tablaFk) . '`';
        $idNuevo = (int) ($origen['ID_PRACTICA_PREPROFESIONAL'] ?? 0);
        $idEst = (int) ($origen['ID_ESTUDIANTE'] ?? $idEstudiante);
        $idAsig = (int) ($origen['ID_ASIGNACION_PRACTICA'] ?? 0);
        $idTutor = (int) ($origen['ID_DOCENTE_TUTOR'] ?? 0);
        $idInst = (int) ($origen['ID_INSTITUCION_CONVENIO'] ?? 0);
        $horas = (int) ($origen['HORAS_PRACTICAS'] ?? 240);
        $fi = (string) ($origen['FECHA_INICIO'] ?? date('Y-m-d'));
        $ff = $origen['FECHA_FIN'] ?? null;
        $estado = (string) ($origen['ESTADO_PRACTICA'] ?? 'En Progreso');

        // Insertar con el mismo ID si es posible.
        if ($idNuevo > 0) {
            $sql = "INSERT IGNORE INTO {$tf}
                    (ID_PRACTICA_PREPROFESIONAL, ID_ASIGNACION_PRACTICA, ID_ESTUDIANTE, ID_DOCENTE_TUTOR,
                     ID_INSTITUCION_CONVENIO, HORAS_PRACTICAS, FECHA_INICIO, FECHA_FIN, ESTADO_PRACTICA)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $ffVal = $ff !== null && $ff !== '' ? (string) $ff : null;
                $stmt->bind_param('iiiiiisss', $idNuevo, $idAsig, $idEst, $idTutor, $idInst, $horas, $fi, $ffVal, $estado);
                @$stmt->execute();
                $stmt->close();
            }
        }

        if ($this->existeIdEnTabla($mysqli, $tablaFk, 'ID_PRACTICA_PREPROFESIONAL', $idNuevo)) {
            return $idNuevo;
        }

        // Sin forzar ID (autoincrement).
        $sql2 = "INSERT INTO {$tf}
                (ID_ASIGNACION_PRACTICA, ID_ESTUDIANTE, ID_DOCENTE_TUTOR, ID_INSTITUCION_CONVENIO,
                 HORAS_PRACTICAS, FECHA_INICIO, FECHA_FIN, ESTADO_PRACTICA)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $mysqli->prepare($sql2);
        if ($stmt2) {
            $ffVal = $ff !== null && $ff !== '' ? (string) $ff : null;
            $stmt2->bind_param('iiiiisss', $idAsig, $idEst, $idTutor, $idInst, $horas, $fi, $ffVal, $estado);
            if (@$stmt2->execute()) {
                $newId = (int) $mysqli->insert_id;
                $stmt2->close();

                return $newId > 0 ? $newId : 0;
            }
            $stmt2->close();
        }

        return 0;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buscarFilaPracticaEnCualquierTabla(\mysqli $mysqli, int $idEstudiante): ?array
    {
        foreach (['TAB_PRACTICAS_PREPROFESIONALES', 'tab_practicas_preprofesionales'] as $t) {
            $real = $this->nombreTablaReal($mysqli, $t);
            $id = $this->obtenerIdPracticaEnTabla($mysqli, $real, $idEstudiante);
            if ($id <= 0) {
                continue;
            }
            $tt = '`' . str_replace('`', '``', $real) . '`';
            $stmt = $mysqli->prepare("SELECT * FROM {$tt} WHERE ID_PRACTICA_PREPROFESIONAL = ? LIMIT 1");
            if (!$stmt) {
                continue;
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $stmt->close();
            if (is_array($row)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buscarFilaPracticaPorIdEnCualquierTabla(\mysqli $mysqli, int $idPractica): ?array
    {
        foreach (['TAB_PRACTICAS_PREPROFESIONALES', 'tab_practicas_preprofesionales'] as $t) {
            $real = $this->nombreTablaReal($mysqli, $t);
            if (!$this->existeIdEnTabla($mysqli, $real, 'ID_PRACTICA_PREPROFESIONAL', $idPractica)) {
                continue;
            }
            $tt = '`' . str_replace('`', '``', $real) . '`';
            $stmt = $mysqli->prepare("SELECT * FROM {$tt} WHERE ID_PRACTICA_PREPROFESIONAL = ? LIMIT 1");
            if (!$stmt) {
                continue;
            }
            $stmt->bind_param('i', $idPractica);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $stmt->close();
            if (is_array($row)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param list<mixed> $valores
     */
    private function ejecutarInsertValoresNativo(\mysqli $mysqli, string $sql, string $tiposBind, array $valores): int
    {
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            throw new \Exception('No se pudo preparar el INSERT: ' . $mysqli->error);
        }
        if (!$stmt->bind_param($tiposBind, ...$valores)) {
            $err = $stmt->error;
            $stmt->close();
            throw new \Exception('No se pudo enlazar parámetros: ' . $err);
        }
        if (!$stmt->execute()) {
            $err = $stmt->error !== '' ? $stmt->error : $mysqli->error;
            $errno = (int) ($stmt->errno ?: $mysqli->errno);
            $stmt->close();
            throw new \Exception(($errno > 0 ? "[{$errno}] " : '') . $err);
        }
        $id = (int) $mysqli->insert_id;
        $affected = (int) $stmt->affected_rows;
        $stmt->close();

        if ($affected < 1 && $id <= 0) {
            return 0;
        }

        return $id > 0 ? $id : -1;
    }

    /**
     * Diagnóstico de tablas/FK/prácticas del estudiante autenticado (JSON).
     */
    public function diagnostico()
    {
        $idUsuario = (int) session()->get('id_usuario');
        $db = \Config\Database::connect();
        $mysqli = $db->connID ?? null;
        if (!($mysqli instanceof \mysqli)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Sin mysqli']);
        }

        $out = [
            'ok' => true,
            'id_usuario' => $idUsuario,
            'database' => null,
            'lower_case_table_names' => null,
            'id_estudiante' => 0,
            'tablas_practicas' => [],
            'fk_docs' => [],
            'practicas_por_tabla' => [],
            'existe_id_1' => [],
        ];

        $r = $mysqli->query('SELECT DATABASE() AS db');
        $out['database'] = $r ? ($r->fetch_assoc()['db'] ?? null) : null;

        $r = $mysqli->query("SHOW VARIABLES LIKE 'lower_case_table_names'");
        $out['lower_case_table_names'] = $r ? ($r->fetch_assoc()['Value'] ?? null) : null;

        $out['id_estudiante'] = $this->obtenerIdEstudiantePorUsuario($mysqli, $idUsuario);

        $r = $mysqli->query("SHOW TABLES LIKE '%practicas_preprofesionales%'");
        if ($r) {
            while ($row = $r->fetch_array()) {
                $out['tablas_practicas'][] = $row[0];
            }
        }
        $r = $mysqli->query("SHOW TABLES LIKE '%PRACTICAS_PREPROFESIONALES%'");
        if ($r) {
            while ($row = $r->fetch_array()) {
                $out['tablas_practicas'][] = $row[0];
            }
        }
        $out['tablas_practicas'] = array_values(array_unique($out['tablas_practicas']));

        $r = $mysqli->query(
            "SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND CONSTRAINT_NAME LIKE 'FK_DOCS_PREPROFESIONALES%'
               AND REFERENCED_TABLE_NAME IS NOT NULL"
        );
        if ($r) {
            while ($row = $r->fetch_assoc()) {
                $out['fk_docs'][] = $row;
            }
        }

        foreach ($out['tablas_practicas'] as $t) {
            $tt = '`' . str_replace('`', '``', $t) . '`';
            $practicas = [];
            $stmt = $mysqli->prepare("SELECT ID_PRACTICA_PREPROFESIONAL, ID_ESTUDIANTE, ID_ASIGNACION_PRACTICA FROM {$tt} WHERE ID_ESTUDIANTE = ? OR ID_ESTUDIANTE = ?");
            if ($stmt) {
                $idEst = (int) $out['id_estudiante'];
                $stmt->bind_param('ii', $idEst, $idUsuario);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $practicas[] = $row;
                    }
                }
                $stmt->close();
            }
            $out['practicas_por_tabla'][$t] = $practicas;

            $stmt2 = $mysqli->prepare("SELECT COUNT(*) AS c FROM {$tt} WHERE ID_PRACTICA_PREPROFESIONAL = 1");
            if ($stmt2) {
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $row2 = $res2 ? $res2->fetch_assoc() : null;
                $out['existe_id_1'][$t] = (int) ($row2['c'] ?? 0);
                $stmt2->close();
            }
        }

        return $this->response->setJSON($out);
    }

    /**
     * Traduce el error MySQL de FK a un mensaje concreto (práctica / tipo / estado / revisor).
     */
    private function mensajeAmigableFkDocumento(string $msgDb, int $idPractica, int $idTipo, int $idEstado): string
    {
        $lower = strtolower($msgDb);
        if (strpos($lower, 'foreign key') === false && strpos($lower, 'constraint') === false) {
            return $msgDb !== '' ? $msgDb : 'Error al guardar en la base de datos';
        }

        if (strpos($lower, 'estado') !== false || strpos($lower, 'revisiones') !== false) {
            return 'Falla la FK de estado de revisión (ID ' . $idEstado . '). Revisa TAB_ESTADOS_REVISIONES.';
        }
        if (strpos($lower, 'tipo') !== false) {
            return 'Falla la FK de tipo de documento (ID ' . $idTipo . '). Revisa TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.';
        }
        if (strpos($lower, 'revisor') !== false) {
            return 'Falla la FK de revisor. No envíes ID_REVISOR al subir.';
        }
        if (strpos($lower, 'practica') !== false || strpos($lower, 'preprofesional') !== false) {
            return 'Falla la FK de práctica (ID detectado ' . $idPractica . '). '
                . 'La práctica del estudiante no está en la tabla que referencia la FK.';
        }

        return 'Restricción de integridad (FK). Detalle: ' . $msgDb;
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
     * Resuelve la práctica preprofesional del estudiante autenticado.
     * Acepta el ID de práctica o, por compatibilidad, el ID_ASIGNACION_PRACTICA.
     *
     * @return array{id_practica: int, id_estudiante: int}
     */
    private function obtenerContextoPracticaEstudiante(int $idUsuario, int $idSolicitado = 0): array
    {
        $vacio = ['id_practica' => 0, 'id_estudiante' => 0];
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
            return $vacio;
        }

        $practicas = $db->query(
            'SELECT ID_PRACTICA_PREPROFESIONAL, ID_ASIGNACION_PRACTICA
             FROM TAB_PRACTICAS_PREPROFESIONALES
             WHERE ID_ESTUDIANTE = ?
             ORDER BY FECHA_INICIO DESC, ID_PRACTICA_PREPROFESIONAL DESC',
            [$idEst]
        )->getResultArray();

        if ($practicas === []) {
            return ['id_practica' => 0, 'id_estudiante' => $idEst];
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
            return ['id_practica' => 0, 'id_estudiante' => $idEst];
        }

        $idPractica = 0;
        if ($idSolicitado > 0) {
            if (isset($porPractica[$idSolicitado])) {
                $idPractica = $idSolicitado;
            } elseif (isset($porAsignacion[$idSolicitado])) {
                $idPractica = $porAsignacion[$idSolicitado];
            }
        } else {
            $idPractica = (int) reset($porPractica);
        }

        return [
            'id_practica' => $idPractica,
            'id_estudiante' => $idEst,
        ];
    }

    /**
     * Resuelve un ID_PRACTICA_PREPROFESIONAL real del estudiante autenticado.
     * Acepta el ID de práctica o, por compatibilidad, el ID_ASIGNACION_PRACTICA.
     */
    private function resolverIdPracticaEstudiante(int $idUsuario, int $idSolicitado = 0): int
    {
        return (int) ($this->obtenerContextoPracticaEstudiante($idUsuario, $idSolicitado)['id_practica'] ?? 0);
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
