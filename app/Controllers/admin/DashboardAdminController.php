<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\EstudiantesModel;
use App\Models\InstructoresModel;
use App\Models\ActividadesEducacionModel;
use App\Models\InstitucionesConveniosModel;
use App\Models\DetallesConveniosModel;
use App\Models\AsignacionesPracticasModel;
use App\Models\CarrerasModel;

class DashboardAdminController extends BaseController
{
    protected $estudiantesModel;
    protected $instructoresModel;
    protected $actividadesModel;
    protected $institucionesModel;
    protected $conveniosModel;
    protected $practicasModel;
    protected $carrerasModel;

    public function __construct()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        // Inicializar modelos
        $this->estudiantesModel = new EstudiantesModel();
        $this->instructoresModel = new InstructoresModel();
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->institucionesModel = new InstitucionesConveniosModel();
        $this->conveniosModel = new DetallesConveniosModel();
        $this->practicasModel = new AsignacionesPracticasModel();
        $this->carrerasModel = new CarrerasModel();
    }
    
    public function index()
    {
        // Obtener métricas principales
        $metricas = $this->obtenerMetricas();
        
        // Obtener datos para gráficas
        $datosGraficas = $this->obtenerDatosGraficas();
        
        // Obtener actividades recientes
        $actividadesRecientes = $this->obtenerActividadesRecientes();
        
        // Obtener vencimientos próximos
        $vencimientos = $this->obtenerVencimientosProximos();
        
        // Obtener distribución por carrera
        $distribucionCarreras = $this->obtenerDistribucionCarreras();

        $data = [
            'title' => 'Panel de Control | ITSI',
            'description' => 'Dashboard del sistema ITSI',
            'author' => 'Yamilex & Ana',
            'metricas' => $metricas,
            'datosGraficas' => $datosGraficas,
            'actividadesRecientes' => $actividadesRecientes,
            'vencimientos' => $vencimientos,
            'distribucionCarreras' => $distribucionCarreras
        ];

        return view('admin/dashboard/dashboardAdmin', $data);
    }
    
    private function obtenerMetricas()
    {
        try {
            // Total de estudiantes
            $totalEstudiantes = $this->estudiantesModel->countAllResults();
        } catch (\Exception $e) {
            $totalEstudiantes = 0;
        }
        
        try {
            // Total de instructores
            $totalInstructores = $this->instructoresModel->countAllResults();
        } catch (\Exception $e) {
            $totalInstructores = 0;
        }
        
        try {
            // Actividades activas (no finalizadas)
            $actividadesActivas = $this->actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();
        } catch (\Exception $e) {
            $actividadesActivas = 0;
        }
        
        try {
            // Convenios vigentes
            $conveniosVigentes = $this->conveniosModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();
        } catch (\Exception $e) {
            $conveniosVigentes = 0;
        }
        
        return [
            'totalEstudiantes' => $totalEstudiantes,
            'totalInstructores' => $totalInstructores,
            'actividadesActivas' => $actividadesActivas,
            'conveniosVigentes' => $conveniosVigentes
        ];
    }
    
    private function obtenerDatosGraficas()
    {
        try {
            // Actividades por mes (últimos 12 meses)
            $actividadesPorMes = $this->actividadesModel
                ->select('MONTH(FECHA_INICIO) as mes, COUNT(*) as total')
                ->where('FECHA_INICIO >=', date('Y-m-d', strtotime('-12 months')))
                ->groupBy('MONTH(FECHA_INICIO)')
                ->orderBy('mes')
                ->findAll();
        } catch (\Exception $e) {
            $actividadesPorMes = [];
        }
        
        try {
            // Prácticas por estado
            $practicasPorEstado = $this->practicasModel
                ->select('EP.ESTADO, COUNT(*) as total')
                ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = TAB_ASIGNACIONES_PRACTICAS.ID_ESTADO_PRACTICAS')
                ->groupBy('EP.ESTADO')
                ->findAll();
        } catch (\Exception $e) {
            $practicasPorEstado = [];
        }
        
        try {
            // Actividades por tipo
            $actividadesPorTipo = $this->actividadesModel
                ->select('TA.ACTIVIDAD, COUNT(*) as total')
                ->join('TAB_TIPOS_ACTIVIDADES TA', 'TA.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
                ->groupBy('TA.ACTIVIDAD')
                ->findAll();
        } catch (\Exception $e) {
            $actividadesPorTipo = [];
        }
        
        // Estadísticas mensuales detalladas
        $estadisticasMensuales = $this->obtenerEstadisticasMensuales();
        
        return [
            'actividadesPorMes' => $actividadesPorMes,
            'practicasPorEstado' => $practicasPorEstado,
            'actividadesPorTipo' => $actividadesPorTipo,
            'estadisticasMensuales' => $estadisticasMensuales
        ];
    }
    
    private function obtenerEstadisticasMensuales()
    {
        $meses = [];
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        // Obtener datos de los últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $fecha = date('Y-m', strtotime("-$i months"));
            $mes = (int)date('m', strtotime("-$i months"));
            
            // Contar actividades en este mes
            $actividades = $this->actividadesModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
            
            // Contar prácticas en este mes
            $practicas = $this->practicasModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
            
            $meses[] = [
                'mes' => $mesesNombres[$mes - 1],
                'actividades' => $actividades,
                'practicas' => $practicas
            ];
        }
        
        return $meses;
    }
    
    private function obtenerActividadesRecientes()
    {
        try {
            // Últimas 5 actividades educativas
            $actividades = $this->actividadesModel
                ->select('TAB_ACTIVIDADES_EDUCACION.*, ta.ACTIVIDAD, dp.NOMBRE, dp.APELLIDO')
                ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
                ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
                ->orderBy('TAB_ACTIVIDADES_EDUCACION.FECHA_INICIO', 'DESC')
                ->limit(5)
                ->findAll();
        } catch (\Exception $e) {
            $actividades = [];
        }
        
        try {
            // Últimas 5 asignaciones de prácticas
            $practicas = $this->practicasModel
                ->select('TAB_ASIGNACIONES_PRACTICAS.*, tp.PRACTICA, dp.NOMBRE, dp.APELLIDO, ic.NOMBRE as INSTITUCION')
                ->join('TAB_TIPOS_PRACTICAS tp', 'tp.ID_TIPO_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_TIPO_PRACTICA')
                ->join('TAB_USUARIOS u', 'u.ID_USUARIO = TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_ASIGNACIONES_PRACTICAS.ID_INSTITUCION_CONVENIO')
                ->orderBy('TAB_ASIGNACIONES_PRACTICAS.FECHA_INICIO', 'DESC')
                ->limit(5)
                ->findAll();
        } catch (\Exception $e) {
            $practicas = [];
        }
        
        return [
            'actividades' => $actividades,
            'practicas' => $practicas
        ];
    }
    
    private function obtenerVencimientosProximos()
    {
        try {
            // Convenios que vencen en los próximos 30 días
            $conveniosPorVencer = $this->conveniosModel
                ->select('TAB_DETALLES_CONVENIOS.*, ic.NOMBRE as INSTITUCION')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_DETALLES_CONVENIOS.ID_INSTITUCION_CONVENIO')
                ->where('TAB_DETALLES_CONVENIOS.FECHA_FIN >=', date('Y-m-d'))
                ->where('TAB_DETALLES_CONVENIOS.FECHA_FIN <=', date('Y-m-d', strtotime('+30 days')))
                ->orderBy('TAB_DETALLES_CONVENIOS.FECHA_FIN', 'ASC')
                ->limit(5)
                ->findAll();
        } catch (\Exception $e) {
            $conveniosPorVencer = [];
        }
        
        try {
            // Prácticas que terminan pronto
            $practicasPorTerminar = $this->practicasModel
                ->select('TAB_ASIGNACIONES_PRACTICAS.*, dp.NOMBRE, dp.APELLIDO, ic.NOMBRE as INSTITUCION')
                ->join('TAB_USUARIOS u', 'u.ID_USUARIO = TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_ASIGNACIONES_PRACTICAS.ID_INSTITUCION_CONVENIO')
                ->where('TAB_ASIGNACIONES_PRACTICAS.FECHA_FIN >=', date('Y-m-d'))
                ->where('TAB_ASIGNACIONES_PRACTICAS.FECHA_FIN <=', date('Y-m-d', strtotime('+30 days')))
                ->orderBy('TAB_ASIGNACIONES_PRACTICAS.FECHA_FIN', 'ASC')
                ->limit(5)
                ->findAll();
        } catch (\Exception $e) {
            $practicasPorTerminar = [];
        }
        
        return [
            'convenios' => $conveniosPorVencer,
            'practicas' => $practicasPorTerminar
        ];
    }
    
    private function obtenerDistribucionCarreras()
    {
        try {
            return $this->estudiantesModel
                ->select('c.NOMBRE as CARRERA, COUNT(*) as TOTAL')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = TAB_ESTUDIANTES.ID_CARRERA')
                ->groupBy('c.NOMBRE')
                ->orderBy('TOTAL', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    // Método para obtener estadísticas adicionales (opcional)
    public function estadisticas()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        $data = [
            'totalEstudiantes' => $this->estudiantesModel->countAllResults(),
            'totalInstructores' => $this->instructoresModel->countAllResults(),
            'actividadesActivas' => $this->actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
            'conveniosVigentes' => $this->conveniosModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
            'practicasActivas' => $this->practicasModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
            'institucionesActivas' => $this->institucionesModel->countAllResults()
        ];
        
        return $this->response->setJSON($data);
    }
}
