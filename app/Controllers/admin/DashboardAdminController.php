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
use Config\Database;

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
        
        // Prácticas preprofesionales y servicio comunitario por carrera
        $practicasPorCarrera = $this->obtenerPracticasPorCarrera();

        // Obtener período académico actual (reutilizando lógica del navbar)
        $periodoNombre = session('periodo_academico_nombre');
        $periodoRango  = session('periodo_academico_rango');

        if (!$periodoNombre) {
            try {
                $db = Database::connect();
                $row = $db->query("SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL LIMIT 1")->getRowArray();

                if ($row) {
                    $periodoNombre = $row['NOMBRE_PERIODO'] ?? null;
                    $periodoRango  = ($row['FECHA_INICIO'] ?? '') . ' - ' . ($row['FECHA_FIN'] ?? '');
                }
            } catch (\Throwable $e) {
                log_message('error', 'Dashboard admin - error obteniendo período académico actual: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => 'Panel de Control | ITSI',
            'description' => 'Dashboard del sistema ITSI',
            'author' => 'Yamilex & Ana',
            'metricas' => $metricas,
            'datosGraficas' => $datosGraficas,
            'actividadesRecientes' => $actividadesRecientes,
            'vencimientos' => $vencimientos,
            'practicasPorCarrera' => $practicasPorCarrera,
            'periodoAcademicoNombre' => $periodoNombre,
            'periodoAcademicoRango' => $periodoRango,
        ];

        return view('admin/dashboard/dashboardAdmin', $data);
    }
    
    private function obtenerMetricas()
    {
        // Verificar que los modelos estén inicializados
        if (!$this->estudiantesModel) {
            $this->estudiantesModel = new EstudiantesModel();
        }
        if (!$this->instructoresModel) {
            $this->instructoresModel = new InstructoresModel();
        }
        if (!$this->actividadesModel) {
            $this->actividadesModel = new ActividadesEducacionModel();
        }
        if (!$this->conveniosModel) {
            $this->conveniosModel = new DetallesConveniosModel();
        }
        
        try {
            // Total de estudiantes
            $totalEstudiantes = $this->estudiantesModel->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener total de estudiantes: ' . $e->getMessage());
            $totalEstudiantes = 0;
        }
        
        try {
            // Total de instructores (internos y externos)
            $totalInstructores = $this->instructoresModel->countAllResults();
            $instructoresInternos = (new InstructoresModel())->where('ID_TIPO_INSTRUCTOR', 1)->countAllResults();
            $instructoresExternos = (new InstructoresModel())->where('ID_TIPO_INSTRUCTOR', 2)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener total de instructores: ' . $e->getMessage());
            $totalInstructores = 0;
            $instructoresInternos = 0;
            $instructoresExternos = 0;
        }
        
        try {
            // Actividades activas (no finalizadas)
            $actividadesActivas = $this->actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades activas: ' . $e->getMessage());
            $actividadesActivas = 0;
        }
        
        try {
            // Convenios por caducar (próximos 3 meses)
            $hoy = date('Y-m-d');
            $en3Meses = date('Y-m-d', strtotime('+3 months'));
            $conveniosPorCaducar = $this->conveniosModel
                ->where('FECHA_FIN >=', $hoy)
                ->where('FECHA_FIN <=', $en3Meses)
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener convenios por caducar: ' . $e->getMessage());
            $conveniosPorCaducar = 0;
        }
        
        return [
            'totalEstudiantes' => $totalEstudiantes,
            'totalInstructores' => $totalInstructores,
            'instructoresInternos' => $instructoresInternos ?? 0,
            'instructoresExternos' => $instructoresExternos ?? 0,
            'actividadesActivas' => $actividadesActivas,
            'conveniosPorCaducar' => $conveniosPorCaducar
        ];
    }
    
    private function obtenerDatosGraficas()
    {
        // Verificar que los modelos estén inicializados
        if (!$this->actividadesModel) {
            $this->actividadesModel = new ActividadesEducacionModel();
        }
        if (!$this->practicasModel) {
            $this->practicasModel = new AsignacionesPracticasModel();
        }
        
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
        $db = $this->practicasModel->db;
        
        // Obtener datos de los últimos 12 meses: estudiantes en prácticas preprofesionales y servicio comunitario
        for ($i = 11; $i >= 0; $i--) {
            $fecha = date('Y-m', strtotime("-$i months"));
            $mes = (int)date('m', strtotime("-$i months"));
            $año = (int)date('Y', strtotime("-$i months"));
            
            try {
                // Estudiantes que ingresaron a prácticas preprofesionales en este mes
                $sqlPre = "
                    SELECT COUNT(*) as total
                    FROM TAB_PRACTICAS_PREPROFESIONALES
                    WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?
                ";
                $queryPre = $db->query($sqlPre, [$fecha]);
                $rowPre = $queryPre->getRow();
                $preprofesionales = $rowPre ? (int)$rowPre->total : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error al obtener prácticas preprofesionales mensuales: ' . $e->getMessage());
                $preprofesionales = 0;
            }
            
            try {
                // Estudiantes que ingresaron a servicio comunitario en este mes
                $sqlServ = "
                    SELECT COUNT(*) as total
                    FROM TAB_SERVICIO_COMUNITARIO
                    WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?
                ";
                $queryServ = $db->query($sqlServ, [$fecha]);
                $rowServ = $queryServ->getRow();
                $servicioComunitario = $rowServ ? (int)$rowServ->total : 0;
            } catch (\Exception $e) {
                log_message('error', 'Error al obtener servicio comunitario mensual: ' . $e->getMessage());
                $servicioComunitario = 0;
            }
            
            $meses[] = [
                'mes' => $mesesNombres[$mes - 1] . ' ' . $año,
                'preprofesionales' => $preprofesionales,
                'servicioComunitario' => $servicioComunitario
            ];
        }
        
        log_message('debug', 'Estadísticas mensuales prácticas: ' . json_encode($meses));
        
        return $meses;
    }
    
    private function obtenerActividadesRecientes()
    {
        // Verificar que los modelos estén inicializados
        if (!$this->actividadesModel) {
            $this->actividadesModel = new ActividadesEducacionModel();
        }
        if (!$this->practicasModel) {
            $this->practicasModel = new AsignacionesPracticasModel();
        }
        
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
            log_message('error', 'Error al obtener actividades recientes: ' . $e->getMessage());
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
            log_message('error', 'Error al obtener prácticas recientes: ' . $e->getMessage());
            $practicas = [];
        }
        
        return [
            'actividades' => $actividades,
            'practicas' => $practicas
        ];
    }
    
    private function obtenerVencimientosProximos()
    {
        // Verificar que los modelos estén inicializados
        if (!$this->conveniosModel) {
            $this->conveniosModel = new DetallesConveniosModel();
        }
        if (!$this->practicasModel) {
            $this->practicasModel = new AsignacionesPracticasModel();
        }
        
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
            log_message('error', 'Error al obtener convenios por vencer: ' . $e->getMessage());
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
            log_message('error', 'Error al obtener prácticas por terminar: ' . $e->getMessage());
            $practicasPorTerminar = [];
        }
        
        return [
            'convenios' => $conveniosPorVencer,
            'practicas' => $practicasPorTerminar
        ];
    }
    
    /**
     * Prácticas preprofesionales y servicio comunitario agrupados por carrera.
     * @return array [ ['CARRERA' => string, 'PREPROFESIONALES' => int, 'SERVICIO_COMUNITARIO' => int], ... ]
     */
    private function obtenerPracticasPorCarrera()
    {
        $db = $this->practicasModel->db;
        $porCarrera = [];

        try {
            $sqlPre = "
                SELECT c.NOMBRE as CARRERA, COUNT(*) as TOTAL
                FROM TAB_PRACTICAS_PREPROFESIONALES pp
                INNER JOIN TAB_ESTUDIANTES e ON e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE
                INNER JOIN TAB_CARRERAS c ON c.ID_CARRERA = e.ID_CARRERA
                GROUP BY c.ID_CARRERA, c.NOMBRE
                ORDER BY c.NOMBRE
            ";
            $queryPre = $db->query($sqlPre);
            foreach ($queryPre->getResultArray() as $row) {
                $nombre = $row['CARRERA'];
                if (!isset($porCarrera[$nombre])) {
                    $porCarrera[$nombre] = ['CARRERA' => $nombre, 'PREPROFESIONALES' => 0, 'SERVICIO_COMUNITARIO' => 0];
                }
                $porCarrera[$nombre]['PREPROFESIONALES'] = (int) $row['TOTAL'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error obtener prácticas preprofesionales por carrera: ' . $e->getMessage());
        }

        try {
            $sqlServ = "
                SELECT c.NOMBRE as CARRERA, COUNT(*) as TOTAL
                FROM TAB_SERVICIO_COMUNITARIO sc
                INNER JOIN TAB_ESTUDIANTES e ON e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE
                INNER JOIN TAB_CARRERAS c ON c.ID_CARRERA = e.ID_CARRERA
                GROUP BY c.ID_CARRERA, c.NOMBRE
                ORDER BY c.NOMBRE
            ";
            $queryServ = $db->query($sqlServ);
            foreach ($queryServ->getResultArray() as $row) {
                $nombre = $row['CARRERA'];
                if (!isset($porCarrera[$nombre])) {
                    $porCarrera[$nombre] = ['CARRERA' => $nombre, 'PREPROFESIONALES' => 0, 'SERVICIO_COMUNITARIO' => 0];
                }
                $porCarrera[$nombre]['SERVICIO_COMUNITARIO'] = (int) $row['TOTAL'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error obtener servicio comunitario por carrera: ' . $e->getMessage());
        }

        // Ordenar por nombre de carrera y devolver valores indexados
        ksort($porCarrera);
        return array_values($porCarrera);
    }
    
    // Método para obtener estadísticas adicionales (opcional)
    public function estadisticas()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        // Verificar que los modelos estén inicializados
        if (!$this->estudiantesModel) {
            $this->estudiantesModel = new EstudiantesModel();
        }
        if (!$this->instructoresModel) {
            $this->instructoresModel = new InstructoresModel();
        }
        if (!$this->actividadesModel) {
            $this->actividadesModel = new ActividadesEducacionModel();
        }
        if (!$this->conveniosModel) {
            $this->conveniosModel = new DetallesConveniosModel();
        }
        if (!$this->practicasModel) {
            $this->practicasModel = new AsignacionesPracticasModel();
        }
        if (!$this->institucionesModel) {
            $this->institucionesModel = new InstitucionesConveniosModel();
        }
        
        try {
            $data = [
                'totalEstudiantes' => $this->estudiantesModel->countAllResults(),
                'totalInstructores' => $this->instructoresModel->countAllResults(),
                'actividadesActivas' => $this->actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
                'conveniosVigentes' => $this->conveniosModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
                'practicasActivas' => $this->practicasModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults(),
                'institucionesActivas' => $this->institucionesModel->countAllResults()
            ];
            
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            log_message('error', 'Error en estadisticas: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor']);
        }
    }
    
    // Método temporal para debug de carreras
    public function debugCarreras()
    {
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        // Verificar que los modelos estén inicializados
        if (!$this->estudiantesModel) {
            $this->estudiantesModel = new EstudiantesModel();
        }
        if (!$this->carrerasModel) {
            $this->carrerasModel = new CarrerasModel();
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
            log_message('error', 'Error en debugCarreras: ' . $e->getMessage());
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
        
        // Verificar que el modelo esté inicializado
        if (!$this->actividadesModel) {
            $this->actividadesModel = new ActividadesEducacionModel();
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
