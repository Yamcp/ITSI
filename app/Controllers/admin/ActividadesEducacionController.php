<?php

namespace App\Controllers\admin;

use App\Models\ActividadesEducacionModel;
use App\Models\InstructoresModel;
use App\Models\LineasInvestigacionModel;
use App\Models\TiposModalidadesModel;
use App\Models\TiposActividadesModel;
use App\Controllers\BaseController;

class ActividadesEducacionController extends BaseController
{
    protected $actividadesModel;
    protected $instructoresModel;
    protected $lineasInvestigacionModel;
    protected $tiposModalidadesModel;
    protected $tiposActividadesModel;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->instructoresModel = new InstructoresModel();
        $this->lineasInvestigacionModel = new LineasInvestigacionModel(); // Corregido: sin tilde
        $this->tiposModalidadesModel = new TiposModalidadesModel();
        $this->tiposActividadesModel = new TiposActividadesModel();
    }

    public function index()
    {
        $actividades = $this->actividadesModel->getActividadesConDatos();
        
        // Depuración temporal - remover en producción
        log_message('debug', 'Actividades cargadas: ' . json_encode($actividades));

        $data = [
            'title' => 'Gestión de Actividades Educativas',
            'actividades' => $actividades,
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('admin/educacion/actividades_educacion_views', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Actividad Educativa',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('admin/educacion/create', $data);
    }

    public function store()
    {
        $rules = [
            'tipo_actividad' => 'required|integer',
            'nombre_actividad' => 'required|max_length[200]',
            'instructor' => 'required|integer',
            'modalidad' => 'required|integer',
            'descripcion' => 'required|min_length[10]',
            'objetivos' => 'required|min_length[10]',
            'duracion_horas' => 'required|integer|greater_than[0]',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'lugar' => 'required|max_length[150]',
            'horario' => 'required|max_length[100]'
        ];

        $messages = [
            'tipo_actividad' => [
                'required' => 'El tipo de actividad es obligatorio',
                'integer' => 'Debe seleccionar un tipo de actividad válido'
            ],
            'nombre_actividad' => [
                'required' => 'El nombre de la actividad es obligatorio',
                'max_length' => 'El nombre no puede exceder 200 caracteres'
            ],
            'instructor' => [
                'required' => 'Debe seleccionar un instructor',
                'integer' => 'Debe seleccionar un instructor válido'
            ],
            'modalidad' => [
                'required' => 'La modalidad es obligatoria',
                'integer' => 'Debe seleccionar una modalidad válida'
            ],
            'descripcion' => [
                'required' => 'La descripción es obligatoria',
                'min_length' => 'La descripción debe tener al menos 10 caracteres'
            ],
            'objetivos' => [
                'required' => 'Los objetivos son obligatorios',
                'min_length' => 'Los objetivos deben tener al menos 10 caracteres'
            ],
            'duracion_horas' => [
                'required' => 'La duración en horas es obligatoria',
                'integer' => 'La duración debe ser un número entero',
                'greater_than' => 'La duración debe ser mayor a 0 horas'
            ],
            'fecha_inicio' => [
                'required' => 'La fecha de inicio es obligatoria',
                'valid_date' => 'La fecha de inicio debe ser válida'
            ],
            'fecha_fin' => [
                'required' => 'La fecha de fin es obligatoria',
                'valid_date' => 'La fecha de fin debe ser válida'
            ],
            'lugar' => [
                'required' => 'El lugar es obligatorio',
                'max_length' => 'El lugar no puede exceder 150 caracteres'
            ],
            'horario' => [
                'required' => 'El horario es obligatorio',
                'max_length' => 'El horario no puede exceder 100 caracteres'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'ID_INSTRUCTOR' => $this->request->getPost('instructor'),
            'ID_TIPO_MODALIDAD' => $this->request->getPost('modalidad'),
            'ID_TIPO_ACTIVIDAD' => $this->request->getPost('tipo_actividad'),
            'ID_USUARIO' => session()->get('usuario_id'),
            'NOMBRE_ACTIVIDAD' => $this->request->getPost('nombre_actividad'),
            'DESCRIPCION' => $this->request->getPost('descripcion'),
            'OBJETIVOS' => $this->request->getPost('objetivos'),
            'DURACION_HORAS' => $this->request->getPost('duracion_horas'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'LUGAR' => $this->request->getPost('lugar'),
            'HORARIO' => $this->request->getPost('horario'),

            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->insert($datos)) {
            return redirect()->to('/admin/actividades-educacion')->with('success', 'Actividad creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la actividad');
    }

    public function show($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);

        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }

        $data = [
            'title' => 'Detalles de la Actividad',
            'actividad' => $actividad
        ];

        return view('admin/educacion/show', $data);
    }

    public function edit($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);

        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }

        $data = [
            'title' => 'Editar Actividad Educativa',
            'actividad' => $actividad,
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('admin/educacion/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'tipo_actividad' => 'required|integer',
            'nombre_actividad' => 'required|max_length[200]',
            'instructor' => 'required|integer',
            'modalidad' => 'required|integer',
            'descripcion' => 'required|min_length[10]',
            'objetivos' => 'required|min_length[10]',
            'duracion_horas' => 'required|integer|greater_than[0]',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'lugar' => 'required|max_length[150]',
            'horario' => 'required|max_length[100]'
        ];

        $messages = [
            'tipo_actividad' => [
                'required' => 'El tipo de actividad es obligatorio',
                'integer' => 'Debe seleccionar un tipo de actividad válido'
            ],
            'nombre_actividad' => [
                'required' => 'El nombre de la actividad es obligatorio',
                'max_length' => 'El nombre no puede exceder 200 caracteres'
            ],
            'instructor' => [
                'required' => 'Debe seleccionar un instructor',
                'integer' => 'Debe seleccionar un instructor válido'
            ],
            'modalidad' => [
                'required' => 'La modalidad es obligatoria',
                'integer' => 'Debe seleccionar una modalidad válida'
            ],
            'descripcion' => [
                'required' => 'La descripción es obligatoria',
                'min_length' => 'La descripción debe tener al menos 10 caracteres'
            ],
            'objetivos' => [
                'required' => 'Los objetivos son obligatorios',
                'min_length' => 'Los objetivos deben tener al menos 10 caracteres'
            ],
            'duracion_horas' => [
                'required' => 'La duración en horas es obligatoria',
                'integer' => 'La duración debe ser un número entero',
                'greater_than' => 'La duración debe ser mayor a 0 horas'
            ],
            'fecha_inicio' => [
                'required' => 'La fecha de inicio es obligatoria',
                'valid_date' => 'La fecha de inicio debe ser válida'
            ],
            'fecha_fin' => [
                'required' => 'La fecha de fin es obligatoria',
                'valid_date' => 'La fecha de fin debe ser válida'
            ],
            'lugar' => [
                'required' => 'El lugar es obligatorio',
                'max_length' => 'El lugar no puede exceder 150 caracteres'
            ],
            'horario' => [
                'required' => 'El horario es obligatorio',
                'max_length' => 'El horario no puede exceder 100 caracteres'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'ID_INSTRUCTOR' => $this->request->getPost('instructor'),
            'ID_TIPO_MODALIDAD' => $this->request->getPost('modalidad'),
            'ID_TIPO_ACTIVIDAD' => $this->request->getPost('tipo_actividad'),
            'NOMBRE_ACTIVIDAD' => $this->request->getPost('nombre_actividad'),
            'DESCRIPCION' => $this->request->getPost('descripcion'),
            'OBJETIVOS' => $this->request->getPost('objetivos'),
            'DURACION_HORAS' => $this->request->getPost('duracion_horas'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'LUGAR' => $this->request->getPost('lugar'),
            'HORARIO' => $this->request->getPost('horario'),

            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->update($id, $datos)) {
            return redirect()->to('/admin/actividades-educacion')->with('success', 'Actividad actualizada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar la actividad');
    }

    public function delete($id)
    {
        if ($this->actividadesModel->delete($id)) {
            return redirect()->to('/admin/actividades-educacion')->with('success', 'Actividad eliminada exitosamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar la actividad');
    }

    public function calendario()
    {
        $actividades = $this->actividadesModel
            ->select('ae.ID_ACTIVIDAD_EDUCACION, ae.NOMBRE_ACTIVIDAD, ae.FECHA_INICIO, ae.FECHA_FIN, ae.LUGAR, ae.HORARIO, ae.DURACION_HORAS, ae.DESCRIPCION, ta.ACTIVIDAD as TIPO_ACTIVIDAD, tm.MODALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->from('TAB_ACTIVIDADES_EDUCACION ae')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->where('ae.FECHA_FIN >=', date('Y-m-d'))
            ->orderBy('ae.FECHA_INICIO', 'ASC')
            ->findAll();

        // Formatear para calendario
        $eventos = [];
        foreach ($actividades as $actividad) {
            $color = '#007bff'; // Azul por defecto
            if ($actividad['TIPO_ACTIVIDAD'] === 'Taller') {
                $color = '#28a745'; // Verde
            } elseif ($actividad['TIPO_ACTIVIDAD'] === 'Seminario') {
                $color = '#17a2b8'; // Azul claro
            }

            $eventos[] = [
                'id' => $actividad['ID_ACTIVIDAD_EDUCACION'],
                'title' => $actividad['NOMBRE_ACTIVIDAD'],
                'start' => $actividad['FECHA_INICIO'],
                'end' => date('Y-m-d', strtotime($actividad['FECHA_FIN'] . ' +1 day')),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'tipo' => $actividad['TIPO_ACTIVIDAD'],
                    'instructor' => $actividad['NOMBRE'] . ' ' . $actividad['APELLIDO'],
                    'lugar' => $actividad['LUGAR'],
                    'horario' => $actividad['HORARIO'],
                    'duracion' => $actividad['DURACION_HORAS'],
                    'descripcion' => $actividad['DESCRIPCION'],
                    'modalidad' => $actividad['MODALIDAD']
                ]
            ];
        }

        return $this->response->setJSON($eventos);
    }

    // Método para obtener datos para AJAX
    public function getActividades()
    {
        $actividades = $this->actividadesModel->getActividadesConDatos();
        return $this->response->setJSON($actividades);
    }

    // Método para obtener estadísticas
    public function getEstadisticas()
    {
        $totalActividades = $this->actividadesModel->countAllResults();
        
        $cursosActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('ta.ACTIVIDAD', 'Curso')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();

        $talleresActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('ta.ACTIVIDAD', 'Taller')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();

        $seminariosActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('ta.ACTIVIDAD', 'Seminario')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();

        return $this->response->setJSON([
            'totalActividades' => $totalActividades,
            'cursosActivos' => $cursosActivos,
            'talleresActivos' => $talleresActivos,
            'seminariosActivos' => $seminariosActivos
        ]);
    }

    // Métodos para reportes y exportación
    public function reportes()
    {
        try {
            $filtros = [
                'tipo_actividad' => $this->request->getGet('tipo_actividad'),
                'modalidad' => $this->request->getGet('modalidad'),
                'fecha_inicio' => $this->request->getGet('fecha_inicio'),
                'fecha_fin' => $this->request->getGet('fecha_fin'),
                'instructor' => $this->request->getGet('instructor')
            ];

            $actividades = $this->aplicarFiltrosReporte($filtros);

            $data = [
                'title' => 'Reportes de Actividades Educativas',
                'actividades' => $actividades,
                'filtros' => $filtros,
                'tipos_actividades' => $this->tiposActividadesModel->findAll(),
                'modalidades' => $this->tiposModalidadesModel->findAll(),
                'instructores' => $this->instructoresModel->getInstructoresConDatos()
            ];

            return view('admin/educacion/reportes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en reportes: ' . $e->getMessage());
            return redirect()->to('/admin/actividades-educacion')->with('error', 'Error al cargar los reportes: ' . $e->getMessage());
        }
    }

    private function aplicarFiltrosReporte($filtros)
    {
        try {
            // Usar el método existente del modelo para obtener datos
            $actividades = $this->actividadesModel->getActividadesConDatos();
            
            // Aplicar filtros manualmente si es necesario
            if (!empty($filtros['tipo_actividad'])) {
                $actividades = array_filter($actividades, function($actividad) use ($filtros) {
                    return $actividad['ID_TIPO_ACTIVIDAD'] == $filtros['tipo_actividad'];
                });
            }

            if (!empty($filtros['modalidad'])) {
                $actividades = array_filter($actividades, function($actividad) use ($filtros) {
                    return $actividad['ID_TIPO_MODALIDAD'] == $filtros['modalidad'];
                });
            }

            if (!empty($filtros['fecha_inicio'])) {
                $actividades = array_filter($actividades, function($actividad) use ($filtros) {
                    return $actividad['FECHA_INICIO'] >= $filtros['fecha_inicio'];
                });
            }

            if (!empty($filtros['fecha_fin'])) {
                $actividades = array_filter($actividades, function($actividad) use ($filtros) {
                    return $actividad['FECHA_FIN'] <= $filtros['fecha_fin'];
                });
            }

            if (!empty($filtros['instructor'])) {
                $actividades = array_filter($actividades, function($actividad) use ($filtros) {
                    return $actividad['ID_INSTRUCTOR'] == $filtros['instructor'];
                });
            }

            return array_values($actividades); // Reindexar el array
        } catch (\Exception $e) {
            log_message('error', 'Error en aplicarFiltrosReporte: ' . $e->getMessage());
            return [];
        }
    }

    public function exportarPDF()
    {
        $filtros = [
            'tipo_actividad' => $this->request->getGet('tipo_actividad'),
            'modalidad' => $this->request->getGet('modalidad'),
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin' => $this->request->getGet('fecha_fin'),
            'instructor' => $this->request->getGet('instructor')
        ];

        $actividades = $this->aplicarFiltrosReporte($filtros);

        $data = [
            'actividades' => $actividades,
            'filtros' => $filtros,
            'fecha_generacion' => date('d/m/Y H:i:s'),
            'total_actividades' => count($actividades)
        ];

        // Generar HTML para PDF
        $html = view('admin/educacion/pdf/reportes', $data);

        // Configurar headers para descarga
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="reporte_actividades_' . date('Y-m-d') . '.pdf"');

        return $html; // Aquí deberías integrar con TCPDF o DomPDF
    }

    public function exportarExcel()
    {
        $filtros = [
            'tipo_actividad' => $this->request->getGet('tipo_actividad'),
            'modalidad' => $this->request->getGet('modalidad'),
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin' => $this->request->getGet('fecha_fin'),
            'instructor' => $this->request->getGet('instructor')
        ];

        $actividades = $this->aplicarFiltrosReporte($filtros);

        // Crear archivo Excel usando PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar encabezados
        $sheet->setTitle('Actividades Educativas');
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Actividad');
        $sheet->setCellValue('C1', 'Tipo');
        $sheet->setCellValue('D1', 'Instructor');
        $sheet->setCellValue('E1', 'Modalidad');
        $sheet->setCellValue('F1', 'Fecha Inicio');
        $sheet->setCellValue('G1', 'Fecha Fin');
        $sheet->setCellValue('H1', 'Duración (h)');
        $sheet->setCellValue('I1', 'Lugar');
        $sheet->setCellValue('J1', 'Horario');

        // Llenar datos
        $row = 2;
        foreach ($actividades as $actividad) {
            $sheet->setCellValue('A' . $row, $actividad['ID_ACTIVIDAD_EDUCACION']);
            $sheet->setCellValue('B' . $row, $actividad['NOMBRE_ACTIVIDAD']);
            $sheet->setCellValue('C' . $row, $actividad['ACTIVIDAD']);
            $sheet->setCellValue('D' . $row, $actividad['NOMBRE'] . ' ' . $actividad['APELLIDO']);
            $sheet->setCellValue('E' . $row, $actividad['MODALIDAD']);
            $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($actividad['FECHA_INICIO'])));
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($actividad['FECHA_FIN'])));
            $sheet->setCellValue('H' . $row, $actividad['DURACION_HORAS']);
            $sheet->setCellValue('I' . $row, $actividad['LUGAR']);
            $sheet->setCellValue('J' . $row, $actividad['HORARIO']);
            $row++;
        }

        // Autoajustar columnas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Configurar headers para descarga
        $filename = 'reporte_actividades_' . date('Y-m-d') . '.xlsx';
        
        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'max-age=0');

        // Escribir archivo
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        
        return $this->response;
    }

    public function exportarCSV()
    {
        $filtros = [
            'tipo_actividad' => $this->request->getGet('tipo_actividad'),
            'modalidad' => $this->request->getGet('modalidad'),
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin' => $this->request->getGet('fecha_fin'),
            'instructor' => $this->request->getGet('instructor')
        ];

        $actividades = $this->aplicarFiltrosReporte($filtros);

        // Configurar headers para CSV
        $filename = 'reporte_actividades_' . date('Y-m-d') . '.csv';
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Crear contenido CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, [
            'ID', 'Actividad', 'Tipo', 'Instructor', 'Modalidad', 
            'Fecha Inicio', 'Fecha Fin', 'Duración (h)', 'Lugar', 'Horario'
        ]);

        // Datos
        foreach ($actividades as $actividad) {
            fputcsv($output, [
                $actividad['ID_ACTIVIDAD_EDUCACION'],
                $actividad['NOMBRE_ACTIVIDAD'],
                $actividad['ACTIVIDAD'],
                $actividad['NOMBRE'] . ' ' . $actividad['APELLIDO'],
                $actividad['MODALIDAD'],
                date('d/m/Y', strtotime($actividad['FECHA_INICIO'])),
                date('d/m/Y', strtotime($actividad['FECHA_FIN'])),
                $actividad['DURACION_HORAS'],
                $actividad['LUGAR'],
                $actividad['HORARIO']
            ]);
        }

        fclose($output);
        return $this->response;
    }
}