<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\ActividadesEducacionModel;
use App\Models\EstudiantesModel;
use App\Models\InstructoresModel;
use App\Models\DocentesTutoresModel;
use App\Models\NotificacionesModel;
use CodeIgniter\Database\BaseConnection;

class DashboardDocenteController extends BaseController
{
    protected $actividadesModel;
    protected $estudiantesModel;
    protected $instructoresModel;
    protected $docentesTutoresModel;
    protected $db;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->instructoresModel = new InstructoresModel();
        $this->docentesTutoresModel = new DocentesTutoresModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar que el usuario esté logueado y sea docente
        if (!session()->get('logged_in') || session()->get('rol') != 3) {
            return redirect()->to('/');
        }

        // Obtener datos del docente actual desde TAB_DOCENTES_TUTORES
        $idUsuario = session()->get('id_usuario');
        $docente = $this->docentesTutoresModel->getDocentePorUsuario($idUsuario);

        // Estudiantes asignados a este docente (prácticas preprofesionales + servicio comunitario)
        $estudiantesAsignados = 0;
        $estudiantesPp = 0;
        $estudiantesSc = 0;
        $instructor = null;
        if ($docente) {
            $idDocente = (int) $docente['ID_DOCENTE_TUTOR'];
            // Contar registros de asignación (mismo criterio que el módulo de prácticas)
            $estudiantesPp = (int) $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
                ->where('ID_DOCENTE_TUTOR', $idDocente)
                ->countAllResults();
            $estudiantesSc = (int) $this->db->table('TAB_SERVICIO_COMUNITARIO')
                ->where('ID_DOCENTE_TUTOR', $idDocente)
                ->countAllResults();
            $estudiantesAsignados = $estudiantesPp + $estudiantesSc;

            // Para actividades de educación, buscar si el docente también está en TAB_INSTRUCTORES
            $usuario = $this->db->table('TAB_USUARIOS')->where('ID_USUARIO', $idUsuario)->get()->getRowArray();
            if ($usuario) {
                $instructor = $this->db->table('TAB_INSTRUCTORES')
                    ->where('ID_DATO_PERSONA', $usuario['ID_DATO_PERSONA'])
                    ->get()->getRowArray();
            }
        }

        $notifTutorNoLeidas = 0;
        try {
            $notifTutorNoLeidas = (new NotificacionesModel())->contarNoLeidasPorTipo((int) $idUsuario, 'tutoria_asignada');
        } catch (\Throwable $e) {
            log_message('error', 'DashboardDocente - notif tutoría: ' . $e->getMessage());
        }
        
        // Obtener estadísticas
        $data = [
            'title' => 'Dashboard Docente',
            'instructor' => $instructor,
            'total_actividades' => $instructor ? $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->countAllResults() : 0,
            'actividades_activas' => $instructor
                ? $this->actividadesModel
                    ->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])
                    ->where('FECHA_FIN >=', date('Y-m-d'))
                    ->countAllResults()
                : 0,
            'total_estudiantes' => $estudiantesAsignados,
            'estudiantes_pp' => $estudiantesPp,
            'estudiantes_sc' => $estudiantesSc,
            'notif_tutor_no_leidas' => $notifTutorNoLeidas,
        ];

        return view('docente/dashboard/dashboardDocente', $data);
    }
}