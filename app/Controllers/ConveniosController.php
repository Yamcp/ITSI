<?php

namespace App\Controllers;

use App\Models\ConveniosModel;
use App\Models\InstitucionesConveniosModel;
use App\Models\TiposConveniosModel;

class ConveniosController extends BaseController
{
    protected $conveniosModel;
    protected $institucionesModel;
    protected $tiposConveniosModel;

    public function __construct()
    {
        $this->conveniosModel = new \App\Models\ConveniosModel();
        $this->institucionesModel = new \App\Models\InstitucionesConveniosModel();
        $this->tiposConveniosModel = new \App\Models\TiposConveniosModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Convenios',
            'convenios' => $this->conveniosModel->getConvenioCompleto()
        ];

        return view('convenios/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nuevo Convenio',
            'instituciones' => $this->institucionesModel->findAll(),
            'tipos_convenios' => $this->tiposConveniosModel->findAll()
        ];

        return view('convenios/create', $data);
    }

    public function vencimientos()
    {
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        
        $conveniosProximos = $this->conveniosModel
            ->where('FECHA_FIN <=', $fechaLimite)
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->getConvenioCompleto();

        $data = [
            'title' => 'Convenios por Vencer',
            'convenios' => $conveniosProximos
        ];

        return view('convenios/vencimientos', $data);
    }
}