<?php

namespace App\Controllers;

use App\Models\AsignacionesPracticasModel;
use App\Models\AsistenciasPracticasModel;
use App\Models\SeguimientoPracticasModel;
use App\Models\TiposPracticasModel;
use App\Models\EstadoPracticasModel;

class PracticasController extends BaseController
{
    protected $asignacionesModel;
    protected $asistenciasModel;
    protected $seguimientoModel;
    protected $tiposPracticasModel;
    protected $estadoPracticasModel;

    public function __construct()
    {
        $this->asignacionesModel = new \App\Models\AsignacionesPracticasModel();
        $this->asistenciasModel = new \App\Models\AsistenciasPracticasModel();
        $this->seguimientoModel = new \App\Models\SeguimientoPracticasModel();
        $this->tiposPracticasModel = new \App\Models\TiposPracticasModel();
        $this->estadoPracticasModel = new \App\Models\EstadoPracticasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Prácticas',
            'asignaciones' => $this->asignacionesModel->getAsignacionCompleta()
        ];

        return view('practicas/index', $data);
    }

    public function asignar()
    {
        $data = [
            'title' => 'Asignar Práctica',
            'tipos_practicas' => $this->tiposPracticasModel->findAll(),
            'estados' => $this->estadoPracticasModel->findAll()
        ];

        return view('practicas/asignar', $data);
    }

    public function registrarAsistencia($idAsignacion)
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->guardarAsistencia($idAsignacion);
        }

        $asignacion = $this->asignacionesModel->getAsignacionCompleta($idAsignacion);
        
        if (!$asignacion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Asignación no encontrada');
        }

        $data = [
            'title' => 'Registrar Asistencia',
            'asignacion' => $asignacion,
            'asistencias' => $this->asistenciasModel->where('ID_ASIGNACION_PRACTICA', $idAsignacion)->findAll()
        ];

        return view('practicas/asistencia', $data);
    }

    private function guardarAsistencia($idAsignacion)
    {
        $rules = [
            'fecha_asistencia' => 'required|valid_date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'actividades_dia' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datosAsistencia = [
            'ID_ASIGNACION_PRACTICA' => $idAsignacion,
            'FECHA_ASISTENCIA' => $this->request->getPost('fecha_asistencia'),
            'HORA_ENTRADA' => $this->request->getPost('hora_entrada'),
            'HORA_SALIDA' => $this->request->getPost('hora_salida'),
            'ACTIVIDADES_DIA' => $this->request->getPost('actividades_dia'),
            'FECHA_REGISTRO' => date('Y-m-d H:i:s'),
            'OBSERVACIONES' => $this->request->getPost('observaciones', '')
        ];

        if ($this->asistenciasModel->insert($datosAsistencia)) {
            return redirect()->back()->with('success', 'Asistencia registrada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al registrar la asistencia');
    }

    public function reporte($idAsignacion)
    {
        $asignacion = $this->asignacionesModel->getAsignacionCompleta($idAsignacion);
        $asistencias = $this->asistenciasModel->where('ID_ASIGNACION_PRACTICA', $idAsignacion)->findAll();

        $data = [
            'title' => 'Reporte de Práctica',
            'asignacion' => $asignacion,
            'asistencias' => $asistencias,
            'total_horas' => $this->calcularTotalHoras($asistencias)
        ];

        return view('practicas/reporte', $data);
    }

    private function calcularTotalHoras($asistencias)
    {
        $totalHoras = 0;
        foreach ($asistencias as $asistencia) {
            $totalHoras += $this->asistenciasModel->calcularHorasTrabajadas(
                $asistencia['HORA_ENTRADA'], 
                $asistencia['HORA_SALIDA']
            );
        }
        return $totalHoras;
    }
}