<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\AsignacionesPracticasModel;
use App\Models\EstudiantesModel;
use App\Models\InstitucionesConveniosModel;
use App\Models\TiposPracticasModel;
use App\Models\EstadosPracticasModel;
use App\Models\CarrerasModel;
use App\Models\NotificacionesModel;
use App\Models\UsuariosModel;
use App\Models\PracticasPreprofesionalesModel;
use App\Models\ServiciosComunitariosModel;
use App\Libraries\EmailNotificaciones;

class PracticasAdminController extends BaseController
{
    protected $asignacionesModel;
    protected $estudiantesModel;
    protected $institucionesModel;
    protected $tiposPracticasModel;
    protected $estadosPracticasModel;
    protected $carrerasModel;
    protected $notificacionesModel;
    protected $usuariosModel;
    protected $practicasPreprofesionalesModel;
    protected $serviciosComunitariosModel;
    protected $emailNotificaciones;

    public function __construct()
    {
        $this->asignacionesModel = new AsignacionesPracticasModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->institucionesModel = new InstitucionesConveniosModel();
        $this->tiposPracticasModel = new TiposPracticasModel();
        $this->estadosPracticasModel = new EstadosPracticasModel();
        $this->carrerasModel = new CarrerasModel();
        $this->notificacionesModel = new NotificacionesModel();
        $this->usuariosModel = new UsuariosModel();
        $this->practicasPreprofesionalesModel = new PracticasPreprofesionalesModel();
        $this->serviciosComunitariosModel = new ServiciosComunitariosModel();
        $this->emailNotificaciones = new EmailNotificaciones();
    }

    public function index()
    {
        helper('tiempo');

        $estadisticas = [
            'totalPracticas' => 0,
            'practicasActivas' => 0,
            'practicasFinalizadas' => 0,
            'practicasPendientes' => 0
        ];
        $practicasPreprofesionales = [];
        $serviciosComunitarios = [];
        $seguimiento = ['actividadesRecientes' => []];

        try {
            $estadisticas = $this->obtenerEstadisticas();
            $practicasPreprofesionales = $this->practicasPreprofesionalesModel->getListaParaAdmin();
            $serviciosComunitarios = $this->serviciosComunitariosModel->getListaParaAdmin();
            $seguimiento = $this->obtenerSeguimientoGeneral();
        } catch (\Throwable $e) {
            log_message('error', 'PracticasAdminController::index - Error BD: ' . $e->getMessage());
        }

        $data = [
            'title' => 'Gestión de Prácticas',
            'estadisticas' => $estadisticas,
            'practicasPreprofesionales' => $practicasPreprofesionales,
            'serviciosComunitarios' => $serviciosComunitarios,
            'seguimiento' => $seguimiento
        ];

        return view('admin/practicas/practicas', $data);
    }

    /**
     * Helper para calcular tiempo transcurrido
     */
    public function tiempoTranscurrido($fecha)
    {
        $ahora = new \DateTime();
        $fechaReporte = new \DateTime($fecha);
        $diferencia = $ahora->diff($fechaReporte);

        if ($diferencia->days > 0) {
            return "Hace {$diferencia->days} día" . ($diferencia->days > 1 ? 's' : '');
        } elseif ($diferencia->h > 0) {
            return "Hace {$diferencia->h} hora" . ($diferencia->h > 1 ? 's' : '');
        } elseif ($diferencia->i > 0) {
            return "Hace {$diferencia->i} minuto" . ($diferencia->i > 1 ? 's' : '');
        } else {
            return "Hace unos segundos";
        }
    }

    /**
     * Obtener estadísticas generales de prácticas
     */
    private function obtenerEstadisticas()
    {
        $db = \Config\Database::connect();
        
        // Total de prácticas preprofesionales
        $totalPreprofesionales = $db->table('TAB_PRACTICAS_PREPROFESIONALES')->countAllResults();
        
        // Total de servicios comunitarios
        $totalServicios = $db->table('TAB_SERVICIO_COMUNITARIO')->countAllResults();
        
        // Prácticas activas (en progreso)
        $activasPreprofesionales = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ESTADO_PRACTICA', 'En Progreso')
            ->countAllResults();
            
        $activasServicios = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ESTADO_SERVICIO', 'En Progreso')
            ->countAllResults();
        
        // Prácticas finalizadas
        $finalizadasPreprofesionales = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ESTADO_PRACTICA', 'Completada')
            ->countAllResults();
            
        $finalizadasServicios = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ESTADO_SERVICIO', 'Completado')
            ->countAllResults();
        
        // Prácticas pendientes
        $pendientesPreprofesionales = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ESTADO_PRACTICA', 'Pendiente')
            ->countAllResults();
            
        $pendientesServicios = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ESTADO_SERVICIO', 'Pendiente')
            ->countAllResults();

        return [
            'totalPracticas' => $totalPreprofesionales + $totalServicios,
            'practicasActivas' => $activasPreprofesionales + $activasServicios,
            'practicasFinalizadas' => $finalizadasPreprofesionales + $finalizadasServicios,
            'practicasPendientes' => $pendientesPreprofesionales + $pendientesServicios
        ];
    }

    /**
     * Obtener prácticas preprofesionales con información relacionada
     */
    private function obtenerPracticasPreprofesionales()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
            ->select('
                pp.*,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                c.NOMBRE as CARRERA_NOMBRE,
                ic.NOMBRE as INSTITUCION_NOMBRE,
                ti.INSTITUCION as TIPO_INSTITUCION,
                CONCAT(dpi.NOMBRE, " ", dpi.APELLIDO) as INSTRUCTOR_NOMBRE
            ')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = pp.ID_INSTRUCTOR', 'left')
            ->join('TAB_DATOS_PERSONAS dpi', 'dpi.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
            ->orderBy('pp.ID_PRACTICA_PREPROFESIONAL', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener servicios comunitarios con información relacionada
     */
    private function obtenerServiciosComunitarios()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_SERVICIO_COMUNITARIO sc')
            ->select('
                sc.*,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                c.NOMBRE as CARRERA_NOMBRE,
                ic.NOMBRE as INSTITUCION_NOMBRE,
                ti.INSTITUCION as TIPO_INSTITUCION,
                CONCAT(dpi.NOMBRE, " ", dpi.APELLIDO) as INSTRUCTOR_NOMBRE
            ')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = sc.ID_INSTRUCTOR', 'left')
            ->join('TAB_DATOS_PERSONAS dpi', 'dpi.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
            ->orderBy('sc.ID_SERVICIO_COMUNITARIO', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener seguimiento general de todas las prácticas
     */
    private function obtenerSeguimientoGeneral()
    {
        $db = \Config\Database::connect();
        
        // Obtener últimas actividades de seguimiento
        $actividadesRecientes = $db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES spp')
            ->select('
                spp.FECHA_REPORTE,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                "Preprofesional" as TIPO_PRACTICA,
                spp.HORAS_CUMPLIDAS,
                pp.HORAS_PRACTICAS as HORAS_TOTALES,
                pp.ESTADO_PRACTICA
            ')
            ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'pp.ID_PRACTICA_PREPROFESIONAL = spp.ID_PRACTICA_PREPROFESIONAL')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->orderBy('spp.FECHA_REPORTE', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return [
            'actividadesRecientes' => $actividadesRecientes
        ];
    }

    /**
     * API: Obtener datos para el modal de nueva práctica
     */
    public function getDatosModal()
    {
        $estudiantes = $this->obtenerEstudiantes();
        $instituciones = $this->institucionesModel->getInstitucionesConTipo();
        $tiposPracticas = $this->tiposPracticasModel->findAll();
        $estadosPracticas = $this->estadosPracticasModel->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'estudiantes' => $estudiantes,
                'instituciones' => $instituciones,
                'tiposPracticas' => $tiposPracticas,
                'estadosPracticas' => $estadosPracticas
            ]
        ]);
    }

    /**
     * Obtener estudiantes con información completa
     */
    private function obtenerEstudiantes()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_ESTUDIANTES e')
            ->select('
                e.ID_ESTUDIANTE,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO,
                c.NOMBRE as CARRERA,
                e.SEMESTRE_ACTUAL
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->where('e.ID_TIPO_ESTADO', 1) // Solo estudiantes activos
            ->orderBy('dp.NOMBRE', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Crear nueva práctica
     */
    /** Horas requeridas por tipo: preprofesionales 240 h (una vez en la carrera), servicio comunitario 60 h (una vez). */
    private const HORAS_PRACTICAS_PREPROFESIONALES = 240;
    private const HORAS_SERVICIO_COMUNITARIO = 60;

    public function crearPractica()
    {
        $tipoPractica = (int) ($this->request->getPost('tipo_practica') ?: $this->request->getPost('tipo_practica_asignar'));
        $estudiante = (int) ($this->request->getPost('estudiante') ?: $this->request->getPost('estudiante_asignar'));
        $institucion = $this->request->getPost('institucion') ?: $this->request->getPost('institucion_asignar');
        $estado = $this->request->getPost('estado') ?: $this->request->getPost('estado_asignar');
        $fechaInicio = $this->request->getPost('fecha_inicio') ?: $this->request->getPost('fecha_inicio_asignar');
        $fechaFin = $this->request->getPost('fecha_fin') ?: $this->request->getPost('fecha_fin_asignar');
        $cronograma = $this->request->getPost('cronograma') ?: $this->request->getPost('cronograma_asignar');
        $descripcion = $this->request->getPost('descripcion') ?: $this->request->getPost('descripcion_asignar');

        // Regla de negocio: una sola vez por estudiante y horas fijas (240 h preprofesionales, 60 h servicio comunitario)
        $db = \Config\Database::connect();
        if ($tipoPractica == 2) { // Prácticas Preprofesionales
            $yaTiene = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
                ->where('ID_ESTUDIANTE', $estudiante)
                ->countAllResults();
            if ($yaTiene > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Este estudiante ya tiene asignadas prácticas preprofesionales. Solo se realizan una vez en la carrera (240 horas).'
                ]);
            }
            $horasTotal = self::HORAS_PRACTICAS_PREPROFESIONALES;
        } else { // Servicio Comunitario (tipo 1)
            $yaTiene = $db->table('TAB_SERVICIO_COMUNITARIO')
                ->where('ID_ESTUDIANTE', $estudiante)
                ->countAllResults();
            if ($yaTiene > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Este estudiante ya tiene asignado servicio comunitario. Solo se realiza una vez (60 horas).'
                ]);
            }
            $horasTotal = self::HORAS_SERVICIO_COMUNITARIO;
        }

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Crear asignación de práctica
            $asignacionData = [
                'ID_TIPO_PRACTICA' => $tipoPractica,
                'ID_USUARIO' => 1, // Usuario administrador por defecto
                'ID_ESTADO_PRACTICAS' => $estado,
                'ID_INSTITUCION_CONVENIO' => $institucion,
                'FECHA_INICIO' => $fechaInicio,
                'FECHA_FIN' => $fechaFin,
                'HORA_TOTAL' => $horasTotal,
                'DESCRIPCION' => $descripcion,
                'CRONOGRAMA' => $cronograma
            ];

            $asignacionId = $this->asignacionesModel->insert($asignacionData);

            if ($tipoPractica == 2) { // Prácticas Preprofesionales
                $practicaData = [
                    'ID_ASIGNACION_PRACTICA' => $asignacionId,
                    'ID_ESTUDIANTE' => $estudiante,
                    'ID_INSTRUCTOR' => 1, // Instructor por defecto
                    'ID_INSTITUCION_CONVENIO' => $institucion,
                    'HORAS_PRACTICAS' => $horasTotal,
                    'FECHA_INICIO' => $fechaInicio,
                    'FECHA_FIN' => $fechaFin,
                    'ESTADO_PRACTICA' => 'Pendiente'
                ];

                $db->table('TAB_PRACTICAS_PREPROFESIONALES')->insert($practicaData);
            } else { // Servicio Comunitario
                $servicioData = [
                    'ID_ASIGNACION_PRACTICA' => $asignacionId,
                    'ID_ESTUDIANTE' => $estudiante,
                    'ID_INSTRUCTOR' => 1, // Instructor por defecto
                    'ID_INSTITUCION_CONVENIO' => $institucion,
                    'HORAS_SERVICIO' => $horasTotal,
                    'FECHA_INICIO' => $fechaInicio,
                    'FECHA_FIN' => $fechaFin,
                    'ESTADO_SERVICIO' => 'Pendiente'
                ];

                $db->table('TAB_SERVICIO_COMUNITARIO')->insert($servicioData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la práctica'
                ]);
            }

            // Enviar notificaciones después de crear la práctica exitosamente
            $this->enviarNotificacionesPractica($estudiante, $institucion, $tipoPractica, $asignacionId, $fechaInicio, $fechaFin, $horasTotal, $descripcion);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Práctica creada exitosamente y notificaciones enviadas'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener detalle de práctica
     */
    public function getDetallePractica($id, $tipo)
    {
        $db = \Config\Database::connect();
        
        if ($tipo == 'preprofesional') {
            $builder = $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('
                    pp.*,
                    CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                    c.NOMBRE as CARRERA_NOMBRE,
                    ic.NOMBRE as INSTITUCION_NOMBRE,
                    CONCAT(dpi.NOMBRE, " ", dpi.APELLIDO) as INSTRUCTOR_NOMBRE
                ')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = pp.ID_INSTRUCTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dpi', 'dpi.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
                ->where('pp.ID_PRACTICA_PREPROFESIONAL', $id);
        } else {
            $builder = $db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('
                    sc.*,
                    CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                    c.NOMBRE as CARRERA_NOMBRE,
                    ic.NOMBRE as INSTITUCION_NOMBRE,
                    CONCAT(dpi.NOMBRE, " ", dpi.APELLIDO) as INSTRUCTOR_NOMBRE
                ')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = sc.ID_INSTRUCTOR', 'left')
                ->join('TAB_DATOS_PERSONAS dpi', 'dpi.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
                ->where('sc.ID_SERVICIO_COMUNITARIO', $id);
        }

        $practica = $builder->get()->getRowArray();

        if (!$practica) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Práctica no encontrada'
            ]);
        }

        // Obtener asistencias
        $asistencias = $this->obtenerAsistencias($id, $tipo);

        // Calcular progreso
        $horasCumplidas = array_sum(array_column($asistencias, 'horas_trabajadas'));
        $progreso = $practica['HORAS_PRACTICAS'] > 0 ? round(($horasCumplidas / $practica['HORAS_PRACTICAS']) * 100, 2) : 0;

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'practica' => $practica,
                'asistencias' => $asistencias,
                'progreso' => $progreso,
                'horasCumplidas' => $horasCumplidas
            ]
        ]);
    }

    /**
     * Obtener asistencias de una práctica
     */
    private function obtenerAsistencias($id, $tipo)
    {
        $db = \Config\Database::connect();
        
        if ($tipo == 'preprofesional') {
            $builder = $db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                ->where('ID_PRACTICA_PREPROFESIONAL', $id)
                ->orderBy('FECHA_ASISTENCIA', 'DESC');
        } else {
            $builder = $db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                ->where('ID_SERVICIO_COMUNITARIO', $id)
                ->orderBy('FECHA_ASISTENCIA', 'DESC');
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Registrar asistencia
     */
    public function registrarAsistencia()
    {
        $practicaId = $this->request->getPost('practica_id');
        $tipoPractica = $this->request->getPost('tipo_practica');
        $fechaAsistencia = $this->request->getPost('fecha_asistencia');
        $horaEntrada = $this->request->getPost('hora_entrada');
        $horaSalida = $this->request->getPost('hora_salida');
        $actividadesDia = $this->request->getPost('actividades_dia');
        $observaciones = $this->request->getPost('observaciones');

        try {
            $asistenciaData = [
                'ID_PRACTICA_PREPROFESIONAL' => $tipoPractica == 'preprofesional' ? $practicaId : null,
                'ID_SERVICIO_COMUNITARIO' => $tipoPractica == 'servicio' ? $practicaId : null,
                'FECHA_ASISTENCIA' => $fechaAsistencia,
                'HORA_ENTRADA' => $horaEntrada,
                'HORA_SALIDA' => $horaSalida,
                'ACTIVIDADES_DIA' => $actividadesDia,
                'OBSERVACIONES' => $observaciones,
                'FECHA_REGISTRO' => date('Y-m-d H:i:s')
            ];

            $db = \Config\Database::connect();
            if ($tipoPractica == 'preprofesional') {
                $db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')->insert($asistenciaData);
            } else {
                $db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')->insert($asistenciaData);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asistencia registrada exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar reporte de prácticas
     */
    public function generarReporte()
    {
        $tipo = $this->request->getGet('tipo') ?? 'general';
        
        $data = [
            'estadisticas' => $this->obtenerEstadisticas(),
            'practicasPreprofesionales' => $this->obtenerPracticasPreprofesionales(),
            'serviciosComunitarios' => $this->obtenerServiciosComunitarios(),
            'fechaGeneracion' => date('Y-m-d H:i:s')
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'message' => 'Reporte generado exitosamente'
        ]);
    }

    /**
     * API: Exportar datos de prácticas en diferentes formatos
     */
    public function exportarDatos($formato = 'json')
    {
        $practicasPreprofesionales = $this->obtenerPracticasPreprofesionales();
        $serviciosComunitarios = $this->obtenerServiciosComunitarios();
        $estadisticas = $this->obtenerEstadisticas();

        $datos = [
            'estadisticas' => $estadisticas,
            'practicasPreprofesionales' => $practicasPreprofesionales,
            'serviciosComunitarios' => $serviciosComunitarios,
            'fecha_exportacion' => date('Y-m-d H:i:s'),
            'total_registros' => count($practicasPreprofesionales) + count($serviciosComunitarios)
        ];

        switch (strtolower($formato)) {
            case 'pdf':
                return $this->exportarPDF($datos);
            case 'excel':
                return $this->exportarExcel($datos);
            default:
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $datos
                ]);
        }
    }

    /**
     * Exportar datos como PDF
     */
    private function exportarPDF($datos)
    {
        // Crear contenido HTML para el PDF
        $html = $this->generarHTMLParaPDF($datos);
        
        // Configurar headers para descarga de PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="practicas_' . date('Y-m-d') . '.pdf"');
        
        // En un entorno real, usarías una librería como TCPDF o DomPDF
        // Por ahora, devolvemos un mensaje indicando que se necesita implementar
        return $this->response->setBody($html);
    }

    /**
     * Exportar datos como Excel
     */
    private function exportarExcel($datos)
    {
        try {
            // Cargar helper de Excel
            helper('ExcelHelper');
            
            // Crear archivo Excel usando PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Configurar encabezados
            $sheet->setTitle('Prácticas');
            
            // Crear encabezado estándar con logo
            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet, 
                'REPORTE DE PRÁCTICAS', 
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'D1'
            );
            
            // Encabezados de columnas para prácticas preprofesionales
            $headersPreprofesionales = [
                'ID',
                'Estudiante',
                'Cédula',
                'Carrera',
                'Empresa',
                'Tutor Empresarial',
                'Fecha Inicio',
                'Fecha Fin',
                'Estado',
                'Horas'
            ];
            
            // Crear encabezados de columnas con estilo
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headersPreprofesionales, 5, 'A');
            
            // Llenar datos de prácticas preprofesionales
            $row = 6;
            foreach ($datos['practicasPreprofesionales'] as $practica) {
                $sheet->setCellValue('A' . $row, $practica['ID_PRACTICA_PREPROFESIONAL']);
                $sheet->setCellValue('B' . $row, $practica['NOMBRE'] . ' ' . $practica['APELLIDO']);
                $sheet->setCellValue('C' . $row, $practica['CEDULA']);
                $sheet->setCellValue('D' . $row, $practica['NOMBRE_CARRERA']);
                $sheet->setCellValue('E' . $row, $practica['NOMBRE_EMPRESA']);
                $sheet->setCellValue('F' . $row, $practica['TUTOR_EMPRESARIAL']);
                $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($practica['FECHA_INICIO'])));
                $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($practica['FECHA_FIN'])));
                $sheet->setCellValue('I' . $row, $practica['ESTADO']);
                $sheet->setCellValue('J' . $row, $practica['HORAS_PRACTICA']);
                $row++;
            }
            
            // Agregar separador y encabezados para servicios comunitarios
            $row += 2;
            $sheet->setCellValue('A' . $row, 'SERVICIOS COMUNITARIOS');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $row)->getFont()->getColor()->setRGB('1A3A8A');
            $row++;
            
            $headersComunitarios = [
                'ID',
                'Estudiante',
                'Cédula',
                'Carrera',
                'Proyecto',
                'Organización',
                'Fecha Inicio',
                'Fecha Fin',
                'Estado',
                'Horas'
            ];
            
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headersComunitarios, $row, 'A');
            $row++;
            
            // Llenar datos de servicios comunitarios
            foreach ($datos['serviciosComunitarios'] as $servicio) {
                $sheet->setCellValue('A' . $row, $servicio['ID_SERVICIO_COMUNITARIO']);
                $sheet->setCellValue('B' . $row, $servicio['NOMBRE'] . ' ' . $servicio['APELLIDO']);
                $sheet->setCellValue('C' . $row, $servicio['CEDULA']);
                $sheet->setCellValue('D' . $row, $servicio['NOMBRE_CARRERA']);
                $sheet->setCellValue('E' . $row, $servicio['NOMBRE_PROYECTO']);
                $sheet->setCellValue('F' . $row, $servicio['ORGANIZACION']);
                $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($servicio['FECHA_INICIO'])));
                $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($servicio['FECHA_FIN'])));
                $sheet->setCellValue('I' . $row, $servicio['ESTADO']);
                $sheet->setCellValue('J' . $row, $servicio['HORAS_SERVICIO']);
                $row++;
            }
            
            // Aplicar estilo a los datos
            \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:J' . ($row - 1));
            
            // Autoajustar columnas
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'J');
            
            // Configurar headers para descarga
            $filename = 'practicas_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);
            
            // Escribir archivo
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar Excel: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar HTML para PDF
     */
    private function generarHTMLParaPDF($datos)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Prácticas - ITSI</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #333; margin-bottom: 10px; }
                .header p { color: #666; }
                .section { margin-bottom: 25px; }
                .section h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-item { text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                .stat-number { font-size: 24px; font-weight: bold; color: #007bff; }
                .stat-label { color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="' . \App\Helpers\PdfHelper::getLogoUrl('Logo PDF.jpg') . '" alt="Logo ITSI" style="height: 60px; max-width: 200px; margin-bottom: 15px;">
                <h1>Reporte de Prácticas</h1>
                <p>Instituto Tecnológico Superior de Ibarra (ITSI)</p>
                <p>Fecha de exportación: ' . \App\Helpers\PdfHelper::getCurrentDateTime() . '</p>
            </div>

            <div class="section">
                <h2>Estadísticas Generales</h2>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">' . $datos['estadisticas']['totalPracticas'] . '</div>
                        <div class="stat-label">Total Prácticas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">' . $datos['estadisticas']['practicasActivas'] . '</div>
                        <div class="stat-label">Prácticas Activas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">' . $datos['estadisticas']['practicasFinalizadas'] . '</div>
                        <div class="stat-label">Finalizadas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">' . $datos['estadisticas']['practicasPendientes'] . '</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Prácticas Preprofesionales</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Institución</th>
                            <th>Período</th>
                            <th>Horas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($datos['practicasPreprofesionales'] as $practica) {
            $html .= '
                        <tr>
                            <td>' . $practica['ID_PRACTICA_PREPROFESIONAL'] . '</td>
                            <td>' . $practica['ESTUDIANTE_NOMBRE'] . '</td>
                            <td>' . $practica['INSTITUCION_NOMBRE'] . '</td>
                            <td>' . date('M Y', strtotime($practica['FECHA_INICIO'])) . ' - ' . date('M Y', strtotime($practica['FECHA_FIN'])) . '</td>
                            <td>' . $practica['HORAS_PRACTICAS'] . 'h</td>
                            <td>' . $practica['ESTADO_PRACTICA'] . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>Servicios Comunitarios</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Institución</th>
                            <th>Período</th>
                            <th>Horas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($datos['serviciosComunitarios'] as $servicio) {
            $html .= '
                        <tr>
                            <td>SC' . $servicio['ID_SERVICIO_COMUNITARIO'] . '</td>
                            <td>' . $servicio['ESTUDIANTE_NOMBRE'] . '</td>
                            <td>' . $servicio['INSTITUCION_NOMBRE'] . '</td>
                            <td>' . date('M Y', strtotime($servicio['FECHA_INICIO'])) . ' - ' . date('M Y', strtotime($servicio['FECHA_FIN'])) . '</td>
                            <td>' . $servicio['HORAS_SERVICIO'] . 'h</td>
                            <td>' . $servicio['ESTADO_SERVICIO'] . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="footer">
                <p>Reporte generado automáticamente por el Sistema de Gestión de Prácticas ITSI</p>
                <p>Total de registros: ' . $datos['total_registros'] . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Generar CSV para Excel
     */
    private function generarCSV($datos)
    {
        $csv = "REPORTE DE PRÁCTICAS - ITSI\n";
        $csv .= "Fecha de exportación: " . $datos['fecha_exportacion'] . "\n";
        $csv .= "Total de registros: " . $datos['total_registros'] . "\n\n";

        // Estadísticas
        $csv .= "ESTADÍSTICAS GENERALES\n";
        $csv .= "Total Prácticas," . $datos['estadisticas']['totalPracticas'] . "\n";
        $csv .= "Prácticas Activas," . $datos['estadisticas']['practicasActivas'] . "\n";
        $csv .= "Finalizadas," . $datos['estadisticas']['practicasFinalizadas'] . "\n";
        $csv .= "Pendientes," . $datos['estadisticas']['practicasPendientes'] . "\n\n";

        // Prácticas Preprofesionales
        $csv .= "PRÁCTICAS PREPROFESIONALES\n";
        $csv .= "ID,Estudiante,Institución,Período,Horas,Estado\n";
        
        foreach ($datos['practicasPreprofesionales'] as $practica) {
            $periodo = date('M Y', strtotime($practica['FECHA_INICIO'])) . ' - ' . date('M Y', strtotime($practica['FECHA_FIN']));
            $csv .= $practica['ID_PRACTICA_PREPROFESIONAL'] . ',"' . $practica['ESTUDIANTE_NOMBRE'] . '","' . $practica['INSTITUCION_NOMBRE'] . '","' . $periodo . '",' . $practica['HORAS_PRACTICAS'] . 'h,"' . $practica['ESTADO_PRACTICA'] . '"' . "\n";
        }

        $csv .= "\n";

        // Servicios Comunitarios
        $csv .= "SERVICIOS COMUNITARIOS\n";
        $csv .= "ID,Estudiante,Institución,Período,Horas,Estado\n";
        
        foreach ($datos['serviciosComunitarios'] as $servicio) {
            $periodo = date('M Y', strtotime($servicio['FECHA_INICIO'])) . ' - ' . date('M Y', strtotime($servicio['FECHA_FIN']));
            $csv .= 'SC' . $servicio['ID_SERVICIO_COMUNITARIO'] . ',"' . $servicio['ESTUDIANTE_NOMBRE'] . '","' . $servicio['INSTITUCION_NOMBRE'] . '","' . $periodo . '",' . $servicio['HORAS_SERVICIO'] . 'h,"' . $servicio['ESTADO_SERVICIO'] . '"' . "\n";
        }

        return $csv;
    }

    /**
     * Vista de reportes de prácticas
     */
    public function reportes()
    {
        // Obtener filtros de la URL
        $filtros = [
            'tipo_practica' => $this->request->getGet('tipo_practica'),
            'estado' => $this->request->getGet('estado'),
            'institucion' => $this->request->getGet('institucion'),
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin' => $this->request->getGet('fecha_fin'),
            'carrera' => $this->request->getGet('carrera')
        ];

        // Obtener datos filtrados
        $practicasPreprofesionales = $this->obtenerPracticasPreprofesionalesFiltradas($filtros);
        $serviciosComunitarios = $this->obtenerServiciosComunitariosFiltrados($filtros);
        $estadisticas = $this->obtenerEstadisticas();
        $instituciones = $this->institucionesModel->getInstitucionesConTipo();

        $data = [
            'practicasPreprofesionales' => $practicasPreprofesionales,
            'serviciosComunitarios' => $serviciosComunitarios,
            'estadisticas' => $estadisticas,
            'instituciones' => $instituciones,
            'filtros' => $filtros
        ];

        return view('admin/practicas/reportes', $data);
    }

    /**
     * Obtener prácticas preprofesionales filtradas
     */
    private function obtenerPracticasPreprofesionalesFiltradas($filtros)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
            ->select('
                pp.ID_PRACTICA_PREPROFESIONAL,
                pp.ESTADO_PRACTICA,
                pp.HORAS_PRACTICAS,
                pp.FECHA_INICIO,
                pp.FECHA_FIN,
                pp.PROYECTO_ESPECIFICO,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                c.NOMBRE as CARRERA_NOMBRE,
                ic.NOMBRE as INSTITUCION_NOMBRE,
                ti.TIPO_INSTITUCION
            ')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->orderBy('pp.FECHA_INICIO', 'DESC');

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $builder->where('pp.ESTADO_PRACTICA', $filtros['estado']);
        }

        if (!empty($filtros['institucion'])) {
            $builder->where('pp.ID_INSTITUCION_CONVENIO', $filtros['institucion']);
        }

        if (!empty($filtros['fecha_inicio'])) {
            $builder->where('pp.FECHA_INICIO >=', $filtros['fecha_inicio']);
        }

        if (!empty($filtros['fecha_fin'])) {
            $builder->where('pp.FECHA_FIN <=', $filtros['fecha_fin']);
        }

        if (!empty($filtros['carrera'])) {
            $builder->like('c.NOMBRE', $filtros['carrera']);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener servicios comunitarios filtrados
     */
    private function obtenerServiciosComunitariosFiltrados($filtros)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_SERVICIO_COMUNITARIO sc')
            ->select('
                sc.ID_SERVICIO_COMUNITARIO,
                sc.ESTADO_SERVICIO,
                sc.HORAS_SERVICIO,
                sc.FECHA_INICIO,
                sc.FECHA_FIN,
                sc.PROYECTO_SOCIAL,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as ESTUDIANTE_NOMBRE,
                c.NOMBRE as CARRERA_NOMBRE,
                ic.NOMBRE as INSTITUCION_NOMBRE,
                ti.TIPO_INSTITUCION
            ')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->orderBy('sc.FECHA_INICIO', 'DESC');

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $builder->where('sc.ESTADO_SERVICIO', $filtros['estado']);
        }

        if (!empty($filtros['institucion'])) {
            $builder->where('sc.ID_INSTITUCION_CONVENIO', $filtros['institucion']);
        }

        if (!empty($filtros['fecha_inicio'])) {
            $builder->where('sc.FECHA_INICIO >=', $filtros['fecha_inicio']);
        }

        if (!empty($filtros['fecha_fin'])) {
            $builder->where('sc.FECHA_FIN <=', $filtros['fecha_fin']);
        }

        if (!empty($filtros['carrera'])) {
            $builder->like('c.NOMBRE', $filtros['carrera']);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Enviar notificaciones de nueva práctica asignada
     */
    private function enviarNotificacionesPractica($idEstudiante, $idInstitucion, $tipoPractica, $asignacionId, $fechaInicio, $fechaFin, $horasTotal, $descripcion)
    {
        try {
            // Obtener información del estudiante
            $estudiante = $this->obtenerInformacionEstudiante($idEstudiante);
            if (!$estudiante) {
                log_message('error', 'No se pudo obtener información del estudiante para notificación');
                return false;
            }

            // Obtener información de la institución
            $institucion = $this->obtenerInformacionInstitucion($idInstitucion);
            if (!$institucion) {
                log_message('error', 'No se pudo obtener información de la institución para notificación');
                return false;
            }

            // Obtener tutor asignado (por defecto el instructor con ID 1, pero se puede mejorar)
            $tutor = $this->obtenerInformacionTutor(1); // Se puede mejorar para asignar tutores automáticamente

            // Preparar datos para las notificaciones
            $datosPractica = [
                'id_practica' => $asignacionId,
                'tipo' => $tipoPractica == 2 ? 'preprofesional' : 'servicio',
                'estudiante' => $estudiante['nombre_completo'],
                'institucion' => $institucion['nombre'],
                'tutor' => $tutor['nombre_completo'],
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'horas' => $horasTotal,
                'descripcion' => $descripcion
            ];

            // Obtener ID de usuario del estudiante
            $idUsuarioEstudiante = $this->obtenerIdUsuarioEstudiante($idEstudiante);
            if (!$idUsuarioEstudiante) {
                log_message('error', 'No se pudo obtener ID de usuario del estudiante');
                return false;
            }

            // Obtener ID de usuario del tutor
            $idUsuarioTutor = $this->obtenerIdUsuarioTutor(1); // Se puede mejorar
            if (!$idUsuarioTutor) {
                log_message('error', 'No se pudo obtener ID de usuario del tutor');
                return false;
            }

            // Crear notificaciones
            $resultado = $this->notificacionesModel->crearNotificacionAsignacionPractica(
                $idUsuarioEstudiante,
                $idUsuarioTutor,
                $datosPractica
            );

            if ($resultado) {
                log_message('info', 'Notificaciones de práctica enviadas exitosamente');
                
                // Enviar email de notificación
                $this->enviarEmailNotificacion($estudiante, $tutor, $datosPractica);
            } else {
                log_message('error', 'Error al crear notificaciones de práctica');
            }

            return $resultado;

        } catch (\Exception $e) {
            log_message('error', 'Error en enviarNotificacionesPractica: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener información del estudiante
     */
    private function obtenerInformacionEstudiante($idEstudiante)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_ESTUDIANTES e')
            ->select('
                e.ID_ESTUDIANTE,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as nombre_completo,
                dp.EMAIL,
                c.NOMBRE as carrera
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->where('e.ID_ESTUDIANTE', $idEstudiante);

        return $builder->get()->getRowArray();
    }

    /**
     * Obtener información de la institución
     */
    private function obtenerInformacionInstitucion($idInstitucion)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_INSTITUCIONES_CONVENIOS ic')
            ->select('
                ic.ID_INSTITUCION_CONVENIO,
                ic.NOMBRE as nombre,
                ti.INSTITUCION as tipo
            ')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->where('ic.ID_INSTITUCION_CONVENIO', $idInstitucion);

        return $builder->get()->getRowArray();
    }

    /**
     * Obtener información del tutor
     */
    private function obtenerInformacionTutor($idInstructor)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_INSTRUCTORES i')
            ->select('
                i.ID_INSTRUCTOR,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as nombre_completo,
                dp.EMAIL
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->where('i.ID_INSTRUCTOR', $idInstructor);

        return $builder->get()->getRowArray();
    }

    /**
     * Obtener ID de usuario del estudiante
     */
    private function obtenerIdUsuarioEstudiante($idEstudiante)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_ESTUDIANTES e')
            ->select('u.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
            ->where('e.ID_ESTUDIANTE', $idEstudiante);

        $result = $builder->get()->getRowArray();
        return $result ? $result['ID_USUARIO'] : null;
    }

    /**
     * Obtener ID de usuario del tutor
     */
    private function obtenerIdUsuarioTutor($idInstructor)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_INSTRUCTORES i')
            ->select('u.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
            ->where('i.ID_INSTRUCTOR', $idInstructor);

        $result = $builder->get()->getRowArray();
        return $result ? $result['ID_USUARIO'] : null;
    }

    /**
     * Enviar email de notificación
     */
    private function enviarEmailNotificacion($estudiante, $tutor, $datosPractica)
    {
        try {
            // Enviar emails usando la librería de notificaciones
            $resultado = $this->emailNotificaciones->enviarNotificacionAsignacionPractica(
                $estudiante,
                $tutor,
                $datosPractica
            );
            
            if ($resultado) {
                log_message('info', "Emails de notificación enviados exitosamente a: {$estudiante['email']} y {$tutor['email']}");
            } else {
                log_message('warning', "Error enviando emails de notificación a: {$estudiante['email']} y {$tutor['email']}");
            }
            
            return $resultado;
        } catch (\Exception $e) {
            log_message('error', 'Error enviando email de notificación: ' . $e->getMessage());
            return false;
        }
    }
}