<?php

namespace App\Controllers\admin;

use App\Models\ActividadesEducacionModel;
use App\Models\InstructoresModel;
use App\Models\LineasInvestigacionModel;
use App\Models\TiposModalidadesModel;
use App\Models\TiposActividadesModel;
use App\Controllers\BaseController;

class ActividadesEducacionController extends BaseController
{
    protected $actividadesModel;
    protected $instructoresModel;
    protected $lineasInvestigacionModel;
    protected $tiposModalidadesModel;
    protected $tiposActividadesModel;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->instructoresModel = new InstructoresModel();
        $this->lineasInvestigacionModel = new LineasInvestigacionModel(); // Corregido: sin tilde
        $this->tiposModalidadesModel = new TiposModalidadesModel();
        $this->tiposActividadesModel = new TiposActividadesModel();
    }

    public function index()
    {
        $actividades = $this->actividadesModel
            ->select('TAB_ACTIVIDADES_EDUCACION.*, TAB_INSTRUCTORES.*, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO, TAB_TIPOS_MODALIDADES.MODALIDAD, TAB_TIPOS_ACTIVIDADES.ACTIVIDAD')
            ->join('TAB_INSTRUCTORES', 'TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR = TAB_INSTRUCTORES.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS', 'TAB_INSTRUCTORES.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_TIPOS_MODALIDADES', 'TAB_ACTIVIDADES_EDUCACION.ID_TIPO_MODALIDAD = TAB_TIPOS_MODALIDADES.ID_TIPO_MODALIDAD')
            ->join('TAB_TIPOS_ACTIVIDADES', 'TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD = TAB_TIPOS_ACTIVIDADES.ID_TIPO_ACTIVIDAD')
            ->findAll();

        $data = [
            'title' => 'Gestión de Actividades Educativas',
            'actividades' => $actividades
        ];

        return view('admin/educacion/actividades_educacion_views', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Actividad Educativa',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'lineas_investigacion' => $this->lineasInvestigacionModel->findAll(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('admin/educacion/create', $data);
    }

    public function store()
    {
        $rules = [
            'nombre_actividad' => 'required|max_length[200]',
            'descripcion' => 'required',
            'objetivos' => 'required',
            'duracion_horas' => 'required|integer',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'lugar' => 'required|max_length[150]',
            'horario' => 'required|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'ID_LINEA_INVESTIGACION' => $this->request->getPost('id_linea_investigacion'),
            'ID_INSTRUCTOR' => $this->request->getPost('id_instructor'),
            'ID_TIPO_MODALIDAD' => $this->request->getPost('id_tipo_modalidad'),
            'ID_TIPO_ACTIVIDAD' => $this->request->getPost('id_tipo_actividad'),
            'ID_USUARIO' => session()->get('usuario_id'), // Corregido: session() en vez de $this->session
            'NOMBRE_ACTIVIDAD' => $this->request->getPost('nombre_actividad'),
            'DESCRIPCION' => $this->request->getPost('descripcion'),
            'OBJETIVOS' => $this->request->getPost('objetivos'),
            'DURACION_HORAS' => $this->request->getPost('duracion_horas'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'LUGAR' => $this->request->getPost('lugar'),
            'HORARIO' => $this->request->getPost('horario'),
            'INCLUYE_CERTIFICADO' => $this->request->getPost('incluye_certificado') ? 1 : 0,
            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->insert($datos)) {
            return redirect()->to('/actividades')->with('success', 'Actividad creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la actividad');
    }

    public function show($id)
    {
        $actividad = $this->actividadesModel
            ->select('TAB_ACTIVIDADES_EDUCACION.*, TAB_INSTRUCTORES.*, TAB_DATOS_PERSONAS.NOMBRE as NOMBRE_INSTRUCTOR, TAB_DATOS_PERSONAS.APELLIDO as APELLIDO_INSTRUCTOR, TAB_TIPOS_MODALIDADES.MODALIDAD, TAB_TIPOS_ACTIVIDADES.ACTIVIDAD, TAB_LINEAS_INVESTIGACION.NOMBRE as LINEA_INVESTIGACION')
            ->join('TAB_INSTRUCTORES', 'TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR = TAB_INSTRUCTORES.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS', 'TAB_INSTRUCTORES.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_TIPOS_MODALIDADES', 'TAB_ACTIVIDADES_EDUCACION.ID_TIPO_MODALIDAD = TAB_TIPOS_MODALIDADES.ID_TIPO_MODALIDAD')
            ->join('TAB_TIPOS_ACTIVIDADES', 'TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD = TAB_TIPOS_ACTIVIDADES.ID_TIPO_ACTIVIDAD')
            ->join('TAB_LINEAS_INVESTIGACION', 'TAB_ACTIVIDADES_EDUCACION.ID_LINEA_INVESTIGACION = TAB_LINEAS_INVESTIGACION.ID_LINEA_INVESTIGACION')
            ->find($id);

        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }

        $data = [
            'title' => 'Detalles de la Actividad',
            'actividad' => $actividad
        ];

        return view('admin/educacion/show', $data);
    }

    public function calendario()
    {
        $actividades = $this->actividadesModel
            ->select('ID_ACTIVIDAD_EDUCACION, NOMBRE_ACTIVIDAD, FECHA_INICIO, FECHA_FIN, LUGAR')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->orderBy('FECHA_INICIO', 'ASC')
            ->findAll();

        // Formatear para calendario
        $eventos = [];
        foreach ($actividades as $actividad) {
            $eventos[] = [
                'id' => $actividad['ID_ACTIVIDAD_EDUCACION'],
                'title' => $actividad['NOMBRE_ACTIVIDAD'],
                'start' => $actividad['FECHA_INICIO'],
                'end' => date('Y-m-d', strtotime($actividad['FECHA_FIN'] . ' +1 day')),
                'description' => $actividad['LUGAR']
            ];
        }

        $data = [
            'title' => 'Calendario de Actividades',
            'eventos' => json_encode($eventos)
        ];

        return view('admin/educacion/calendario', $data);
    }
}