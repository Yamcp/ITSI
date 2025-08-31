<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\AsignacionesPracticasModel;
use App\Models\ActividadesEducacionModel;
use App\Models\EstudiantesModel;
use CodeIgniter\Database\BaseConnection;

class DashboardEstudianteController extends BaseController
{
    protected $asignacionesModel;
    protected $actividadesModel;
    protected $estudiantesModel;
    protected $db;

    public function __construct()
    {
        $this->asignacionesModel = new AsignacionesPracticasModel();
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

        // Obtener datos del estudiante actual
        $idUsuario = session()->get('id_usuario');
        
        // Buscar estudiante por ID_DATO_PERSONA del usuario
        $usuario = $this->db->table('TAB_USUARIOS')->where('ID_USUARIO', $idUsuario)->get()->getRowArray();
        $estudiante = null;
        
        if ($usuario) {
            $estudiante = $this->db->table('TAB_ESTUDIANTES')
                ->where('ID_DATO_PERSONA', $usuario['ID_DATO_PERSONA'])
                ->get()->getRowArray();
        }
        
        // Obtener estadísticas
        $data = [
            'title' => 'Dashboard Estudiante',
            'estudiante' => $estudiante,
            'total_practicas' => $this->asignacionesModel->where('ID_USUARIO', $idUsuario)->countAllResults(),
            'practicas_activas' => $this->asignacionesModel->where('ID_USUARIO', $idUsuario)->where('ID_ESTADO_PRACTICAS', '1')->countAllResults(),
            'total_actividades' => $this->actividadesModel->countAllResults(),
        ];

        return view('estudiante/dashboard/dashboardEstudiante', $data);
    }
}