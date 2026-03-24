<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;

class DocumentosPracticasAdminController extends BaseController
{
    protected $documentosModel;
    protected $usuariosModel;
    protected $estadosRevisionesModel;
    protected $tiposDocumentosModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentosPracticasModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
        $this->tiposDocumentosModel = new TiposDocumentosPracticasModel();
    }

    /**
     * Mostrar la vista de gestión de documentos de prácticas
     */
    public function index()
    {
        $idEstudiante = (int) $this->request->getGet('estudiante');
        $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
        
        $data = [
            'title' => 'Gestión de Documentos de Prácticas',
            'documentos' => $this->getDocumentosCompletos($idEstudiante > 0 ? $idEstudiante : null),
            'estadisticas' => $this->getEstadisticas(),
            'tipos_documentos' => $tiposDocumentos,
            'tiposDocumentos' => $tiposDocumentos, // Duplicado para compatibilidad
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'estudiantes' => $this->getEstudiantes(),
            'documentos_formatos_practicas' => $this->getListaFormatosPracticas(),
            'estudiante_filtro' => $idEstudiante > 0 ? $idEstudiante : null,
        ];

        // Log para depuración
        log_message('debug', 'Tipos de documentos en index: ' . json_encode($tiposDocumentos));

        return view('admin/documentos/documentos_practicas', $data);
    }

    /**
     * Mostrar formulario de subida de documentos
     */
    public function upload()
    {
        $data = [
            'title' => 'Subir Documento de Práctica',
            'estudiantes' => $this->getEstudiantes(),
            'tipos_documentos' => $this->tiposDocumentosModel->findAll()
        ];

        return view('admin/documentos/DocumentosPracticas', $data);
    }

    /**
     * Procesar subida de documento
     */
    public function procesarSubida()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'id_practica' => 'required|integer',
            'id_tipo_documento' => 'required|integer',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf,doc,docx]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }

            $archivo = $this->request->getFile('archivo');

            if ($archivo->isValid() && !$archivo->hasMoved()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move(WRITEPATH . 'uploads/documentos-practicas/', $nombreArchivo);

            $data = [
                'ID_PRACTICA_PREPROFESIONAL' => $this->request->getPost('id_practica'),
                'ID_TIPO_DOCUMENTO' => $this->request->getPost('id_tipo_documento'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                'TIPO_ARCHIVO' => $archivo->getClientMimeType(),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                'ESTADO_REVISION' => 'Pendiente',
                'OBSERVACIONES' => $this->request->getPost('observaciones')
                ];

            if ($this->documentosModel->insert($data)) {
                    return $this->response->setJSON([
                        'success' => true,
                    'message' => 'Documento subido exitosamente'
                ]);
            } else {
            return $this->response->setJSON([
                'success' => false,
                    'message' => 'Error al guardar el documento'
            ]);
        }
    }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al subir el archivo'
        ]);
    }

    /**
     * Listar documentos por estudiante
     */
    public function listarPorEstudiante($idPractica)
    {
        $documentos = $this->documentosModel
            ->select('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.*, TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.NOMBRE as TIPO_DOCUMENTO_NOMBRE')
            ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES', 'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.ID_TIPO_DOCUMENTO_PREPROFESIONAL')
            ->where('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.ID_PRACTICA_PREPROFESIONAL', $idPractica)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.FECHA_SUBIDA', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Cambiar estado de revisión del documento
     */
    public function cambiarEstado($id)
    {
        $nuevoEstado = $this->request->getPost('estado');
        $observaciones = $this->request->getPost('observaciones_revisor');

        $data = [
            'ID_ESTADO_REVISION' => $nuevoEstado,
            'OBSERVACIONES_REVISOR' => $observaciones,
            'FECHA_REVISION' => date('Y-m-d H:i:s'),
            'ID_REVISOR' => session()->get('id_usuario')
        ];

        if ($this->documentosModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ]);
        }
    }

    /**
     * Descargar documento
     */
    public function descargar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setStatusCode(404, 'Documento no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($rutaArchivo)) {
            return $this->response->setStatusCode(404, 'Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }

    /**
     * Ver documento en el navegador
     */
    public function ver($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setStatusCode(404, 'Documento no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($rutaArchivo)) {
            return $this->response->setStatusCode(404, 'Archivo no encontrado');
        }

        // Obtener el tipo MIME del archivo
        $tipoMime = mime_content_type($rutaArchivo);
        
        // Configurar headers para mostrar el archivo en el navegador
        $this->response->setHeader('Content-Type', $tipoMime);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $documento['NOMBRE_ARCHIVO'] . '"');
        $this->response->setHeader('Content-Length', filesize($rutaArchivo));
        
        // Leer y enviar el contenido del archivo
        $contenido = file_get_contents($rutaArchivo);
        return $this->response->setBody($contenido);
    }

    /**
     * Eliminar documento
     */
    public function eliminar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        // Eliminar archivo físico
        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        // Eliminar registro de la base de datos
        if ($this->documentosModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el documento'
            ]);
        }
    }

    /**
     * Obtener documentos completos con información relacionada.
     * @param int|null $idEstudiante Si se indica, solo documentos de prácticas de ese estudiante.
     */
    private function getDocumentosCompletos($idEstudiante = null)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp')
            ->select('
                dp.*,
                tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
                tdp.CODIGO as TIPO_DOCUMENTO_CODIGO,
                tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                tdp.DESCRIPCION as TIPO_DOCUMENTO_DESCRIPCION,
                tdp.ORDEN as TIPO_DOCUMENTO_ORDEN,
                tdp.OBLIGATORIO as TIPO_DOCUMENTO_OBLIGATORIO,
                er.ESTADO as ESTADO_REVISION,
                pp.ID_ESTUDIANTE,
                ic.NOMBRE as ENTIDAD_RECEPTORA,
                CONCAT(persona.NOMBRE, " ", persona.APELLIDO) as ESTUDIANTE_NOMBRE,
                persona.NOMBRE as NOMBRE_ESTUDIANTE,
                persona.APELLIDO as APELLIDO_ESTUDIANTE,
                persona.CEDULA as CEDULA_ESTUDIANTE
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left')
            ->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
            ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL', 'left')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'pp.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO', 'left')
            ->join('TAB_ESTUDIANTES e', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS persona', 'e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA', 'left')
            ->orderBy('dp.FECHA_SUBIDA', 'DESC');

        if ($idEstudiante !== null && (int) $idEstudiante > 0) {
            $builder->where('pp.ID_ESTUDIANTE', (int) $idEstudiante);
        }

        $result = $builder->get()->getResultArray();
        
        // Log para depuración
        log_message('debug', 'Consulta SQL: ' . $builder->getCompiledSelect());
        log_message('debug', 'Resultados obtenidos: ' . count($result));
        
        return $result;
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes()
    {
        return $this->documentosModel
            ->orderBy('FECHA_SUBIDA', 'DESC')
            ->limit(10)
            ->findAll();
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticas()
    {
        // Usar ID_ESTADO_REVISION en lugar de ESTADO_REVISION
        $aprobados = $this->documentosModel->where('ID_ESTADO_REVISION', 2)->countAllResults(); // Aprobado = 2
        $rechazados = $this->documentosModel->where('ID_ESTADO_REVISION', 3)->countAllResults(); // Rechazado = 3
        $requiereCorreccion = $this->documentosModel->where('ID_ESTADO_REVISION', 5)->countAllResults(); // Requiere Corrección = 5
        $pendientes = $this->documentosModel->where('ID_ESTADO_REVISION', 1)->countAllResults(); // Pendiente = 1

        return [
            'Aprobados' => $aprobados,
            'aprobados' => $aprobados, // Corregido para mostrar aprobados
            'pendientes' => $pendientes, // Corregido para mostrar pendientes
            'rechazados' => $rechazados, // Corregido para mostrar rechazados
            'requiere_correccion' => $requiereCorreccion // Agregado para compatibilidad
        ];
    }

    /**
     * Obtener estudiantes
     */
    private function getEstudiantes()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_ESTUDIANTES e')
            ->select('
                e.ID_ESTUDIANTE,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO,
                dp.CEDULA,
                c.NOMBRE as CARRERA
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->where('e.ID_TIPO_ESTADO', 1) // Solo estudiantes activos
            ->orderBy('dp.NOMBRE', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Buscar documentos con filtros
     */
    public function buscarDocumentos()
    {
        $filtros = $this->request->getGet();
        
        $builder = $this->documentosModel;
        
        if (!empty($filtros['tipo_documento'])) {
            $builder->where('ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('ESTADO_REVISION', $filtros['estado']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('DATE(FECHA_SUBIDA) >=', $filtros['fecha_desde']);
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('DATE(FECHA_SUBIDA) <=', $filtros['fecha_hasta']);
        }

        $documentos = $builder->orderBy('FECHA_SUBIDA', 'DESC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Exportar documentos
     */
    public function exportar($formato = 'excel')
    {
            $documentos = $this->getDocumentosCompletos();
            
        switch (strtolower($formato)) {
            case 'excel':
                return $this->exportarExcel($documentos);
            case 'pdf':
                return $this->exportarPDF($documentos);
            default:
            return $this->response->setJSON([
                'success' => true,
                    'data' => $documentos
            ]);
        }
    }

    /**
     * Exportar a Excel
     */
    private function exportarExcel($documentos)
    {
        try {
            helper('ExcelHelper');
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setTitle('Documentos de Prácticas');
            
            // Crear encabezado estándar
            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet, 
                'REPORTE DE DOCUMENTOS DE PRÁCTICAS', 
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'F1'
            );
            
            // Encabezados de columnas
            $headers = [
                'ID',
                'Estudiante',
                'Tipo Documento',
                'Archivo',
                'Fecha Subida',
                'Estado'
            ];
            
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');
            
            // Llenar datos
            $row = 6;
            foreach ($documentos as $doc) {
                $sheet->setCellValue('A' . $row, $doc['ID_DOCUMENTO_PREPROFESIONAL']);
                $sheet->setCellValue('B' . $row, $doc['ESTUDIANTE_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('C' . $row, $doc['TIPO_DOCUMENTO_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $doc['NOMBRE_ARCHIVO']);
                $sheet->setCellValue('E' . $row, date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])));
                $sheet->setCellValue('F' . $row, $doc['ESTADO_REVISION']);
                $row++;
            }
            
            // Aplicar estilos
            \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:F' . ($row - 1));
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'F');
            
            // Configurar descarga
            $filename = 'documentos_practicas_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar Excel: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar a PDF
     */
    private function exportarPDF($documentos)
    {
        // Implementar exportación a PDF si es necesario
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Exportación a PDF no implementada aún'
        ]);
    }

    /** Directorio donde se guardan los documentos de formato (prácticas preprofesionales). */
    private function getDirFormatosPracticas()
    {
        $dir = WRITEPATH . 'uploads/formatos_practicas/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /** Ruta del archivo JSON con la lista de documentos de formato. */
    private function getListaFormatosPracticasPath()
    {
        return $this->getDirFormatosPracticas() . 'lista.json';
    }

    /**
     * Obtener lista de documentos de formato (prácticas preprofesionales).
     */
    public function getListaFormatosPracticas()
    {
        $path = $this->getListaFormatosPracticasPath();
        if (!file_exists($path) || !is_readable($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $lista = json_decode($json, true);
        return is_array($lista) ? $lista : [];
    }

    /**
     * Subir un documento de formato (PDF, DOC, DOCX). Se muestra en la sección Formatos del estudiante.
     */
    public function subirDocumentoFormato()
    {
        $nombre = trim((string) $this->request->getPost('nombre'));
        $file = $this->request->getFile('documento');
        if (!$nombre) {
            return $this->response->setJSON(['success' => false, 'message' => 'Indique el nombre del documento.']);
        }
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Seleccione un archivo válido.']);
        }
        $ext = strtolower($file->getClientExtension());
        $permitidos = ['pdf', 'doc', 'docx'];
        if (!in_array($ext, $permitidos, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos PDF, DOC o DOCX.']);
        }
        $dir = $this->getDirFormatosPracticas();
        $nombreArchivo = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file->getClientName());
        $nombreArchivo = time() . '_' . $nombreArchivo;
        $file->move($dir, $nombreArchivo);
        $lista = $this->getListaFormatosPracticas();
        $lista[] = ['nombre' => $nombre, 'archivo' => $nombreArchivo];
        $path = $this->getListaFormatosPracticasPath();
        if (file_put_contents($path, json_encode($lista, JSON_UNESCAPED_UNICODE)) === false) {
            @unlink($dir . $nombreArchivo);
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la lista.']);
        }
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documento subido. Los estudiantes podrán descargarlo en Formatos.',
            'lista' => $this->getListaFormatosPracticas(),
        ]);
    }

    /**
     * Eliminar un documento de formato por nombre de archivo.
     */
    public function eliminarDocumentoFormato($archivo)
    {
        $archivo = basename($archivo);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.']);
        }
        $dir = $this->getDirFormatosPracticas();
        $rutaArchivo = $dir . $archivo;
        $lista = $this->getListaFormatosPracticas();
        $nuevaLista = array_values(array_filter($lista, function ($item) use ($archivo) {
            return ($item['archivo'] ?? '') !== $archivo;
        }));
        if (count($nuevaLista) === count($lista)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado.']);
        }
        if (file_exists($rutaArchivo) && is_file($rutaArchivo)) {
            @unlink($rutaArchivo);
        }
        $path = $this->getListaFormatosPracticasPath();
        file_put_contents($path, json_encode($nuevaLista, JSON_UNESCAPED_UNICODE));
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documento eliminado.',
            'lista' => $this->getListaFormatosPracticas(),
        ]);
    }

    /**
     * API: Obtener documentos para el grid
     */
    public function obtenerDocumentos()
    {
        try {
            $idEstudiante = (int) $this->request->getGet('estudiante');
            $documentos = $this->getDocumentosCompletos($idEstudiante > 0 ? $idEstudiante : null);
            
            // Log para depuración
            log_message('debug', 'Documentos obtenidos: ' . json_encode($documentos));
            
            return $this->response->setJSON([
                'success' => true,
                'documentos' => $documentos,
                'data' => $documentos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener documentos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cargar documentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Método de prueba para verificar datos
     */
    public function testDatos()
    {
        try {
            // Verificar tipos de documentos
            $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
            
            // Verificar documentos
            $documentos = $this->getDocumentosCompletos();
            
            return $this->response->setJSON([
                'success' => true,
                'tipos_documentos' => $tiposDocumentos,
                'documentos' => $documentos,
                'count_tipos' => count($tiposDocumentos),
                'count_documentos' => count($documentos)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Crear nuevo tipo de documento PPR
     */
    public function crearTipo()
    {
        try {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'codigo' => 'required|max_length[50]|regex_match[/^PPR-\d{3}$/]',
                'nombre' => 'required|max_length[150]',
                'descripcion' => 'permit_empty|max_length[500]',
                'orden' => 'required|integer|greater_than[0]|less_than[100]',
                'obligatorio' => 'required|in_list[0,1]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validation->getErrors()
                ]);
            }

            $codigo = $this->request->getPost('codigo');
            $nombre = $this->request->getPost('nombre');
            $descripcion = $this->request->getPost('descripcion');
            $orden = $this->request->getPost('orden');
            $obligatorio = $this->request->getPost('obligatorio');

            // Verificar si el código ya existe
            if ($this->tiposDocumentosModel->tipoExiste($codigo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El código PPR ya existe. Por favor, use un código diferente.'
                ]);
            }

            // Verificar si el orden ya existe
            $tipoConOrden = $this->tiposDocumentosModel->where('ORDEN', $orden)->first();
            if ($tipoConOrden) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El orden ya está en uso. Por favor, use un orden diferente.'
                ]);
            }

            $data = [
                'CODIGO' => $codigo,
                'NOMBRE' => $nombre,
                'DESCRIPCION' => $descripcion,
                'ORDEN' => $orden,
                'OBLIGATORIO' => $obligatorio
            ];

            if ($this->tiposDocumentosModel->insert($data)) {
                $nuevoTipo = $this->tiposDocumentosModel->where('CODIGO', $codigo)->first();
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de documento PPR creado exitosamente',
                    'tipo' => $nuevoTipo
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el tipo de documento'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al crear tipo PPR: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }
}