<?php

namespace App\Controllers\docente;

use App\Controllers\BaseController;
use App\Models\EvaluacionesEnlacesModel;
use CodeIgniter\HTTP\ResponseInterface;

class EvaluacionesDocenteController extends BaseController
{
    protected $evaluacionesModel;

    public function __construct()
    {
        // Verificar autenticación y rol de docente
        if (!session()->get('logged_in') || session()->get('rol') != 2) {
            return redirect()->to('/');
        }
        
        $this->evaluacionesModel = new EvaluacionesEnlacesModel();
    }

    public function index()
    {
        // Cargar la vista de evaluaciones para docentes
        return view('docente/evaluaciones/evaluaciones_docente');
    }

    /**
     * Obtener evaluaciones disponibles para docentes
     * Solo muestra evaluaciones activas y no vencidas
     */
    public function obtenerEvaluaciones()
    {
        try {
            $evaluaciones = $this->evaluacionesModel->obtenerEvaluacionesParaDocentes();
            
            // Formatear datos para la vista
            $evaluacionesFormateadas = [];
            foreach ($evaluaciones as $eval) {
                $evaluacionesFormateadas[] = [
                    'id' => $eval['ID_EVALUACION_ENLACE'],
                    'nombre' => $eval['NOMBRE_EVALUACION'],
                    'tipo' => $eval['TIPO_EVALUACION'],
                    'enlace' => $eval['ENLACE_FORMULARIO'],
                    'descripcion' => $eval['DESCRIPCION'] ?? '',
                    'fecha_vencimiento' => $eval['FECHA_VENCIMIENTO'],
                    'estado' => $eval['ESTADO'],
                    'curso' => $eval['NOMBRE_ACTIVIDAD'] ?? 'Sin curso asignado',
                    'fecha_creacion' => $eval['FECHA_CREACION']
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $evaluacionesFormateadas
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener evaluaciones: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Obtener estadísticas básicas para docentes
     */
    public function obtenerEstadisticas()
    {
        try {
            $estadisticas = $this->evaluacionesModel->obtenerEstadisticasParaDocentes();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
