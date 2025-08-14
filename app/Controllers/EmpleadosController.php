<?php

namespace App\Controllers;

use App\Models\EmpleadosModel;
use App\Models\DatosPersonasModel;
use App\Models\DepartamentosModel;
use App\Models\TipoContratoModel;

class EmpleadosController extends BaseController
{
    protected $empleadosModel;
    protected $datosPersonasModel;
    protected $departamentosModel;
    protected $tipoContratoModel;

    public function __construct()
    {
        $this->empleadosModel = new \App\Models\EmpleadosModel();
        $this->datosPersonasModel = new \App\Models\DatosPersonasModel();
        $this->departamentosModel = new \App\Models\DepartamentosModel();
        $this->tipoContratoModel = new \App\Models\TipoContratoModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Empleados',
            'empleados' => $this->empleadosModel->getEmpleadoCompleto()
        ];

        return view('empleados/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nuevo Empleado',
            'departamentos' => $this->departamentosModel->findAll(),
            'tipos_contrato' => $this->tipoContratoModel->findAll()
        ];

        return view('empleados/create', $data);
    }

    public function store()
    {
        $rules = [
            'nombre' => 'required|max_length[100]',
            'apellido' => 'required|max_length[100]',
            'cedula' => 'required|max_length[10]|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
            'email' => 'required|valid_email|max_length[100]',
            'cargo' => 'required|max_length[100]',
            'fecha_ingreso' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insertar datos personales
            $datosPersonales = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'EMAIL' => $this->request->getPost('email'),
                'FECHA_NACIMIENTO' => $this->request->getPost('fecha_nacimiento'),
                'GENERO' => $this->request->getPost('genero'),
                'ESTADO_CIVIL' => $this->request->getPost('estado_civil'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad'),
                'FECHA_INGRESO' => $this->request->getPost('fecha_ingreso'),
                'ACTIVO' => true,
                'FOTO_URL' => ''
            ];

            $idDatosPersona = $this->datosPersonasModel->insert($datosPersonales);

            // Insertar empleado
            $datosEmpleado = [
                'ID_DEPARTAMENTO' => $this->request->getPost('id_departamento'),
                'ID_DATO_PERSONA' => $idDatosPersona,
                'ID_TIPO_CONTRATO' => $this->request->getPost('id_tipo_contrato'),
                'CARGO' => $this->request->getPost('cargo'),
                'FECHA_INGRESO' => $this->request->getPost('fecha_ingreso')
            ];

            $this->empleadosModel->insert($datosEmpleado);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Error al guardar el empleado');
            }

            return redirect()->to('/empleados')->with('success', 'Empleado creado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $empleado = $this->empleadosModel->getEmpleadoCompleto($id);
        
        if (!$empleado) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Empleado no encontrado');
        }

        $data = [
            'title' => 'Detalles del Empleado',
            'empleado' => $empleado
        ];

        return view('empleados/show', $data);
    }

    // Métodos edit, update, delete similares a Estudiantes...
}