<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosServicioComunitarioModel;
use App\Services\EstudianteAsistenciaService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DocumentosServicioComunitarioEstudianteController extends BaseController
{
    protected DocumentosServicioComunitarioModel $documentosModel;
    protected TiposDocumentosServicioComunitarioModel $tiposModel;
    protected EstadosRevisionesModel $estadosModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        if (!session()->get('logged_in') || (int) session()->get('rol') !== 4) {
            redirect()->to('/')->send();
            exit;
        }

        $this->documentosModel = new DocumentosServicioComunitarioModel();
        $this->tiposModel = new TiposDocumentosServicioComunitarioModel();
        $this->estadosModel = new EstadosRevisionesModel();
    }

    public function index()
    {
        $idUsuario = (int) session()->get('id_usuario');
        $tipos = $this->tiposModel->getAllTipos();
        $servicios = $this->obtenerServiciosDelEstudiante($idUsuario);
        $progreso = $this->documentosModel->getProgresoEstudianteServicio($idUsuario);

        $pendAsist = EstudianteAsistenciaService::pendientesAsistenciaHoy($idUsuario);
        $itemsSc = array_values(array_filter(
            $pendAsist['items'],
            static fn (array $i): bool => ($i['tipo'] ?? '') === 'servicio'
        ));
        $itemsScActivas = EstudianteAsistenciaService::itemsServiciosComunitariosEnProgreso($idUsuario);
        $tieneScActiva = EstudianteAsistenciaService::tieneServicioComunitarioEnProgreso($idUsuario);
        $asistenciaHorasSc = EstudianteAsistenciaService::horasServicioComunitarioEnProgreso($idUsuario);
        $serviciosDocumentacion = $this->obtenerServiciosDocumentacionEstudiante($idUsuario);

        $data = [
            'title' => 'Documentos de Servicio Comunitario',
            'tipos_documentos' => $tipos,
            'estados_revision' => $this->estadosModel->getAllEstados(),
            'progreso' => $progreso,
            'servicios_comunitarios' => $servicios,
            'id_servicio_default' => !empty($servicios) ? (int) $servicios[0]['ID_SERVICIO_COMUNITARIO'] : 0,
            'estadisticas' => $this->calcularEstadisticas($progreso, count($tipos)),
            'total_tipos_documentos' => count($tipos),
            'asistencia_items' => $itemsSc,
            'asistencia_items_activa' => $itemsScActivas,
            'asistencia_fecha' => $pendAsist['fecha'],
            'asistencia_tiene_activa' => $tieneScActiva,
            'asistencia_mostrar_tarjeta' => $tieneScActiva,
            'asistencia_modal_automatico' => false,
            'asistencia_titulo_tarjeta' => 'Asistencia — servicio comunitario',
            'asistencia_horas_sc' => $asistenciaHorasSc,
            'servicios_documentacion' => $serviciosDocumentacion,
        ];

        return view('estudiante/documentos/documentos_servicio_comunitario', $data);
    }

    /**
     * Servicios del estudiante con institución convenio e instructor (pantalla documentación PSC).
     *
     * @return list<array<string, mixed>>
     */
    private function obtenerServiciosDocumentacionEstudiante(int $idUsuario): array
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

            return $db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.ID_SERVICIO_COMUNITARIO, sc.PROYECTO_SOCIAL, ic.NOMBRE as INSTITUCION_NOMBRE, CONCAT(COALESCE(dpdt.NOMBRE,\'\'), \' \', COALESCE(dpdt.APELLIDO,\'\')) as SUPERVISOR_NOMBRE', false)
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO', 'left')
                ->join('TAB_DOCENTES_TUTORES dt', 'dt.ID_DOCENTE_TUTOR = sc.ID_DOCENTE_TUTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dpdt', 'dpdt.ID_DATO_PERSONA = dt.ID_DATO_PERSONA', 'left')
                ->where('sc.ID_ESTUDIANTE', $idEst)
                ->orderBy('sc.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'obtenerServiciosDocumentacionEstudiante: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function obtenerServiciosDelEstudiante(int $idUsuario): array
    {
        try {
            return \Config\Database::connect()->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.ID_SERVICIO_COMUNITARIO, sc.PROYECTO_SOCIAL, sc.FECHA_INICIO')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('u.ID_USUARIO', $idUsuario)
                ->orderBy('sc.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'obtenerServiciosDelEstudiante: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * @param list<array<string, mixed>> $progreso
     */
    private function calcularEstadisticas(array $progreso, int $totalTipos): array
    {
        $aprobados = 0;
        $pendientes = 0;
        $rechazados = 0;
        $en_revision = 0;
        $requiere = 0;
        $subidos = 0;

        foreach ($progreso as $row) {
            if (empty($row['ID_DOCUMENTO_SERVICIO'])) {
                continue;
            }
            $subidos++;
            $est = $row['ESTADO_REVISION'] ?? '';
            switch ($est) {
                case 'Aprobado':
                    $aprobados++;
                    break;
                case 'Rechazado':
                    $rechazados++;
                    break;
                case 'En Revisión':
                    $en_revision++;
                    break;
                case 'Requiere Corrección':
                    $requiere++;
                    break;
                case 'Pendiente':
                    $pendientes++;
                    break;
                default:
                    $pendientes++;
                    break;
            }
        }

        $den = $totalTipos > 0 ? $totalTipos : 1;

        return [
            'total' => $subidos,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados,
            'en_revision' => $en_revision,
            'requiere_correccion' => $requiere,
            'porcentaje_completado' => round(($aprobados / $den) * 100, 1),
        ];
    }
}
