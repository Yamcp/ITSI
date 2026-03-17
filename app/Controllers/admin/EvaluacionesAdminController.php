<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\EvaluacionesEnlacesModel;
use App\Models\ActividadesEducacionModel;
use CodeIgniter\HTTP\ResponseInterface;

class EvaluacionesAdminController extends BaseController
{
    protected $evaluacionesModel;
    protected $actividadesModel;

    public function __construct()
    {
        // Verificar autenticación y rol de administrador
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        $this->evaluacionesModel = new EvaluacionesEnlacesModel();
        $this->actividadesModel = new ActividadesEducacionModel();
    }

    public function index()
    {
        // Cargar la vista de evaluaciones
        return view('admin/evaluaciones/evaluaciones');
    }

    public function agregarEvaluacion()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
        }
        
        try {
            $data = [
                'ID_ACTIVIDAD_EDUCACION' => $this->request->getPost('curso_id'),
                'ID_USUARIO_CREADOR' => session()->get('user_id'),
                'NOMBRE_EVALUACION' => $this->request->getPost('nombre_evaluacion'),
                'TIPO_EVALUACION' => $this->request->getPost('tipo_evaluacion'),
                'ENLACE_FORMULARIO' => $this->request->getPost('enlace_formulario'),
                'DESCRIPCION' => $this->request->getPost('descripcion'),
                'FECHA_VENCIMIENTO' => $this->request->getPost('fecha_vencimiento'),
                'ESTADO' => $this->request->getPost('estado'),
                'NUMERO_RESPUESTAS' => 0,
                'ACTIVO' => true
            ];

            // Validar datos
            if (!$this->evaluacionesModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al guardar la evaluación: ' . implode(', ', $this->evaluacionesModel->errors())
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Evaluación agregada exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function obtenerEvaluaciones()
    {
        try {
            // Primero, obtener todas las evaluaciones sin filtros para depuración
            $todasEvaluaciones = $this->evaluacionesModel->findAll();
            log_message('debug', 'Total evaluaciones en BD: ' . count($todasEvaluaciones));
            
            // Luego obtener las evaluaciones completas con filtros
            $evaluaciones = $this->evaluacionesModel->obtenerEvaluacionesCompletas();
            log_message('debug', 'Evaluaciones activas encontradas: ' . count($evaluaciones));
            
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
                    'respuestas' => $eval['NUMERO_RESPUESTAS'],
                    'fecha_creacion' => $eval['FECHA_CREACION'],
                    'curso' => $eval['NOMBRE_ACTIVIDAD'] ?? 'Sin curso asignado',
                    'usuario_creador' => $eval['USUARIO_CREADOR'] ?? 'Desconocido'
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $evaluacionesFormateadas,
                'debug_count' => count($evaluacionesFormateadas),
                'debug_total_bd' => count($todasEvaluaciones)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener evaluaciones: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function obtenerCursos()
    {
        try {
            $cursos = $this->actividadesModel->findAll();
            
            // Formatear datos para la vista
            $cursosFormateados = [];
            foreach ($cursos as $curso) {
                $cursosFormateados[] = [
                    'ID_ACTIVIDAD_EDUCACION' => $curso['ID_ACTIVIDAD_EDUCACION'],
                    'NOMBRE_ACTIVIDAD' => $curso['NOMBRE_ACTIVIDAD'],
                    'TIPO_ACTIVIDAD' => $curso['TIPO_ACTIVIDAD'] ?? 'Curso',
                    'FECHA_INICIO' => $curso['FECHA_INICIO'],
                    'FECHA_FIN' => $curso['FECHA_FIN'],
                    'ESTADO' => $this->determinarEstadoCurso($curso['FECHA_INICIO'], $curso['FECHA_FIN'])
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $cursosFormateados
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener cursos: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Determinar el estado del curso basado en las fechas
     */
    private function determinarEstadoCurso($fechaInicio, $fechaFin)
    {
        $hoy = date('Y-m-d');
        
        if ($hoy < $fechaInicio) {
            return 'pendiente';
        } elseif ($hoy >= $fechaInicio && $hoy <= $fechaFin) {
            return 'activo';
        } else {
            return 'finalizado';
        }
    }

    public function eliminarEvaluacion($id = null)
    {
        if ($id) {
            try {
                // Verificar que la evaluación existe
                $evaluacion = $this->evaluacionesModel->find($id);
                if (!$evaluacion) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Evaluación no encontrada'
                    ]);
                }

                // Eliminar la evaluación (soft delete marcando como inactivo)
                if ($this->evaluacionesModel->update($id, ['ACTIVO' => false])) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Evaluación eliminada exitosamente'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al eliminar la evaluación'
                    ]);
                }
                
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de evaluación requerido'
        ]);
    }

    public function cambiarEstadoEvaluacion($id = null)
    {
        if ($id) {
            try {
                $nuevoEstado = $this->request->getPost('nuevo_estado');
                
                // Validar estado
                $estadosValidos = ['activo', 'inactivo', 'borrador'];
                if (!in_array($nuevoEstado, $estadosValidos)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Estado no válido'
                    ]);
                }

                // Verificar que la evaluación existe
                $evaluacion = $this->evaluacionesModel->find($id);
                if (!$evaluacion) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Evaluación no encontrada'
                    ]);
                }

                // Actualizar estado
                if ($this->evaluacionesModel->update($id, ['ESTADO' => $nuevoEstado])) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Estado cambiado exitosamente',
                        'nuevo_estado' => $nuevoEstado
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al cambiar el estado'
                    ]);
                }
                
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de evaluación requerido'
        ]);
    }

    /**
     * Obtener estadísticas de evaluaciones
     */
    public function obtenerEstadisticas()
    {
        try {
            $estadisticas = $this->evaluacionesModel->obtenerEstadisticas();
            
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

    /**
     * Obtener una evaluación específica para edición
     */
    public function obtenerEvaluacion($id = null)
    {
        if ($id) {
            try {
                $evaluacion = $this->evaluacionesModel->obtenerEvaluacionCompleta($id);
                
                if (!$evaluacion) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Evaluación no encontrada'
                    ]);
                }

                // Formatear datos para la vista
                $evaluacionFormateada = [
                    'id' => $evaluacion['ID_EVALUACION_ENLACE'],
                    'curso_id' => $evaluacion['ID_ACTIVIDAD_EDUCACION'],
                    'nombre' => $evaluacion['NOMBRE_EVALUACION'],
                    'tipo' => $evaluacion['TIPO_EVALUACION'],
                    'enlace' => $evaluacion['ENLACE_FORMULARIO'],
                    'descripcion' => $evaluacion['DESCRIPCION'] ?? '',
                    'fecha_vencimiento' => $evaluacion['FECHA_VENCIMIENTO'],
                    'estado' => $evaluacion['ESTADO'],
                    'respuestas' => $evaluacion['NUMERO_RESPUESTAS'],
                    'fecha_creacion' => $evaluacion['FECHA_CREACION'],
                    'curso' => $evaluacion['NOMBRE_ACTIVIDAD'] ?? 'Sin curso asignado',
                    'usuario_creador' => $evaluacion['USUARIO_CREADOR'] ?? 'Desconocido'
                ];
                
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $evaluacionFormateada
                ]);
                
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al obtener la evaluación: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de evaluación requerido'
        ]);
    }

    /**
     * Actualizar una evaluación existente
     */
    public function actualizarEvaluacion($id = null)
    {
        if ($id && $this->request->getMethod() === 'post') {
            try {
                // Verificar que la evaluación existe
                $evaluacion = $this->evaluacionesModel->find($id);
                if (!$evaluacion) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Evaluación no encontrada'
                    ]);
                }

                $data = [
                    'ID_ACTIVIDAD_EDUCACION' => $this->request->getPost('curso_id'),
                    'NOMBRE_EVALUACION' => $this->request->getPost('nombre_evaluacion'),
                    'TIPO_EVALUACION' => $this->request->getPost('tipo_evaluacion'),
                    'ENLACE_FORMULARIO' => $this->request->getPost('enlace_formulario'),
                    'DESCRIPCION' => $this->request->getPost('descripcion'),
                    'FECHA_VENCIMIENTO' => $this->request->getPost('fecha_vencimiento'),
                    'ESTADO' => $this->request->getPost('estado')
                ];

                // Validar datos
                if (!$this->evaluacionesModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al actualizar la evaluación: ' . implode(', ', $this->evaluacionesModel->errors())
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Evaluación actualizada exitosamente',
                    'data' => $data
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage()
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido o ID requerido'
        ]);
    }

    /**
     * Aplicar filtros a las evaluaciones
     */
    public function aplicarFiltros()
    {
        try {
            $filtros = [
                'tipo' => $this->request->getPost('filtro_tipo'),
                'estado' => $this->request->getPost('filtro_estado'),
                'fecha_desde' => $this->request->getPost('fecha_desde'),
                'fecha_hasta' => $this->request->getPost('fecha_hasta')
            ];

            // Remover filtros vacíos
            $filtros = array_filter($filtros, function($value) {
                return !empty($value);
            });

            $evaluaciones = $this->evaluacionesModel->obtenerConFiltros($filtros);
            
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
                    'respuestas' => $eval['NUMERO_RESPUESTAS'],
                    'fecha_creacion' => $eval['FECHA_CREACION'],
                    'curso' => $eval['NOMBRE_ACTIVIDAD'] ?? 'Sin curso asignado'
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $evaluacionesFormateadas
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al aplicar filtros: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
