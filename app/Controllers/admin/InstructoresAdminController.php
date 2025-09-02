<?php

namespace App\Controllers\admin;

use App\Models\InstructoresModel;
use App\Models\ActividadesEducacionModel;
use App\Models\TiposInstructoresModel;
use App\Models\DatosPersonasModel;
use App\Models\EmpleadosModel;
use App\Controllers\BaseController;

class InstructoresAdminController extends BaseController
{
    protected $instructoresModel;
    protected $actividadesModel;
    protected $tipoInstructoresModel;
    protected $datosPersonasModel;
    protected $empleadosModel;

    public function __construct()
    {
        $this->instructoresModel = new InstructoresModel();
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->tipoInstructoresModel = new TiposInstructoresModel();
        $this->datosPersonasModel = new DatosPersonasModel();
        $this->empleadosModel = new EmpleadosModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Instructores',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'tiposInstructores' => $this->tipoInstructoresModel->findAll()
        ];

        return view('admin/instructores/instructores_views', $data);
    }

    // Obtener todos los instructores (AJAX)
    public function getInstructores()
    {
        try {
            $instructores = $this->instructoresModel->getInstructoresConDatos();
            
            // Agregar estadísticas para cada instructor
            foreach ($instructores as &$instructor) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->findAll();
                $instructor['total_actividades'] = count($actividades);
                $instructor['actividades_activas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) >= time();
                }));
                $instructor['actividades_completadas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) < time();
                }));
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $instructores
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener instructores: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener instructor por ID (AJAX)
    public function getInstructor($id)
    {
        try {
            $instructor = $this->instructoresModel->getInstructorCompleto($id);
            
            if (!$instructor) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Instructor no encontrado'
                ]);
            }

            // Obtener actividades del instructor
            $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $id)->findAll();
            $instructor['actividades'] = $actividades;

            return $this->response->setJSON([
                'success' => true,
                'data' => $instructor
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener instructor: ' . $e->getMessage()
            ]);
        }
    }

    // Crear nuevo instructor
    public function crear()
    {
        try {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'tipo_instructor' => 'required|integer',
                'titulo_profesional' => 'required|min_length[3]|max_length[200]',
                'nombre' => 'required|min_length[2]|max_length[100]',
                'apellido' => 'required|min_length[2]|max_length[100]',
                'cedula' => 'required|exact_length[10]|numeric|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
                'email' => 'required|valid_email|max_length[100]|is_unique[TAB_DATOS_PERSONAS.EMAIL]',
                'celular' => 'required|exact_length[10]|numeric',
                'genero' => 'required|in_list[Masculino,Femenino]',
                'direccion' => 'required|min_length[5]',
                'especialidad' => 'required|min_length[3]',
                'nacionalidad' => 'required|min_length[3]|max_length[50]',
                'estado_civil' => 'required|in_list[Soltero,Casado,Divorciado,Viudo]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validation->getErrors()
                ]);
            }

            $this->db->transStart();

            // Crear datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'EMAIL' => $this->request->getPost('email'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'GENERO' => $this->request->getPost('genero'),
                'ESTADO_CIVIL' => $this->request->getPost('estado_civil'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad'),
                'FECHA_INGRESO' => date('Y-m-d'),
                'ACTIVO' => 1,
                'FOTO_URL' => ''
            ];

            $idDatoPersona = $this->datosPersonasModel->insert($datosPersona);

            // Crear instructor
            $instructor = [
                'ID_TIPO_INSTRUCTOR' => $this->request->getPost('tipo_instructor'),
                'ID_DATO_PERSONA' => $idDatoPersona,
                'ESPECIALIDAD' => $this->request->getPost('especialidad'),
                'TITULO_PROFESIONAL' => $this->request->getPost('titulo_profesional')
            ];

            $idInstructor = $this->instructoresModel->insert($instructor);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear instructor'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Instructor creado exitosamente',
                'data' => ['id' => $idInstructor]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear instructor: ' . $e->getMessage()
            ]);
        }
    }

    // Actualizar instructor
    public function actualizar($id)
    {
        try {
            $instructor = $this->instructoresModel->getInstructorCompleto($id);
            
            if (!$instructor) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Instructor no encontrado'
                ]);
            }

            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'tipo_instructor' => 'required|integer',
                'titulo_profesional' => 'required|min_length[3]|max_length[200]',
                'nombre' => 'required|min_length[2]|max_length[100]',
                'apellido' => 'required|min_length[2]|max_length[100]',
                'cedula' => "required|exact_length[10]|numeric|is_unique[TAB_DATOS_PERSONAS.CEDULA,ID_DATO_PERSONA,{$instructor['ID_DATO_PERSONA']}]",
                'email' => "required|valid_email|max_length[100]|is_unique[TAB_DATOS_PERSONAS.EMAIL,ID_DATO_PERSONA,{$instructor['ID_DATO_PERSONA']}]",
                'celular' => 'required|exact_length[10]|numeric',
                'genero' => 'required|in_list[Masculino,Femenino]',
                'direccion' => 'required|min_length[5]',
                'especialidad' => 'required|min_length[3]',
                'nacionalidad' => 'required|min_length[3]|max_length[50]',
                'estado_civil' => 'required|in_list[Soltero,Casado,Divorciado,Viudo]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validation->getErrors()
                ]);
            }

            $this->db->transStart();

            // Actualizar datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'EMAIL' => $this->request->getPost('email'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'GENERO' => $this->request->getPost('genero'),
                'ESTADO_CIVIL' => $this->request->getPost('estado_civil'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad')
            ];

            $this->datosPersonasModel->update($instructor['ID_DATO_PERSONA'], $datosPersona);

            // Actualizar instructor
            $datosInstructor = [
                'ID_TIPO_INSTRUCTOR' => $this->request->getPost('tipo_instructor'),
                'ESPECIALIDAD' => $this->request->getPost('especialidad'),
                'TITULO_PROFESIONAL' => $this->request->getPost('titulo_profesional')
            ];

            $this->instructoresModel->update($id, $datosInstructor);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar instructor'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Instructor actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar instructor: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar instructor
    public function eliminar($id)
    {
        try {
            $instructor = $this->instructoresModel->getInstructorCompleto($id);
            
            if (!$instructor) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Instructor no encontrado'
                ]);
            }

            // Verificar si tiene actividades asociadas
            $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $id)->countAllResults();
            
            if ($actividades > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar el instructor porque tiene actividades asociadas'
                ]);
            }

            $this->db->transStart();

            // Eliminar instructor
            $this->instructoresModel->delete($id);
            
            // Eliminar datos personales
            $this->datosPersonasModel->delete($instructor['ID_DATO_PERSONA']);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar instructor'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Instructor eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar instructor: ' . $e->getMessage()
            ]);
        }
    }

    // Generar reporte PDF
    public function generarReporte()
    {
        try {
            $instructores = $this->instructoresModel->getInstructoresConDatos();
            
            // Agregar estadísticas
            foreach ($instructores as &$instructor) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->findAll();
                $instructor['total_actividades'] = count($actividades);
                $instructor['actividades_activas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) >= time();
                }));
            }

            $data = [
                'instructores' => $instructores,
                'fecha_generacion' => date('d/m/Y H:i:s'),
                'total_instructores' => count($instructores)
            ];

            return view('admin/educacion/pdf/reportes', $data);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar reporte: ' . $e->getMessage()
            ]);
        }
    }

    // Exportar datos a Excel
    public function exportarExcel()
    {
        try {
            $instructores = $this->instructoresModel->getInstructoresConDatos();
            
            // Agregar estadísticas
            foreach ($instructores as &$instructor) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->findAll();
                $instructor['total_actividades'] = count($actividades);
                $instructor['actividades_activas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) >= time();
                }));
            }

            $filename = 'instructores_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($output, [
                'ID', 'Nombre', 'Apellido', 'Cédula', 'Email', 'Celular', 
                'Tipo', 'Especialidad', 'Título', 'Total Actividades', 'Actividades Activas'
            ]);
            
            // Datos
            foreach ($instructores as $instructor) {
                fputcsv($output, [
                    $instructor['ID_INSTRUCTOR'],
                    $instructor['NOMBRE'],
                    $instructor['APELLIDO'],
                    $instructor['CEDULA'],
                    $instructor['EMAIL'],
                    $instructor['CELULAR'],
                    $instructor['TIPO_INSTRUCTOR'],
                    $instructor['ESPECIALIDAD'],
                    $instructor['TITULO_PROFESIONAL'],
                    $instructor['total_actividades'],
                    $instructor['actividades_activas']
                ]);
            }
            
            fclose($output);
            exit;

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar datos: ' . $e->getMessage()
            ]);
        }
    }

    // Exportar datos a CSV
    public function exportarCSV()
    {
        try {
            $instructores = $this->instructoresModel->getInstructoresConDatos();
            
            // Agregar estadísticas
            foreach ($instructores as &$instructor) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->findAll();
                $instructor['total_actividades'] = count($actividades);
                $instructor['actividades_activas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) >= time();
                }));
            }

            $filename = 'instructores_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($output, [
                'ID', 'Nombre', 'Apellido', 'Cédula', 'Email', 'Celular', 
                'Tipo', 'Especialidad', 'Título', 'Total Actividades', 'Actividades Activas'
            ]);
            
            // Datos
            foreach ($instructores as $instructor) {
                fputcsv($output, [
                    $instructor['ID_INSTRUCTOR'],
                    $instructor['NOMBRE'],
                    $instructor['APELLIDO'],
                    $instructor['CEDULA'],
                    $instructor['EMAIL'],
                    $instructor['CELULAR'],
                    $instructor['TIPO_INSTRUCTOR'],
                    $instructor['ESPECIALIDAD'],
                    $instructor['TITULO_PROFESIONAL'],
                    $instructor['total_actividades'],
                    $instructor['actividades_activas']
                ]);
            }
            
            fclose($output);
            exit;

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar datos CSV: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener estadísticas generales
    public function getEstadisticas()
    {
        try {
            $totalInstructores = $this->instructoresModel->countAllResults();
            $instructoresActivos = $this->instructoresModel->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = TAB_INSTRUCTORES.ID_DATO_PERSONA')
                ->where('dp.ACTIVO', 1)
                ->countAllResults();
            
            $totalActividades = $this->actividadesModel->countAllResults();
            $actividadesActivas = $this->actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'total_instructores' => $totalInstructores,
                    'instructores_activos' => $instructoresActivos,
                    'total_actividades' => $totalActividades,
                    'actividades_activas' => $actividadesActivas,
                    'promedio_evaluacion' => 4.8 // Placeholder - implementar cuando tengas tabla de evaluaciones
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener tipos de instructores
    public function getTiposInstructores()
    {
        try {
            $tipos = $this->tipoInstructoresModel->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $tipos
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener tipos de instructores: ' . $e->getMessage()
            ]);
        }
    }

    public function actividades($idInstructor)
    {
        $instructor = $this->instructoresModel->getInstructorCompleto($idInstructor);
        
        if (!$instructor) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Instructor no encontrado');
        }

        $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $idInstructor)->findAll();

        $data = [
            'title' => 'Actividades del Instructor',
            'instructor' => $instructor,
            'actividades' => $actividades
        ];

        return view('admin/instructores/actividades', $data);
    }
}
