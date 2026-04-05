<?php

namespace App\Controllers\estudiante;

use App\Controllers\BaseController;
use App\Models\PracticasPreprofesionalesModel;
use App\Models\ServiciosComunitariosModel;
use App\Models\ActividadesPracticasModel;
use App\Models\DocumentosPracticasModel;
use App\Models\TiposDocumentosPracticasModel;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\TiposDocumentosServicioComunitarioModel;
use App\Models\UsuariosModel;
use App\Services\EstudianteAsistenciaService;

class PracticasEstudianteController extends BaseController
{
    /** Horas requeridas: preprofesionales una sola vez en la carrera (240 h), servicio comunitario una sola vez (60 h). */
    private const HORAS_PRACTICAS_PREPROFESIONALES = 240;
    private const HORAS_SERVICIO_COMUNITARIO = 60;

    protected $practicasPreprofesionalesModel;
    protected $serviciosComunitariosModel;
    protected $actividadesPracticasModel;
    protected $documentosPracticasModel;
    protected $tiposDocumentosPracticasModel;
    protected $documentosServicioComunitarioModel;
    protected $tiposDocumentosServicioComunitarioModel;
    protected $usuariosModel;
    protected $db;

    public function __construct()
    {
        $this->practicasPreprofesionalesModel = new PracticasPreprofesionalesModel();
        $this->serviciosComunitariosModel = new ServiciosComunitariosModel();
        $this->actividadesPracticasModel = new ActividadesPracticasModel();
        $this->documentosPracticasModel = new DocumentosPracticasModel();
        $this->tiposDocumentosPracticasModel = new TiposDocumentosPracticasModel();
        $this->documentosServicioComunitarioModel = new DocumentosServicioComunitarioModel();
        $this->tiposDocumentosServicioComunitarioModel = new TiposDocumentosServicioComunitarioModel();
        $this->usuariosModel = new UsuariosModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $userId = (int) session()->get('id_usuario');

        // Evitar mostrar la vista de "Prácticas Asignadas" (búsqueda por cédula/nombre)
        // al estudiante logueado. Se redirige a la sección correcta según la vinculación activa.
        if (EstudianteAsistenciaService::tienePracticaPreprofesionalEnProgreso($userId)) {
            return redirect()->to(site_url('estudiante/documentos-practicas'));
        }
        if (EstudianteAsistenciaService::tieneServicioComunitarioEnProgreso($userId)) {
            return redirect()->to(site_url('estudiante/documentos-servicio-comunitario'));
        }

        return redirect()->to(site_url('estudiante/dashboard'));

        $terminoBusqueda = trim((string) $this->request->getGet('buscar'));
        $mostrarResultados = false;
        $mensajeBusqueda = null;

        // Datos del estudiante logueado (cédula y nombre) para validar la búsqueda
        $estudianteDatos = $this->db->table('TAB_ESTUDIANTES e')
            ->select('dp.CEDULA, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $userId)
            ->get()
            ->getRowArray();

        $cedula = $estudianteDatos['CEDULA'] ?? '';
        $nombreCompleto = trim(($estudianteDatos['NOMBRE'] ?? '') . ' ' . ($estudianteDatos['APELLIDO'] ?? ''));
        $nombreCompletoInv = trim(($estudianteDatos['APELLIDO'] ?? '') . ' ' . ($estudianteDatos['NOMBRE'] ?? ''));

        if ($terminoBusqueda !== '') {
            $buscarNorm = preg_replace('/\s+/', '', $terminoBusqueda);
            $cedulaNorm = preg_replace('/\s+/', '', $cedula);
            $coincideCedula = $cedulaNorm !== '' && (stripos($cedula, $terminoBusqueda) !== false || stripos($cedulaNorm, $buscarNorm) !== false);
            $coincideNombre = $nombreCompleto !== '' && (stripos($nombreCompleto, $terminoBusqueda) !== false || stripos($nombreCompletoInv, $terminoBusqueda) !== false);
            if ($coincideCedula || $coincideNombre) {
                $mostrarResultados = true;
            } else {
                $mostrarResultados = true;
                $mensajeBusqueda = 'No se encontraron prácticas con ese criterio. Verifique su cédula o nombre.';
            }
        }

        $estadisticas = ['totalPracticas' => 0, 'practicasActivas' => 0, 'practicasFinalizadas' => 0, 'horasCompletadas' => 0];
        $practicasPreprofesionales = [];
        $serviciosComunitarios = [];
        $whatsappSupervisor = null;
        $tiposDocumentos = $this->tiposDocumentosPracticasModel->getAllTipos();
        $progresoDocumentos = [];
        $idTipoDocumentoFinal = null;
        $alertaDocumentoFinal = ['mostrar' => false, 'fecha_limite' => null, 'dias_restantes' => null, 'superado_plazo' => false, 'mensaje' => ''];

        if ($mostrarResultados && $mensajeBusqueda === null) {
            $estadisticas = $this->obtenerEstadisticasEstudiante($userId);
            $practicasPreprofesionales = $this->obtenerPracticasPreprofesionales($userId);
            $serviciosComunitarios = $this->obtenerServiciosComunitarios($userId);
            $whatsappSupervisor = $this->obtenerWhatsappSupervisor($practicasPreprofesionales, $serviciosComunitarios);
            $progresoDocumentos = $this->documentosPracticasModel->getProgresoEstudiante($userId);
            foreach ($tiposDocumentos as $t) {
                $nombre = $t['NOMBRE'] ?? '';
                if (stripos($nombre, 'documento final') !== false || stripos($nombre, 'informe final') !== false) {
                    $idTipoDocumentoFinal = (int) ($t['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $t['ID_TIPO_DOCUMENTO'] ?? 0);
                    break;
                }
            }
            if ($idTipoDocumentoFinal === null && !empty($tiposDocumentos)) {
                $t = $tiposDocumentos[count($tiposDocumentos) - 1];
                $idTipoDocumentoFinal = (int) ($t['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $t['ID_TIPO_DOCUMENTO'] ?? 0);
            }
            $alertaDocumentoFinal = $this->calcularAlertaDocumentoFinal($practicasPreprofesionales, $progresoDocumentos, $idTipoDocumentoFinal ?? 0);
        }

        $data = [
            'title' => 'Mis Prácticas - ITSI',
            'termino_busqueda' => $terminoBusqueda,
            'mostrar_resultados' => $mostrarResultados,
            'mensaje_busqueda' => $mensajeBusqueda,
            'estadisticas' => $estadisticas,
            'practicasPreprofesionales' => $practicasPreprofesionales,
            'serviciosComunitarios' => $serviciosComunitarios,
            'whatsapp_supervisor' => $whatsappSupervisor,
            'tipos_documentos' => $tiposDocumentos,
            'progreso_documentos' => $progresoDocumentos,
            'checklist_informe' => $this->getChecklistInformePracticasLaborales(),
            'checklist_rubricas' => $this->getChecklistRubricasSeguimiento(),
            'horas_requeridas_preprof' => self::HORAS_PRACTICAS_PREPROFESIONALES,
            'horas_requeridas_servicio' => self::HORAS_SERVICIO_COMUNITARIO,
            'id_tipo_documento_final' => $idTipoDocumentoFinal,
            'alerta_documento_final' => $alertaDocumentoFinal,
        ];

        return view('estudiante/practicas_preprofesionales/index', $data);
    }

    /**
     * Vista exclusiva con los documentos de formato de Prácticas Laborales.
     * Los documentos se configuran en Coordinador > Documentos - Prácticas Preprofesionales.
     */
    public function formatos()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'title' => 'Formatos de las prácticas - ITSI',
            'documentos_formatos' => $this->getListaFormatosPracticasEstudiante(),
        ];

        return view('estudiante/practicas_preprofesionales/formatos', $data);
    }

    /**
     * Formatos descargables de servicio comunitario (misma estructura que prácticas preprofesionales).
     * Lista: WRITEPATH uploads/formatos_servicio/lista.json
     */
    public function formatosServicioComunitario()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'title' => 'Formatos de servicio comunitario - ITSI',
            'documentos_formatos' => $this->getListaFormatosServicioEstudiante(),
        ];

        return view('estudiante/practicas_servicio_comunitario/formatos', $data);
    }

    private function getListaFormatosPracticasEstudiante()
    {
        $path = WRITEPATH . 'uploads/formatos_practicas/lista.json';
        if (!file_exists($path) || !is_readable($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $lista = json_decode($json, true);
        return is_array($lista) ? $lista : [];
    }

    private function getListaFormatosServicioEstudiante()
    {
        $path = WRITEPATH . 'uploads/formatos_servicio/lista.json';
        if (!file_exists($path) || !is_readable($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $lista = json_decode($json, true);

        return is_array($lista) ? $lista : [];
    }

    /**
     * Descargar un documento de formato de prácticas preprofesionales.
     */
    public function descargarFormatoPracticas($archivo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }
        $archivo = basename($archivo);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setStatusCode(400)->setBody('Archivo no válido');
        }
        $lista = $this->getListaFormatosPracticasEstudiante();
        $enLista = false;
        foreach ($lista as $item) {
            if (($item['archivo'] ?? '') === $archivo) {
                $enLista = true;
                break;
            }
        }
        if (!$enLista) {
            return $this->response->setStatusCode(404)->setBody('Documento no encontrado');
        }
        $ruta = WRITEPATH . 'uploads/formatos_practicas/' . $archivo;
        if (!file_exists($ruta) || !is_file($ruta)) {
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }
        return $this->response->download($ruta, $archivo);
    }

    /**
     * Descargar un documento de formato de servicio comunitario.
     */
    public function descargarFormatoServicio($archivo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/'));
        }
        $archivo = basename($archivo);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setStatusCode(400)->setBody('Archivo no válido');
        }
        $lista = $this->getListaFormatosServicioEstudiante();
        $enLista = false;
        foreach ($lista as $item) {
            if (($item['archivo'] ?? '') === $archivo) {
                $enLista = true;
                break;
            }
        }
        if (!$enLista) {
            return $this->response->setStatusCode(404)->setBody('Documento no encontrado');
        }
        $ruta = WRITEPATH . 'uploads/formatos_servicio/' . $archivo;
        if (!file_exists($ruta) || !is_file($ruta)) {
            return $this->response->setStatusCode(404)->setBody('Archivo no encontrado');
        }

        return $this->response->download($ruta, $archivo);
    }

    /**
     * Subir documento de servicio comunitario (estudiante).
     */
    public function subirDocumentoServicioComunitario()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }
        $userId = session()->get('id_usuario');
        $idServicio = (int) $this->request->getPost('id_servicio');
        if ($idServicio <= 0) {
            $servicios = $this->obtenerServiciosComunitarios($userId);
            $idServicio = !empty($servicios) ? (int) $servicios[0]['ID_SERVICIO_COMUNITARIO'] : 0;
        }
        if ($idServicio <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes un servicio comunitario asignado.']);
        }
        if (!$this->perteneceServicioAlEstudiante($idServicio, $userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado para este servicio.']);
        }
        $idTipo = (int) $this->request->getPost('tipo_documento');
        if ($idTipo <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tipo de documento requerido.']);
        }
        if ($this->documentosServicioComunitarioModel->verificarDocumentoExistente($idServicio, $idTipo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ya tienes un documento de este tipo. Elimina el anterior para reemplazarlo.']);
        }
        $archivo = $this->request->getFile('archivo');
        if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selecciona un archivo PDF válido (máx. 10 MB).']);
        }
        $ext = strtolower($archivo->getClientExtension());
        if ($ext !== 'pdf') {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos PDF.']);
        }
        if ($archivo->getSize() > 10 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no debe superar 10 MB.']);
        }
        $dir = WRITEPATH . 'uploads/documentos-servicio/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $nombreArchivo = 'serv_' . $idServicio . '_' . $idTipo . '_' . date('YmdHis') . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $archivo->getClientName());
        $archivo->move($dir, $nombreArchivo);
        $datos = [
            'ID_SERVICIO_COMUNITARIO' => $idServicio,
            'ID_TIPO_DOCUMENTO' => $idTipo,
            'ID_ESTADO_REVISION' => 1,
            'NOMBRE_ARCHIVO' => $nombreArchivo,
            'TIPO_ARCHIVO' => $archivo->getClientMimeType() ?: 'application/pdf',
            'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
            'OBSERVACIONES' => $this->request->getPost('observaciones') ?? '',
        ];
        if ($this->documentosServicioComunitarioModel->skipValidation(true)->insert($datos)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Documento subido correctamente. Será revisado por el coordinador.']);
        }
        @unlink($dir . $nombreArchivo);
        return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar el documento.']);
    }

    /**
     * Descargar documento de servicio comunitario (estudiante).
     */
    public function descargarDocumentoServicioComunitario($id)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(403)->setBody('No autorizado');
        }
        $userId = session()->get('id_usuario');
        $doc = $this->documentosServicioComunitarioModel->find($id);
        if (!$doc || !$this->perteneceServicioAlEstudiante($doc['ID_SERVICIO_COMUNITARIO'], $userId)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }
        $ruta = WRITEPATH . 'uploads/documentos-servicio/' . $doc['NOMBRE_ARCHIVO'];
        if (!file_exists($ruta) || !is_file($ruta)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }
        return $this->response->download($ruta, null);
    }

    /**
     * Eliminar documento de servicio comunitario (estudiante).
     */
    public function eliminarDocumentoServicioComunitario($id)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }
        $userId = session()->get('id_usuario');
        $doc = $this->documentosServicioComunitarioModel->find($id);
        if (!$doc || !$this->perteneceServicioAlEstudiante($doc['ID_SERVICIO_COMUNITARIO'], $userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
        }
        if (!empty($doc['ID_ESTADO_REVISION']) && (int) $doc['ID_ESTADO_REVISION'] === 3) {
            return $this->response->setJSON(['success' => false, 'message' => 'No puedes eliminar un documento ya aprobado']);
        }
        $ruta = WRITEPATH . 'uploads/documentos-servicio/' . $doc['NOMBRE_ARCHIVO'];
        if (file_exists($ruta)) {
            @unlink($ruta);
        }
        $this->documentosServicioComunitarioModel->delete($id);
        return $this->response->setJSON(['success' => true, 'message' => 'Documento eliminado']);
    }

    /**
     * Comprueba si el servicio comunitario pertenece al estudiante logueado.
     */
    private function perteneceServicioAlEstudiante(int $idServicio, int $userId): bool
    {
        $servicios = $this->obtenerServiciosComunitarios($userId);
        foreach ($servicios as $s) {
            if ((int) $s['ID_SERVICIO_COMUNITARIO'] === $idServicio) {
                return true;
            }
        }
        return false;
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

    /**
     * Registrar asistencia (fecha, entrada, salida, actividades). Solo para la práctica del estudiante logueado.
     */
    public function registrarAsistencia()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $userId = session()->get('id_usuario');
        $practicaId = (int) $this->request->getPost('practica_id');
        $tipoPractica = $this->request->getPost('tipo_practica');
        $fechaAsistencia = $this->request->getPost('fecha_asistencia');
        $horaEntrada = $this->request->getPost('hora_entrada');
        $horaSalida = $this->request->getPost('hora_salida');
        $actividadesDia = $this->request->getPost('actividades_dia');
        $observaciones = $this->request->getPost('observaciones');

        if ($practicaId <= 0 || !in_array($tipoPractica, ['preprofesional', 'servicio'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        if (empty($fechaAsistencia) || !is_string($fechaAsistencia) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaAsistencia)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Fecha inválida']);
        }

        // Obtener ID_ESTUDIANTE del usuario logueado (mismo criterio que index)
        $estudiante = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $userId)
            ->get()
            ->getRowArray();
        $idEstudiante = (int) ($estudiante['ID_ESTUDIANTE'] ?? 0);
        if ($idEstudiante <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se encontró el perfil de estudiante']);
        }

        // Verificar que la práctica pertenece al estudiante
        if ($tipoPractica === 'preprofesional') {
            $pp = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES')
                ->where('ID_PRACTICA_PREPROFESIONAL', $practicaId)
                ->where('ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRowArray();
            if (!$pp) {
                return $this->response->setJSON(['success' => false, 'message' => 'No tiene permiso para registrar asistencia en esta práctica']);
            }
        } else {
            $sc = $this->db->table('TAB_SERVICIO_COMUNITARIO')
                ->where('ID_SERVICIO_COMUNITARIO', $practicaId)
                ->where('ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRowArray();
            if (!$sc) {
                return $this->response->setJSON(['success' => false, 'message' => 'No tiene permiso para registrar asistencia en esta práctica']);
            }
        }

        $obsText = trim((string) ($observaciones ?? ''));
        if ($obsText === '') {
            $obsText = '—';
        }

        $fechaReg = date('Y-m-d H:i:s');

        try {
            if ($tipoPractica === 'preprofesional') {
                // Evitar duplicados por misma práctica y fecha (útil para "fechas faltantes").
                $yaExiste = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $practicaId)
                    ->where('FECHA_ASISTENCIA', $fechaAsistencia)
                    ->countAllResults() > 0;
                if ($yaExiste) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Ya registraste asistencia para esa fecha en esta práctica'
                    ]);
                }
                $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')->insert([
                    'ID_PRACTICA_PREPROFESIONAL' => $practicaId,
                    'FECHA_ASISTENCIA' => $fechaAsistencia,
                    'HORA_ENTRADA' => $horaEntrada,
                    'HORA_SALIDA' => $horaSalida,
                    'ACTIVIDADES_DIA' => $actividadesDia,
                    'OBSERVACIONES' => $obsText,
                    'FECHA_REGISTRO' => $fechaReg,
                ]);
            } else {
                $yaExiste = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                    ->where('ID_SERVICIO_COMUNITARIO', $practicaId)
                    ->where('FECHA_ASISTENCIA', $fechaAsistencia)
                    ->countAllResults() > 0;
                if ($yaExiste) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Ya registraste asistencia para esa fecha en este servicio'
                    ]);
                }
                $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')->insert([
                    'ID_SERVICIO_COMUNITARIO' => $practicaId,
                    'FECHA_ASISTENCIA' => $fechaAsistencia,
                    'HORA_ENTRADA' => $horaEntrada,
                    'HORA_SALIDA' => $horaSalida,
                    'ACTIVIDADES_DIA' => $actividadesDia,
                    'OBSERVACIONES' => $obsText,
                    'FECHA_REGISTRO' => $fechaReg,
                ]);
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asistencia registrada exitosamente'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Estudiante registrarAsistencia: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al registrar la asistencia']);
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

        // Solo PDF, máximo 10 MB
        $fileExtension = strtolower($file->getClientExtension());
        if ($fileExtension !== 'pdf') {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos en formato PDF.']);
        }
        $maxBytes = 10 * 1024 * 1024; // 10 MB
        if ($file->getSize() > $maxBytes) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo no debe superar 10 MB.']);
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
            // Estudiante por ID_USUARIO (TAB_ESTUDIANTES se relaciona con TAB_USUARIOS por ID_DATO_PERSONA)
            $estudiante = $this->db->table('TAB_ESTUDIANTES e')
                ->select('e.ID_ESTUDIANTE')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->where('u.ID_USUARIO', $userId)
                ->get()
                ->getRowArray();
            if (!$estudiante) {
                return ['totalPracticas' => 0, 'practicasActivas' => 0, 'practicasFinalizadas' => 0, 'horasCompletadas' => 0];
            }
            $idEstudiante = (int) $estudiante['ID_ESTUDIANTE'];

            $totalPreprofesionales = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->where('pp.ID_ESTUDIANTE', $idEstudiante)
                ->countAllResults();

            $totalServicios = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->where('sc.ID_ESTUDIANTE', $idEstudiante)
                ->countAllResults();

            $practicasActivas = $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->where('pp.ID_ESTUDIANTE', $idEstudiante)
                ->where('pp.ESTADO_PRACTICA', 'En Progreso')
                ->countAllResults();

            $serviciosActivos = $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->where('sc.ID_ESTUDIANTE', $idEstudiante)
                ->where('sc.ESTADO_SERVICIO', 'En Progreso')
                ->countAllResults();

            // Horas completadas: TAB_ASISTENCIAS_* (HORA_ENTRADA/HORA_SALIDA) + TAB_SEGUIMIENTO_* (HORAS_CUMPLIDAS)
            $horasPp = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES ap')
                ->select('SUM(TIMESTAMPDIFF(HOUR, ap.HORA_ENTRADA, ap.HORA_SALIDA)) AS total_horas', false)
                ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'pp.ID_PRACTICA_PREPROFESIONAL = ap.ID_PRACTICA_PREPROFESIONAL')
                ->where('pp.ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRow();
            $horasSc = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO as_')
                ->select('SUM(TIMESTAMPDIFF(HOUR, as_.HORA_ENTRADA, as_.HORA_SALIDA)) AS total_horas', false)
                ->join('TAB_SERVICIO_COMUNITARIO sc', 'sc.ID_SERVICIO_COMUNITARIO = as_.ID_SERVICIO_COMUNITARIO')
                ->where('sc.ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRow();
            $segPp = $this->db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES sp')
                ->selectSum('sp.HORAS_CUMPLIDAS', 'total_horas')
                ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'pp.ID_PRACTICA_PREPROFESIONAL = sp.ID_PRACTICA_PREPROFESIONAL')
                ->where('pp.ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRow();
            $segSc = $this->db->table('TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO ss')
                ->selectSum('ss.HORAS_CUMPLIDAS', 'total_horas')
                ->join('TAB_SERVICIO_COMUNITARIO sc', 'sc.ID_SERVICIO_COMUNITARIO = ss.ID_SERVICIO_COMUNITARIO')
                ->where('sc.ID_ESTUDIANTE', $idEstudiante)
                ->get()
                ->getRow();
            $horasCompletadas = (int)($horasPp->total_horas ?? 0) + (int)($horasSc->total_horas ?? 0)
                + (int)($segPp->total_horas ?? 0) + (int)($segSc->total_horas ?? 0);

            return [
                'totalPracticas' => $totalPreprofesionales + $totalServicios,
                'practicasActivas' => $practicasActivas + $serviciosActivos,
                'practicasFinalizadas' => ($totalPreprofesionales + $totalServicios) - ($practicasActivas + $serviciosActivos),
                'horasCompletadas' => $horasCompletadas
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

    /**
     * Checklist: Informe de Prácticas Laborales (documentos requeridos)
     */
    private function getChecklistInformePracticasLaborales()
    {
        return [
            ['num' => 1, 'datos' => 'Oficio de asignación de tutor docente emitido por el coordinador de la carrera.'],
            ['num' => 2, 'datos' => 'Oficio personal realizado a la entidad receptora donde se realizó las prácticas laborales.'],
            ['num' => 3, 'datos' => 'Carta de aceptación de la entidad receptora, dirigida al estudiante o al ITSI, con él o los nombres de los estudiantes que van a realizar las Prácticas laborales.'],
            ['num' => 4, 'datos' => 'Solicitud institucional valorada dirigida al Sr. Rector Dr. José Pijal solicitando a la institución la aceptación de las prácticas laborales, previamente aprobada.'],
            ['num' => 5, 'datos' => 'Certificado de haber culminado las Prácticas Laborales por 240 horas, emitido por la entidad receptora al estudiante.']
        ];
    }

    /**
     * Checklist: Rúbricas y hojas de asistencia para el seguimiento docente
     */
    private function getChecklistRubricasSeguimiento()
    {
        return [
            ['num' => 1, 'datos' => 'Rúbrica de Evaluación Entidad Receptora. (Llena y firmada y sellada por las entidades receptoras)'],
            ['num' => 2, 'sub' => 'Depende del Total de horas', 'items' => [
                ['datos' => 'Hojas de asistencia de estudiantes. (Llenas y firmadas y selladas por las entidades receptoras)'],
                ['datos' => 'Ficha de registro de Actividades Realizadas. (Llenas y firmadas y selladas por las entidades receptoras)']
            ]],
            ['num' => 3, 'sub' => 'Por semana Una Visita', 'items' => [
                ['datos' => 'Ficha de Control y Seguimiento Docente. (Llenas y firmadas y selladas por los docentes tutores)'],
                ['datos' => 'Rúbrica de Evaluación de Control y Seguimiento Docente. (Llena y firmada y sellada por los docentes tutores)']
            ]],
            ['num' => 4, 'datos' => 'Respaldo en fotos de los trabajos y actividades realizadas en las prácticas laborales, por medio de fotos, capturas, videos, impresiones, entre otros.']
        ];
    }

    /** Días máximos para subir el documento final tras culminar las prácticas (240 h). */
    private const DIAS_PLAZO_DOCUMENTO_FINAL = 15;

    /**
     * Calcula si debe mostrarse la alerta de plazo para subir el documento final.
     * Una vez culminada la fecha fin de las prácticas, el estudiante tiene 15 días para subir el documento final.
     *
     * @param array $practicasPreprofesionales
     * @param array $progresoDocumentos
     * @param int $idTipoDocumentoFinal
     * @return array { mostrar, fecha_limite, dias_restantes, superado_plazo, mensaje }
     */
    private function calcularAlertaDocumentoFinal(array $practicasPreprofesionales, array $progresoDocumentos, int $idTipoDocumentoFinal): array
    {
        $default = ['mostrar' => false, 'fecha_limite' => null, 'dias_restantes' => null, 'superado_plazo' => false, 'mensaje' => ''];

        if (empty($practicasPreprofesionales) || $idTipoDocumentoFinal <= 0) {
            return $default;
        }

        $tieneDocumentoFinalSubido = false;
        foreach ($progresoDocumentos as $doc) {
            $docTipoId = (int) ($doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? 0);
            if ($docTipoId === $idTipoDocumentoFinal && !empty($doc['ID_DOCUMENTO_PRACTICA'] ?? $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? null)) {
                $tieneDocumentoFinalSubido = true;
                break;
            }
        }
        if ($tieneDocumentoFinalSubido) {
            return $default;
        }

        $practica = $practicasPreprofesionales[0];
        $fechaFin = $practica['FECHA_FIN'] ?? null;
        if (!$fechaFin) {
            return $default;
        }

        $hoy = date('Y-m-d');
        $fechaFinDate = date('Y-m-d', strtotime($fechaFin));
        if ($fechaFinDate > $hoy) {
            return $default;
        }

        $fechaLimite = date('Y-m-d', strtotime($fechaFin . ' + ' . self::DIAS_PLAZO_DOCUMENTO_FINAL . ' days'));
        $superadoPlazo = $hoy > $fechaLimite;
        $diasRestantes = null;
        if (!$superadoPlazo) {
            $diasRestantes = (int) ((strtotime($fechaLimite) - strtotime($hoy)) / 86400);
        }

        $fechaLimiteFormato = date('d/m/Y', strtotime($fechaLimite));
        if ($superadoPlazo) {
            $mensaje = 'Has superado el plazo de ' . self::DIAS_PLAZO_DOCUMENTO_FINAL . ' días para subir el documento final (la fecha límite era el ' . $fechaLimiteFormato . '). Por favor, súbelo lo antes posible.';
        } else {
            $mensaje = 'Una vez culminadas las horas de prácticas tienes un máximo de ' . self::DIAS_PLAZO_DOCUMENTO_FINAL . ' días para subir el documento final. <strong>Fecha límite: ' . $fechaLimiteFormato . '</strong>. Días restantes: <strong>' . $diasRestantes . '</strong>.';
        }

        return [
            'mostrar' => true,
            'fecha_limite' => $fechaLimiteFormato,
            'dias_restantes' => $diasRestantes,
            'superado_plazo' => $superadoPlazo,
            'mensaje' => $mensaje,
        ];
    }

    /**
     * Obtiene el número WhatsApp del primer supervisor (preprofesional o servicio comunitario).
     * ID_DOCENTE_SUPERVISOR en prácticas es el ID_USUARIO del docente.
     * Devuelve URL lista para wa.me o null si no hay número.
     */
    private function obtenerWhatsappSupervisor(array $practicasPreprofesionales, array $serviciosComunitarios)
    {
        $idUsuarioSupervisor = null;
        // TAB_PRACTICAS_PREPROFESIONALES y TAB_SERVICIO_COMUNITARIO usan ID_INSTRUCTOR; obtener ID_USUARIO del instructor vía TAB_EMPLEADOS_INSTRUCTORES/TAB_EMPLEADOS/TAB_USUARIOS o por ID_DATO_PERSONA
        if (!empty($practicasPreprofesionales) && !empty($practicasPreprofesionales[0]['ID_INSTRUCTOR'])) {
            $idInstructor = (int) $practicasPreprofesionales[0]['ID_INSTRUCTOR'];
            $u = $this->db->table('TAB_EMPLEADOS_INSTRUCTORES ei')->select('e.ID_DATO_PERSONA')
                ->join('TAB_EMPLEADOS e', 'e.ID_EMPLEADO = ei.ID_EMPLEADO')
                ->where('ei.ID_INSTRUCTOR', $idInstructor)->get()->getRowArray();
            if ($u) {
                $usuario = $this->db->table('TAB_USUARIOS')->where('ID_DATO_PERSONA', $u['ID_DATO_PERSONA'])->get()->getRowArray();
                if ($usuario) {
                    $idUsuarioSupervisor = (int) $usuario['ID_USUARIO'];
                }
            }
        }
        if ($idUsuarioSupervisor === null && !empty($serviciosComunitarios) && !empty($serviciosComunitarios[0]['ID_INSTRUCTOR'])) {
            $idInstructor = (int) $serviciosComunitarios[0]['ID_INSTRUCTOR'];
            $u = $this->db->table('TAB_EMPLEADOS_INSTRUCTORES ei')->select('e.ID_DATO_PERSONA')
                ->join('TAB_EMPLEADOS e', 'e.ID_EMPLEADO = ei.ID_EMPLEADO')
                ->where('ei.ID_INSTRUCTOR', $idInstructor)->get()->getRowArray();
            if ($u) {
                $usuario = $this->db->table('TAB_USUARIOS')->where('ID_DATO_PERSONA', $u['ID_DATO_PERSONA'])->get()->getRowArray();
                if ($usuario) {
                    $idUsuarioSupervisor = (int) $usuario['ID_USUARIO'];
                }
            }
        }
        if ($idUsuarioSupervisor === null) {
            return null;
        }
        try {
            $row = $this->db->table('TAB_USUARIOS u')
                ->select('dp.CELULAR')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
                ->where('u.ID_USUARIO', $idUsuarioSupervisor)
                ->get()
                ->getRowArray();
            if (empty($row) || empty(trim($row['CELULAR'] ?? ''))) {
                return null;
            }
            $celular = preg_replace('/\D/', '', trim($row['CELULAR']));
            if ($celular === '') {
                return null;
            }
            if (strlen($celular) <= 10 && substr($celular, 0, 1) !== '0') {
                $celular = '593' . $celular;
            } elseif (substr($celular, 0, 1) === '0') {
                $celular = '593' . substr($celular, 1);
            }
            return 'https://wa.me/' . $celular;
        } catch (\Throwable $e) {
            log_message('error', 'Error al obtener WhatsApp supervisor: ' . $e->getMessage());
            return null;
        }
    }

    private function obtenerPracticasPreprofesionales($userId)
    {
        try {
            return $this->db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
                ->select('pp.*, CONCAT(COALESCE(dp.NOMBRE,\'\'), \' \', COALESCE(dp.APELLIDO,\'\')) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.ID_TIPO_INSTITUCION as TIPO_INSTITUCION,
                    CONCAT(COALESCE(dsup.NOMBRE,\'\'), \' \', COALESCE(dsup.APELLIDO,\'\')) as SUPERVISOR_NOMBRE')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = pp.ID_INSTITUCION_CONVENIO', 'left')
                ->join('TAB_EMPLEADOS_INSTRUCTORES ei', 'ei.ID_INSTRUCTOR = pp.ID_INSTRUCTOR', 'left')
                ->join('TAB_EMPLEADOS em', 'em.ID_EMPLEADO = ei.ID_EMPLEADO', 'left')
                ->join('TAB_DATOS_PERSONAS dsup', 'dsup.ID_DATO_PERSONA = em.ID_DATO_PERSONA', 'left')
                ->where('u.ID_USUARIO', $userId)
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
            return $this->db->table('TAB_SERVICIO_COMUNITARIO sc')
                ->select('sc.*, CONCAT(COALESCE(dp.NOMBRE,\'\'), \' \', COALESCE(dp.APELLIDO,\'\')) as ESTUDIANTE_NOMBRE, c.NOMBRE as CARRERA_NOMBRE, ic.NOMBRE as INSTITUCION_NOMBRE, ic.ID_TIPO_INSTITUCION as TIPO_INSTITUCION')
                ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left')
                ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = sc.ID_INSTITUCION_CONVENIO', 'left')
                ->where('u.ID_USUARIO', $userId)
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
            if ($tipo === 'preprofesional') {
                $asist = $this->db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                    ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
                    ->where('ID_PRACTICA_PREPROFESIONAL', $practicaId)
                    ->get()
                    ->getRow();
                $seg = $this->db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES')
                    ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
                    ->where('ID_PRACTICA_PREPROFESIONAL', $practicaId)
                    ->get()
                    ->getRow();
                return (int)($asist->total_horas ?? 0) + (int)($seg->total_horas ?? 0);
            }
            $asist = $this->db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
                ->where('ID_SERVICIO_COMUNITARIO', $practicaId)
                ->get()
                ->getRow();
            $seg = $this->db->table('TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO')
                ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
                ->where('ID_SERVICIO_COMUNITARIO', $practicaId)
                ->get()
                ->getRow();
            return (int)($asist->total_horas ?? 0) + (int)($seg->total_horas ?? 0);
        } catch (\Exception $e) {
            log_message('error', 'Error al calcular horas cumplidas: ' . $e->getMessage());
            return 0;
        }
    }
}
