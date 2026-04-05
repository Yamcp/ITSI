<?php

namespace App\Controllers\docente;

use App\Models\ActividadesEducacionModel;
use App\Models\InstructoresModel;
use App\Models\LineasInvestigacionModel;
use App\Models\TiposModalidadesModel;
use App\Models\TiposActividadesModel;
use App\Models\InscripcionesActividadesModel;
use App\Models\EstudiantesModel;
use App\Models\EvaluacionesEnlacesModel;
use App\Controllers\BaseController;

class ActividadesEducacionDocenteController extends BaseController
{
    protected $actividadesModel;
    protected $instructoresModel;
    protected $lineasInvestigacionModel;
    protected $tiposModalidadesModel;
    protected $tiposActividadesModel;
    protected $inscripcionesModel;
    protected $estudiantesModel;
    protected $evaluacionesEnlacesModel;
    protected $db;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->instructoresModel = new InstructoresModel();
        $this->lineasInvestigacionModel = new LineasInvestigacionModel();
        $this->tiposModalidadesModel = new TiposModalidadesModel();
        $this->tiposActividadesModel = new TiposActividadesModel();
        $this->inscripcionesModel = new InscripcionesActividadesModel();
        $this->estudiantesModel = new EstudiantesModel();
        $this->evaluacionesEnlacesModel = new EvaluacionesEnlacesModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mapa ID_ACTIVIDAD_EDUCACION => enlace de evaluación de satisfacción.
     * Usado para mostrar el botón "Completar encuesta" automáticamente.
     */
    private function obtenerEncuestasSatisfaccionPorActividad()
    {
        $lista = $this->evaluacionesEnlacesModel
            ->where('TIPO_EVALUACION', 'satisfaccion')
            ->where('ACTIVO', true)
            ->where('ESTADO', 'activo')
            ->where('FECHA_VENCIMIENTO >=', date('Y-m-d'))
            ->findAll();

        $mapa = [];
        foreach ($lista as $ev) {
            $mapa[(int) $ev['ID_ACTIVIDAD_EDUCACION']] = $ev;
        }

        return $mapa;
    }

    /**
     * Obtiene ID_INSTRUCTOR del usuario logueado (docente) vía TAB_INSTRUCTORES + TAB_USUARIOS.
     */
    private function obtenerIdInstructorPorUsuario($idUsuario)
    {
        $row = $this->db->table('TAB_USUARIOS u')
            ->select('i.ID_INSTRUCTOR')
            ->join('TAB_INSTRUCTORES i', 'i.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();
        return $row ? (int) $row['ID_INSTRUCTOR'] : null;
    }

    public function index()
    {
        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        $actividades = ($idInstructor !== null && $idInstructor > 0)
            ? $this->actividadesModel->getActividadesConDatosPorInstructor($idInstructor)
            : [];

        $encuestasPorActividad = $this->obtenerEncuestasSatisfaccionPorActividad();

        $conteoParticipantes = [];
        foreach ($actividades as $act) {
            $conteoParticipantes[$act['ID_ACTIVIDAD_EDUCACION']] = $this->inscripcionesModel->contarPorActividad($act['ID_ACTIVIDAD_EDUCACION']);
        }

        $data = [
            'title' => 'Gestión de Actividades Educativas',
            'actividades' => $actividades,
            'encuestasPorActividad' => $encuestasPorActividad,
            'conteoParticipantes' => $conteoParticipantes,
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('docente/educacion/actividades_educacion', $data);
    }

    /**
     * Endpoint para que la vista actualice enlaces automáticamente (polling).
     */
    public function apiEncuestasSatisfaccion()
    {
        return $this->response->setJSON([
            'success' => true,
            'data' => $this->obtenerEncuestasSatisfaccionPorActividad()
        ]);
    }

    public function create()
    {
        $data = [
            'title' => 'Nueva Actividad Educativa',
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];

        return view('docente/educacion/create', $data);
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
            'ID_USUARIO' => session()->get('id_usuario'),
            'NOMBRE_ACTIVIDAD' => $this->request->getPost('nombre_actividad'),
            'DESCRIPCION' => $this->request->getPost('descripcion'),
            'OBJETIVOS' => $this->request->getPost('objetivos'),
            'DURACION_HORAS' => $this->request->getPost('duracion_horas'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'LUGAR' => trim((string) $this->request->getPost('lugar')),
            'ENLACE' => trim((string) $this->request->getPost('enlace')),
            'HORARIO' => $this->request->getPost('horario'),
            'INCLUYE_CERTIFICADO' => $this->request->getPost('incluye_certificado') ? 1 : 0,
            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        // Debug: Verificar datos antes de insertar
        log_message('debug', 'Datos a insertar: ' . json_encode($datos));
        log_message('debug', 'Usuario ID de sesión: ' . session()->get('id_usuario'));
        
        // Verificar si el usuario está logueado
        if (!session()->get('id_usuario')) {
            return redirect()->back()->withInput()->with('error', 'Error: No se encontró el ID de usuario en la sesión');
        }
        
        // Intentar insertar con manejo de errores más detallado
        try {
            if ($this->actividadesModel->insert($datos)) {
                return redirect()->to(site_url('docente/actividades-educacion'))->with('success', 'Actividad creada exitosamente');
            } else {
                $errors = $this->actividadesModel->errors();
                log_message('error', 'Errores del modelo: ' . json_encode($errors));
                return redirect()->back()->withInput()->with('error', 'Error al crear la actividad. Errores: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al insertar: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al crear la actividad: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);
        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'No tiene permiso para ver esta actividad.');
        }
        $data = [
            'title' => 'Detalles de la Actividad',
            'actividad' => $actividad
        ];
        return view('docente/educacion/show', $data);
    }

    public function edit($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);
        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'No tiene permiso para editar esta actividad.');
        }
        $data = [
            'title' => 'Editar Actividad Educativa',
            'actividad' => $actividad,
            'instructores' => $this->instructoresModel->getInstructoresConDatos(),
            'modalidades' => $this->tiposModalidadesModel->findAll(),
            'tipos_actividades' => $this->tiposActividadesModel->findAll()
        ];
        return view('docente/educacion/edit', $data);
    }

    public function update($id)
    {
        $actividad = $this->actividadesModel->find($id);
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if (!$actividad || $idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'No tiene permiso para editar esta actividad.');
        }
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
            'INCLUYE_CERTIFICADO' => $this->request->getPost('incluye_certificado') ? 1 : 0,
            'PROGRAMA_DETALLADO' => $this->request->getPost('programa_detallado')
        ];

        if ($this->actividadesModel->update($id, $datos)) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('success', 'Actividad actualizada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar la actividad');
    }

    public function delete($id)
    {
        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        $actividad = $this->actividadesModel->find($id);
        if (!$actividad || (int) ($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'No tiene permiso para eliminar esta actividad.');
        }
        if ($this->actividadesModel->delete($id)) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('success', 'Actividad eliminada exitosamente');
        }
        return redirect()->back()->with('error', 'Error al eliminar la actividad');
    }

    /**
     * Vista de gestión de participantes de una actividad
     */
    public function participantes($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);
        if (!$actividad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Actividad no encontrada');
        }
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if ($idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'No tiene permiso para gestionar participantes de esta actividad.');
        }
        $participantes = $this->inscripcionesModel->getParticipantesPorActividad($id);
        $estudiantes = $this->estudiantesModel->getEstudiantesParaInscripcion();

        $data = [
            'title' => 'Participantes - ' . $actividad['NOMBRE_ACTIVIDAD'],
            'actividad' => $actividad,
            'participantes' => $participantes,
            'estudiantes' => $estudiantes,
        ];

        return view('docente/educacion/participantes', $data);
    }

    /**
     * Agregar participante (estudiante) a una actividad
     */
    public function agregarParticipante()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $idActividad = (int) $this->request->getPost('id_actividad');
        $idEstudiante = (int) $this->request->getPost('id_estudiante');

        if (!$idActividad || !$idEstudiante) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }
        $actividad = $this->actividadesModel->find($idActividad);
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if (!$actividad || $idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tiene permiso para esta actividad']);
        }

        if ($this->inscripcionesModel->inscribir($idActividad, $idEstudiante)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Participante agregado correctamente']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'El estudiante ya está inscrito o no se pudo inscribir']);
    }

    /**
     * Quitar participante de una actividad
     */
    public function quitarParticipante()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $idActividad = (int) $this->request->getPost('id_actividad');
        $idEstudiante = (int) $this->request->getPost('id_estudiante');

        if (!$idActividad || !$idEstudiante) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }
        $actividad = $this->actividadesModel->find($idActividad);
        $idInstructor = $this->obtenerIdInstructorPorUsuario(session()->get('id_usuario'));
        if (!$actividad || $idInstructor === null || (int)($actividad['ID_INSTRUCTOR'] ?? 0) !== $idInstructor) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tiene permiso para esta actividad']);
        }

        if ($this->inscripcionesModel->quitarInscripcion($idActividad, $idEstudiante)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Participante dado de baja']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'No se pudo quitar la inscripción']);
    }

    // Método de prueba temporal para debug
    public function testInsert()
    {
        $datos = [
            'ID_INSTRUCTOR' => 1,
            'ID_TIPO_MODALIDAD' => 1,
            'ID_TIPO_ACTIVIDAD' => 1,
            'ID_USUARIO' => 1,
            'NOMBRE_ACTIVIDAD' => 'Prueba de Actividad',
            'DESCRIPCION' => 'Esta es una descripción de prueba para verificar la inserción',
            'OBJETIVOS' => 'Objetivos de prueba para verificar la inserción',
            'DURACION_HORAS' => 10,
            'FECHA_INICIO' => '2025-08-07',
            'FECHA_FIN' => '2025-08-08',
            'LUGAR' => 'Lugar de Prueba',
            'HORARIO' => '8:00-12:00',
            'INCLUYE_CERTIFICADO' => 1,
            'PROGRAMA_DETALLADO' => 'Programa de prueba'
        ];
        
        log_message('debug', 'Datos de prueba: ' . json_encode($datos));
        
        if ($this->actividadesModel->insert($datos)) {
            echo "Inserción exitosa! ID: " . $this->actividadesModel->getInsertID();
        } else {
            $errors = $this->actividadesModel->errors();
            echo "Error en inserción: " . implode(', ', $errors);
        }
    }

    public function calendario()
    {
        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        if ($idInstructor === null || $idInstructor <= 0) {
            return $this->response->setJSON([]);
        }

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
            ->where('ae.ID_INSTRUCTOR', $idInstructor)
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
        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        $actividades = ($idInstructor !== null && $idInstructor > 0)
            ? $this->actividadesModel->getActividadesConDatosPorInstructor($idInstructor)
            : [];
        return $this->response->setJSON($actividades);
    }

    // Método para obtener estadísticas (solo del docente logueado)
    public function getEstadisticas()
    {
        $idUsuario = session()->get('id_usuario');
        $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
        if ($idInstructor === null || $idInstructor <= 0) {
            return $this->response->setJSON([
                'totalActividades' => 0,
                'cursosActivos' => 0,
                'talleresActivos' => 0,
                'seminariosActivos' => 0
            ]);
        }
        $totalActividades = $this->actividadesModel->where('ID_INSTRUCTOR', $idInstructor)->countAllResults();
        $cursosActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR', $idInstructor)
            ->where('ta.ACTIVIDAD', 'Curso')
            ->where('TAB_ACTIVIDADES_EDUCACION.FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();
        $talleresActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR', $idInstructor)
            ->where('ta.ACTIVIDAD', 'Taller')
            ->where('TAB_ACTIVIDADES_EDUCACION.FECHA_FIN >=', date('Y-m-d'))
            ->countAllResults();
        $seminariosActivos = $this->actividadesModel
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = TAB_ACTIVIDADES_EDUCACION.ID_TIPO_ACTIVIDAD')
            ->where('TAB_ACTIVIDADES_EDUCACION.ID_INSTRUCTOR', $idInstructor)
            ->where('ta.ACTIVIDAD', 'Seminario')
            ->where('TAB_ACTIVIDADES_EDUCACION.FECHA_FIN >=', date('Y-m-d'))
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

            return view('docente/educacion/reportes', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error en reportes: ' . $e->getMessage());
            return redirect()->to(site_url('docente/actividades-educacion'))->with('error', 'Error al cargar los reportes: ' . $e->getMessage());
        }
    }

    private function aplicarFiltrosReporte($filtros)
    {
        try {
            $idUsuario = session()->get('id_usuario');
            $idInstructor = $this->obtenerIdInstructorPorUsuario($idUsuario);
            $actividades = ($idInstructor !== null && $idInstructor > 0)
                ? $this->actividadesModel->getActividadesConDatosPorInstructor($idInstructor)
                : [];

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
        $html = view('docente/educacion/reportes_pdf', $data);

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