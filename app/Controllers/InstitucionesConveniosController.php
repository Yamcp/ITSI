<?php

namespace App\Controllers;

use App\Models\InstitucionesConveniosModel;
use App\Models\TiposInstitucionModel;

class InstitucionesConveniosController extends BaseController
{
    protected $institucionesModel;
    protected $tiposInstitucionModel;

    public function __construct()
    {
        $this->institucionesModel = new \App\Models\InstitucionesConveniosModel();
        $this->tiposInstitucionModel = new \App\Models\TiposInstitucionModel();
    }

    public function index()
    {
        $instituciones = $this->institucionesModel
            ->select('TAB_INSTITUCIONES_CONVENIOS.*, TAB_TIPOS_INSTITUCION.INSTITUCION as TIPO')
            ->join('TAB_TIPOS_INSTITUCION', 'TAB_INSTITUCIONES_CONVENIOS.ID_TIPO_INSTITUCION = TAB_TIPOS_INSTITUCION.ID_TIPO_INSTITUCION')
            ->findAll();

        $data = [
            'title' => 'Gestión de Instituciones',
            'instituciones' => $instituciones
        ];

        return view('instituciones/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Institución',
            'tipos_institucion' => $this->tiposInstitucionModel->findAll()
        ];

        return view('instituciones/create', $data);
    }

    public function store()
    {
        $rules = [
            'nombre' => 'required|max_length[200]',
            'ruc' => 'required|max_length[20]|is_unique[TAB_INSTITUCIONES_CONVENIOS.RUC]',
            'direccion' => 'required',
            'telefono' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]',
            'representante_legal' => 'required|max_length[150]',
            'contacto' => 'required|max_length[150]',
            'telefono_contacto' => 'required|max_length[20]',
            'email_contacto' => 'required|valid_email|max_length[100]',
            'area_interes' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'ID_TIPO_INSTITUCION' => $this->request->getPost('id_tipo_institucion'),
            'NOMBRE' => $this->request->getPost('nombre'),
            'RUC' => $this->request->getPost('ruc'),
            'DIRECCION' => $this->request->getPost('direccion'),
            'TELEFONO' => $this->request->getPost('telefono'),
            'EMAIL' => $this->request->getPost('email'),
            'REPRESENTANTE_LEGAL' => $this->request->getPost('representante_legal'),
            'CONTACTO' => $this->request->getPost('contacto'),
            'TELEFONO_CONTACTO' => $this->request->getPost('telefono_contacto'),
            'EMAIL_CONTACTO' => $this->request->getPost('email_contacto'),
            'AREA_INTERES' => $this->request->getPost('area_interes')
        ];

        if ($this->institucionesModel->insert($datos)) {
            return redirect()->to('/instituciones')->with('success', 'Institución creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la institución');
    }

    public function show($id)
    {
        $institucion = $this->institucionesModel
            ->select('TAB_INSTITUCIONES_CONVENIOS.*, TAB_TIPOS_INSTITUCION.INSTITUCION as TIPO')
            ->join('TAB_TIPOS_INSTITUCION', 'TAB_INSTITUCIONES_CONVENIOS.ID_TIPO_INSTITUCION = TAB_TIPOS_INSTITUCION.ID_TIPO_INSTITUCION')
            ->find($id);

        if (!$institucion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Institución no encontrada');
        }

        // Buscar convenios asociados
        $conveniosModel = new \App\Models\ConveniosModel();
        $convenios = $conveniosModel->where('ID_INSTITUCION_CONVENIO', $id)->findAll();

        $data = [
            'title' => 'Detalles de la Institución',
            'institucion' => $institucion,
            'convenios' => $convenios
        ];

        return view('instituciones/show', $data);
    }
}