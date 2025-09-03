<?php

namespace App\Controllers\estudiante;

use App\Models\InstitucionesConveniosModel;
use App\Models\DetallesConveniosModel;
use App\Models\TiposInstitucionesModel;
use App\Controllers\BaseController;

class InstitucionesConveniosController extends BaseController
{
    protected $institucionesModel;
    protected $conveniosModel;
    protected $tiposInstitucionesModel;

    public function __construct()
    {
        $this->institucionesModel = new \App\Models\InstitucionesConveniosModel();
        $this->conveniosModel = new \App\Models\DetallesConveniosModel();
        $this->tiposInstitucionesModel = new \App\Models\TiposInstitucionesModel();
    }

    public function index()
    {
        // Obtener instituciones con sus tipos
        $instituciones = $this->institucionesModel
            ->select('TAB_INSTITUCIONES_CONVENIOS.*, TAB_TIPOS_INSTITUCION.INSTITUCION as TIPO')
            ->join('TAB_TIPOS_INSTITUCION', 'TAB_INSTITUCIONES_CONVENIOS.ID_TIPO_INSTITUCION = TAB_TIPOS_INSTITUCION.ID_TIPO_INSTITUCION')
            ->findAll();

        // Obtener convenios activos
        $convenios = $this->conveniosModel->getConveniosCompletos();

        $data = [
            'title' => 'Instituciones y Convenios',
            'instituciones' => $instituciones,
            'convenios' => $convenios
        ];

        return view('estudiante/convenios/index', $data);
    }
}
