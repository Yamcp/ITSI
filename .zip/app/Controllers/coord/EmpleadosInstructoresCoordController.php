<?php

namespace App\Controllers\coord;

use App\Controllers\BaseController;
use App\Models\EmpleadosInstructoresModel;
use App\Models\EmpleadosModel;
use App\Models\InstructoresModel;

class EmpleadosInstructoresCoordController extends BaseController
{
    protected $empleadosInstructoresModel;
    protected $empleadosModel;
    protected $instructoresModel;

    public function __construct()
    {
        $this->empleadosInstructoresModel = new EmpleadosInstructoresModel();
        $this->empleadosModel = new EmpleadosModel();
        $this->instructoresModel = new InstructoresModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Empleados-Instructores',
            'relaciones' => $this->empleadosInstructoresModel->getRelacionesCompletas()
        ];

        return view('coord/empleados-instructores/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Relación Empleado-Instructor',
            'empleados' => $this->empleadosModel->getEmpleadoConDatos(),
            'instructores' => $this->instructoresModel->getInstructoresConDatos()
        ];

        return view('coord/empleados-instructores/create', $data);
    }

    public function store()
    {
        $rules = [
            'id_empleado' => 'required|integer',
            'id_instructor' => 'required|integer'
        ];

        $messages = [
            'id_empleado' => [
                'required' => 'Debe seleccionar un empleado',
                'integer' => 'El empleado seleccionado no es válido'
            ],
            'id_instructor' => [
                'required' => 'Debe seleccionar un instructor',
                'integer' => 'El instructor seleccionado no es válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $idEmpleado = $this->request->getPost('id_empleado');
        $idInstructor = $this->request->getPost('id_instructor');

        // Verificar si la relación ya existe
        if ($this->empleadosInstructoresModel->existeRelacion($idEmpleado, $idInstructor)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Esta relación empleado-instructor ya existe');
        }

        $resultado = $this->empleadosInstructoresModel->crearRelacion($idEmpleado, $idInstructor);

        if ($resultado) {
            return redirect()->to('/coord/empleados-instructores')
                ->with('success', 'Relación empleado-instructor creada exitosamente');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la relación empleado-instructor');
        }
    }

    public function show($id)
    {
        $relacion = $this->empleadosInstructoresModel->find($id);
        
        if (!$relacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Relación no encontrada');
        }

        $data = [
            'title' => 'Detalles de Relación Empleado-Instructor',
            'relacion' => $relacion
        ];

        return view('coord/empleados-instructores/show', $data);
    }

    public function edit($id)
    {
        $relacion = $this->empleadosInstructoresModel->find($id);
        
        if (!$relacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Relación no encontrada');
        }

        $data = [
            'title' => 'Editar Relación Empleado-Instructor',
            'relacion' => $relacion,
            'empleados' => $this->empleadosModel->getEmpleadoConDatos(),
            'instructores' => $this->instructoresModel->getInstructoresConDatos()
        ];

        return view('coord/empleados-instructores/edit', $data);
    }

    public function update($id)
    {
        $relacion = $this->empleadosInstructoresModel->find($id);
        
        if (!$relacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Relación no encontrada');
        }

        $rules = [
            'id_empleado' => 'required|integer',
            'id_instructor' => 'required|integer'
        ];

        $messages = [
            'id_empleado' => [
                'required' => 'Debe seleccionar un empleado',
                'integer' => 'El empleado seleccionado no es válido'
            ],
            'id_instructor' => [
                'required' => 'Debe seleccionar un instructor',
                'integer' => 'El instructor seleccionado no es válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $idEmpleado = $this->request->getPost('id_empleado');
        $idInstructor = $this->request->getPost('id_instructor');

        // Verificar si la nueva relación ya existe (excluyendo la actual)
        if ($this->empleadosInstructoresModel->where('ID_EMPLEADO', $idEmpleado)
                                           ->where('ID_INSTRUCTOR', $idInstructor)
                                           ->where('ID_EMPLEADO_INSTRUCTOR !=', $id)
                                           ->countAllResults() > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Esta relación empleado-instructor ya existe');
        }

        $data = [
            'ID_EMPLEADO' => $idEmpleado,
            'ID_INSTRUCTOR' => $idInstructor
        ];

        $resultado = $this->empleadosInstructoresModel->update($id, $data);

        if ($resultado) {
            return redirect()->to('/coord/empleados-instructores')
                ->with('success', 'Relación empleado-instructor actualizada exitosamente');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la relación empleado-instructor');
        }
    }

    public function delete($id)
    {
        $relacion = $this->empleadosInstructoresModel->find($id);
        
        if (!$relacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Relación no encontrada');
        }

        $resultado = $this->empleadosInstructoresModel->delete($id);

        if ($resultado) {
            return redirect()->to('/coord/empleados-instructores')
                ->with('success', 'Relación empleado-instructor eliminada exitosamente');
        } else {
            return redirect()->back()
                ->with('error', 'Error al eliminar la relación empleado-instructor');
        }
    }

    // Método AJAX para verificar si un empleado es instructor
    public function verificarEmpleadoInstructor()
    {
        $idEmpleado = $this->request->getPost('id_empleado');
        
        if (!$idEmpleado) {
            return $this->response->setJSON(['es_instructor' => false]);
        }

        $esInstructor = $this->empleadosModel->esInstructor($idEmpleado);
        
        return $this->response->setJSON(['es_instructor' => $esInstructor]);
    }

    // Método AJAX para obtener instructores de un empleado
    public function getInstructoresEmpleado()
    {
        $idEmpleado = $this->request->getPost('id_empleado');
        
        if (!$idEmpleado) {
            return $this->response->setJSON(['instructores' => []]);
        }

        $instructores = $this->empleadosModel->getInstructoresDelEmpleado($idEmpleado);
        
        return $this->response->setJSON(['instructores' => $instructores]);
    }

    // Método AJAX para obtener empleados de un instructor
    public function getEmpleadosInstructor()
    {
        $idInstructor = $this->request->getPost('id_instructor');
        
        if (!$idInstructor) {
            return $this->response->setJSON(['empleados' => []]);
        }

        $empleados = $this->empleadosInstructoresModel->getEmpleadosPorInstructor($idInstructor);
        
        return $this->response->setJSON(['empleados' => $empleados]);
    }
}
