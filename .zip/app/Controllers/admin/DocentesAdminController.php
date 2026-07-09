<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocentesTutoresModel;

class DocentesAdminController extends BaseController
{
    protected $docentesModel;

    public function __construct()
    {
        $this->docentesModel = new DocentesTutoresModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Docentes Tutores',
            'docentes' => $this->docentesModel->getDocentesConDatos(),
            'layout' => $this->getLayoutForRole()
        ];

        return view('admin/docentes/docentes', $data);
    }
}
