<?php

namespace App\Controllers;

use App\Models\InstructoresModel;
use App\Models\ActividadesEducacionModel;
use App\Models\TipoInstructoresModel;

class InstructoresController extends BaseController
{
    protected $instructoresModel;
    protected $actividadesModel;
    protected $tipoInstructoresModel;

    public function __construct()
    {
        $this->instructoresModel = new \App\Models\InstructoresModel();
        $this->actividadesModel = new \App\Models\ActividadesEducacionModel();
        $this->tipoInstructoresModel = new \App\Models\TiposInstructoresModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Instructores',
            'instructores' => $this->instructoresModel->getInstructorCompleto()
        ];

        return view('instructores/index', $data);
    }

    public function actividades($idInstructor)
    {
        $instructor = $this->instructoresModel->getInstructorCompleto($idInstructor);
        
        if (!$instructor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Instructor no encontrado');
        }

        $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $idInstructor)->findAll();

        $data = [
            'title' => 'Actividades del Instructor',
            'instructor' => $instructor,
            'actividades' => $actividades
        ];

        return view('instructores/actividades', $data);
    }
}
