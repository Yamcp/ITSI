<?php

namespace App\Controllers\admin;

use App\Models\EstudiantesModel;
use App\Models\DatosPersonasModel;
use App\Models\AsignaturasModel;
use App\Models\TiposEstadosModel;
use App\Controllers\BaseController;

class EstudiantesController extends BaseController
{
    protected $estudiantesModel;
    protected $datosPersonasModel;
    protected $asignaturasModel;
    protected $tiposEstadosModel;

    public function __construct()
    {
        $this->estudiantesModel = new \App\Models\EstudiantesModel();
        $this->datosPersonasModel = new \App\Models\DatosPersonasModel();
        $this->asignaturasModel = new \App\Models\AsignaturasModel();
        $this->tiposEstadosModel = new \App\Models\TiposEstadosModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Estudiantes',
            'estudiantes' => $this->estudiantesModel->getEstudianteCompleto()
        ];

        return view('admin/educacion/educacion', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nuevo Estudiante',
            'asignaturas' => $this->asignaturasModel->getAsignaturasConCarrera(),
            'estados' => $this->tiposEstadosModel->findAll()
        ];

        return view('estudiantes/create', $data);
    }

    public function store()
    {
        $rules = [
            'nombre' => 'required|max_length[100]',
            'apellido' => 'required|max_length[100]',
            'cedula' => 'required|max_length[10]|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
            'email' => 'required|valid_email|max_length[100]',
            'celular' => 'required|max_length[10]',
            'fecha_nacimiento' => 'required|valid_date',
            'semestre_actual' => 'required|integer',
            'promedio_general' => 'required|decimal'
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
                'FECHA_INGRESO' => date('Y-m-d'),
                'ACTIVO' => true,
                'FOTO_URL' => ''
            ];

            $idDatosPersona = $this->datosPersonasModel->insert($datosPersonales);

            // Insertar estudiante
            $datosEstudiante = [
                'ID_TIPO_ESTADO' => $this->request->getPost('id_tipo_estado'),
                'ID_ASIGNATURA' => $this->request->getPost('id_asignatura'),
                'ID_DATO_PERSONA' => $idDatosPersona,
                'SEMESTRE_ACTUAL' => $this->request->getPost('semestre_actual'),
                'PROMEDIO_GENERAL' => $this->request->getPost('promedio_general'),
                'MATERIAS_APROBADAS' => $this->request->getPost('materias_aprobadas', 0)
            ];

            $this->estudiantesModel->insert($datosEstudiante);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Error al guardar el estudiante');
            }

            return redirect()->to('/estudiantes')->with('success', 'Estudiante creado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $estudiante = $this->estudiantesModel->getEstudianteCompleto($id);
        
        if (!$estudiante) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Estudiante no encontrado');
        }

        $data = [
            'title' => 'Detalles del Estudiante',
            'estudiante' => $estudiante
        ];

        return view('estudiantes/show', $data);
    }

    public function edit($id)
    {
        $estudiante = $this->estudiantesModel->getEstudianteCompleto($id);
        
        if (!$estudiante) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Estudiante no encontrado');
        }

        $data = [
            'title' => 'Editar Estudiante',
            'estudiante' => $estudiante,
            'asignaturas' => $this->asignaturasModel->getAsignaturasConCarrera(),
            'estados' => $this->tiposEstadosModel->findAll()
        ];

        return view('estudiantes/edit', $data);
    }

    public function update($id)
    {
        $estudiante = $this->estudiantesModel->find($id);
        
        if (!$estudiante) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Estudiante no encontrado');
        }

        $rules = [
            'nombre' => 'required|max_length[100]',
            'apellido' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'semestre_actual' => 'required|integer',
            'promedio_general' => 'required|decimal'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Actualizar datos personales
            $datosPersonales = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'EMAIL' => $this->request->getPost('email'),
                'GENERO' => $this->request->getPost('genero'),
                'ESTADO_CIVIL' => $this->request->getPost('estado_civil'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad')
            ];

            $this->datosPersonasModel->update($estudiante['ID_DATO_PERSONA'], $datosPersonales);

            // Actualizar estudiante
            $datosEstudiante = [
                'ID_TIPO_ESTADO' => $this->request->getPost('id_tipo_estado'),
                'ID_ASIGNATURA' => $this->request->getPost('id_asignatura'),
                'SEMESTRE_ACTUAL' => $this->request->getPost('semestre_actual'),
                'PROMEDIO_GENERAL' => $this->request->getPost('promedio_general'),
                'MATERIAS_APROBADAS' => $this->request->getPost('materias_aprobadas')
            ];

            $this->estudiantesModel->update($id, $datosEstudiante);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Error al actualizar el estudiante');
            }

            return redirect()->to('/estudiantes')->with('success', 'Estudiante actualizado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $estudiante = $this->estudiantesModel->find($id);
        
        if (!$estudiante) {
            return $this->response->setJSON(null, 404, 'Estudiante no encontrado');
        }

        if ($this->estudiantesModel->delete($id)) {
            return $this->response->setJSON(null, 200, 'Estudiante eliminado exitosamente');
        }

        return $this->response->setJSON(null, 500, 'Error al eliminar el estudiante');
    }

    public function buscar()
    {
        $termino = $this->request->getGet('q');
        
        if (strlen($termino) < 2) {
            return $this->response->setJSON([]);
        }

        $estudiantes = $this->estudiantesModel
            ->join('TAB_DATOS_PERSONAS', 'TAB_ESTUDIANTES.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->like('TAB_DATOS_PERSONAS.NOMBRE', $termino)
            ->orLike('TAB_DATOS_PERSONAS.APELLIDO', $termino)
            ->orLike('TAB_DATOS_PERSONAS.CEDULA', $termino)
            ->select('TAB_ESTUDIANTES.ID_ESTUDIANTE, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO, TAB_DATOS_PERSONAS.CEDULA')
            ->findAll(10);

        return $this->response->setJSON($estudiantes);
    }
}