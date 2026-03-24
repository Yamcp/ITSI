<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosServicioComunitarioModel;

class DocumentosServicioComunitarioEstudianteController extends BaseController
{
    protected DocumentosServicioComunitarioModel $documentosModel;
    protected TiposDocumentosServicioComunitarioModel $tiposModel;
    protected EstadosRevisionesModel $estadosModel;

    public function __construct()
    {
        if (!session()->get('logged_in') || (int) session()->get('rol') !== 3) {
            return redirect()->to('/');
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

        $data = [
            'title' => 'Documentos de Servicio Comunitario',
            'tipos_documentos' => $tipos,
            'estados_revision' => $this->estadosModel->getAllEstados(),
            'progreso' => $progreso,
            'servicios_comunitarios' => $servicios,
            'id_servicio_default' => !empty($servicios) ? (int) $servicios[0]['ID_SERVICIO_COMUNITARIO'] : 0,
            'estadisticas' => $this->calcularEstadisticas($progreso, count($tipos)),
            'total_tipos_documentos' => count($tipos),
        ];

        return view('estudiante/documentos/documentos_servicio_comunitario', $data);
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
