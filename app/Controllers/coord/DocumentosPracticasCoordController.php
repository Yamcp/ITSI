<?php

namespace App\Controllers\coord;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;

class DocumentosPracticasCoordController extends BaseController
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

        return view('coord/documentos/documentos_practicas', $data);
    }

    /**
     * Mostrar formulario de subida de documentos
     */
    public function upload()
    {
        $data = [
            'title' => 'Subir Documento de Práctica',
            'estudiantes' => $this->getEstudiantes(),
            'tipos_documentos' => $this->tiposDocumentosModel->getAllTipos()
        ];

        return view('coord/documentos/documentos_practicas', $data);
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
        try {
            $query = $this->documentosModel->db->table('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp')
                ->select('dp.*, tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE, er.ESTADO as ESTADO_REVISION')
                ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left')
                ->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
                ->where('dp.ID_PRACTICA_PREPROFESIONAL', (int) $idPractica)
                ->orderBy('dp.FECHA_SUBIDA', 'DESC')
                ->get();

            return $this->response->setJSON([
                'success' => true,
                'data' => $query === false ? [] : $query->getResultArray(),
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ])->setStatusCode(500);
        }
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
        try {
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

            $query = $builder->get();
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'getDocumentosCompletos practicas SQL: ' . ($error['message'] ?? 'query failed'));
                return [];
            }

            return $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'getDocumentosCompletos practicas: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes()
    {
        try {
            $query = $this->documentosModel->db->table($this->documentosModel->getTable())
                ->orderBy('FECHA_SUBIDA', 'DESC')
                ->limit(10)
                ->get();

            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'getDocumentosRecientes practicas: ' . $e->getMessage());
            return [];
        }
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

        $total = $this->documentosModel->countAllResults();

        return [
            'total' => $total,
            'Aprobados' => $aprobados,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados,
            'requiere_correccion' => $requiereCorreccion,
        ];
    }

    /**
     * Mostrar vista de reportes de documentos de prácticas
     */
    public function reportes()
    {
        $filtros = $this->request->getGet() ?? [];
        $documentos = $this->getDocumentosCompletos();

        if (!empty($filtros['tipo_documento'])) {
            $tipoFiltro = (int) $filtros['tipo_documento'];
            $documentos = array_values(array_filter($documentos, static function ($doc) use ($tipoFiltro) {
                return (int) ($doc['ID_TIPO_DOCUMENTO'] ?? $doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? 0) === $tipoFiltro;
            }));
        }

        if (!empty($filtros['estado_revision'])) {
            $estadoFiltro = (int) $filtros['estado_revision'];
            $documentos = array_values(array_filter($documentos, static function ($doc) use ($estadoFiltro) {
                return (int) ($doc['ID_ESTADO_REVISION'] ?? 0) === $estadoFiltro;
            }));
        }

        if (!empty($filtros['fecha_inicio'])) {
            $desde = $filtros['fecha_inicio'];
            $documentos = array_values(array_filter($documentos, static function ($doc) use ($desde) {
                $fecha = substr((string) ($doc['FECHA_SUBIDA'] ?? ''), 0, 10);
                return $fecha !== '' && $fecha >= $desde;
            }));
        }

        if (!empty($filtros['fecha_fin'])) {
            $hasta = $filtros['fecha_fin'];
            $documentos = array_values(array_filter($documentos, static function ($doc) use ($hasta) {
                $fecha = substr((string) ($doc['FECHA_SUBIDA'] ?? ''), 0, 10);
                return $fecha !== '' && $fecha <= $hasta;
            }));
        }

        if (!empty($filtros['entidad_receptora'])) {
            $entidad = mb_strtolower(trim((string) $filtros['entidad_receptora']));
            $documentos = array_values(array_filter($documentos, static function ($doc) use ($entidad) {
                return str_contains(mb_strtolower((string) ($doc['ENTIDAD_RECEPTORA'] ?? '')), $entidad);
            }));
        }

        $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();

        $data = [
            'title' => 'Reportes de Documentos de Prácticas',
            'documentos' => $documentos,
            'estadisticas' => $this->getEstadisticas(),
            'tipos_documentos' => $tiposDocumentos,
            'tiposDocumentos' => $tiposDocumentos,
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'filtros' => $filtros,
        ];

        return view('coord/documentos/reportes_practicas', $data);
    }

    /**
     * Obtener estudiantes
     */
    private function getEstudiantes()
    {
        try {
            $db = \Config\Database::connect();

            $query = $db->table('TAB_ESTUDIANTES e')
                ->select('
                    e.ID_ESTUDIANTE,
                    CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO,
                    dp.CEDULA,
                    c.NOMBRE as CARRERA
                ')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA', 'left')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left')
                ->where('e.ID_TIPO_ESTADO', 1)
                ->orderBy('dp.NOMBRE', 'ASC')
                ->get();

            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'getEstudiantes practicas docs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar documentos con filtros
     */
    public function buscarDocumentos()
    {
        try {
            $filtros = $this->request->getGet() ?? [];
            $builder = $this->documentosModel->db->table('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp')
                ->select('dp.*, er.ESTADO as ESTADO_REVISION, tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE')
                ->join('TAB_ESTADOS_REVISIONES er', 'dp.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION', 'left')
                ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left');

            if (!empty($filtros['tipo_documento'])) {
                $builder->where('dp.ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
            }

            if (!empty($filtros['estado'])) {
                if (is_numeric($filtros['estado'])) {
                    $builder->where('dp.ID_ESTADO_REVISION', (int) $filtros['estado']);
                } else {
                    $builder->where('er.ESTADO', (string) $filtros['estado']);
                }
            }

            if (!empty($filtros['fecha_desde'])) {
                $builder->where('DATE(dp.FECHA_SUBIDA) >=', $filtros['fecha_desde']);
            }

            if (!empty($filtros['fecha_hasta'])) {
                $builder->where('DATE(dp.FECHA_SUBIDA) <=', $filtros['fecha_hasta']);
            }

            $query = $builder->orderBy('dp.FECHA_SUBIDA', 'DESC')->get();

            return $this->response->setJSON([
                'success' => true,
                'data' => $query === false ? [] : $query->getResultArray(),
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ])->setStatusCode(500);
        }
    }

    /**
     * Exportar documentos
     */
    public function exportar($formato = 'excel')
    {
        try {
            $documentos = $this->getDocumentosCompletos();

            switch (strtolower((string) $formato)) {
                case 'excel':
                    return $this->exportarExcel($documentos);
                case 'pdf':
                    return $this->exportarPDF($documentos);
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Formato no soportado. Use excel o pdf.',
                    ])->setStatusCode(400);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error exportar documentos practicas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage(),
            ])->setStatusCode(500);
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
                $estudiante = trim((string) ($doc['ESTUDIANTE_NOMBRE'] ?? (($doc['NOMBRE_ESTUDIANTE'] ?? '') . ' ' . ($doc['APELLIDO_ESTUDIANTE'] ?? ''))));
                $sheet->setCellValue('A' . $row, $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? '');
                $sheet->setCellValue('B' . $row, $estudiante !== '' ? $estudiante : 'N/A');
                $sheet->setCellValue('C' . $row, $doc['TIPO_DOCUMENTO_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $doc['NOMBRE_ORIGINAL'] ?? $doc['NOMBRE_ARCHIVO'] ?? '');
                $sheet->setCellValue('E' . $row, !empty($doc['FECHA_SUBIDA']) ? date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])) : '');
                $sheet->setCellValue('F' . $row, $doc['ESTADO_REVISION'] ?? '');
                $row++;
            }
            
            // Aplicar estilos
            if ($row > 6) {
                \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:F' . ($row - 1));
            }
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'F');
            
            // Configurar descarga
            $filename = 'documentos_practicas_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
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
        try {
            $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('Sistema ITSI');
            $pdf->SetAuthor('Coordinador');
            $pdf->SetTitle('Documentos de Prácticas');
            $pdf->SetSubject('Reporte de documentos de prácticas preprofesionales');
            $pdf->SetMargins(12, 18, 12);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->AddPage();

            helper('PdfHelper');
            \App\Helpers\PdfHelper::addLogoToPdf($pdf, 'Logo PDF.jpg', 12, 10, 28);

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 8, 'REPORTE DE DOCUMENTOS DE PRÁCTICAS', 0, 1, 'C');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, 'Instituto Tecnológico Superior Ibarra', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Generado el: ' . \App\Helpers\PdfHelper::getCurrentDateTime(), 0, 1, 'C');
            $pdf->Ln(4);

            $headers = [
                ['w' => 18, 'label' => 'ID'],
                ['w' => 55, 'label' => 'Estudiante'],
                ['w' => 50, 'label' => 'Tipo'],
                ['w' => 70, 'label' => 'Archivo'],
                ['w' => 35, 'label' => 'Fecha'],
                ['w' => 35, 'label' => 'Estado'],
            ];

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(52, 58, 64);
            $pdf->SetTextColor(255, 255, 255);
            foreach ($headers as $header) {
                $pdf->Cell($header['w'], 7, $header['label'], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(0, 0, 0);
            $fill = false;

            if (empty($documentos)) {
                $pdf->Cell(array_sum(array_column($headers, 'w')), 8, 'No hay documentos registrados', 1, 1, 'C');
            } else {
                foreach ($documentos as $doc) {
                    if ($pdf->GetY() > 185) {
                        $pdf->AddPage();
                        $pdf->SetFont('helvetica', 'B', 8);
                        $pdf->SetFillColor(52, 58, 64);
                        $pdf->SetTextColor(255, 255, 255);
                        foreach ($headers as $header) {
                            $pdf->Cell($header['w'], 7, $header['label'], 1, 0, 'C', true);
                        }
                        $pdf->Ln();
                        $pdf->SetFont('helvetica', '', 7);
                        $pdf->SetTextColor(0, 0, 0);
                    }

                    $pdf->SetFillColor(245, 245, 245);
                    $fecha = !empty($doc['FECHA_SUBIDA']) ? date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])) : '';
                    $row = [
                        (string) ($doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? ''),
                        (string) ($doc['ESTUDIANTE_NOMBRE'] ?? trim(($doc['NOMBRE_ESTUDIANTE'] ?? '') . ' ' . ($doc['APELLIDO_ESTUDIANTE'] ?? ''))),
                        (string) ($doc['TIPO_DOCUMENTO_NOMBRE'] ?? ''),
                        (string) ($doc['NOMBRE_ORIGINAL'] ?? $doc['NOMBRE_ARCHIVO'] ?? ''),
                        $fecha,
                        (string) ($doc['ESTADO_REVISION'] ?? ''),
                    ];

                    foreach ($headers as $i => $header) {
                        $pdf->Cell($header['w'], 6, mb_substr($row[$i], 0, 40), 1, 0, $i === 0 || $i === 4 ? 'C' : 'L', $fill);
                    }
                    $pdf->Ln();
                    $fill = !$fill;
                }
            }

            $filename = 'documentos_practicas_' . date('Y-m-d_H-i-s') . '.pdf';
            $pdf->Output($filename, 'D');
            exit;
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar PDF: ' . $e->getMessage()
            ]);
        }
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
     * Actualizar solo el nombre mostrado de un documento de formato (no renombra el archivo en disco).
     */
    public function actualizarNombreDocumentoFormato()
    {
        $archivo = basename((string) $this->request->getPost('archivo'));
        $nombre = trim((string) $this->request->getPost('nombre'));
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.']);
        }
        if ($nombre === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Indique el nombre del documento.']);
        }
        if (mb_strlen($nombre) > 500) {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre no puede superar 500 caracteres.']);
        }
        $lista = $this->getListaFormatosPracticas();
        $found = false;
        foreach ($lista as $k => $item) {
            if (($item['archivo'] ?? '') === $archivo) {
                $lista[$k]['nombre'] = $nombre;
                $found = true;
                break;
            }
        }
        if (!$found) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado.']);
        }
        $path = $this->getListaFormatosPracticasPath();
        if (file_put_contents($path, json_encode($lista, JSON_UNESCAPED_UNICODE)) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la lista.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Nombre actualizado correctamente.',
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
                'descripcion' => 'permit_empty|max_length[5000]',
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

    /**
     * Actualizar tipo de documento PPR (descripción, nombre, código, orden, obligatorio)
     */
    public function actualizarTipo($id = null)
    {
        try {
            $id = (int) $id;
            if ($id <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Identificador de tipo no válido',
                ]);
            }

            $actual = $this->tiposDocumentosModel->find($id);
            if (!$actual) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de documento no encontrado',
                ]);
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'codigo' => 'required|max_length[50]|regex_match[/^PPR-\d{3}$/]',
                'nombre' => 'required|max_length[150]',
                'descripcion' => 'permit_empty|max_length[5000]',
                'orden' => 'required|integer|greater_than[0]|less_than[100]',
                'obligatorio' => 'required|in_list[0,1]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validation->getErrors(),
                ]);
            }

            $codigo = $this->request->getPost('codigo');
            $nombre = $this->request->getPost('nombre');
            $descripcion = $this->request->getPost('descripcion') ?? '';
            $orden = (int) $this->request->getPost('orden');
            $obligatorio = $this->request->getPost('obligatorio');

            $duplicadoCodigo = $this->tiposDocumentosModel
                ->where('CODIGO', $codigo)
                ->where('ID_TIPO_DOCUMENTO_PREPROFESIONAL !=', $id)
                ->first();
            if ($duplicadoCodigo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El código PPR ya está en uso por otro tipo.',
                ]);
            }

            $duplicadoOrden = $this->tiposDocumentosModel
                ->where('ORDEN', $orden)
                ->where('ID_TIPO_DOCUMENTO_PREPROFESIONAL !=', $id)
                ->first();
            if ($duplicadoOrden) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El orden ya está en uso. Elija otro número de orden.',
                ]);
            }

            $data = [
                'CODIGO' => $codigo,
                'NOMBRE' => $nombre,
                'DESCRIPCION' => $descripcion,
                'ORDEN' => $orden,
                'OBLIGATORIO' => (int) $obligatorio,
            ];

            if ($this->tiposDocumentosModel->skipValidation(true)->update($id, $data)) {
                $actualizado = $this->tiposDocumentosModel->find($id);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de documento actualizado correctamente',
                    'tipo' => $actualizado,
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo guardar el tipo de documento',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar tipo PPR: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage(),
            ]);
        }
    }
}