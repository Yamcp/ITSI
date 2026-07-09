<?php

namespace App\Controllers\docente;

use App\Models\ActividadesEducacionModel;
use App\Models\DocentesTutoresModel;
use App\Models\EvaluacionesEnlacesModel;
use App\Models\InscripcionesActividadesModel;
use App\Controllers\BaseController;

class ActividadesEducacionDocenteController extends BaseController
{
    protected $actividadesModel;
    protected $docentesTutoresModel;
    protected $inscripcionesModel;
    protected $evaluacionesEnlacesModel;

    public function __construct()
    {
        $this->actividadesModel = new ActividadesEducacionModel();
        $this->docentesTutoresModel = new DocentesTutoresModel();
        $this->inscripcionesModel = new InscripcionesActividadesModel();
        $this->evaluacionesEnlacesModel = new EvaluacionesEnlacesModel();
    }

    /**
     * El docente no puede crear, editar ni eliminar actividades.
     */
    private function denegarGestion()
    {
        return redirect()->to(site_url('docente/actividades-educacion'))
            ->with('error', 'No tiene permiso para gestionar cursos. Solo puede consultar e inscribirse.');
    }

    /**
     * ID_DOCENTE_TUTOR del usuario en sesión, o null.
     */
    private function obtenerIdDocenteTutorSesion(): ?int
    {
        $idUsuario = (int) session()->get('id_usuario');
        if ($idUsuario < 1) {
            return null;
        }

        $docente = $this->docentesTutoresModel->getDocentePorUsuario($idUsuario);

        return $docente ? (int) $docente['ID_DOCENTE_TUTOR'] : null;
    }

    /**
     * Mapa ID_ACTIVIDAD_EDUCACION => enlace de evaluación de satisfacción.
     */
    private function obtenerEncuestasSatisfaccionPorActividad(): array
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

    public function index()
    {
        $idDocente = $this->obtenerIdDocenteTutorSesion();
        $actividadesVigentes = $this->actividadesModel->getActividadesVigentesConDatos();
        $actividadesInscritas = $idDocente !== null
            ? $this->inscripcionesModel->obtenerMapaInscritasPorDocente($idDocente)
            : [];

        $data = [
            'title' => 'Actividades Educativas',
            'actividades' => $actividadesVigentes,
            'actividadesInscritas' => $actividadesInscritas,
            'encuestasPorActividad' => $this->obtenerEncuestasSatisfaccionPorActividad(),
        ];

        return view('docente/educacion/actividades_educacion', $data);
    }

    public function apiEncuestasSatisfaccion()
    {
        return $this->response->setJSON([
            'success' => true,
            'data' => $this->obtenerEncuestasSatisfaccionPorActividad(),
        ]);
    }

    /**
     * Detalle de una actividad en JSON (para modal Ver detalle).
     */
    public function detalle($id)
    {
        $actividad = $this->actividadesModel->getActividadCompleta($id);
        if (! $actividad) {
            return $this->response->setJSON(['success' => false, 'message' => 'Actividad no encontrada']);
        }

        $fechaFin = (string) ($actividad['FECHA_FIN'] ?? '');
        if ($fechaFin !== '' && $fechaFin < date('Y-m-d')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Esta actividad ya no está vigente']);
        }

        $actividad['ACTIVIDAD'] = $actividad['TIPO_ACTIVIDAD'] ?? $actividad['ACTIVIDAD'] ?? '';

        return $this->response->setJSON(['success' => true, 'data' => $actividad]);
    }

    /**
     * El docente autenticado se inscribe en una actividad vigente.
     */
    public function inscribirse()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Método no permitido',
            ]);
        }

        $idActividad = (int) $this->request->getPost('id_actividad');
        if ($idActividad < 1) {
            $json = $this->request->getJSON(true);
            if (is_array($json) && ! empty($json['id_actividad'])) {
                $idActividad = (int) $json['id_actividad'];
            }
        }

        if ($idActividad < 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Actividad no válida']);
        }

        $idDocente = $this->obtenerIdDocenteTutorSesion();
        if ($idDocente === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontró tu perfil de docente. Inicia sesión de nuevo.',
            ]);
        }

        $actividad = $this->actividadesModel->find($idActividad);
        if (! $actividad) {
            return $this->response->setJSON(['success' => false, 'message' => 'Actividad no encontrada']);
        }

        $fechaFin = (string) ($actividad['FECHA_FIN'] ?? '');
        if ($fechaFin !== '' && $fechaFin < date('Y-m-d')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Esta actividad ya finalizó; no es posible inscribirse.',
            ]);
        }

        if ($this->inscripcionesModel->estaInscritoDocente($idActividad, $idDocente)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya estás inscrito en esta actividad.',
            ]);
        }

        if ($this->inscripcionesModel->inscribirDocente($idActividad, $idDocente)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inscripción registrada correctamente.',
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo completar la inscripción. Intenta de nuevo.',
        ]);
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

        $eventosPorId = [];
        foreach ($actividades as $actividad) {
            $idActividad = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
            if (isset($eventosPorId[$idActividad])) {
                continue;
            }

            $color = '#007bff';
            if ($actividad['TIPO_ACTIVIDAD'] === 'Taller') {
                $color = '#28a745';
            } elseif ($actividad['TIPO_ACTIVIDAD'] === 'Seminario') {
                $color = '#17a2b8';
            }

            $eventosPorId[$idActividad] = [
                'id' => (string) $idActividad,
                'title' => $actividad['NOMBRE_ACTIVIDAD'],
                'start' => $actividad['FECHA_INICIO'],
                'end' => date('Y-m-d', strtotime($actividad['FECHA_FIN'] . ' +1 day')),
                'allDay' => true,
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
                    'modalidad' => $actividad['MODALIDAD'],
                ],
            ];
        }

        return $this->response->setJSON(array_values($eventosPorId));
    }

    public function getActividades()
    {
        return $this->response->setJSON($this->actividadesModel->getActividadesVigentesConDatos());
    }

    public function getEstadisticas()
    {
        $actividades = $this->actividadesModel->getActividadesVigentesConDatos();
        $total = count($actividades);
        $cursosActivos = 0;
        $talleresActivos = 0;
        $seminariosActivos = 0;

        foreach ($actividades as $actividad) {
            $tipo = $actividad['ACTIVIDAD'] ?? '';
            if ($tipo === 'Curso') {
                $cursosActivos++;
            } elseif ($tipo === 'Taller') {
                $talleresActivos++;
            } elseif ($tipo === 'Seminario') {
                $seminariosActivos++;
            }
        }

        return $this->response->setJSON([
            'totalActividades' => $total,
            'cursosActivos' => $cursosActivos,
            'talleresActivos' => $talleresActivos,
            'seminariosActivos' => $seminariosActivos,
        ]);
    }

    // --- Rutas de gestión bloqueadas para el docente ---

    public function create()
    {
        return $this->denegarGestion();
    }

    public function store()
    {
        return $this->denegarGestion();
    }

    public function show($id)
    {
        return $this->denegarGestion();
    }

    public function edit($id)
    {
        return $this->denegarGestion();
    }

    public function update($id)
    {
        return $this->denegarGestion();
    }

    public function delete($id)
    {
        return $this->denegarGestion();
    }

    public function participantes($id)
    {
        return $this->denegarGestion();
    }

    public function agregarParticipante()
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tiene permiso para gestionar participantes.',
        ]);
    }

    public function quitarParticipante()
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tiene permiso para gestionar participantes.',
        ]);
    }

    public function testInsert()
    {
        return $this->denegarGestion();
    }

    public function reportes()
    {
        return $this->denegarGestion();
    }

    public function exportarPDF()
    {
        return $this->denegarGestion();
    }

    public function exportarExcel()
    {
        return $this->denegarGestion();
    }

    public function exportarCSV()
    {
        return $this->denegarGestion();
    }
}
