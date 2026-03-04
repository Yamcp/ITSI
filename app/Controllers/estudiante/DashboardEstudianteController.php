<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\ActividadesEducacionModel;
use App\Models\EstudiantesModel;
use CodeIgniter\Database\BaseConnection;

class DashboardEstudianteController extends BaseController
{
    protected $actividadesModel;
    protected $estudiantesModel;
    protected $db;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar que el usuario esté logueado y sea estudiante
        if (!session()->get('logged_in') || session()->get('rol') != 3) {
            return redirect()->to('/');
        }

        $idUsuario = session()->get('id_usuario');

        // Buscar estudiante: TAB_ESTUDIANTES se relaciona con usuario por ID_DATO_PERSONA (vía TAB_USUARIOS)
        $estudiante = null;
        $idDatoPersona = null;
        $usuario = $this->db->table('TAB_USUARIOS')->where('ID_USUARIO', $idUsuario)->get()->getRowArray();
        if ($usuario) {
            $idDatoPersona = $usuario['ID_DATO_PERSONA'] ?? null;
            if ($idDatoPersona) {
                $estudiante = $this->db->table('TAB_ESTUDIANTES')
                    ->where('ID_DATO_PERSONA', $idDatoPersona)
                    ->get()->getRowArray();
            }
        }

        // Sin ID_DATO_PERSONA no podemos filtrar prácticas; dejar estadísticas en 0
        if (!$idDatoPersona) {
            $data = [
                'title' => 'Dashboard Estudiante - Prácticas Preprofesionales y Servicio Comunitario',
                'estudiante' => $estudiante,
                'total_practicas' => 0,
                'practicas_activas' => 0,
                'total_actividades' => $this->actividadesModel->countAllResults(),
                'total_preprofesionales' => 0,
                'preprofesionales_activas' => 0,
                'total_servicio_comunitario' => 0,
                'servicio_comunitario_activos' => 0,
                'practicas_preprofesionales' => [],
                'servicios_comunitarios' => [],
            ];
            return view('estudiante/dashboard/dashboardEstudiante', $data);
        }

        // Tablas del proyecto: TAB_ESTUDIANTES, TAB_INSTITUCIONES_CONVENIOS (o instituciones_convenios)
        $tblInstituciones = $this->db->tableExists('TAB_INSTITUCIONES_CONVENIOS') ? 'TAB_INSTITUCIONES_CONVENIOS' : 'instituciones_convenios';

        // Estadísticas: Prácticas preprofesionales (join por TAB_ESTUDIANTES e ID_DATO_PERSONA)
        $totalPreprofesionales = 0;
        $preprofesionalesActivas = 0;
        $practicasPreprofesionales = [];
        try {
            $totalPreprofesionales = $this->db->table('practicas_preprofesionales pp')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->countAllResults();

            $preprofesionalesActivas = $this->db->table('practicas_preprofesionales pp')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->countAllResults();

            $practicasPreprofesionales = $this->db->table('practicas_preprofesionales pp')
                ->select('pp.*, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join($tblInstituciones . ' ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO', 'left')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->orderBy('pp.FECHA_INICIO', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard preprofesionales: ' . $e->getMessage());
        }

        // Estadísticas: Prácticas de servicio comunitario
        $totalServicioComunitario = 0;
        $servicioComunitarioActivos = 0;
        $serviciosComunitarios = [];
        try {
            $totalServicioComunitario = $this->db->table('servicios_comunitarios sc')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->countAllResults();
            $servicioComunitarioActivos = $this->db->table('servicios_comunitarios sc')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                ->countAllResults();
            $serviciosComunitarios = $this->db->table('servicios_comunitarios sc')
                ->select('sc.*, ic.NOMBRE as INSTITUCION_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join($tblInstituciones . ' ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO', 'left')
                ->where('e.ID_DATO_PERSONA', $idDatoPersona)
                ->orderBy('sc.FECHA_INICIO', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard servicio comunitario: ' . $e->getMessage());
        }

        $totalPracticas = $totalPreprofesionales + $totalServicioComunitario;
        $practicasActivas = $preprofesionalesActivas + $servicioComunitarioActivos;

        $data = [
            'title' => 'Dashboard Estudiante - Prácticas Preprofesionales y Servicio Comunitario',
            'estudiante' => $estudiante,
            'total_practicas' => $totalPracticas,
            'practicas_activas' => $practicasActivas,
            'total_actividades' => $this->actividadesModel->countAllResults(),
            'total_preprofesionales' => $totalPreprofesionales,
            'preprofesionales_activas' => $preprofesionalesActivas,
            'total_servicio_comunitario' => $totalServicioComunitario,
            'servicio_comunitario_activos' => $servicioComunitarioActivos,
            'practicas_preprofesionales' => $practicasPreprofesionales,
            'servicios_comunitarios' => $serviciosComunitarios,
        ];

        return view('estudiante/dashboard/dashboardEstudiante', $data);
    }
}