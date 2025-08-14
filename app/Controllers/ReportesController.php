<?php

namespace App\Controllers;

use App\Models\EstudiantesModel;
use App\Models\AsignacionesPracticasModel;
use App\Models\ExportacionesModel;

class ReportesController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Centro de Reportes'
        ];

        return view('reportes/index', $data);
    }

    public function estudiantes()
    {
        $estudiantesModel = new EstudiantesModel();

        $filtros = [
            'carrera' => $this->request->getGet('carrera'),
            'estado' => $this->request->getGet('estado'),
            'semestre' => $this->request->getGet('semestre')
        ];

        $estudiantes = $this->aplicarFiltrosEstudiantes($estudiantesModel, $filtros);

        $data = [
            'title' => 'Reporte de Estudiantes',
            'estudiantes' => $estudiantes,
            'filtros' => $filtros
        ];

        return view('reportes/estudiantes', $data);
    }

    public function practicas()
    {
        $asignacionesModel = new AsignacionesPracticasModel();

        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin = $this->request->getGet('fecha_fin');

        $builder = $asignacionesModel;

        if ($fechaInicio) {
            $builder->where('FECHA_INICIO >=', $fechaInicio);
        }

        if ($fechaFin) {
            $builder->where('FECHA_FIN <=', $fechaFin);
        }

        $practicas = $builder->getAsignacionCompleta();

        $data = [
            'title' => 'Reporte de Prácticas',
            'practicas' => $practicas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('reportes/practicas', $data);
    }

    private function aplicarFiltrosEstudiantes($model, $filtros)
    {
        $builder = $model->builder();

        if (!empty($filtros['carrera'])) {
            $builder->where('TAB_CARRERAS.ID_CARRERA', $filtros['carrera']);
        }

        if (!empty($filtros['estado'])) {
            $builder->where('TAB_ESTUDIANTES.ID_TIPO_ESTADO', $filtros['estado']);
        }

        if (!empty($filtros['semestre'])) {
            $builder->where('TAB_ESTUDIANTES.SEMESTRE_ACTUAL', $filtros['semestre']);
        }

        return $model->getEstudianteCompleto();
    }

    public function exportarPDF($tipo)
    {
        // Implementar exportación a PDF usando dompdf o similar
        switch ($tipo) {
            case 'estudiantes':
                return $this->exportarEstudiantesPDF();
            case 'practicas':
                return $this->exportarPracticasPDF();
            default:
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de reporte no válido');
        }
    }

    private function exportarEstudiantesPDF()
    {
        $estudiantesModel = new EstudiantesModel();
        $estudiantes = $estudiantesModel->getEstudianteCompleto();

        $exportacionesModel = new ExportacionesModel();
        $exportacionesModel->insert([
            'ID_USUARIO' => session()->get('usuario_id'),
            'FECHA_EXPORTACION' => date('Y-m-d H:i:s'),
            'DESCRIPCION_EXPORTACION' => 'Reporte PDF de Estudiantes'
        ]);

        $data = [
            'estudiantes' => $estudiantes,
            'fecha_generacion' => date('d/m/Y H:i:s')
        ];

        $html = view('reportes/pdf/estudiantes', $data);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="reporte_estudiantes_' . date('Y-m-d') . '.pdf"');

        return $html; // Aquí deberías generar el PDF real con Dompdf
    }

    private function exportarPracticasPDF()
    {
        $asignacionesModel = new AsignacionesPracticasModel();
        $practicas = $asignacionesModel->getAsignacionCompleta();

        $exportacionesModel = new ExportacionesModel();
        $exportacionesModel->insert([
            'ID_USUARIO' => session()->get('usuario_id'),
            'FECHA_EXPORTACION' => date('Y-m-d H:i:s'),
            'DESCRIPCION_EXPORTACION' => 'Reporte PDF de Prácticas'
        ]);

        $data = [
            'practicas' => $practicas,
            'fecha_generacion' => date('d/m/Y H:i:s')
        ];

        $html = view('reportes/pdf/practicas', $data);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="reporte_practicas_' . date('Y-m-d') . '.pdf"');

        return $html; // Aquí deberías generar el PDF real con Dompdf
    }
}
