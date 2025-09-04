<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\PracticasPreprofesionalesModel;
use App\Models\ServiciosComunitariosModel;
use App\Models\ActividadesPracticasModel;
use App\Models\DocumentosPracticasModel;
use App\Models\UsuariosModel;

class PracticasEstudianteController extends BaseController
{
    protected $practicasPreprofesionalesModel;
    protected $serviciosComunitariosModel;
    protected $actividadesPracticasModel;
    protected $documentosPracticasModel;
    protected $usuariosModel;
    protected $db;

    public function __construct()
    {
        $this->practicasPreprofesionalesModel = new PracticasPreprofesionalesModel();
        $this->serviciosComunitariosModel = new ServiciosComunitariosModel();
        $this->actividadesPracticasModel = new ActividadesPracticasModel();
        $this->documentosPracticasModel = new DocumentosPracticasModel();
        $this->usuariosModel = new UsuariosModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $userId = session()->get('id_usuario');
        
        // Obtener estadísticas del estudiante
        $estadisticas = $this->obtenerEstadisticasEstudiante($userId);
        
        // Obtener prácticas preprofesionales del estudiante
        $practicasPreprofesionales = $this->obtenerPracticasPreprofesionales($userId);
        
        // Obtener servicios comunitarios del estudiante
        $serviciosComunitarios = $this->obtenerServiciosComunitarios($userId);

        $data = [
            'title' => 'Mis Prácticas - ITSI',
            'estadisticas' => $estadisticas,
            'practicasPreprofesionales' => $practicasPreprofesionales,
            'serviciosComunitarios' => $serviciosComunitarios
        ];

        return view('estudiante/practicas/practicas_estudiante', $data);
    }

    public function detalle($id, $tipo)
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $userId = session()->get('id_usuario');
        
        try {
            if ($tipo === 'preprofesional') {
                $practica = $this->practicasPreprofesionalesModel->getPracticaConDatos($id, $userId);
            } else {
                $practica = $this->serviciosComunitariosModel->getServicioConDatos($id, $userId);
            }

            if (!$practica) {
                return $this->response->setJSON(['success' => false, 'message' => 'Práctica no encontrada']);
            }

            // Calcular progreso
            $progreso = $this->calcularProgreso($id, $tipo);
            $horasCumplidas = $this->calcularHorasCumplidas($id, $tipo);

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'practica' => $practica,
                    'progreso' => $progreso,
                    'horasCumplidas' => $horasCumplidas
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en detalle de práctica: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function registrarActividad()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $userId = session()->get('id_usuario');
        
        // Validar datos
        $rules = [
            'practica_id' => 'required|integer',
            'tipo_practica' => 'required|in_list[preprofesional,servicio]',
            'fecha_actividad' => 'required|valid_date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'actividades' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $data = [
                'ID_ESTUDIANTE' => $userId,
                'ID_PRACTICA' => $this->request->getPost('practica_id'),
                'TIPO_PRACTICA' => $this->request->getPost('tipo_practica'),
                'FECHA_ACTIVIDAD' => $this->request->getPost('fecha_actividad'),
                'HORA_ENTRADA' => $this->request->getPost('hora_entrada'),
                'HORA_SALIDA' => $this->request->getPost('hora_salida'),
                'ACTIVIDADES_REALIZADAS' => $this->request->getPost('actividades'),
                'OBSERVACIONES' => $this->request->getPost('observaciones'),
                'FECHA_REGISTRO' => date('Y-m-d H:i:s')
            ];

            $this->actividadesPracticasModel->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Actividad registrada exitosamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al registrar actividad: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al registrar la actividad']);
        }
    }

    public function subirDocumento()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $userId = session()->get('id_usuario');
        
        // Validar archivo
        $file = $this->request->getFile('archivo');
        
        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo inválido']);
        }

        // Validar tipo de archivo
        $allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $fileExtension = $file->getClientExtension();
        
        if (!in_array($fileExtension, $allowedTypes)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tipo de archivo no permitido']);
        }

        try {
            // Mover archivo a directorio de uploads
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/documentos_practicas/', $newName);

            $data = [
                'ID_ESTUDIANTE' => $userId,
                'TIPO_DOCUMENTO' => $this->request->getPost('tipo_documento'),
                'NOMBRE_ARCHIVO' => $file->getClientName(),
                'NOMBRE_SERVIDOR' => $newName,
                'RUTA_ARCHIVO' => 'uploads/documentos_practicas/' . $newName,
                'DESCRIPCION' => $this->request->getPost('descripcion'),
                'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                'ESTADO' => 'Pendiente'
            ];

            $this->documentosPracticasModel->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento subido exitosamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al subir documento: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al subir el documento']);
        }
    }

    public function obtenerActividades($practicaId, $tipo)
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $userId = session()->get('id_usuario');
        
        try {
            $actividades = $this->actividadesPracticasModel
                ->where('ID_ESTUDIANTE', $userId)
                ->where('ID_PRACTICA', $practicaId)
                ->where('TIPO_PRACTICA', $tipo)
                ->orderBy('FECHA_ACTIVIDAD', 'DESC')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $actividades
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener actividades: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al obtener actividades']);
        }
    }

    private function obtenerEstadisticasEstudiante($userId)
    {
        try {
            // Obtener total de prácticas preprofesionales
            $totalPreprofesionales = $this->db->table('practicas_preprofesionales pp')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->where('e.ID_USUARIO', $userId)
                ->countAllResults();

            // Obtener total de servicios comunitarios
            $totalServicios = $this->db->table('servicios_comunitarios sc')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->where('e.ID_USUARIO', $userId)
                ->countAllResults();

            // Obtener prácticas activas
            $practicasActivas = $this->db->table('practicas_preprofesionales pp')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->where('e.ID_USUARIO', $userId)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->countAllResults();

            $serviciosActivos = $this->db->table('servicios_comunitarios sc')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->where('e.ID_USUARIO', $userId)
                ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                ->countAllResults();

            // Obtener horas completadas
            $horasCompletadas = $this->db->table('actividades_practicas ap')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = ap.ID_ESTUDIANTE')
                ->where('e.ID_USUARIO', $userId)
                ->selectSum('TIMESTAMPDIFF(HOUR, CONCAT(ap.FECHA_ACTIVIDAD, " ", ap.HORA_ENTRADA), CONCAT(ap.FECHA_ACTIVIDAD, " ", ap.HORA_SALIDA))', 'total_horas')
                ->get()
                ->getRow();

            return [
                'totalPracticas' => $totalPreprofesionales + $totalServicios,
                'practicasActivas' => $practicasActivas + $serviciosActivos,
                'practicasFinalizadas' => ($totalPreprofesionales + $totalServicios) - ($practicasActivas + $serviciosActivos),
                'horasCompletadas' => $horasCompletadas->total_horas ?? 0
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas: ' . $e->getMessage());
            return [
                'totalPracticas' => 0,
                'practicasActivas' => 0,
                'practicasFinalizadas' => 0,
                'horasCompletadas' => 0
            ];
        }
    }

    private function obtenerPracticasPreprofesionales($userId)
    {
        try {
            return $this->db->table('practicas_preprofesionales pp')
                ->select('pp.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO')
                ->where('e.ID_USUARIO', $userId)
                ->orderBy('pp.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener prácticas preprofesionales: ' . $e->getMessage());
            return [];
        }
    }

    private function obtenerServiciosComunitarios($userId)
    {
        try {
            return $this->db->table('servicios_comunitarios sc')
                ->select('sc.*, e.NOMBRE_COMPLETO as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.TIPO_INSTITUCION')
                ->join('estudiantes e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('carreras c', 'c.ID_CARRERA = e.ID_CARRERA')
                ->join('instituciones_convenios ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO')
                ->where('e.ID_USUARIO', $userId)
                ->orderBy('sc.FECHA_INICIO', 'DESC')
                ->get()
                ->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener servicios comunitarios: ' . $e->getMessage());
            return [];
        }
    }

    private function calcularProgreso($practicaId, $tipo)
    {
        try {
            if ($tipo === 'preprofesional') {
                $practica = $this->practicasPreprofesionalesModel->find($practicaId);
                $horasTotales = $practica['HORAS_PRACTICAS'] ?? 0;
            } else {
                $servicio = $this->serviciosComunitariosModel->find($practicaId);
                $horasTotales = $servicio['HORAS_SERVICIO'] ?? 0;
            }

            $horasCumplidas = $this->calcularHorasCumplidas($practicaId, $tipo);
            
            if ($horasTotales > 0) {
                return round(($horasCumplidas / $horasTotales) * 100, 2);
            }
            
            return 0;

        } catch (\Exception $e) {
            log_message('error', 'Error al calcular progreso: ' . $e->getMessage());
            return 0;
        }
    }

    private function calcularHorasCumplidas($practicaId, $tipo)
    {
        try {
            $result = $this->db->table('actividades_practicas')
                ->selectSum('TIMESTAMPDIFF(HOUR, CONCAT(FECHA_ACTIVIDAD, " ", HORA_ENTRADA), CONCAT(FECHA_ACTIVIDAD, " ", HORA_SALIDA))', 'total_horas')
                ->where('ID_PRACTICA', $practicaId)
                ->where('TIPO_PRACTICA', $tipo)
                ->get()
                ->getRow();

            return $result->total_horas ?? 0;

        } catch (\Exception $e) {
            log_message('error', 'Error al calcular horas cumplidas: ' . $e->getMessage());
            return 0;
        }
    }
}
