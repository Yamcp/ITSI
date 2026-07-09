<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\PracticasPreprofesionalesModel;
use App\Models\ServiciosComunitariosModel;
use App\Models\ActividadesPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstudiantesModel;
use App\Models\DocentesTutoresModel;
use App\Models\NotificacionesModel;

class PracticasDocenteController extends BaseController
{
    protected $practicasPreprofesionalesModel;
    protected $serviciosComunitariosModel;
    protected $actividadesPracticasModel;
    protected $usuariosModel;
    protected $estudiantesModel;
    protected $docentesTutoresModel;
    protected $db;

    public function __construct()
    {
        $this->practicasPreprofesionalesModel = new PracticasPreprofesionalesModel();
        $this->serviciosComunitariosModel = new ServiciosComunitariosModel();
        $this->actividadesPracticasModel = new ActividadesPracticasModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->docentesTutoresModel = new DocentesTutoresModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        return $this->renderDocenteModo('preprofesional');
    }

    public function servicioComunitario()
    {
        return $this->renderDocenteModo('servicio');
    }

    private function renderDocenteModo(string $modo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $idUsuario = session()->get('id_usuario');
        $idDocente = $this->obtenerIdDocentePorUsuario($idUsuario);
        if ($idDocente === null) {
            $idDocente = 0;
        }

        $estadisticas = $this->obtenerEstadisticasDocente($idDocente);
        $estudiantesAsignados = $this->obtenerEstudiantesAsignadosPorModalidad($idDocente, $modo);

        if ($modo === 'preprofesional') {
            $estadisticas['estudiantesAsignados'] = $estadisticas['estudiantesPp'] ?? 0;
            $estadisticas['practicasActivas'] = $estadisticas['practicasActivasPre'] ?? 0;
        } else {
            $estadisticas['estudiantesAsignados'] = $estadisticas['estudiantesSc'] ?? 0;
            $estadisticas['practicasActivas'] = $estadisticas['serviciosActivos'] ?? 0;
        }

        $notificacionesLista = [];
        $estadisticasNotificaciones = ['total' => 0, 'no_leidas' => 0, 'leidas' => 0];
        try {
            $notifModel = new NotificacionesModel();
            $notificacionesLista = $notifModel->obtenerNotificacionesUsuario((int) $idUsuario, 50);
            $estadisticasNotificaciones = $notifModel->obtenerEstadisticas((int) $idUsuario);
        } catch (\Throwable $e) {
            log_message('error', 'PracticasDocente - notificaciones: ' . $e->getMessage());
        }

        $modoLabel = $modo === 'servicio' ? 'Servicio Comunitario' : 'Prácticas Preprofesionales';

        $data = [
            'title' => 'Supervisión de ' . $modoLabel . ' - ITSI',
            'modo' => $modo,
            'modoLabel' => $modoLabel,
            'urlPracticas' => base_url('docente/practicas'),
            'urlServicio' => base_url('docente/servicio-comunitario'),
            'estadisticas' => $estadisticas,
            'estudiantesAsignados' => $estudiantesAsignados,
            'notificaciones_lista' => $notificacionesLista,
            'estadisticas_notificaciones' => $estadisticasNotificaciones,
        ];

        return view('docente/practicas/practicas_docente', $data);
    }

    public function detalleEstudiante($estudianteId)
    {
        $this->response->setHeader('Content-Type', 'application/json');

        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $estudianteId = (int) $estudianteId;
        if ($estudianteId < 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Estudiante no válido']);
        }

        $idDocente = $this->obtenerIdDocentePorUsuario(session()->get('id_usuario'));
        if ($idDocente === null) {
            $idDocente = 0;
        }
        try {
            $estudiante = $this->obtenerEstudianteAsignado($estudianteId, $idDocente);
            if (!$estudiante) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estudiante no encontrado o no está bajo su tutoría',
                ]);
            }
            // Registro de asistencias que el estudiante ha ido registrando (para control del docente/tutor)
            $actividadesRecientes = [];
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.*, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO', 'left')
                ->where('pp.ID_ESTUDIANTE', $estudianteId)->where('pp.ID_DOCENTE_TUTOR', $idDocente)
                ->get()->getRowArray();
            if ($pp) {
                foreach ($this->obtenerActividadesRecientesPractica($pp['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional', 50) as $ap) {
                    $ap['TIPO_REGISTRO_ETIQUETA'] = 'Práctica preprofesional';
                    $actividadesRecientes[] = $ap;
                }
            }
            $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.*, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO', 'left')
                ->where('sc.ID_ESTUDIANTE', $estudianteId)->where('sc.ID_DOCENTE_TUTOR', $idDocente)
                ->get()->getRowArray();
            if ($sc) {
                foreach ($this->obtenerActividadesRecientesPractica($sc['ID_SERVICIO_COMUNITARIO'], 'servicio', 50) as $as) {
                    $as['TIPO_REGISTRO_ETIQUETA'] = 'Servicio comunitario';
                    $actividadesRecientes[] = $as;
                }
            }
            usort($actividadesRecientes, function ($a, $b) {
                $f1 = $a['FECHA_ASISTENCIA'] ?? '';
                $f2 = $b['FECHA_ASISTENCIA'] ?? '';
                if ($f1 !== $f2) return strcmp($f2, $f1);
                $r1 = $a['FECHA_REGISTRO'] ?? '';
                $r2 = $b['FECHA_REGISTRO'] ?? '';
                return strcmp($r2, $r1);
            });
            $actividadesRecientes = array_slice($actividadesRecientes, 0, 50);

            $progresos = [];
            if ($pp) {
                $bloque = $this->construirBloqueProgresoTutor($pp, 'preprofesional');
                if ($bloque) {
                    $progresos[] = $bloque;
                }
                $estudiante['INSTITUCION_NOMBRE'] = $pp['INSTITUCION_NOMBRE'] ?? null;
                $estudiante['FECHA_INICIO'] = $pp['FECHA_INICIO'] ?? null;
                $estudiante['FECHA_FIN'] = $pp['FECHA_FIN'] ?? null;
                $estudiante['ESTADO_PRACTICA'] = $pp['ESTADO_PRACTICA'] ?? null;
            }
            if ($sc) {
                $bloqueSc = $this->construirBloqueProgresoTutor($sc, 'servicio');
                if ($bloqueSc) {
                    $progresos[] = $bloqueSc;
                }
                if (empty($estudiante['INSTITUCION_NOMBRE'])) {
                    $estudiante['INSTITUCION_NOMBRE'] = $sc['INSTITUCION_NOMBRE'] ?? null;
                    $estudiante['FECHA_INICIO'] = $sc['FECHA_INICIO'] ?? null;
                    $estudiante['FECHA_FIN'] = $sc['FECHA_FIN'] ?? null;
                    $estudiante['ESTADO_SERVICIO'] = $sc['ESTADO_SERVICIO'] ?? null;
                }
            }

            $progreso = $progresos[0]['porcentaje'] ?? 0;
            if (isset($progresos[1])) {
                $estudiante['TIENE_AMBAS_MODALIDADES'] = true;
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'estudiante' => $estudiante,
                    'actividades' => $actividadesRecientes,
                    'progreso' => $progreso,
                    'progresos' => $progresos,
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en detalle de estudiante: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function generarReporte()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $docenteId = session()->get('id_usuario');

        $rules = [
            'tipo_reporte' => 'required',
            'fecha_desde' => 'required|valid_date',
            'fecha_hasta' => 'required|valid_date',
            'formato' => 'required|in_list[pdf,excel,word]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        try {
            $tipoReporte = $this->request->getPost('tipo_reporte');
            $fechaDesde = $this->request->getPost('fecha_desde');
            $fechaHasta = $this->request->getPost('fecha_hasta');
            $formato = $this->request->getPost('formato');

            $idDocente = $this->obtenerIdDocentePorUsuario($docenteId);
            if ($idDocente === null) {
                $idDocente = 0;
            }

            $datosReporte = $this->generarDatosReporte($tipoReporte, $fechaDesde, $fechaHasta, $idDocente);

            $csv = null;
            if ($formato === 'excel') {
                $csv = $this->generarCsvReporte($datosReporte);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'data' => $datosReporte,
                'formato' => $formato,
                'csv' => $csv,
                'nombre_archivo' => 'reporte_practicas_' . date('Y-m-d_His') . ($formato === 'excel' ? '.csv' : ''),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al generar reporte: ' . $e->getMessage());

            return $this->response->setJSON(['success' => false, 'message' => 'Error al generar el reporte']);
        }
    }

    /**
     * Devuelve eventos para el calendario (asistencias de prácticas y servicio comunitario).
     */
    public function calendario()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([]);
        }
        $idDocente = $this->obtenerIdDocentePorUsuario(session()->get('id_usuario'));
        if ($idDocente === null || $idDocente <= 0) {
            return $this->response->setJSON([]);
        }
        $eventos = [];
        try {
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.ID_PRACTICA_PREPROFESIONAL, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('pp.ID_DOCENTE_TUTOR', $idDocente)
                ->get()
                ->getResultArray();
            $idsPp = array_column($pp, 'ID_PRACTICA_PREPROFESIONAL');
            $nombresPp = [];
            foreach ($pp as $r) {
                $nombresPp[$r['ID_PRACTICA_PREPROFESIONAL']] = $r['ESTUDIANTE'];
            }
            if (!empty($idsPp)) {
                $asistPp = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->whereIn('ID_PRACTICA_PREPROFESIONAL', $idsPp)
                    ->get()
                    ->getResultArray();
                foreach ($asistPp as $a) {
                    $titulo = 'Práctica: ' . ($nombresPp[$a['ID_PRACTICA_PREPROFESIONAL']] ?? '');
                    $act = $a['ACTIVIDADES_DIA'] ?? '';
                    if (strlen($act) > 50) {
                        $act = substr($act, 0, 47) . '...';
                    }
                    if ($act) {
                        $titulo .= ' - ' . $act;
                    }
                    $eventos[] = [
                        'title' => $titulo,
                        'start' => ($a['FECHA_ASISTENCIA'] ?? '') . 'T' . ($a['HORA_ENTRADA'] ?? '08:00:00'),
                        'end'   => ($a['FECHA_ASISTENCIA'] ?? '') . 'T' . ($a['HORA_SALIDA'] ?? '17:00:00'),
                        'color' => '#0d6efd'
                    ];
                }
            }
            $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.ID_SERVICIO_COMUNITARIO, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('sc.ID_DOCENTE_TUTOR', $idDocente)
                ->get()
                ->getResultArray();
            $idsSc = array_column($sc, 'ID_SERVICIO_COMUNITARIO');
            $nombresSc = [];
            foreach ($sc as $r) {
                $nombresSc[$r['ID_SERVICIO_COMUNITARIO']] = $r['ESTUDIANTE'];
            }
            if (!empty($idsSc)) {
                $asistSc = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->whereIn('ID_SERVICIO_COMUNITARIO', $idsSc)
                    ->get()
                    ->getResultArray();
                foreach ($asistSc as $a) {
                    $titulo = 'Servicio: ' . ($nombresSc[$a['ID_SERVICIO_COMUNITARIO']] ?? '');
                    $act = $a['ACTIVIDADES_DIA'] ?? '';
                    if (strlen($act) > 50) {
                        $act = substr($act, 0, 47) . '...';
                    }
                    if ($act) {
                        $titulo .= ' - ' . $act;
                    }
                    $eventos[] = [
                        'title' => $titulo,
                        'start' => ($a['FECHA_ASISTENCIA'] ?? '') . 'T' . ($a['HORA_ENTRADA'] ?? '08:00:00'),
                        'end'   => ($a['FECHA_ASISTENCIA'] ?? '') . 'T' . ($a['HORA_SALIDA'] ?? '16:00:00'),
                        'color' => '#198754'
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error calendario prácticas: ' . $e->getMessage());
        }
        return $this->response->setJSON($eventos);
    }

    public function obtenerAlertas()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $idDocente = $this->obtenerIdDocentePorUsuario(session()->get('id_usuario'));
        if ($idDocente === null) {
            $idDocente = 0;
        }
        try {
            $alertas = $this->generarAlertas($idDocente);

            return $this->response->setJSON([
                'success' => true,
                'data' => $alertas
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener alertas: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al obtener alertas']);
        }
    }

    /**
     * Obtiene ID_DOCENTE_TUTOR del usuario logueado (docente) vía TAB_DOCENTES_TUTORES.
     */
    private function obtenerIdDocentePorUsuario($idUsuario)
    {
        $docente = $this->docentesTutoresModel->getDocentePorUsuario($idUsuario);
        return $docente ? (int) $docente['ID_DOCENTE_TUTOR'] : null;
    }

    private function obtenerEstadisticasDocente($idDocente)
    {
        try {
            $estudiantesPp = 0;
            $estudiantesSc = 0;
            $practicasActivasPre = 0;
            $serviciosActivos = 0;
            if ($idDocente > 0) {
                $estudiantesPp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                    ->where('pp.ID_DOCENTE_TUTOR', $idDocente)
                    ->countAllResults();
                $estudiantesSc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_DOCENTE_TUTOR', $idDocente)
                    ->countAllResults();
                $practicasActivasPre = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                    ->where('pp.ID_DOCENTE_TUTOR', $idDocente)
                    ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                    ->countAllResults();
                $serviciosActivos = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_DOCENTE_TUTOR', $idDocente)
                    ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                    ->countAllResults();
            }
            return [
                'estudiantesAsignados' => $estudiantesPp + $estudiantesSc,
                'estudiantesPp' => $estudiantesPp,
                'estudiantesSc' => $estudiantesSc,
                'practicasActivas' => $practicasActivasPre + $serviciosActivos,
                'practicasActivasPre' => $practicasActivasPre,
                'serviciosActivos' => $serviciosActivos,
                'alertas' => $idDocente > 0 ? $this->contarAlertas($idDocente) : 0
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas del docente: ' . $e->getMessage());
            return [
                'estudiantesAsignados' => 0,
                'estudiantesPp' => 0,
                'estudiantesSc' => 0,
                'practicasActivas' => 0,
                'practicasActivasPre' => 0,
                'serviciosActivos' => 0,
                'alertas' => 0
            ];
        }
    }

    private function obtenerEstudiantesAsignadosPorModalidad($idDocente, $modo)
    {
        if ($idDocente <= 0) {
            return [];
        }
        try {
            if ($modo === 'servicio') {
                $serviciosSc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->select('sc.*, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                    ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                    ->where('sc.ID_DOCENTE_TUTOR', $idDocente)
                    ->get()
                    ->getResultArray();

                $estudiantes = [];
                foreach ($serviciosSc as $s) {
                    $horasCum = $this->calcularHorasCumplidasPractica($s['ID_SERVICIO_COMUNITARIO'], 'servicio');
                    $horasTot = (float) ($s['HORAS_SERVICIO'] ?? 0);
                    $cumpl = $this->evaluarRitmoCumplimiento(
                        $s['FECHA_INICIO'] ?? null,
                        $s['FECHA_FIN'] ?? null,
                        $horasTot,
                        $horasCum,
                        $s['ESTADO_SERVICIO'] ?? null
                    );
                    $estudiantes[] = [
                        'ID_ESTUDIANTE' => $s['ID_ESTUDIANTE'],
                        'NOMBRE_COMPLETO' => $s['ESTUDIANTE_NOMBRE'],
                        'CARRERA' => $s['CARRERA'],
                        'INSTITUCION_NOMBRE' => $s['INSTITUCION_NOMBRE'],
                        'FECHA_INICIO' => $s['FECHA_INICIO'],
                        'FECHA_FIN' => $s['FECHA_FIN'],
                        'HORAS_TOTALES' => $horasTot,
                        'ESTADO_PRACTICA' => $s['ESTADO_SERVICIO'],
                        'TIPO' => 'Servicio Comunitario',
                        'HORAS_CUMPLIDAS' => $horasCum,
                        'PORCENTAJE_PROGRESO' => $this->calcularProgresoPractica($s['ID_SERVICIO_COMUNITARIO'], $horasTot, 'servicio'),
                        'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividadPractica($s['ID_SERVICIO_COMUNITARIO'], 'servicio'),
                        'CUMPLIMIENTO_ETIQUETA' => $cumpl['etiqueta'],
                        'CUMPLIMIENTO_NIVEL' => $cumpl['nivel'],
                        'CUMPLIMIENTO_DESCRIPCION' => $cumpl['descripcion'],
                    ];
                }
                return $estudiantes;
            }

            $practicasPp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.*, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->where('pp.ID_DOCENTE_TUTOR', $idDocente)
                ->get()
                ->getResultArray();

            $estudiantes = [];
            foreach ($practicasPp as $p) {
                $horasCum = $this->calcularHorasCumplidasPractica($p['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional');
                $horasTot = (float) ($p['HORAS_PRACTICAS'] ?? 0);
                $cumpl = $this->evaluarRitmoCumplimiento(
                    $p['FECHA_INICIO'] ?? null,
                    $p['FECHA_FIN'] ?? null,
                    $horasTot,
                    $horasCum,
                    $p['ESTADO_PRACTICA'] ?? null
                );
                $estudiantes[] = [
                    'ID_ESTUDIANTE' => $p['ID_ESTUDIANTE'],
                    'NOMBRE_COMPLETO' => $p['ESTUDIANTE_NOMBRE'],
                    'CARRERA' => $p['CARRERA'],
                    'INSTITUCION_NOMBRE' => $p['INSTITUCION_NOMBRE'],
                    'FECHA_INICIO' => $p['FECHA_INICIO'],
                    'FECHA_FIN' => $p['FECHA_FIN'],
                    'HORAS_TOTALES' => $horasTot,
                    'ESTADO_PRACTICA' => $p['ESTADO_PRACTICA'],
                    'TIPO' => 'Preprofesional',
                    'HORAS_CUMPLIDAS' => $horasCum,
                    'PORCENTAJE_PROGRESO' => $this->calcularProgresoPractica($p['ID_PRACTICA_PREPROFESIONAL'], $horasTot, 'preprofesional'),
                    'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividadPractica($p['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional'),
                    'CUMPLIMIENTO_ETIQUETA' => $cumpl['etiqueta'],
                    'CUMPLIMIENTO_NIVEL' => $cumpl['nivel'],
                    'CUMPLIMIENTO_DESCRIPCION' => $cumpl['descripcion'],
                ];
            }
            return $estudiantes;
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estudiantes asignados: ' . $e->getMessage());
            return [];
        }
    }

    private function obtenerEstudiantesAsignados($idDocente)
    {
        if ($idDocente <= 0) {
            return [];
        }

        $practicas = $this->obtenerEstudiantesAsignadosPorModalidad($idDocente, 'preprofesional');
        $servicios = $this->obtenerEstudiantesAsignadosPorModalidad($idDocente, 'servicio');

        return array_merge($practicas, $servicios);
    }

    private function obtenerEstudianteAsignado($estudianteId, $idInstructor)
    {
        if ($idInstructor <= 0) {
            return null;
        }
        try {
            $estudiante = $this->db->table('TAB_ESTUDIANTES e')
                ->select('e.*, c.NOMBRE as CARRERA_NOMBRE, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->where('e.ID_ESTUDIANTE', $estudianteId)
                ->get()
                ->getRowArray();
            if (!$estudiante) {
                return null;
            }
            $asignado = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->where('pp.ID_ESTUDIANTE', $estudianteId)
                ->where('pp.ID_DOCENTE_TUTOR', $idInstructor)
                ->countAllResults() > 0;
            if (!$asignado) {
                $asignado = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_ESTUDIANTE', $estudianteId)
                    ->where('sc.ID_DOCENTE_TUTOR', $idInstructor)
                    ->countAllResults() > 0;
            }
            return $asignado ? $estudiante : null;
        } catch (\Exception $e) {
            log_message('error', 'Error al verificar estudiante asignado: ' . $e->getMessage());
            return null;
        }
    }

    private function obtenerActividadesRecientesPractica($idPractica, $tipo, $limite = 5)
    {
        try {
            if ($tipo === 'preprofesional') {
                return $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES ap')
                    ->where('ap.ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->orderBy('ap.FECHA_ASISTENCIA', 'DESC')
                    ->orderBy('ap.FECHA_REGISTRO', 'DESC')
                    ->limit($limite)
                    ->get()
                    ->getResultArray();
            }
            return $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO as_')
                ->where('as_.ID_SERVICIO_COMUNITARIO', $idPractica)
                ->orderBy('as_.FECHA_ASISTENCIA', 'DESC')
                ->orderBy('as_.FECHA_REGISTRO', 'DESC')
                ->limit($limite)
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades recientes: ' . $e->getMessage());
            return [];
        }
    }

    private function calcularHorasCumplidasPractica($idPractica, $tipo)
    {
        try {
            if ($tipo === 'preprofesional') {
                $asist = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
                    ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->get()
                    ->getRow();
                $seg = $this->db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES')
                    ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->get()
                    ->getRow();
            } else {
                $asist = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
                    ->where('ID_SERVICIO_COMUNITARIO', $idPractica)
                    ->get()
                    ->getRow();
                $seg = $this->db->table('TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO')
                    ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
                    ->where('ID_SERVICIO_COMUNITARIO', $idPractica)
                    ->get()
                    ->getRow();
            }

            return round((float) ($asist->total_horas ?? 0) + (float) ($seg->total_horas ?? 0), 1);
        } catch (\Exception $e) {
            log_message('error', 'calcularHorasCumplidasPractica: ' . $e->getMessage());

            return 0;
        }
    }

    /**
     * Resume horas y cumplimiento temporal para tutor (preprofesional o servicio).
     */
    private function construirBloqueProgresoTutor(array $fila, string $tipoApi): ?array
    {
        if ($tipoApi === 'preprofesional') {
            $id = (int) ($fila['ID_PRACTICA_PREPROFESIONAL'] ?? 0);
            $horasTot = (float) ($fila['HORAS_PRACTICAS'] ?? 0);
            $estado = $fila['ESTADO_PRACTICA'] ?? null;
            $etiquetaTipo = 'Práctica preprofesional';
            $tipoCalc = 'preprofesional';
        } else {
            $id = (int) ($fila['ID_SERVICIO_COMUNITARIO'] ?? 0);
            $horasTot = (float) ($fila['HORAS_SERVICIO'] ?? 0);
            $estado = $fila['ESTADO_SERVICIO'] ?? null;
            $etiquetaTipo = 'Servicio comunitario';
            $tipoCalc = 'servicio';
        }
        if ($id <= 0) {
            return null;
        }
        $horasCum = $this->calcularHorasCumplidasPractica($id, $tipoCalc);
        $porcentaje = $this->calcularProgresoPractica($id, $horasTot, $tipoCalc);
        $cumpl = $this->evaluarRitmoCumplimiento(
            $fila['FECHA_INICIO'] ?? null,
            $fila['FECHA_FIN'] ?? null,
            $horasTot,
            $horasCum,
            $estado
        );

        return [
            'tipo' => $tipoApi,
            'tipo_etiqueta' => $etiquetaTipo,
            'id_practica' => $id,
            'institucion' => $fila['INSTITUCION_NOMBRE'] ?? null,
            'fecha_inicio' => $fila['FECHA_INICIO'] ?? null,
            'fecha_fin' => $fila['FECHA_FIN'] ?? null,
            'estado' => $estado,
            'horas_cumplidas' => $horasCum,
            'horas_totales' => $horasTot,
            'porcentaje' => $porcentaje,
            'cumplimiento' => $cumpl,
        ];
    }

    /**
     * Compara horas registradas frente a la meta y al calendario del período (ritmo esperado).
     *
     * @return array{etiqueta: string, descripcion: string, nivel: string, porcentaje_horas?: float}
     */
    private function evaluarRitmoCumplimiento(
        ?string $fechaInicio,
        ?string $fechaFin,
        float $horasTotales,
        float $horasCumplidas,
        ?string $estado
    ): array {
        $estadoNorm = $estado !== null ? trim((string) $estado) : '';
        $pctHoras = $horasTotales > 0 ? min(100, round(($horasCumplidas / $horasTotales) * 100, 1)) : 0;

        if ($horasTotales <= 0) {
            return [
                'etiqueta' => 'Meta de horas no definida',
                'descripcion' => 'No hay horas objetivo registradas para comparar el avance del estudiante.',
                'nivel' => 'secondary',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        if ($estadoNorm === 'Completada' || $horasCumplidas >= $horasTotales - 0.05) {
            return [
                'etiqueta' => 'Meta de horas cumplida',
                'descripcion' => 'El estudiante alcanzó las horas requeridas para esta asignación.',
                'nivel' => 'success',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        $ts0 = $fechaInicio ? strtotime($fechaInicio . ' 00:00:00') : false;
        $ts1 = $fechaFin ? strtotime($fechaFin . ' 23:59:59') : false;

        if ($ts0 === false || $ts1 === false || $ts1 < $ts0) {
            return [
                'etiqueta' => 'Avance por horas',
                'descripcion' => sprintf(
                    'Lleva %.1f h de %.0f h requeridas (%s%%). No hay fechas válidas del período para calcular ritmo en calendario.',
                    $horasCumplidas,
                    $horasTotales,
                    $pctHoras
                ),
                'nivel' => $pctHoras >= 40 ? 'info' : 'warning',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        $now = time();
        $diasTotal = max(1, (int) ceil(($ts1 - $ts0) / 86400));

        if ($now < $ts0) {
            return [
                'etiqueta' => 'Período no iniciado',
                'descripcion' => 'Aún no comienza el período oficial de la práctica.',
                'nivel' => 'info',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        $tsFinEfectivo = min($now, $ts1);
        $diasTrans = max(1, (int) ceil(($tsFinEfectivo - $ts0) / 86400));
        $horasEsperadas = $horasTotales * ($diasTrans / $diasTotal);
        $tolerancia = 0.88;

        if ($now > $ts1 && $horasCumplidas < $horasTotales - 0.05) {
            $faltan = max(0, $horasTotales - $horasCumplidas);

            return [
                'etiqueta' => 'Plazo vencido',
                'descripcion' => sprintf(
                    'El período terminó y faltan aproximadamente %.1f h de %.0f h requeridas.',
                    $faltan,
                    $horasTotales
                ),
                'nivel' => 'danger',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        if ($horasCumplidas >= $horasEsperadas * $tolerancia) {
            return [
                'etiqueta' => 'Ritmo adecuado',
                'descripcion' => sprintf(
                    'Las horas registradas (%.1f h / %.0f h, %s%%) están alineadas con el tiempo transcurrido del período.',
                    $horasCumplidas,
                    $horasTotales,
                    $pctHoras
                ),
                'nivel' => 'success',
                'porcentaje_horas' => $pctHoras,
            ];
        }

        return [
            'etiqueta' => 'Por debajo del ritmo esperado',
            'descripcion' => sprintf(
                'Respecto al calendario, conviene reforzar registros de asistencia. Lleva %.1f h; en esta etapa del período se esperaba alrededor de %.1f h (meta %.0f h).',
                $horasCumplidas,
                round($horasEsperadas, 1),
                $horasTotales
            ),
            'nivel' => 'warning',
            'porcentaje_horas' => $pctHoras,
        ];
    }

    private function obtenerUltimaActividadPractica($idPractica, $tipo)
    {
        try {
            if ($tipo === 'preprofesional') {
                $row = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->select('FECHA_ASISTENCIA, ACTIVIDADES_DIA')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->orderBy('FECHA_ASISTENCIA', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
            } else {
                $row = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->select('FECHA_ASISTENCIA, ACTIVIDADES_DIA')
                    ->where('ID_SERVICIO_COMUNITARIO', $idPractica)
                    ->orderBy('FECHA_ASISTENCIA', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
            }
            if (!$row) {
                return 'Sin registro';
            }
            $fecha = $row['FECHA_ASISTENCIA'] ?? '';
            $act = $row['ACTIVIDADES_DIA'] ?? '';
            return $fecha . ($act ? ': ' . (strlen($act) > 40 ? substr($act, 0, 40) . '...' : $act) : '');
        } catch (\Exception $e) {
            return 'Sin registro';
        }
    }

    private function calcularProgresoPractica($idPractica, $horasTotales, $tipo)
    {
        if ($horasTotales <= 0) {
            return 0;
        }
        $horas = $this->calcularHorasCumplidasPractica($idPractica, $tipo);
        return min(100, round(($horas / $horasTotales) * 100, 1));
    }

    private function generarDatosReporte($tipo, $fechaDesde, $fechaHasta, $idInstructor)
    {
        $titulo = 'Reporte de prácticas';
        $columnas = [];
        $filas = [];

        if ($idInstructor <= 0) {
            return [
                'tipo' => $tipo,
                'fecha_desde' => $fechaDesde,
                'fecha_hasta' => $fechaHasta,
                'titulo' => $titulo,
                'columnas' => ['Mensaje'],
                'filas' => [['No tiene estudiantes asignados en el período.']],
            ];
        }

        try {
            if ($tipo === 'progreso_estudiantes') {
                $titulo = 'Progreso de estudiantes';
                $columnas = ['Estudiante', 'Carrera', 'Institución', 'Tipo', 'Horas cumplidas', 'Horas totales', 'Progreso %', 'Estado'];
                $estudiantes = $this->obtenerEstudiantesAsignados($idInstructor);
                foreach ($estudiantes as $e) {
                    $filas[] = [
                        $e['NOMBRE_COMPLETO'] ?? '',
                        $e['CARRERA'] ?? '',
                        $e['INSTITUCION_NOMBRE'] ?? '',
                        $e['TIPO'] ?? '',
                        $e['HORAS_CUMPLIDAS'] ?? 0,
                        $e['HORAS_TOTALES'] ?? 0,
                        ($e['PORCENTAJE_PROGRESO'] ?? 0) . '%',
                        $e['ESTADO_PRACTICA'] ?? '',
                    ];
                }
            } elseif ($tipo === 'actividades_realizadas') {
                $titulo = 'Actividades realizadas';
                $columnas = ['Fecha', 'Estudiante', 'Tipo', 'Actividad', 'Hora entrada', 'Hora salida'];
                $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                    ->select('a.FECHA_ASISTENCIA as FECHA, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE, a.HORA_ENTRADA, a.HORA_SALIDA, a.ACTIVIDADES_DIA')
                    ->join('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES a', 'a.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->where('pp.ID_DOCENTE_TUTOR', $idInstructor)
                    ->where('a.FECHA_ASISTENCIA >=', $fechaDesde)
                    ->where('a.FECHA_ASISTENCIA <=', $fechaHasta)
                    ->orderBy('a.FECHA_ASISTENCIA', 'DESC')
                    ->get()
                    ->getResultArray();
                foreach ($pp as $r) {
                    $filas[] = [
                        $r['FECHA'] ?? '',
                        $r['ESTUDIANTE'] ?? '',
                        'Práctica preprofesional',
                        isset($r['ACTIVIDADES_DIA']) ? (strlen($r['ACTIVIDADES_DIA']) > 80 ? substr($r['ACTIVIDADES_DIA'], 0, 77) . '...' : $r['ACTIVIDADES_DIA']) : '',
                        $r['HORA_ENTRADA'] ?? '',
                        $r['HORA_SALIDA'] ?? '',
                    ];
                }
                $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->select('a.FECHA_ASISTENCIA as FECHA, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE, a.HORA_ENTRADA, a.HORA_SALIDA, a.ACTIVIDADES_DIA')
                    ->join('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO a', 'a.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->where('sc.ID_DOCENTE_TUTOR', $idInstructor)
                    ->where('a.FECHA_ASISTENCIA >=', $fechaDesde)
                    ->where('a.FECHA_ASISTENCIA <=', $fechaHasta)
                    ->orderBy('a.FECHA_ASISTENCIA', 'DESC')
                    ->get()
                    ->getResultArray();
                foreach ($sc as $r) {
                    $filas[] = [
                        $r['FECHA'] ?? '',
                        $r['ESTUDIANTE'] ?? '',
                        'Servicio comunitario',
                        isset($r['ACTIVIDADES_DIA']) ? (strlen($r['ACTIVIDADES_DIA']) > 80 ? substr($r['ACTIVIDADES_DIA'], 0, 77) . '...' : $r['ACTIVIDADES_DIA']) : '',
                        $r['HORA_ENTRADA'] ?? '',
                        $r['HORA_SALIDA'] ?? '',
                    ];
                }
            } else {
                $titulo = 'Reporte de prácticas';
                $columnas = ['Estudiante', 'Carrera', 'Tipo', 'Progreso %', 'Estado'];
                $estudiantes = $this->obtenerEstudiantesAsignados($idInstructor);
                foreach ($estudiantes as $e) {
                    $filas[] = [
                        $e['NOMBRE_COMPLETO'] ?? '',
                        $e['CARRERA'] ?? '',
                        $e['TIPO'] ?? '',
                        ($e['PORCENTAJE_PROGRESO'] ?? 0) . '%',
                        $e['ESTADO_PRACTICA'] ?? '',
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'generarDatosReporte: ' . $e->getMessage());
            $columnas = ['Error'];
            $filas = [[$e->getMessage()]];
        }

        return [
            'tipo' => $tipo,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'titulo' => $titulo,
            'columnas' => $columnas,
            'filas' => $filas,
        ];
    }

    private function generarCsvReporte(array $datosReporte)
    {
        $out = fopen('php://temp', 'r+');
        fputcsv($out, array_merge(['Período: ' . ($datosReporte['fecha_desde'] ?? '') . ' a ' . ($datosReporte['fecha_hasta'] ?? '')], []));
        fputcsv($out, []);
        fputcsv($out, $datosReporte['columnas'] ?? []);
        foreach ($datosReporte['filas'] ?? [] as $fila) {
            fputcsv($out, $fila);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $csv;
    }

    private function generarAlertas($idInstructor)
    {
        $alertas = [];
        if ($idInstructor <= 0) {
            return $alertas;
        }
        try {
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.ID_PRACTICA_PREPROFESIONAL, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('pp.ID_DOCENTE_TUTOR', $idInstructor)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->get()
                ->getResultArray();
            foreach ($pp as $r) {
                $ultima = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->selectMax('FECHA_ASISTENCIA')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $r['ID_PRACTICA_PREPROFESIONAL'])
                    ->get()
                    ->getRow();
                $ultimaStr = $ultima && !empty($ultima->FECHA_ASISTENCIA) ? $ultima->FECHA_ASISTENCIA : null;
                $dias = $ultimaStr ? (new \DateTime($ultimaStr))->diff(new \DateTime())->days : 999;
                if ($dias > 3) {
                    $alertas[] = [
                        'tipo' => 'warning',
                        'titulo' => 'Estudiante con retraso',
                        'mensaje' => ($r['NOMBRE_COMPLETO'] ?? 'Estudiante') . ' no ha registrado actividades en ' . $dias . ' días',
                        'fecha' => date('Y-m-d H:i:s')
                    ];
                }
            }
            $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.ID_SERVICIO_COMUNITARIO, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('sc.ID_DOCENTE_TUTOR', $idInstructor)
                ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                ->get()
                ->getResultArray();
            foreach ($sc as $r) {
                $ultima = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->selectMax('FECHA_ASISTENCIA')
                    ->where('ID_SERVICIO_COMUNITARIO', $r['ID_SERVICIO_COMUNITARIO'])
                    ->get()
                    ->getRow();
                $ultimaStr = $ultima && !empty($ultima->FECHA_ASISTENCIA) ? $ultima->FECHA_ASISTENCIA : null;
                $dias = $ultimaStr ? (new \DateTime($ultimaStr))->diff(new \DateTime())->days : 999;
                if ($dias > 3) {
                    $alertas[] = [
                        'tipo' => 'warning',
                        'titulo' => 'Estudiante con retraso',
                        'mensaje' => ($r['NOMBRE_COMPLETO'] ?? 'Estudiante') . ' no ha registrado actividades en ' . $dias . ' días',
                        'fecha' => date('Y-m-d H:i:s')
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al generar alertas: ' . $e->getMessage());
        }
        return $alertas;
    }

    private function contarAlertas($idInstructor)
    {
        if ($idInstructor <= 0) {
            return 0;
        }
        try {
            return count($this->generarAlertas($idInstructor));
        } catch (\Exception $e) {
            return 0;
        }
    }
}
