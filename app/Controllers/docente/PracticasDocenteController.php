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
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $docenteId = session()->get('id_usuario');
        
        // Obtener estadísticas del docente
        $estadisticas = $this->obtenerEstadisticasDocente($docenteId);
        
        // Obtener estudiantes asignados al docente
        $estudiantesAsignados = $this->obtenerEstudiantesAsignados($docenteId);
        
        // Obtener evaluaciones pendientes
        $evaluacionesPendientes = $this->obtenerEvaluacionesPendientes($docenteId);

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

        $docenteId = session()->get('id_usuario');
        
        try {
            // Verificar que el estudiante esté asignado al docente
            $estudiante = $this->obtenerEstudianteAsignado($estudianteId, $docenteId);
            
            if (!$estudiante) {
                return $this->response->setJSON(['success' => false, 'message' => 'Estudiante no encontrado']);
            }

            // Obtener actividades recientes del estudiante
            $actividadesRecientes = $this->obtenerActividadesRecientes($estudianteId);
            
            // Calcular progreso general
            $progreso = $this->calcularProgresoEstudiante($estudianteId);

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

            // Generar reporte según el tipo
            $datosReporte = $this->generarDatosReporte($tipoReporte, $fechaDesde, $fechaHasta, $docenteId);
            
            // Aquí se implementaría la generación del archivo según el formato
            // Por ahora solo retornamos los datos
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'data' => $datosReporte
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al generar reporte: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al generar el reporte']);
        }
    }

    public function obtenerAlertas()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $docenteId = session()->get('id_usuario');
        
        try {
            $alertas = $this->generarAlertas($docenteId);

            return $this->response->setJSON([
                'success' => true,
                'data' => $alertas
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener alertas: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al obtener alertas']);
        }
    }

    private function obtenerEstadisticasDocente($docenteId)
    {
        try {
            // Obtener estudiantes asignados
            $estudiantesAsignados = $this->db->table('estudiantes e')
                ->join('practicas_preprofesionales pp', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE')
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->countAllResults();

            $estudiantesServicio = $this->db->table('estudiantes e')
                ->join('servicios_comunitarios sc', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE')
                ->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->countAllResults();

            // Obtener prácticas activas
            $practicasActivas = $this->db->table('practicas_preprofesionales pp')
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->countAllResults();

            $serviciosActivos = $this->db->table('servicios_comunitarios sc')
                ->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                ->countAllResults();

            // Obtener evaluaciones pendientes
            $evaluacionesPendientes = $this->db->table('evaluaciones_practicas ep')
                ->where('ep.ID_DOCENTE', $docenteId)
                ->where('ep.ESTADO', 'Pendiente')
                ->countAllResults();

            // Obtener alertas
            $alertas = $this->contarAlertas($docenteId);

            return [
                'estudiantesAsignados' => $estudiantesAsignados + $estudiantesServicio,
                'practicasActivas' => $practicasActivas + $serviciosActivos,
                'evaluacionesPendientes' => $evaluacionesPendientes,
                'evaluacionesCompletadas' => $this->db->table('evaluaciones_practicas')
                    ->where('ID_DOCENTE', $docenteId)
                    ->where('ESTADO', 'Completada')
                    ->countAllResults(),
                'alertas' => $alertas
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

    private function obtenerEstudiantesAsignados($docenteId)
    {
        try {
            // Obtener estudiantes de prácticas preprofesionales
            $practicasPreprofesionales = $this->db->table('practicas_preprofesionales pp')
                ->select('pp.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->get()
                ->getResultArray();

            // Obtener estudiantes de servicios comunitarios
            $serviciosComunitarios = $this->db->table('servicios_comunitarios sc')
                ->select('sc.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                ->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->get()
                ->getResultArray();

            // Combinar y procesar datos
            $estudiantes = [];
            
            foreach ($practicasPreprofesionales as $practica) {
                $estudiantes[] = [
                    'ID_ESTUDIANTE' => $practica['ID_ESTUDIANTE'],
                    'NOMBRE_COMPLETO' => $practica['ESTUDIANTE_NOMBRE'],
                    'CARRERA' => $practica['CARRERA'],
                    'INSTITUCION_NOMBRE' => $practica['INSTITUCION_NOMBRE'],
                    'TIPO_INSTITUCION' => $practica['TIPO_INSTITUCION'],
                    'FECHA_INICIO' => $practica['FECHA_INICIO'],
                    'FECHA_FIN' => $practica['FECHA_FIN'],
                    'HORAS_TOTALES' => $practica['HORAS_PRACTICAS'],
                    'ESTADO_PRACTICA' => $practica['ESTADO_PRACTICA'],
                    'TIPO' => 'Preprofesional',
                    'HORAS_CUMPLIDAS' => $this->calcularHorasCumplidas($practica['ID_ESTUDIANTE'], 'preprofesional'),
                    'PORCENTAJE_PROGRESO' => $this->calcularProgresoEstudiante($practica['ID_ESTUDIANTE']),
                    'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividad($practica['ID_ESTUDIANTE'])
                ];
            }

            foreach ($serviciosComunitarios as $servicio) {
                $estudiantes[] = [
                    'ID_ESTUDIANTE' => $servicio['ID_ESTUDIANTE'],
                    'NOMBRE_COMPLETO' => $servicio['ESTUDIANTE_NOMBRE'],
                    'CARRERA' => $servicio['CARRERA'],
                    'INSTITUCION_NOMBRE' => $servicio['INSTITUCION_NOMBRE'],
                    'TIPO_INSTITUCION' => $servicio['TIPO_INSTITUCION'],
                    'FECHA_INICIO' => $servicio['FECHA_INICIO'],
                    'FECHA_FIN' => $servicio['FECHA_FIN'],
                    'HORAS_TOTALES' => $servicio['HORAS_SERVICIO'],
                    'ESTADO_PRACTICA' => $servicio['ESTADO_SERVICIO'],
                    'TIPO' => 'Servicio Comunitario',
                    'HORAS_CUMPLIDAS' => $this->calcularHorasCumplidas($servicio['ID_ESTUDIANTE'], 'servicio'),
                    'PORCENTAJE_PROGRESO' => $this->calcularProgresoEstudiante($servicio['ID_ESTUDIANTE']),
                    'ULTIMA_ACTIVIDAD' => $this->obtenerUltimaActividad($servicio['ID_ESTUDIANTE'])
                ];
            }

            return $estudiantes;

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estudiantes asignados: ' . $e->getMessage());
            return [];
        }
    }

    private function obtenerEvaluacionesPendientes($docenteId)
    {
        try {
            return $this->db->table('evaluaciones_practicas ep')
                ->select('ep.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE')
                ->join('practicas_preprofesionales pp', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
                ->join('servicios_comunitarios sc', 'sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
                ->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = COALESCE(pp.ID_INSTITUCION_CONVENIO, sc.ID_INSTITUCION_CONVENIO)')
                ->where('ep.ID_DOCENTE', $docenteId)
                ->where('ep.ESTADO', 'Pendiente')
                ->orderBy('ep.FECHA_CREACION', 'DESC')
                ->get()
                ->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener evaluaciones pendientes: ' . $e->getMessage());
            return [];
        }
    }

    private function obtenerEstudianteAsignado($estudianteId, $docenteId)
    {
        try {
            // Verificar que el estudiante esté asignado al docente
            $estudiante = $this->db->table('estudiantes e')
                ->select('e.*, c.NOMBRE as CARRERA_NOMBRE')
                ->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->where('e.ID_ESTUDIANTE', $estudianteId)
                ->get()
                ->getRowArray();

            if (!$estudiante) {
                return null;
            }

            // Verificar asignación en prácticas o servicios
            $asignado = $this->db->table('practicas_preprofesionales pp')
                ->where('pp.ID_ESTUDIANTE', $estudianteId)
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->countAllResults() > 0;

            if (!$asignado) {
                $asignado = $this->db->table('servicios_comunitarios sc')
                    ->where('sc.ID_ESTUDIANTE', $estudianteId)
                    ->where('sc.ID_DOCENTE_SUPERVISOR', $docenteId)
                    ->countAllResults() > 0;
            }

            return $asignado ? $estudiante : null;

        } catch (\Exception $e) {
            log_message('error', 'Error al verificar estudiante asignado: ' . $e->getMessage());
            return null;
        }
    }

    private function obtenerActividadesRecientes($estudianteId)
    {
        try {
            return $this->db->table('actividades_practicas ap')
                ->where('ap.ID_ESTUDIANTE', $estudianteId)
                ->orderBy('ap.FECHA_ACTIVIDAD', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades recientes: ' . $e->getMessage());
            return [];
        }
    }

    private function calcularProgresoEstudiante($estudianteId)
    {
        try {
            $actividades = $this->db->table('actividades_practicas')
                ->where('ID_ESTUDIANTE', $estudianteId)
                ->get()
                ->getResultArray();

            $totalHoras = 0;
            foreach ($actividades as $actividad) {
                $entrada = strtotime($actividad['FECHA_ACTIVIDAD'] . ' ' . $actividad['HORA_ENTRADA']);
                $salida = strtotime($actividad['FECHA_ACTIVIDAD'] . ' ' . $actividad['HORA_SALIDA']);
                $totalHoras += ($salida - $entrada) / 3600; // Convertir a horas
            }

            // Obtener horas totales requeridas
            $practica = $this->db->table('practicas_preprofesionales pp')
                ->where('pp.ID_ESTUDIANTE', $estudianteId)
                ->get()
                ->getRowArray();

            if (!$practica) {
                $servicio = $this->db->table('servicios_comunitarios sc')
                    ->where('sc.ID_ESTUDIANTE', $estudianteId)
                    ->get()
                    ->getRowArray();
                $horasRequeridas = $servicio['HORAS_SERVICIO'] ?? 0;
            } else {
                $horasRequeridas = $practica['HORAS_PRACTICAS'] ?? 0;
            }

            if ($horasRequeridas > 0) {
                return round(($totalHoras / $horasRequeridas) * 100, 2);
            }

            return 0;

        } catch (\Exception $e) {
            log_message('error', 'Error al calcular progreso del estudiante: ' . $e->getMessage());
            return 0;
        }
    }

    private function calcularHorasCumplidas($estudianteId, $tipo)
    {
        try {
            $result = $this->db->table('actividades_practicas')
                ->selectSum('TIMESTAMPDIFF(HOUR, CONCAT(FECHA_ACTIVIDAD, " ", HORA_ENTRADA), CONCAT(FECHA_ACTIVIDAD, " ", HORA_SALIDA))', 'total_horas')
                ->where('ID_ESTUDIANTE', $estudianteId)
                ->where('TIPO_PRACTICA', $tipo)
                ->get()
                ->getRow();

            return $result->total_horas ?? 0;

        } catch (\Exception $e) {
            log_message('error', 'Error al calcular horas cumplidas: ' . $e->getMessage());
            return 0;
        }
    }

    private function obtenerUltimaActividad($estudianteId)
    {
        try {
            $actividad = $this->db->table('actividades_practicas')
                ->where('ID_ESTUDIANTE', $estudianteId)
                ->orderBy('FECHA_ACTIVIDAD', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();

            return $actividad ? $actividad['ACTIVIDADES_REALIZADAS'] : 'Sin actividades';

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener última actividad: ' . $e->getMessage());
            return 'Sin actividades';
        }
    }

    private function generarDatosReporte($tipo, $fechaDesde, $fechaHasta, $docenteId)
    {
        // Implementar generación de datos según el tipo de reporte
        // Por ahora retornamos datos de ejemplo
        return [
            'tipo' => $tipo,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'docente_id' => $docenteId,
            'datos' => []
        ];
    }

    private function generarAlertas($docenteId)
    {
        $alertas = [];
        
        try {
            // Alertas de estudiantes con retraso en actividades
            $estudiantesRetraso = $this->db->table('estudiantes e')
                ->select('e.NOMBRE_COMPLETO, DATEDIFF(NOW(), MAX(ap.FECHA_ACTIVIDAD)) as dias_sin_actividad')
                ->join('practicas_preprofesionales pp', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE')
                ->join('actividades_practicas ap', 'ap.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->groupBy('e.ID_ESTUDIANTE')
                ->having('dias_sin_actividad > 3 OR dias_sin_actividad IS NULL')
                ->get()
                ->getResultArray();

            foreach ($estudiantesRetraso as $estudiante) {
                $alertas[] = [
                    'tipo' => 'warning',
                    'titulo' => 'Estudiante con retraso',
                    'mensaje' => $estudiante['NOMBRE_COMPLETO'] . ' no ha registrado actividades en ' . ($estudiante['dias_sin_actividad'] ?? 'muchos') . ' días',
                    'fecha' => date('Y-m-d H:i:s')
                ];
            }

            // Alertas de evaluaciones próximas a vencer
            $evaluacionesVencimiento = $this->db->table('evaluaciones_practicas ep')
                ->select('ep.*, e.NOMBRE_COMPLETO')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = ep.ID_ESTUDIANTE')
                ->where('ep.ID_DOCENTE', $docenteId)
                ->where('ep.ESTADO', 'Pendiente')
                ->where('ep.FECHA_LIMITE <= DATE_ADD(NOW(), INTERVAL 3 DAY)')
                ->get()
                ->getResultArray();

            foreach ($evaluacionesVencimiento as $evaluacion) {
                $alertas[] = [
                    'tipo' => 'warning',
                    'titulo' => 'Evaluación próxima a vencer',
                    'mensaje' => 'Evaluación de ' . $evaluacion['NOMBRE_COMPLETO'] . ' vence en ' . $evaluacion['FECHA_LIMITE'],
                    'fecha' => date('Y-m-d H:i:s')
                ];
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al generar alertas: ' . $e->getMessage());
        }

        return $alertas;
    }

    private function contarAlertas($docenteId)
    {
        try {
            $count = 0;
            
            // Contar estudiantes con retraso
            $count += $this->db->table('estudiantes e')
                ->join('practicas_preprofesionales pp', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE')
                ->join('actividades_practicas ap', 'ap.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
                ->where('pp.ID_DOCENTE_SUPERVISOR', $docenteId)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->groupBy('e.ID_ESTUDIANTE')
                ->having('DATEDIFF(NOW(), MAX(ap.FECHA_ACTIVIDAD)) > 3 OR MAX(ap.FECHA_ACTIVIDAD) IS NULL')
                ->countAllResults();

            // Contar evaluaciones próximas a vencer
            $count += $this->db->table('evaluaciones_practicas ep')
                ->where('ep.ID_DOCENTE', $docenteId)
                ->where('ep.ESTADO', 'Pendiente')
                ->where('ep.FECHA_LIMITE <= DATE_ADD(NOW(), INTERVAL 3 DAY)')
                ->countAllResults();

            return $count;

        } catch (\Exception $e) {
            log_message('error', 'Error al contar alertas: ' . $e->getMessage());
            return 0;
        }
    }
}
