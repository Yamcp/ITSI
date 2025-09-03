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
            // Actividades por mes (últimos 12 meses) - consulta mejorada
            $sql = "
                SELECT 
                    MONTH(FECHA_INICIO) as mes,
                    YEAR(FECHA_INICIO) as año,
                    COUNT(*) as total
                FROM TAB_ACTIVIDADES_EDUCACION 
                WHERE FECHA_INICIO >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY YEAR(FECHA_INICIO), MONTH(FECHA_INICIO)
                ORDER BY año, mes
            ";
            $query = $this->actividadesModel->db->query($sql);
            $actividadesPorMes = $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades por mes: ' . $e->getMessage());
            $actividadesPorMes = [];
        }
        
        try {
            // Prácticas por estado (usando estados predefinidos ya que no hay tabla de estados)
            $practicasPorEstado = [
                ['ESTADO' => 'Asignada', 'total' => $this->practicasModel->where('ID_ESTADO_PRACTICAS', 1)->countAllResults(false)],
                ['ESTADO' => 'En Progreso', 'total' => $this->practicasModel->where('ID_ESTADO_PRACTICAS', 2)->countAllResults(false)],
                ['ESTADO' => 'Completada', 'total' => $this->practicasModel->where('ID_ESTADO_PRACTICAS', 3)->countAllResults(false)],
                ['ESTADO' => 'Cancelada', 'total' => $this->practicasModel->where('ID_ESTADO_PRACTICAS', 4)->countAllResults(false)]
            ];
        } catch (\Exception $e) {
            $practicasPorEstado = [];
        }
        
        try {
            // Actividades por tipo - consulta mejorada
            $sql = "
                SELECT 
                    ta.ACTIVIDAD,
                    COUNT(ae.ID_ACTIVIDAD_EDUCACION) as total
                FROM TAB_ACTIVIDADES_EDUCACION ae
                INNER JOIN TAB_TIPOS_ACTIVIDADES ta ON ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD
                GROUP BY ta.ID_TIPO_ACTIVIDAD, ta.ACTIVIDAD
                ORDER BY total DESC
            ";
            $query = $this->actividadesModel->db->query($sql);
            $actividadesPorTipo = $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades por tipo: ' . $e->getMessage());
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
            $año = (int)date('Y', strtotime("-$i months"));
            
            try {
                // Contar actividades educativas en este mes (usando consulta SQL directa para mejor rendimiento)
                $sql = "
                    SELECT COUNT(*) as total
                    FROM TAB_ACTIVIDADES_EDUCACION 
                    WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?
                ";
                $query = $this->actividadesModel->db->query($sql, [$fecha]);
                $resultado = $query->getRow();
                $actividades = $resultado ? $resultado->total : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error al obtener actividades mensuales: ' . $e->getMessage());
                $actividades = 0;
            }
            
            try {
                // Contar prácticas en este mes
                $sql = "
                    SELECT COUNT(*) as total
                    FROM TAB_ASIGNACIONES_PRACTICAS 
                    WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?
                ";
                $query = $this->practicasModel->db->query($sql, [$fecha]);
                $resultado = $query->getRow();
                $practicas = $resultado ? $resultado->total : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error al obtener prácticas mensuales: ' . $e->getMessage());
                $practicas = 0;
            }
            
            $meses[] = [
                'mes' => $mesesNombres[$mes - 1] . ' ' . $año,
                'actividades' => (int)$actividades,
                'practicas' => (int)$practicas
            ];
        }
        
        // Debug: Log para verificar los datos
        log_message('debug', 'Estadísticas mensuales: ' . json_encode($meses));
        
        return $meses;
    }
    
    private function obtenerActividadesRecientes()
    {
        try {
            // Últimas 5 actividades educativas
            $actividades = $this->actividadesModel
                ->select('TAB_ACTIVIDADES_EDUCACION.*, ta.ACTIVIDAD, dp.NOMBRE, dp.APELLIDO')
                ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD', 'left')
                ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
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
                ->join('TAB_TIPOS_PRACTICAS tp', 'tp.ID_TIPO_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_TIPO_PRACTICA', 'left')
                ->join('TAB_USUARIOS u', 'u.ID_USUARIO = TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO', 'left')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA', 'left')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_ASIGNACIONES_PRACTICAS.ID_INSTITUCION_CONVENIO', 'left')
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
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_DETALLES_CONVENIOS.ID_INSTITUCION_CONVENIO', 'left')
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
                ->join('TAB_USUARIOS u', 'u.ID_USUARIO = TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO', 'left')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA', 'left')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = TAB_ASIGNACIONES_PRACTICAS.ID_INSTITUCION_CONVENIO', 'left')
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
            // Usar consulta SQL directa para asegurar que funcione
            $sql = "
                SELECT 
                    c.NOMBRE as CARRERA, 
                    COUNT(e.ID_ESTUDIANTE) as TOTAL
                FROM TAB_CARRERAS c
                LEFT JOIN TAB_ESTUDIANTES e ON c.ID_CARRERA = e.ID_CARRERA
                GROUP BY c.ID_CARRERA, c.NOMBRE
                HAVING COUNT(e.ID_ESTUDIANTE) > 0
                ORDER BY TOTAL DESC
            ";
            
            $query = $this->estudiantesModel->db->query($sql);
            $resultado = $query->getResultArray();
            
            // Debug: Log para ver qué datos se obtienen
            log_message('debug', 'Distribución carreras: ' . json_encode($resultado));
            
            return $resultado;
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerDistribucionCarreras: ' . $e->getMessage());
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
    
    // Método temporal para debug de carreras
    public function debugCarreras()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        try {
            // Verificar datos en TAB_ESTUDIANTES
            $totalEstudiantes = $this->estudiantesModel->countAllResults();
            $estudiantes = $this->estudiantesModel->findAll();
            
            // Verificar datos en TAB_CARRERAS
            $totalCarreras = $this->carrerasModel->countAllResults();
            $carreras = $this->carrerasModel->findAll();
            
            // Probar la consulta de distribución
            $distribucion = $this->obtenerDistribucionCarreras();
            
            $debug = [
                'total_estudiantes' => $totalEstudiantes,
                'total_carreras' => $totalCarreras,
                'estudiantes_sample' => array_slice($estudiantes, 0, 3),
                'carreras_sample' => array_slice($carreras, 0, 3),
                'distribucion' => $distribucion
            ];
            
            return $this->response->setJSON($debug);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    // Método para obtener estadísticas de actividades educativas (similar al controlador de actividades)
    public function getEstadisticasActividades()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return $this->response->setJSON(['error' => 'No autorizado']);
        }
        
        try {
            $totalActividades = $this->actividadesModel->countAllResults();
            
            $cursosActivos = $this->actividadesModel
                ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
                ->where('ta.ACTIVIDAD', 'Curso')
                ->where('FECHA_FIN >=', date('Y-m-d'))
                ->countAllResults();

            $talleresActivos = $this->actividadesModel
                ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
                ->where('ta.ACTIVIDAD', 'Taller')
                ->where('FECHA_FIN >=', date('Y-m-d'))
                ->countAllResults();

            $seminariosActivos = $this->actividadesModel
                ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
                ->where('ta.ACTIVIDAD', 'Seminario')
                ->where('FECHA_FIN >=', date('Y-m-d'))
                ->countAllResults();
            
            $estadisticas = [
                'totalActividades' => $totalActividades,
                'cursosActivos' => $cursosActivos,
                'talleresActivos' => $talleresActivos,
                'seminariosActivos' => $seminariosActivos
            ];
            
            return $this->response->setJSON($estadisticas);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de actividades: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor']);
        }
    }
}
