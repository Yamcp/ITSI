<?php

namespace App\Controllers;

use App\Models\SeguimientoPracticasModel;
use App\Models\AsignacionesPracticasModel;

class SeguimientoPracticasController extends BaseController
{
    protected $seguimientoModel;
    protected $asignacionesModel;

    public function __construct()
    {
        $this->seguimientoModel = new \App\Models\SeguimientoPracticasModel();
        $this->asignacionesModel = new \App\Models\AsignacionesPracticasModel();
    }

    public function index()
    {
        $seguimientos = $this->seguimientoModel
            ->select('TAB_SEGUIMIENTO_PRACTICAS.*, TAB_ASIGNACIONES_PRACTICAS.*, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_ASIGNACIONES_PRACTICAS', 'TAB_SEGUIMIENTO_PRACTICAS.ID_ASIGNACION_PRACTICA = TAB_ASIGNACIONES_PRACTICAS.ID_ASIGNACION_PRACTICA')
            ->join('TAB_USUARIOS', 'TAB_ASIGNACIONES_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->findAll();

        $data = [
            'title' => 'Seguimiento de Prácticas',
            'seguimientos' => $seguimientos
        ];

        return view('seguimiento/index', $data);
    }

    public function create($idAsignacion)
    {
        $asignacion = $this->asignacionesModel->getAsignacionCompleta($idAsignacion);
        
        if (!$asignacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Asignación no encontrada');
        }

        $data = [
            'title' => 'Crear Seguimiento',
            'asignacion' => $asignacion
        ];

        return view('seguimiento/create', $data);
    }

    public function store()
    {
        $rules = [
            'id_asignacion_practica' => 'required|integer',
            'horas_cumplidas' => 'required|integer',
            'actividades_realizadas' => 'required',
            'observaciones' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Manejar subida de archivo de reporte
        $archivoReporte = '';
        $archivo = $this->request->getFile('archivo_reporte');
        
        if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move(WRITEPATH . 'uploads/reportes/', $nombreArchivo);
            $archivoReporte = $nombreArchivo;
        }

        $datos = [
            'ID_ASIGNACION_PRACTICA' => $this->request->getPost('id_asignacion_practica'),
            'HORAS_CUMPLIDAS' => $this->request->getPost('horas_cumplidas'),
            'ACTIVIDADES_REALIZADAS' => $this->request->getPost('actividades_realizadas'),
            'OBSERVACIONES' => $this->request->getPost('observaciones'),
            'ARCHIVO_REPORTE' => $archivoReporte
        ];

        if ($this->seguimientoModel->insert($datos)) {
            return redirect()->to('/seguimiento')->with('success', 'Seguimiento creado exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear el seguimiento');
    }

    public function downloadReporte($id)
    {
        $seguimiento = $this->seguimientoModel->find($id);
        
        if (!$seguimiento || empty($seguimiento['ARCHIVO_REPORTE'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Reporte no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/reportes/' . $seguimiento['ARCHIVO_REPORTE'];
        
        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }
}