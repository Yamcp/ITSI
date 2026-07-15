<?php

namespace App\Controllers\coord;

use App\Models\ActividadesEducacionModel;
use App\Models\InstructoresModel;
use App\Models\LineasInvestigacionModel;
use App\Models\TiposModalidadesModel;
use App\Models\TiposActividadesModel;
use App\Models\EvaluacionesEnlacesModel;
use App\Models\CarrerasModel;
use App\Controllers\BaseController;

class ActividadesEducacionCoordController extends BaseController
{
    protected $actividadesModel;
    protected $instructoresModel;
    protected $lineasInvestigacionModel;
    protected $tiposModalidadesModel;
    protected $tiposActividadesModel;
    protected $evaluacionesEnlacesModel;
    protected $carrerasModel;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->instructoresModel = new InstructoresModel();
        $this->lineasInvestigacionModel = new LineasInvestigacionModel();
        $this->tiposModalidadesModel = new TiposModalidadesModel();
        $this->tiposActividadesModel = new TiposActividadesModel();
        $this->evaluacionesEnlacesModel = new EvaluacionesEnlacesModel();
        $this->carrerasModel = new CarrerasModel();
    }

    public function index()
    {
        $actividades = $this->actividadesModel->getActividadesConDatos();
        $encuestasPorActividad = $this->obtenerEncuestasSatisfaccionPorActividad();

        $data = [
            'title' => 'Gestión de Actividades Educativas',
            'actividades' => $actividades,
            'encuestasPorActividad' => $encuestasPorActividad,
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('coord/educacion/actividades_educacion', $data);
    }

    /**
     * Mapa ID_ACTIVIDAD_EDUCACION => evaluación de satisfacción (para listado).
     */
    private function obtenerEncuestasSatisfaccionPorActividad()
    {
        $lista = $this->evaluacionesEnlacesModel
            ->where('TIPO_EVALUACION', 'satisfaccion')
            ->where('ACTIVO', true)
            ->findAll();
        $mapa = [];
        foreach ($lista as $ev) {
            $mapa[(int) $ev['ID_ACTIVIDAD_EDUCACION']] = $ev;
        }
        return $mapa;
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Actividad Educativa',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('coord/educacion/create', $data);
    }

    public function store()
    {
        $idModalidad = (int) $this->request->getPost('modalidad');
        $filaModalidad = $idModalidad > 0 ? $this->tiposModalidadesModel->find($idModalidad) : null;
        $slugMod = ActividadesEducacionModel::slugModalidadDesdeNombre($filaModalidad['MODALIDAD'] ?? '');
        $reglasLyE = ActividadesEducacionModel::reglasLugarEnlacePorSlug($slugMod);

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
            'lugar' => $reglasLyE['lugar'],
            'enlace' => $reglasLyE['enlace'],
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
                'required' => 'El lugar es obligatorio para modalidad presencial o híbrida',
                'max_length' => 'El lugar no puede exceder 150 caracteres'
            ],
            'enlace' => [
                'required' => 'El enlace es obligatorio para modalidad virtual o híbrida',
                'max_length' => 'El enlace no puede exceder 500 caracteres'
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
            'LUGAR' => trim((string) $this->request->getPost('lugar')),
            'ENLACE' => trim((string) $this->request->getPost('enlace')),
            'HORARIO' => $this->request->getPost('horario'),

            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->insert($datos)) {
            return redirect()->to('/coord/actividades-educacion')->with('success', 'Actividad creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la actividad');
    }

    public function show($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);

        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }

        $encuestaSatisfaccion = $this->evaluacionesEnlacesModel
            ->where('ID_ACTIVIDAD_EDUCACION', $id)
            ->where('TIPO_EVALUACION', 'satisfaccion')
            ->where('ACTIVO', true)
            ->first();

        $data = [
            'title' => 'Detalles de la Actividad',
            'actividad' => $actividad,
            'encuestaSatisfaccion' => $encuestaSatisfaccion
        ];

        return view('coord/educacion/show', $data);
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

        return view('coord/educacion/edit', $data);
    }

    public function update($id)
    {
        $idModalidad = (int) $this->request->getPost('modalidad');
        $filaModalidad = $idModalidad > 0 ? $this->tiposModalidadesModel->find($idModalidad) : null;
        $slugMod = ActividadesEducacionModel::slugModalidadDesdeNombre($filaModalidad['MODALIDAD'] ?? '');
        $reglasLyE = ActividadesEducacionModel::reglasLugarEnlacePorSlug($slugMod);

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
            'lugar' => $reglasLyE['lugar'],
            'enlace' => $reglasLyE['enlace'],
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
                'required' => 'El lugar es obligatorio para modalidad presencial o híbrida',
                'max_length' => 'El lugar no puede exceder 150 caracteres'
            ],
            'enlace' => [
                'required' => 'El enlace es obligatorio para modalidad virtual o híbrida',
                'max_length' => 'El enlace no puede exceder 500 caracteres'
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
            'LUGAR' => trim((string) $this->request->getPost('lugar')),
            'ENLACE' => trim((string) $this->request->getPost('enlace')),
            'HORARIO' => $this->request->getPost('horario'),

            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->update($id, $datos)) {
            return redirect()->to('/coord/actividades-educacion')->with('success', 'Actividad actualizada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar la actividad');
    }

    public function delete($id)
    {
        if ($this->actividadesModel->delete($id)) {
            return redirect()->to('/coord/actividades-educacion')->with('success', 'Actividad eliminada exitosamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar la actividad');
    }

    public function calendario()
    {
        $selectCal = 'ae.ID_ACTIVIDAD_EDUCACION, ae.NOMBRE_ACTIVIDAD, ae.FECHA_INICIO, ae.FECHA_FIN, ae.LUGAR, ';
        if ($this->actividadesModel->tablaTieneColumnaEnlace()) {
            $selectCal .= 'ae.ENLACE, ';
        }
        $selectCal .= 'ae.HORARIO, ae.DURACION_HORAS, ae.DESCRIPCION, ta.ACTIVIDAD as TIPO_ACTIVIDAD, tm.MODALIDAD, dp.NOMBRE, dp.APELLIDO';

        $actividades = $this->actividadesModel
            ->select($selectCal)
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
            } elseif ($actividad['TIPO_ACTIVIDAD'] === 'Conferencia') {
                $color = '#17a2b8'; // Azul claro
            } elseif ($actividad['TIPO_ACTIVIDAD'] === 'Capacitación') {
                $color = '#fd7e14'; // Naranja
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
                    'enlace' => $actividad['ENLACE'] ?? '',
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

        $conferenciasActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('ta.ACTIVIDAD', 'Conferencia')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();

        $capacitacionesActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('ta.ACTIVIDAD', 'Capacitación')
            ->where('FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();

        return $this->response->setJSON([
            'totalActividades' => $totalActividades,
            'cursosActivos' => $cursosActivos,
            'talleresActivos' => $talleresActivos,
            'conferenciasActivos' => $conferenciasActivos,
            'capacitacionesActivos' => $capacitacionesActivos
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
                'instructor' => $this->request->getGet('instructor'),
                'carrera' => $this->request->getGet('carrera')
            ];

            $actividades = $this->aplicarFiltrosReporte($filtros);

            $data = [
                'title' => 'Reportes de Actividades Educativas',
                'actividades' => $actividades,
                'filtros' => $filtros,
                'tipos_actividades' => $this->tiposActividadesModel->findAll(),
                'modalidades' => $this->tiposModalidadesModel->findAll(),
                'instructores' => $this->instructoresModel->getInstructoresConDatos(),
                'carreras' => $this->carrerasModel->orderBy('NOMBRE')->findAll()
            ];

            return view('coord/educacion/reportes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en reportes: ' . $e->getMessage());
            return redirect()->to('/coord/actividades-educacion')->with('error', 'Error al cargar los reportes: ' . $e->getMessage());
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

            if (!empty($filtros['carrera'])) {
                $idCarrera = (int) $filtros['carrera'];
                if ($idCarrera > 0) {
                    $db = \Config\Database::connect();
                    $ids = $db->table('TAB_INSCRIPCIONES_ACTIVIDADES ia')
                        ->select('ia.ID_ACTIVIDAD_EDUCACION')
                        ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = ia.ID_ESTUDIANTE')
                        ->where('e.ID_CARRERA', $idCarrera)
                        ->groupBy('ia.ID_ACTIVIDAD_EDUCACION')
                        ->get()
                        ->getResultArray();
                    $idsActividad = array_map('intval', array_column($ids, 'ID_ACTIVIDAD_EDUCACION'));
                    $actividades = array_filter($actividades, function ($actividad) use ($idsActividad) {
                        return in_array((int) $actividad['ID_ACTIVIDAD_EDUCACION'], $idsActividad, true);
                    });
                }
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
            'instructor' => $this->request->getGet('instructor'),
            'carrera' => $this->request->getGet('carrera')
        ];

        $actividades = $this->aplicarFiltrosReporte($filtros);

        $data = [
            'actividades' => $actividades,
            'filtros' => $filtros,
            'fecha_generacion' => date('d/m/Y H:i:s'),
            'total_actividades' => count($actividades)
        ];

        // Generar HTML para PDF
        $html = view('coord/educacion/reportes_pdf', $data);

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
            'instructor' => $this->request->getGet('instructor'),
            'carrera' => $this->request->getGet('carrera')
        ];

        $actividades = $this->aplicarFiltrosReporte($filtros);

        // Cargar helper de Excel
        helper('ExcelHelper');
        
        // Crear archivo Excel usando PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar encabezados
        $sheet->setTitle('Actividades Educativas');
        
        // Crear encabezado estándar con logo
        \App\Helpers\ExcelHelper::createStandardHeader(
            $sheet, 
            'REPORTE DE ACTIVIDADES EDUCATIVAS', 
            'Sistema de Gestión Académica ITSI',
            'Logo PDF.jpg',
            'A1',
            'D1'
        );
        
        // Encabezados de columnas
        $headers = [
            'ID',
            'Actividad',
            'Tipo',
            'Instructor',
            'Modalidad',
            'Fecha Inicio',
            'Fecha Fin',
            'Duración (h)',
            'Lugar',
            'Horario'
        ];
        
        // Crear encabezados de columnas con estilo
        \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');

        // Llenar datos
        $row = 6; // Empezar después del encabezado
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
        
        // Aplicar estilo a los datos
        if ($row > 6) {
            \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:J' . ($row - 1));
        }

        // Autoajustar columnas
        \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'J');

        // Configurar headers para descarga
        $filename = 'reporte_actividades_' . date('Y-m-d') . '.xlsx';
        \App\Helpers\ExcelHelper::setDownloadHeaders($filename);

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
            'instructor' => $this->request->getGet('instructor'),
            'carrera' => $this->request->getGet('carrera')
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