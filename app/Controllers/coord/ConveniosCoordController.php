<?php

namespace App\Controllers\coord;

use App\Models\DetallesConveniosModel;
use App\Models\InstitucionesConveniosModel;
use App\Models\TiposConveniosModel;
use App\Models\CarrerasModel;
use App\Controllers\BaseController;

class ConveniosCoordController extends BaseController
{
    protected $conveniosModel;
    protected $institucionesModel;
    protected $tiposConveniosModel;
    protected $carrerasModel;

    public function __construct()
    {
        $this->conveniosModel = new DetallesConveniosModel();
        $this->institucionesModel = new InstitucionesConveniosModel();
        $this->tiposConveniosModel = new TiposConveniosModel();
        $this->carrerasModel = new CarrerasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Convenios',
            'convenios' => $this->conveniosModel->getConveniosCompletos(),
            'instituciones' => $this->institucionesModel->getInstitucionesConTipo(),
            'tipos_convenios' => $this->tiposConveniosModel->findAll(),
            'carreras' => $this->carrerasModel->orderBy('NOMBRE')->findAll(),
            'estadisticas' => $this->getEstadisticas()
        ];

        return view('coord/convenios/convenios', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Nuevo Convenio',
            'instituciones' => $this->institucionesModel->getInstitucionesConTipo(),
            'tipos_convenios' => $this->tiposConveniosModel->findAll()
        ];

        return view('convenios/create', $data);
    }

    public function store()
    {
        $rules = [
            'tipo_convenio' => 'required|integer',
            'institucion' => 'required|integer',
            'carrera' => 'required|integer',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'duracion' => 'required|integer|greater_than[0]',
            'objetivo' => 'required|min_length[10]',
            'renovable' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'ID_TIPO_CONVENIO' => $this->request->getPost('tipo_convenio'),
            'ID_INSTITUCION_CONVENIO' => $this->request->getPost('institucion'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'DURACION' => $this->request->getPost('duracion'),
            'OBJETIVO' => $this->request->getPost('objetivo'),
            'OBSERVACIONES' => $this->request->getPost('observaciones') ?? '',
            'RENOVABLE' => $this->request->getPost('renovable'),
        ];
        if ($this->conveniosModel->tieneColumnasCarreraYPlazas()) {
            $data['ID_CARRERA'] = $this->request->getPost('carrera');
            $data['PLAZAS_DISPONIBLES'] = (int) ($this->request->getPost('plazas_disponibles') ?? 0);
        }

        // Manejar archivo si se sube
        $archivo = $this->request->getFile('archivo_convenio');
        if ($archivo && $archivo->isValid()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move(WRITEPATH . 'uploads/convenios/', $nombreArchivo);
            $data['ARCHIVO_CONVENIO'] = $nombreArchivo;
        } else {
            $data['ARCHIVO_CONVENIO'] = '';
        }

        if ($this->conveniosModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Convenio guardado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar el convenio',
                'errors' => $this->conveniosModel->errors()
            ]);
        }
    }

    public function storeInstitucion()
    {
        $rules = [
            'tipo_institucion' => 'required|integer',
            'nombre' => 'required|min_length[5]|max_length[200]',
            'ruc' => 'required|min_length[10]|max_length[20]|is_unique[TAB_INSTITUCIONES_CONVENIOS.RUC]',
            'ciudad' => 'required|min_length[2]|max_length[50]',
            'direccion' => 'required|min_length[10]',
            'telefono' => 'required|min_length[7]|max_length[20]',
            'email' => 'required|valid_email',
            'representante_legal' => 'required|min_length[5]|max_length[150]',
            'contacto' => 'required|min_length[5]|max_length[150]',
            'telefono_contacto' => 'required|min_length[7]|max_length[20]',
            'email_contacto' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'ID_TIPO_INSTITUCION' => $this->request->getPost('tipo_institucion'),
            'NOMBRE' => $this->request->getPost('nombre'),
            'RUC' => $this->request->getPost('ruc'),
            'CIUDAD' => $this->request->getPost('ciudad'),
            'DIRECCION' => $this->request->getPost('direccion'),
            'TELEFONO' => $this->request->getPost('telefono'),
            'EMAIL' => $this->request->getPost('email'),
            'REPRESENTANTE_LEGAL' => $this->request->getPost('representante_legal'),
            'CONTACTO' => $this->request->getPost('contacto'),
            'TELEFONO_CONTACTO' => $this->request->getPost('telefono_contacto'),
            'EMAIL_CONTACTO' => $this->request->getPost('email_contacto')
        ];

        $dirLogos = FCPATH . 'uploads/logos_instituciones/';
        if (!is_dir($dirLogos)) {
            mkdir($dirLogos, 0755, true);
        }
        $archivoLogo = $this->request->getFile('logo_empresa');
        if ($archivoLogo && $archivoLogo->isValid() && !$archivoLogo->hasMoved()) {
            $ext = $archivoLogo->getClientExtension();
            $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($ext ?? ''), $permitidos)) {
                $nombreLogo = $archivoLogo->getRandomName();
                $archivoLogo->move($dirLogos, $nombreLogo);
                $data['LOGO'] = $nombreLogo;
            }
        }
        if (empty($data['LOGO'])) {
            $data['LOGO'] = null;
        }

        if ($this->institucionesModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Institución guardada exitosamente',
                'institucion_id' => $this->institucionesModel->getInsertID()
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar la institución',
                'errors' => $this->institucionesModel->errors()
            ]);
        }
    }

    public function getInstituciones()
    {
        $instituciones = $this->institucionesModel->getInstitucionesConTipo();
        return $this->response->setJSON([
            'success' => true,
            'data' => $instituciones
        ]);
    }

    public function getConvenios()
    {
        $tipo = $this->request->getGet('tipo');
        $convenios = $this->conveniosModel->getConveniosCompletos();
        
        if ($tipo) {
            $convenios = array_filter($convenios, function($convenio) use ($tipo) {
                return $convenio['ID_TIPO_CONVENIO'] == $tipo;
            });
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => array_values($convenios)
        ]);
    }

    /**
     * Actualizar solo plazas disponibles de un convenio (registrar o actualizar si existe).
     */
    public function actualizarPlazas($id)
    {
        $id = (int) $id;
        $plazas = (int) ($this->request->getPost('plazas_disponibles') ?? $this->request->getGet('plazas_disponibles'));
        if ($plazas < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Las plazas no pueden ser negativas.']);
        }
        $convenio = $this->conveniosModel->find($id);
        if (!$convenio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Convenio no encontrado.']);
        }
        if (!$this->conveniosModel->tieneColumnasCarreraYPlazas()) {
            return $this->response->setJSON(['success' => false, 'message' => 'La base de datos no tiene la columna de plazas (ni el esquema esperado de convenios). Actualice el esquema según bddITSI.sql (TAB_DETALLES_CONVENIOS: ID_CARRERA, PLAZAS_DISPONIBLES) o importe de nuevo ese script.']);
        }
        if ($this->conveniosModel->update($id, ['PLAZAS_DISPONIBLES' => $plazas])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Plazas actualizadas correctamente.', 'plazas_disponibles' => $plazas]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar plazas.']);
    }

    /**
     * Obtener un convenio por ID (para formulario de edición).
     */
    public function getConvenio($id)
    {
        $id = (int) $id;
        $convenio = $this->conveniosModel->find($id);
        if (!$convenio) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Convenio no encontrado.']);
        }
        return $this->response->setJSON(['success' => true, 'data' => $convenio]);
    }

    /**
     * Actualizar un convenio existente.
     */
    public function update($id)
    {
        $id = (int) $id;
        $convenio = $this->conveniosModel->find($id);
        if (!$convenio) {
            return $this->response->setJSON(['success' => false, 'message' => 'Convenio no encontrado.']);
        }

        $rules = [
            'tipo_convenio' => 'required|integer',
            'institucion' => 'required|integer',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'duracion' => 'required|integer|greater_than[0]',
            'objetivo' => 'required|min_length[10]',
            'renovable' => 'required|in_list[0,1]'
        ];
        if ($this->conveniosModel->tieneColumnasCarreraYPlazas()) {
            $rules['carrera'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'ID_TIPO_CONVENIO' => $this->request->getPost('tipo_convenio'),
            'ID_INSTITUCION_CONVENIO' => $this->request->getPost('institucion'),
            'FECHA_INICIO' => $this->request->getPost('fecha_inicio'),
            'FECHA_FIN' => $this->request->getPost('fecha_fin'),
            'DURACION' => $this->request->getPost('duracion'),
            'OBJETIVO' => $this->request->getPost('objetivo'),
            'OBSERVACIONES' => $this->request->getPost('observaciones') ?? '',
            'RENOVABLE' => $this->request->getPost('renovable'),
        ];
        if ($this->conveniosModel->tieneColumnasCarreraYPlazas()) {
            $data['ID_CARRERA'] = $this->request->getPost('carrera');
            $data['PLAZAS_DISPONIBLES'] = (int) ($this->request->getPost('plazas_disponibles') ?? 0);
        }

        $archivo = $this->request->getFile('archivo_convenio');
        if ($archivo && $archivo->isValid()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move(WRITEPATH . 'uploads/convenios/', $nombreArchivo);
            $data['ARCHIVO_CONVENIO'] = $nombreArchivo;
        }

        if ($this->conveniosModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Convenio actualizado correctamente'
            ]);
        }
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar el convenio',
            'errors' => $this->conveniosModel->errors()
        ]);
    }

    public function getEstadisticas()
    {
        $convenios = $this->conveniosModel->getConveniosCompletos();
        $fechaActual = date('Y-m-d');
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        
        $total = count($convenios);
        $vigentes = 0;
        $porVencer = 0;
        $vencidos = 0;
        
        foreach ($convenios as $convenio) {
            if ($convenio['FECHA_FIN'] >= $fechaActual) {
                if ($convenio['FECHA_FIN'] <= $fechaLimite) {
                    $porVencer++;
                } else {
                    $vigentes++;
                }
            } else {
                $vencidos++;
            }
        }
        
        return [
            'total' => $total,
            'vigentes' => $vigentes,
            'por_vencer' => $porVencer,
            'vencidos' => $vencidos
        ];
    }

    public function generarReporte()
    {
        $filtros = $this->request->getGet();
        $formato = $filtros['formato'] ?? 'pdf';
        
        // Obtener convenios con filtros aplicados
        $convenios = $this->conveniosModel->getConveniosCompletos();
        
        // Aplicar filtros
        if (!empty($filtros['tipo'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['ID_TIPO_CONVENIO'] == $filtros['tipo'];
            });
        }
        
        if (!empty($filtros['tipo_convenio'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['ID_TIPO_CONVENIO'] == $filtros['tipo_convenio'];
            });
        }
        
        if (!empty($filtros['tipo_institucion'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['ID_TIPO_INSTITUCION'] == $filtros['tipo_institucion'];
            });
        }
        
        if (!empty($filtros['estado'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                $estado = $this->calcularEstado($c['FECHA_FIN']);
                return strtolower($estado) === $filtros['estado'];
            });
        }
        
        if (!empty($filtros['fecha_inicio'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['FECHA_INICIO'] >= $filtros['fecha_inicio'];
            });
        }
        
        if (!empty($filtros['fecha_fin'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['FECHA_FIN'] <= $filtros['fecha_fin'];
            });
        }
        
        if (isset($filtros['renovable']) && $filtros['renovable'] !== '') {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['RENOVABLE'] == $filtros['renovable'];
            });
        }
        
        // Reindexar array
        $convenios = array_values($convenios);
        
        $data = [
            'convenios' => $convenios,
            'estadisticas' => $this->getEstadisticas(),
            'fecha_generacion' => date('Y-m-d H:i:s')
        ];
        
        if ($formato === 'excel') {
            return $this->exportarExcel($data);
        } elseif ($formato === 'csv') {
            return $this->exportarCSV($data);
        } else {
            return $this->exportarPDF($data);
        }
    }

    private function exportarPDF($data)
    {
        try {
            // Cargar la librería TCPDF
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            
            // Configurar información del documento
            $pdf->SetCreator('Sistema ITSI');
            $pdf->SetAuthor('Coordinador');
            $pdf->SetTitle('Reporte de Convenios');
            $pdf->SetSubject('Reporte de Convenios del Sistema');
            
            // Configurar márgenes
            $pdf->SetMargins(15, 20, 15);
            $pdf->SetHeaderMargin(10);
            $pdf->SetFooterMargin(10);
            
            // Configurar fuente
            $pdf->SetFont('helvetica', '', 10);
            
            // Agregar página
            $pdf->AddPage();
            
            // Generar contenido del PDF
            $this->generarContenidoPDF($pdf, $data);
            
            // Generar nombre del archivo
            $nombreArchivo = 'reporte_convenios_' . date('Y-m-d_H-i-s') . '.pdf';
            
            // Descargar PDF
            $pdf->Output($nombreArchivo, 'D');
            
        } catch (\Exception $e) {
            log_message('error', 'Error al generar PDF: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ]);
        }
    }
    
    private function generarContenidoPDF($pdf, $data)
    {
        // Agregar logo usando el helper
        helper('PdfHelper');
        \App\Helpers\PdfHelper::addLogoToPdf($pdf, 'Logo PDF.jpg', 15, 10, 30);
        
        // Encabezado
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'REPORTE DE CONVENIOS', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, 'Instituto Tecnológico Superior Ibarra', 0, 1, 'C');
        $pdf->Cell(0, 8, 'Generado el: ' . \App\Helpers\PdfHelper::getCurrentDateTime(), 0, 1, 'C');
        $pdf->Ln(10);
        
        // Estadísticas
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'ESTADÍSTICAS GENERALES', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        
        $estadisticas = $data['estadisticas'];
        $pdf->Cell(45, 6, 'Total Convenios:', 0, 0, 'L');
        $pdf->Cell(20, 6, $estadisticas['total'], 0, 0, 'R');
        $pdf->Cell(45, 6, 'Vigentes:', 0, 0, 'L');
        $pdf->Cell(20, 6, $estadisticas['vigentes'], 0, 1, 'R');
        
        $pdf->Cell(45, 6, 'Por Vencer:', 0, 0, 'L');
        $pdf->Cell(20, 6, $estadisticas['por_vencer'], 0, 0, 'R');
        $pdf->Cell(45, 6, 'Vencidos:', 0, 0, 'L');
        $pdf->Cell(20, 6, $estadisticas['vencidos'], 0, 1, 'R');
        $pdf->Ln(10);
        
        // Tabla de convenios
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'DETALLE DE CONVENIOS', 0, 1, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        
        // Encabezados de tabla
        $pdf->Cell(15, 6, 'ID', 1, 0, 'C');
        $pdf->Cell(50, 6, 'Institución', 1, 0, 'C');
        $pdf->Cell(25, 6, 'RUC', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Tipo', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Fecha Inicio', 1, 0, 'C');
        $pdf->Cell(25, 6, 'Fecha Fin', 1, 0, 'C');
        $pdf->Cell(20, 6, 'Estado', 1, 1, 'C');
        
        // Datos de la tabla
        $pdf->SetFont('helvetica', '', 7);
        foreach ($data['convenios'] as $convenio) {
            // Calcular estado
            $fechaActual = date('Y-m-d');
            $fechaLimite = date('Y-m-d', strtotime('+30 days'));
            if ($convenio['FECHA_FIN'] < $fechaActual) {
                $estado = 'Vencido';
            } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                $estado = 'Por Vencer';
            } else {
                $estado = 'Vigente';
            }
            
            $pdf->Cell(15, 5, $convenio['ID_DETALLE_CONVENIO'], 1, 0, 'C');
            $pdf->Cell(50, 5, substr($convenio['NOMBRE'], 0, 25), 1, 0, 'L');
            $pdf->Cell(25, 5, $convenio['RUC'], 1, 0, 'C');
            $pdf->Cell(30, 5, substr($convenio['TIPO_CONVENIO'], 0, 15), 1, 0, 'C');
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($convenio['FECHA_INICIO'])), 1, 0, 'C');
            $pdf->Cell(25, 5, date('d/m/Y', strtotime($convenio['FECHA_FIN'])), 1, 0, 'C');
            $pdf->Cell(20, 5, $estado, 1, 1, 'C');
        }
        
        // Pie de página
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 5, 'Este reporte fue generado automáticamente por el Sistema de Gestión de Convenios', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Instituto Tecnológico Superior Ibarra - ' . date('Y'), 0, 1, 'C');
    }

    private function exportarExcel($data)
    {
        try {
            // Cargar helper de Excel
            helper('ExcelHelper');
            
            // Crear archivo Excel usando PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Configurar encabezados
            $sheet->setTitle('Convenios');
            
            // Crear encabezado estándar con logo
            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet, 
                'REPORTE DE CONVENIOS', 
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'D1'
            );
            
            // Encabezados de columnas
            $headers = [
                'ID',
                'Institución',
                'RUC',
                'Tipo',
                'Fecha Inicio',
                'Fecha Fin',
                'Duración (meses)',
                'Objetivo',
                'Renovable',
                'Estado',
                'Observaciones'
            ];
            
            // Crear encabezados de columnas con estilo
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');
            
            // Llenar datos
            $row = 6;
            foreach ($data['convenios'] as $convenio) {
                $estado = $this->calcularEstado($convenio['FECHA_FIN']);
                $sheet->setCellValue('A' . $row, $convenio['ID_DETALLE_CONVENIO']);
                $sheet->setCellValue('B' . $row, $convenio['NOMBRE']);
                $sheet->setCellValue('C' . $row, $convenio['RUC']);
                $sheet->setCellValue('D' . $row, $convenio['TIPO_CONVENIO']);
                $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($convenio['FECHA_INICIO'])));
                $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($convenio['FECHA_FIN'])));
                $sheet->setCellValue('G' . $row, $convenio['DURACION']);
                $sheet->setCellValue('H' . $row, $convenio['OBJETIVO']);
                $sheet->setCellValue('I' . $row, $convenio['RENOVABLE'] ? 'Sí' : 'No');
                $sheet->setCellValue('J' . $row, $estado);
                $sheet->setCellValue('K' . $row, $convenio['OBSERVACIONES'] ?? '');
                $row++;
            }
            
            // Aplicar estilo a los datos
            if ($row > 6) {
                \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:K' . ($row - 1));
            }
            
            // Autoajustar columnas
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'K');
            
            // Configurar headers para descarga
            $filename = 'convenios_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);
            
            // Escribir archivo
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar Excel: ' . $e->getMessage()
            ]);
        }
    }

    private function exportarCSV($data)
    {
        // Implementar exportación a CSV
        $filename = 'convenios_' . date('Y-m-d') . '.csv';
        
        $output = fopen('php://temp', 'w');
        
        // Headers con BOM para UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Headers
        fputcsv($output, [
            'ID', 'Institución', 'RUC', 'Tipo', 'Fecha Inicio', 'Fecha Fin', 
            'Duración (meses)', 'Objetivo', 'Renovable', 'Estado', 'Observaciones'
        ]);
        
        // Data
        foreach ($data['convenios'] as $convenio) {
            $estado = $this->calcularEstado($convenio['FECHA_FIN']);
            fputcsv($output, [
                $convenio['ID_DETALLE_CONVENIO'],
                $convenio['NOMBRE'],
                $convenio['RUC'],
                $convenio['TIPO_CONVENIO'],
                date('d/m/Y', strtotime($convenio['FECHA_INICIO'])),
                date('d/m/Y', strtotime($convenio['FECHA_FIN'])),
                $convenio['DURACION'],
                $convenio['OBJETIVO'],
                $convenio['RENOVABLE'] ? 'Sí' : 'No',
                $estado,
                $convenio['OBSERVACIONES'] ?? ''
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8')
                             ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                             ->setBody($csv);
    }

    private function calcularEstado($fechaFin)
    {
        $fechaActual = date('Y-m-d');
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        
        if ($fechaFin < $fechaActual) {
            return 'Vencido';
        } elseif ($fechaFin <= $fechaLimite) {
            return 'Por Vencer';
        } else {
            return 'Vigente';
        }
    }

    public function vencimientos()
    {
        $conveniosProximos = $this->conveniosModel->getConveniosPorVencer();

        $data = [
            'title' => 'Convenios por Vencer',
            'convenios' => $conveniosProximos
        ];

        return view('convenios/vencimientos', $data);
    }

    public function reportes()
    {
        // Obtener filtros de la URL
        $filtros = $this->request->getGet();
        
        // Obtener datos base
        $tipos_convenios = $this->tiposConveniosModel->findAll();
        $estadisticas = $this->getEstadisticas();
        
        // Aplicar filtros a los convenios
        $convenios = $this->conveniosModel->getConveniosCompletos();
        
        // Filtrar por tipo de convenio
        if (!empty($filtros['tipo_convenio'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['ID_TIPO_CONVENIO'] == $filtros['tipo_convenio'];
            });
        }
        
        // Filtrar por tipo de institución
        if (!empty($filtros['tipo_institucion'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['ID_TIPO_INSTITUCION'] == $filtros['tipo_institucion'];
            });
        }
        
        // Filtrar por estado
        if (!empty($filtros['estado'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                $estado = $this->calcularEstado($c['FECHA_FIN']);
                return strtolower($estado) === $filtros['estado'];
            });
        }
        
        // Filtrar por fechas
        if (!empty($filtros['fecha_inicio'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['FECHA_INICIO'] >= $filtros['fecha_inicio'];
            });
        }
        
        if (!empty($filtros['fecha_fin'])) {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['FECHA_FIN'] <= $filtros['fecha_fin'];
            });
        }
        
        // Filtrar por renovable
        if (isset($filtros['renovable']) && $filtros['renovable'] !== '') {
            $convenios = array_filter($convenios, function($c) use ($filtros) {
                return $c['RENOVABLE'] == $filtros['renovable'];
            });
        }
        
        // Reindexar array después de filtros
        $convenios = array_values($convenios);
        
        return view('coord/convenios/reportes', [
            'convenios' => $convenios,
            'tipos_convenios' => $tipos_convenios,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros
        ]);
    }
}