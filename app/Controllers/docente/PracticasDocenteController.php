<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\PracticasPreprofesionalesModel;
use App\Models\ServiciosComunitariosModel;
use App\Models\ActividadesPracticasModel;
use App\Models\EvaluacionesPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstudiantesModel;

class PracticasDocenteController extends BaseController
{
    protected $practicasPreprofesionalesModel;
    protected $serviciosComunitariosModel;
    protected $actividadesPracticasModel;
    protected $evaluacionesPracticasModel;
    protected $usuariosModel;
    protected $estudiantesModel;
    protected $db;

    public function __construct()
    {
        $this->practicasPreprofesionalesModel = new PracticasPreprofesionalesModel();
        $this->serviciosComunitariosModel = new ServiciosComunitariosModel();
        $this->actividadesPracticasModel = new ActividadesPracticasModel();
        $this->evaluacionesPracticasModel = new EvaluacionesPracticasModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        if ($idInstructor === null) {
            $idInstructor = 0;
        }

        $estadisticas = $this->obtenerEstadisticasDocente($idInstructor, $idUsuario);
        $estudiantesAsignados = $this->obtenerEstudiantesAsignados($idInstructor);
        $evaluacionesPendientes = $this->obtenerEvaluacionesPendientes($idUsuario);

        $data = [
            'title' => 'Supervisión de Prácticas - ITSI',
            'estadisticas' => $estadisticas,
            'estudiantesAsignados' => $estudiantesAsignados,
            'evaluacionesPendientes' => $evaluacionesPendientes
        ];

        return view('docente/practicas/practicas_docente', $data);
    }

    public function detalleEstudiante($estudianteId)
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null) {
            $idInstructor = 0;
        }
        try {
            $estudiante = $this->obtenerEstudianteAsignado($estudianteId, $idInstructor);
            if (!$estudiante) {
                return $this->response->setJSON(['success' => false, 'message' => 'Estudiante no encontrado']);
            }
            $actividadesRecientes = [];
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')->where('ID_ESTUDIANTE', $estudianteId)->where('ID_INSTRUCTOR', $idInstructor)->get()->getRowArray();
            if ($pp) {
                $actividadesRecientes = array_merge($actividadesRecientes, $this->obtenerActividadesRecientesPractica($pp['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional'));
            }
            $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO')->where('ID_ESTUDIANTE', $estudianteId)->where('ID_INSTRUCTOR', $idInstructor)->get()->getRowArray();
            if ($sc) {
                $actividadesRecientes = array_merge($actividadesRecientes, $this->obtenerActividadesRecientesPractica($sc['ID_SERVICIO_COMUNITARIO'], 'servicio'));
            }
            usort($actividadesRecientes, function ($a, $b) {
                $f1 = $a['FECHA_ASISTENCIA'] ?? '';
                $f2 = $b['FECHA_ASISTENCIA'] ?? '';
                return strcmp($f2, $f1);
            });
            $actividadesRecientes = array_slice($actividadesRecientes, 0, 5);
            $progreso = 0;
            if ($pp) {
                $progreso = $this->calcularProgresoPractica($pp['ID_PRACTICA_PREPROFESIONAL'], $pp['HORAS_PRACTICAS'] ?? 0, 'preprofesional');
            }
            if ($sc && $progreso === 0) {
                $progreso = $this->calcularProgresoPractica($sc['ID_SERVICIO_COMUNITARIO'], $sc['HORAS_SERVICIO'] ?? 0, 'servicio');
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'estudiante' => $estudiante,
                    'actividades' => $actividadesRecientes,
                    'progreso' => $progreso
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en detalle de estudiante: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function evaluarEstudiante()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $docenteId = session()->get('id_usuario');
        
        // Validar datos
        $rules = [
            'estudiante_id' => 'required|integer',
            'criterio' => 'required',
            'calificacion' => 'required|decimal|greater_than_equal_to[1]|less_than_equal_to[10]',
            'comentarios' => 'permit_empty|max_length[500]',
            'recomendaciones' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $data = [
                'ID_DOCENTE' => $docenteId,
                'ID_ESTUDIANTE' => $this->request->getPost('estudiante_id'),
                'CRITERIO_EVALUACION' => $this->request->getPost('criterio'),
                'CALIFICACION' => $this->request->getPost('calificacion'),
                'COMENTARIOS' => $this->request->getPost('comentarios'),
                'RECOMENDACIONES' => $this->request->getPost('recomendaciones'),
                'FECHA_EVALUACION' => date('Y-m-d H:i:s'),
                'ESTADO' => 'Completada'
            ];

            $this->evaluacionesPracticasModel->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Evaluación guardada exitosamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al evaluar estudiante: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la evaluación']);
        }
    }

    public function generarReporte()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $docenteId = session()->get('id_usuario');
        
        // Validar datos
        $rules = [
            'tipo_reporte' => 'required',
            'fecha_desde' => 'required|valid_date',
            'fecha_hasta' => 'required|valid_date',
            'formato' => 'required|in_list[pdf,excel,word]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $tipoReporte = $this->request->getPost('tipo_reporte');
            $fechaDesde = $this->request->getPost('fecha_desde');
            $fechaHasta = $this->request->getPost('fecha_hasta');
            $formato = $this->request->getPost('formato');

            $idInstructor = $this->obtenerIdInstructorPorUsuario($docenteId);
            if ($idInstructor === null) {
                $idInstructor = 0;
            }

            $datosReporte = $this->generarDatosReporte($tipoReporte, $fechaDesde, $fechaHasta, $idInstructor);

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
                'nombre_archivo' => 'reporte_practicas_' . date('Y-m-d_His') . ($formato === 'excel' ? '.csv' : '')
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
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null || $idInstructor <= 0) {
            return $this->response->setJSON([]);
        }
        $eventos = [];
        try {
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.ID_PRACTICA_PREPROFESIONAL, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('pp.ID_INSTRUCTOR', $idInstructor)
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
                ->where('sc.ID_INSTRUCTOR', $idInstructor)
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

        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null) {
            $idInstructor = 0;
        }
        try {
            $alertas = $this->generarAlertas($idInstructor);

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
     * Obtiene ID_INSTRUCTOR del usuario logueado (docente) vía TAB_INSTRUCTORES + TAB_USUARIOS.
     */
    private function obtenerIdInstructorPorUsuario($idUsuario)
    {
        $row = $this->db->table('TAB_USUARIOS u')
            ->select('i.ID_INSTRUCTOR')
            ->join('TAB_INSTRUCTORES i', 'i.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();
        return $row ? (int) $row['ID_INSTRUCTOR'] : null;
    }

    private function obtenerEstadisticasDocente($idInstructor, $idUsuario)
    {
        try {
            $estudiantesPp = 0;
            $estudiantesSc = 0;
            $practicasActivas = 0;
            $serviciosActivos = 0;
            if ($idInstructor > 0) {
                $estudiantesPp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                    ->where('pp.ID_INSTRUCTOR', $idInstructor)
                    ->countAllResults();
                $estudiantesSc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_INSTRUCTOR', $idInstructor)
                    ->countAllResults();
                $practicasActivas = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                    ->where('pp.ID_INSTRUCTOR', $idInstructor)
                    ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                    ->countAllResults();
                $serviciosActivos = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_INSTRUCTOR', $idInstructor)
                    ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                    ->countAllResults();
            }
            $evalPend = $this->db->table('TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES ep')
                ->where('ep.ID_EVALUADOR', $idUsuario)
                ->countAllResults();
            $evalPend += $this->db->table('TAB_EVALUACIONES_SERVICIO_COMUNITARIO es')
                ->where('es.ID_EVALUADOR', $idUsuario)
                ->countAllResults();
            $evalCompl = $this->db->table('TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES ep')
                ->where('ep.ID_EVALUADOR', $idUsuario)
                ->countAllResults();
            $evalCompl += $this->db->table('TAB_EVALUACIONES_SERVICIO_COMUNITARIO es')
                ->where('es.ID_EVALUADOR', $idUsuario)
                ->countAllResults();

            return [
                'estudiantesAsignados' => $estudiantesPp + $estudiantesSc,
                'practicasActivas' => $practicasActivas + $serviciosActivos,
                'evaluacionesPendientes' => $evalPend,
                'evaluacionesCompletadas' => $evalCompl,
                'alertas' => $idInstructor > 0 ? $this->contarAlertas($idInstructor) : 0
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas del docente: ' . $e->getMessage());
            return [
                'estudiantesAsignados' => 0,
                'practicasActivas' => 0,
                'evaluacionesPendientes' => 0,
                'evaluacionesCompletadas' => 0,
                'alertas' => 0
            ];
        }
    }

    private function obtenerEstudiantesAsignados($idInstructor)
    {
        if ($idInstructor <= 0) {
            return [];
        }
        try {
            $practicasPp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.*, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->where('pp.ID_INSTRUCTOR', $idInstructor)
                ->get()
                ->getResultArray();

            $serviciosSc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.*, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                ->where('sc.ID_INSTRUCTOR', $idInstructor)
                ->get()
                ->getResultArray();

            $estudiantes = [];
            foreach ($practicasPp as $p) {
                $estudiantes[] = [
                    'ID_ESTUDIANTE' => $p['ID_ESTUDIANTE'],
                    'NOMBRE_COMPLETO' => $p['ESTUDIANTE_NOMBRE'],
                    'CARRERA' => $p['CARRERA'],
                    'INSTITUCION_NOMBRE' => $p['INSTITUCION_NOMBRE'],
                    'FECHA_INICIO' => $p['FECHA_INICIO'],
                    'FECHA_FIN' => $p['FECHA_FIN'],
                    'HORAS_TOTALES' => $p['HORAS_PRACTICAS'] ?? 0,
                    'ESTADO_PRACTICA' => $p['ESTADO_PRACTICA'],
                    'TIPO' => 'Preprofesional',
                    'HORAS_CUMPLIDAS' => $this->calcularHorasCumplidasPractica($p['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional'),
                    'PORCENTAJE_PROGRESO' => $this->calcularProgresoPractica($p['ID_PRACTICA_PREPROFESIONAL'], $p['HORAS_PRACTICAS'] ?? 0, 'preprofesional'),
                    'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividadPractica($p['ID_PRACTICA_PREPROFESIONAL'], 'preprofesional')
                ];
            }
            foreach ($serviciosSc as $s) {
                $estudiantes[] = [
                    'ID_ESTUDIANTE' => $s['ID_ESTUDIANTE'],
                    'NOMBRE_COMPLETO' => $s['ESTUDIANTE_NOMBRE'],
                    'CARRERA' => $s['CARRERA'],
                    'INSTITUCION_NOMBRE' => $s['INSTITUCION_NOMBRE'],
                    'FECHA_INICIO' => $s['FECHA_INICIO'],
                    'FECHA_FIN' => $s['FECHA_FIN'],
                    'HORAS_TOTALES' => $s['HORAS_SERVICIO'] ?? 0,
                    'ESTADO_PRACTICA' => $s['ESTADO_SERVICIO'],
                    'TIPO' => 'Servicio Comunitario',
                    'HORAS_CUMPLIDAS' => $this->calcularHorasCumplidasPractica($s['ID_SERVICIO_COMUNITARIO'], 'servicio'),
                    'PORCENTAJE_PROGRESO' => $this->calcularProgresoPractica($s['ID_SERVICIO_COMUNITARIO'], $s['HORAS_SERVICIO'] ?? 0, 'servicio'),
                    'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividadPractica($s['ID_SERVICIO_COMUNITARIO'], 'servicio')
                ];
            }
            return $estudiantes;
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estudiantes asignados: ' . $e->getMessage());
            return [];
        }
    }

    private function obtenerEvaluacionesPendientes($idUsuario)
    {
        try {
            $lista = [];
            $pp = $this->db->table('TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES ep')
                ->select('ep.ID_EVALUACION_PREPROFESIONAL as ID_EVALUACION, ep.TIPO_EVALUACION, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'pp.ID_PRACTICA_PREPROFESIONAL = ep.ID_PRACTICA_PREPROFESIONAL')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->where('ep.ID_EVALUADOR', $idUsuario)
                ->orderBy('ep.FECHA_EVALUACION', 'DESC')
                ->get()
                ->getResultArray();
            foreach ($pp as $r) {
                $r['TIPO'] = 'Preprofesional';
                $lista[] = $r;
            }
            $sc = $this->db->table('TAB_EVALUACIONES_SERVICIO_COMUNITARIO es')
                ->select('es.ID_EVALUACION_SERVICIO as ID_EVALUACION, es.TIPO_EVALUACION, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_SERVICIO_COMUNITARIO sc', 'sc.ID_SERVICIO_COMUNITARIO = es.ID_SERVICIO_COMUNITARIO')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                ->where('es.ID_EVALUADOR', $idUsuario)
                ->orderBy('es.FECHA_EVALUACION', 'DESC')
                ->get()
                ->getResultArray();
            foreach ($sc as $r) {
                $r['TIPO'] = 'Servicio Comunitario';
                $lista[] = $r;
            }
            return $lista;
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener evaluaciones pendientes: ' . $e->getMessage());
            return [];
        }
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
                ->where('pp.ID_INSTRUCTOR', $idInstructor)
                ->countAllResults() > 0;
            if (!$asignado) {
                $asignado = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->where('sc.ID_ESTUDIANTE', $estudianteId)
                    ->where('sc.ID_INSTRUCTOR', $idInstructor)
                    ->countAllResults() > 0;
            }
            return $asignado ? $estudiante : null;
        } catch (\Exception $e) {
            log_message('error', 'Error al verificar estudiante asignado: ' . $e->getMessage());
            return null;
        }
    }

    private function obtenerActividadesRecientesPractica($idPractica, $tipo)
    {
        try {
            if ($tipo === 'preprofesional') {
                return $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES ap')
                    ->where('ap.ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->orderBy('ap.FECHA_ASISTENCIA', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
            }
            return $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO as_')
                ->where('as_.ID_SERVICIO_COMUNITARIO', $idPractica)
                ->orderBy('as_.FECHA_ASISTENCIA', 'DESC')
                ->limit(5)
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
                $rows = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->select('HORA_ENTRADA, HORA_SALIDA')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
                    ->get()
                    ->getResultArray();
            } else {
                $rows = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->select('HORA_ENTRADA, HORA_SALIDA')
                    ->where('ID_SERVICIO_COMUNITARIO', $idPractica)
                    ->get()
                    ->getResultArray();
            }
            $total = 0;
            foreach ($rows as $r) {
                $entrada = strtotime($r['HORA_ENTRADA'] ?? '00:00:00');
                $salida = strtotime($r['HORA_SALIDA'] ?? '00:00:00');
                $total += max(0, ($salida - $entrada) / 3600);
            }
            return round($total, 1);
        } catch (\Exception $e) {
            return 0;
        }
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
                'filas' => [['No tiene estudiantes asignados en el período.']]
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
                        $e['ESTADO_PRACTICA'] ?? ''
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
                    ->where('pp.ID_INSTRUCTOR', $idInstructor)
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
                        $r['HORA_SALIDA'] ?? ''
                    ];
                }
                $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                    ->select('a.FECHA_ASISTENCIA as FECHA, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE, a.HORA_ENTRADA, a.HORA_SALIDA, a.ACTIVIDADES_DIA')
                    ->join('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO a', 'a.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->where('sc.ID_INSTRUCTOR', $idInstructor)
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
                        $r['HORA_SALIDA'] ?? ''
                    ];
                }
            } elseif ($tipo === 'evaluaciones_periodo') {
                $titulo = 'Evaluaciones por período';
                $columnas = ['Fecha', 'Estudiante', 'Tipo', 'Evaluación'];
                $evalPp = $this->db->table('TAB_EVALUACIONES_PRACTICAS_PREPROFESIONALES ep')
                    ->select('ep.FECHA_EVALUACION, ep.TIPO_EVALUACION, ep.NOTA_FINAL, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE')
                    ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'pp.ID_PRACTICA_PREPROFESIONAL = ep.ID_PRACTICA_PREPROFESIONAL')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->where('ep.ID_EVALUADOR', session()->get('id_usuario'))
                    ->where('DATE(ep.FECHA_EVALUACION) >=', $fechaDesde)
                    ->where('DATE(ep.FECHA_EVALUACION) <=', $fechaHasta)
                    ->get()
                    ->getResultArray();
                foreach ($evalPp as $r) {
                    $filas[] = [
                        $r['FECHA_EVALUACION'] ?? '',
                        $r['ESTUDIANTE'] ?? '',
                        'Práctica preprofesional',
                        ($r['TIPO_EVALUACION'] ?? '') . ' - Nota: ' . ($r['NOTA_FINAL'] ?? '')
                    ];
                }
                $evalSc = $this->db->table('TAB_EVALUACIONES_SERVICIO_COMUNITARIO es')
                    ->select('es.FECHA_EVALUACION, es.TIPO_EVALUACION, es.NOTA_FINAL, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE')
                    ->join('TAB_SERVICIO_COMUNITARIO sc', 'sc.ID_SERVICIO_COMUNITARIO = es.ID_SERVICIO_COMUNITARIO')
                    ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                    ->where('es.ID_EVALUADOR', session()->get('id_usuario'))
                    ->where('DATE(es.FECHA_EVALUACION) >=', $fechaDesde)
                    ->where('DATE(es.FECHA_EVALUACION) <=', $fechaHasta)
                    ->get()
                    ->getResultArray();
                foreach ($evalSc as $r) {
                    $filas[] = [
                        $r['FECHA_EVALUACION'] ?? '',
                        $r['ESTUDIANTE'] ?? '',
                        'Servicio comunitario',
                        ($r['TIPO_EVALUACION'] ?? '') . ' - Nota: ' . ($r['NOTA_FINAL'] ?? '')
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
                        $e['ESTADO_PRACTICA'] ?? ''
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
            'filas' => $filas
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
                ->where('pp.ID_INSTRUCTOR', $idInstructor)
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
                ->where('sc.ID_INSTRUCTOR', $idInstructor)
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
