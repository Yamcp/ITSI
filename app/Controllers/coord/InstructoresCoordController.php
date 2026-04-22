<?php

namespace App\Controllers\coord;

use App\Models\InstructoresModel;
use App\Models\ActividadesEducacionModel;
use App\Models\TiposInstructoresModel;
use App\Models\DatosPersonasModel;
use App\Models\EmpleadosModel;
use App\Models\DocentesTutoresModel;
use App\Models\UsuariosModel;
use App\Controllers\BaseController;

class InstructoresCoordController extends BaseController
{
    protected $db;
    protected $instructoresModel;
    protected $actividadesModel;
    protected $tipoInstructoresModel;
    protected $datosPersonasModel;
    protected $empleadosModel;
    protected $docentesTutoresModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->instructoresModel = new InstructoresModel();
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->tipoInstructoresModel = new TiposInstructoresModel();
        $this->datosPersonasModel = new DatosPersonasModel();
        $this->empleadosModel = new EmpleadosModel();
        $this->docentesTutoresModel = new DocentesTutoresModel();
    }

    /**
     * Regla de negocio: desde este módulo solo se gestionan instructores externos.
     */
    private function obtenerIdTipoInstructorExterno(): ?int
    {
        $tipos = $this->tipoInstructoresModel->findAll();
        $tipoExterno = null;
        foreach ($tipos as $tipo) {
            if (strtolower(trim((string) ($tipo['TIPO'] ?? ''))) === 'externo') {
                $tipoExterno = $tipo;
                break;
            }
        }

        if (!$tipoExterno) {
            return null;
        }

        return (int) ($tipoExterno['ID_TIPO_INSTRUCTOR'] ?? 0);
    }

    public function index()
    {
        $data = [
            'title' => 'Instructores externos',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'tiposInstructores' => $this->tipoInstructoresModel->findAll()
        ];

        return view('coord/instructores/instructores', $data);
    }

    /**
     * Vista de docentes/tutores internos — datos desde TAB_DOCENTES_TUTORES.
     */
    public function docentes()
    {
        $lista = $this->docentesTutoresModel->getDocentesConDatos();

        // Contar prácticas asignadas a cada docente
        foreach ($lista as &$docente) {
            $idDocente = (int) ($docente['ID_DOCENTE_TUTOR'] ?? 0);
            $docente['total_practicas_pp'] = 0;
            $docente['total_practicas_sc'] = 0;
            if ($idDocente > 0) {
                $docente['total_practicas_pp'] = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
                    ->where('ID_DOCENTE_TUTOR', $idDocente)
                    ->countAllResults();
                $docente['total_practicas_sc'] = $this->db->table('TAB_SERVICIO_COMUNITARIO')
                    ->where('ID_DOCENTE_TUTOR', $idDocente)
                    ->countAllResults();
            }
            $docente['total_actividades'] = $docente['total_practicas_pp'] + $docente['total_practicas_sc'];
        }
        unset($docente);

        return view('coord/docentes/docentes', [
            'title' => 'Gestión de docentes',
            'docentes' => $lista,
        ]);
    }

    private function esTipoInstructorInterno(?string $tipo): bool
    {
        return strtolower(trim((string) $tipo)) === 'interno';
    }

    private function esTipoInstructorExterno(?string $tipo): bool
    {
        return strtolower(trim((string) $tipo)) === 'externo';
    }

    /**
     * Solo instructores con tipo «Externo» (educación continua / externos).
     *
     * @param list<array<string, mixed>> $instructoresTodos
     * @return list<array<string, mixed>>
     */
    private function obtenerInstructoresExternosDesdeLista(array $instructoresTodos): array
    {
        $out = [];
        foreach ($instructoresTodos as $row) {
            if ($this->esTipoInstructorExterno($row['TIPO_INSTRUCTOR'] ?? null)) {
                $out[] = $row;
            }
        }

        return $out;
    }

    /**
     * Docentes internos: instructores con tipo «Interno» en TAB_INSTRUCTORES y empleados con cargo docente/tutor.
     *
     * @param list<array<string, mixed>> $instructoresTodos Resultado de getInstructoresConDatos()
     * @return list<array<string, mixed>>
     */
    private function obtenerDocentesInternosUnificados(array $instructoresTodos): array
    {
        $docentesInternos = [];
        foreach ($instructoresTodos as $instructor) {
            if ($this->esTipoInstructorInterno($instructor['TIPO_INSTRUCTOR'] ?? null)) {
                $docentesInternos[] = $instructor;
            }
        }

        $docentesInstituto = $this->db->table('TAB_EMPLEADOS e')
            ->select('
                NULL as ID_INSTRUCTOR,
                e.ID_DATO_PERSONA,
                NULL as ID_TIPO_INSTRUCTOR,
                dp.NOMBRE,
                dp.APELLIDO,
                dp.CEDULA,
                dp.EMAIL,
                dp.CELULAR,
                dp.DIRECCION,
                dp.GENERO,
                dp.ESTADO_CIVIL,
                dp.NACIONALIDAD,
                dp.FOTO_URL,
                e.CARGO as ESPECIALIDAD,
                e.CARGO as TITULO_PROFESIONAL,
                "Interno" as TIPO_INSTRUCTOR
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('dp.ACTIVO', 1)
            ->groupStart()
            ->where('UPPER(e.CARGO) LIKE', '%DOCENTE%')
            ->orWhere('UPPER(e.CARGO) LIKE', '%TUTOR%')
            ->groupEnd()
            ->get()
            ->getResultArray();

        $docentesInternosPorPersona = [];
        foreach ($docentesInternos as $docenteInterno) {
            $idDatoPersona = (int) ($docenteInterno['ID_DATO_PERSONA'] ?? 0);
            if ($idDatoPersona > 0) {
                $docentesInternosPorPersona[$idDatoPersona] = $docenteInterno;
            }
        }
        foreach ($docentesInstituto as $docenteInstituto) {
            $idDatoPersona = (int) ($docenteInstituto['ID_DATO_PERSONA'] ?? 0);
            if ($idDatoPersona > 0 && !isset($docentesInternosPorPersona[$idDatoPersona])) {
                $docentesInternosPorPersona[$idDatoPersona] = $docenteInstituto;
            }
        }

        return array_values($docentesInternosPorPersona);
    }

    /**
     * @param list<array<string, mixed>> $registros
     * @return list<array<string, mixed>>
     */
    private function adjuntarEstadisticasActividades(array $registros): array
    {
        foreach ($registros as &$registro) {
            $idInstructor = (int) ($registro['ID_INSTRUCTOR'] ?? 0);
            if ($idInstructor > 0) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $idInstructor)->findAll();
            } else {
                $actividades = [];
            }
            $registro['total_actividades'] = count($actividades);
            $registro['actividades_activas'] = count(array_filter($actividades, static function ($act) {
                return strtotime($act['FECHA_FIN']) >= time();
            }));
            $registro['actividades_completadas'] = count(array_filter($actividades, static function ($act) {
                return strtotime($act['FECHA_FIN']) < time();
            }));
        }
        unset($registro);

        return $registros;
    }

    // Instructores externos únicamente (los internos están en coord/docentes).
    public function getInstructores()
    {
        try {
            $instructores = $this->instructoresModel->getInstructoresConDatos();
            $registros = $this->obtenerInstructoresExternosDesdeLista($instructores);
            $registros = $this->adjuntarEstadisticasActividades($registros);

            return $this->response->setJSON([
                'success' => true,
                'data' => $registros
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
                'titulo_profesional' => 'required|min_length[3]|max_length[200]',
                'nombre' => 'required|min_length[2]|max_length[100]',
                'apellido' => 'required|min_length[2]|max_length[100]',
                'cedula' => 'required|exact_length[10]|numeric|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
                'email' => 'required|valid_email|max_length[100]|is_unique[TAB_DATOS_PERSONAS.EMAIL]',
                'celular' => 'required|exact_length[10]|numeric',
                'genero' => 'required|in_list[Masculino,Femenino]',
                'direccion' => 'required|min_length[5]',
                'especialidad' => 'required|min_length[3]',
                'nacionalidad' => 'required|min_length[3]|max_length[50]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validation->getErrors()
                ]);
            }

            $this->db->transStart();

            $idTipoExterno = $this->obtenerIdTipoInstructorExterno();
            if (!$idTipoExterno) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No existe el tipo de instructor Externo configurado en el sistema.'
                ]);
            }

            // Crear datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'EMAIL' => $this->request->getPost('email'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'GENERO' => $this->request->getPost('genero'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad'),
                'FECHA_INGRESO' => date('Y-m-d'),
                'ACTIVO' => 1,
                'FOTO_URL' => ''
            ];

            $idDatoPersona = $this->datosPersonasModel->insert($datosPersona);

            // Crear instructor
            $instructor = [
                'ID_TIPO_INSTRUCTOR' => $idTipoExterno,
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
                'titulo_profesional' => 'required|min_length[3]|max_length[200]',
                'nombre' => 'required|min_length[2]|max_length[100]',
                'apellido' => 'required|min_length[2]|max_length[100]',
                'cedula' => "required|exact_length[10]|numeric|is_unique[TAB_DATOS_PERSONAS.CEDULA,ID_DATO_PERSONA,{$instructor['ID_DATO_PERSONA']}]",
                'email' => "required|valid_email|max_length[100]|is_unique[TAB_DATOS_PERSONAS.EMAIL,ID_DATO_PERSONA,{$instructor['ID_DATO_PERSONA']}]",
                'celular' => 'required|exact_length[10]|numeric',
                'genero' => 'required|in_list[Masculino,Femenino]',
                'direccion' => 'required|min_length[5]',
                'especialidad' => 'required|min_length[3]',
                'nacionalidad' => 'required|min_length[3]|max_length[50]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validation->getErrors()
                ]);
            }

            $this->db->transStart();

            $idTipoExterno = $this->obtenerIdTipoInstructorExterno();
            if (!$idTipoExterno) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No existe el tipo de instructor Externo configurado en el sistema.'
                ]);
            }

            // Actualizar datos personales
            $datosPersona = [
                'NOMBRE' => $this->request->getPost('nombre'),
                'APELLIDO' => $this->request->getPost('apellido'),
                'CEDULA' => $this->request->getPost('cedula'),
                'EMAIL' => $this->request->getPost('email'),
                'CELULAR' => $this->request->getPost('celular'),
                'DIRECCION' => $this->request->getPost('direccion'),
                'GENERO' => $this->request->getPost('genero'),
                'NACIONALIDAD' => $this->request->getPost('nacionalidad')
            ];

            $this->datosPersonasModel->update($instructor['ID_DATO_PERSONA'], $datosPersona);

            // Actualizar instructor
            $datosInstructor = [
                'ID_TIPO_INSTRUCTOR' => $idTipoExterno,
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
            $todos = $this->instructoresModel->getInstructoresConDatos();
            $instructores = $this->obtenerInstructoresExternosDesdeLista($todos);

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

            return view('coord/educacion/reportes_pdf', $data);

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
            // Limpiar cualquier output previo
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            $todos = $this->instructoresModel->getInstructoresConDatos();
            $instructores = $this->obtenerInstructoresExternosDesdeLista($todos);

            // Agregar estadísticas
            foreach ($instructores as &$instructor) {
                $actividades = $this->actividadesModel->where('ID_INSTRUCTOR', $instructor['ID_INSTRUCTOR'])->findAll();
                $instructor['total_actividades'] = count($actividades);
                $instructor['actividades_activas'] = count(array_filter($actividades, function($act) {
                    return strtotime($act['FECHA_FIN']) >= time();
                }));
            }

            // Cargar helper de Excel
            helper('ExcelHelper');
            
            // Crear archivo Excel usando PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Configurar encabezados
            $sheet->setTitle('Instructores');
            
            // Verificar si el logo existe antes de agregarlo
            if (\App\Helpers\ExcelHelper::logoExists('Logo PDF.jpg')) {
                // Crear encabezado estándar con logo
                \App\Helpers\ExcelHelper::createStandardHeader(
                    $sheet, 
                    'REPORTE DE INSTRUCTORES', 
                    'Sistema de Gestión Académica ITSI',
                    'Logo PDF.jpg',
                    'A1',
                    'D1'
                );
                $startRow = 5;
            } else {
                // Crear encabezado simple sin logo
                $sheet->setCellValue('A1', 'REPORTE DE INSTRUCTORES');
                $sheet->getStyle('A1')->getFont()
                    ->setBold(true)
                    ->setSize(16)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1A3A8A'));
                
                $sheet->setCellValue('A2', 'Sistema de Gestión Académica ITSI');
                $sheet->getStyle('A2')->getFont()
                    ->setBold(false)
                    ->setSize(12)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('009EE0'));
                
                $sheet->setCellValue('A3', 'Fecha de generación: ' . \App\Helpers\ExcelHelper::getCurrentDateTime());
                $sheet->getStyle('A3')->getFont()
                    ->setBold(false)
                    ->setSize(10)
                    ->setItalic(true);
                
                $startRow = 4;
            }
            
            // Encabezados de columnas
            $headers = [
                'ID',
                'Nombre',
                'Apellido',
                'Cédula',
                'Email',
                'Celular',
                'Tipo',
                'Especialidad',
                'Título',
                'Total Actividades',
                'Actividades Activas'
            ];
            
            // Crear encabezados de columnas con estilo
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, $startRow, 'A');
            
            // Llenar datos
            $row = $startRow + 1;
            foreach ($instructores as $instructor) {
                $sheet->setCellValue('A' . $row, $instructor['ID_INSTRUCTOR']);
                $sheet->setCellValue('B' . $row, $instructor['NOMBRE']);
                $sheet->setCellValue('C' . $row, $instructor['APELLIDO']);
                $sheet->setCellValue('D' . $row, $instructor['CEDULA']);
                $sheet->setCellValue('E' . $row, $instructor['EMAIL']);
                $sheet->setCellValue('F' . $row, $instructor['CELULAR']);
                $sheet->setCellValue('G' . $row, $instructor['TIPO_INSTRUCTOR']);
                $sheet->setCellValue('H' . $row, $instructor['ESPECIALIDAD']);
                $sheet->setCellValue('I' . $row, $instructor['TITULO_PROFESIONAL']);
                $sheet->setCellValue('J' . $row, $instructor['total_actividades']);
                $sheet->setCellValue('K' . $row, $instructor['actividades_activas']);
                $row++;
            }
            
            // Aplicar estilo a los datos
            if ($row > $startRow + 1) {
                \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A' . ($startRow + 1) . ':K' . ($row - 1));
            }
            
            // Autoajustar columnas
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'K');
            
            // Configurar headers para descarga
            $filename = 'instructores_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Configurar headers HTTP apropiados
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            
            // Escribir archivo
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            
            // Limpiar memoria
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            exit;

        } catch (\Exception $e) {
            // Log del error para debugging
            log_message('error', 'Error al exportar Excel de instructores: ' . $e->getMessage());
            
            // Limpiar output buffer si hay error
            if (ob_get_level()) {
                ob_end_clean();
            }
            
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
            $todos = $this->instructoresModel->getInstructoresConDatos();
            $instructores = $this->obtenerInstructoresExternosDesdeLista($todos);

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
            $idTipoExterno = $this->obtenerIdTipoInstructorExterno();
            $totalInstructores = 0;
            $instructoresActivos = 0;
            if ($idTipoExterno) {
                $totalInstructores = (int) $this->db->table('TAB_INSTRUCTORES')
                    ->where('ID_TIPO_INSTRUCTOR', $idTipoExterno)
                    ->countAllResults();
                $instructoresActivos = (int) $this->db->table('TAB_INSTRUCTORES i')
                    ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
                    ->where('i.ID_TIPO_INSTRUCTOR', $idTipoExterno)
                    ->where('dp.ACTIVO', 1)
                    ->countAllResults();
            }
            
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

        return view('coord/instructores/actividades', $data);
    }
}
