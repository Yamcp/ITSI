<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\ActividadesEducacionModel;
use App\Models\EstudiantesModel;
use App\Models\InstructoresModel;
use CodeIgniter\Database\BaseConnection;

class DashboardDocenteController extends BaseController
{
    protected $actividadesModel;
    protected $estudiantesModel;
    protected $instructoresModel;
    protected $db;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->instructoresModel = new InstructoresModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar que el usuario esté logueado y sea docente
        if (!session()->get('logged_in') || session()->get('rol') != 2) {
            return redirect()->to('/');
        }

        // Obtener datos del docente actual
        $idUsuario = session()->get('id_usuario');
        
        // Buscar instructor por ID_DATO_PERSONA del usuario
        $usuario = $this->db->table('TAB_USUARIOS')->where('ID_USUARIO', $idUsuario)->get()->getRowArray();
        $instructor = null;
        
        if ($usuario) {
            $instructor = $this->db->table('TAB_INSTRUCTORES')
                ->where('ID_DATO_PERSONA', $usuario['ID_DATO_PERSONA'])
                ->get()->getRowArray();
        }

        // Estudiantes asignados a este docente (prácticas preprofesionales + servicio comunitario)
        $estudiantesAsignados = 0;
        if ($instructor) {
            $idInstructor = (int) $instructor['ID_INSTRUCTOR'];
            $idsPp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
                ->select('ID_ESTUDIANTE')
                ->where('ID_INSTRUCTOR', $idInstructor)
                ->get()
                ->getResultArray();
            $idsSc = $this->db->table('TAB_SERVICIO_COMUNITARIO')
                ->select('ID_ESTUDIANTE')
                ->where('ID_INSTRUCTOR', $idInstructor)
                ->get()
                ->getResultArray();
            $todosIds = array_merge(
                array_column($idsPp, 'ID_ESTUDIANTE'),
                array_column($idsSc, 'ID_ESTUDIANTE')
            );
            $estudiantesAsignados = count(array_unique(array_filter($todosIds)));
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
        ];

        return view('docente/dashboard/dashboardDocente', $data);
    }
}