<?php

namespace App\Controllers\coord;

use App\Controllers\BaseController;
use App\Models\EvaluacionesEnlacesModel;
use App\Models\ActividadesEducacionModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReportesEvaluacionesCoordController extends BaseController
{
    protected $evaluacionesModel;
    protected $actividadesModel;

    public function __construct()
    {
        // Verificar autenticación y rol de coordinador
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
        
        $this->evaluacionesModel = new EvaluacionesEnlacesModel();
        $this->actividadesModel = new ActividadesEducacionModel();
    }

    /**
     * Vista principal de reportes
     */
    public function index()
    {
        $data = [
            'title' => 'Reportes de Evaluaciones',
            'tipos_evaluacion' => $this->obtenerTiposEvaluacion(),
            'estados' => ['activo', 'inactivo', 'borrador'],
            'cursos' => $this->actividadesModel->findAll()
        ];
        
        return view('coord/evaluaciones/reportes', $data);
    }

    /**
     * Generar reporte en PDF
     */
    public function generarPDF()
    {
        try {
            $filtros = $this->obtenerFiltros();
            $evaluaciones = $this->evaluacionesModel->obtenerConFiltros($filtros);
            $estadisticas = $this->evaluacionesModel->obtenerEstadisticas();
            
            // Configurar PDF
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('Sistema ITSI');
            $pdf->SetAuthor('Coordinador');
            $pdf->SetTitle('Reporte de Evaluaciones');
            $pdf->SetSubject('Reporte de Evaluaciones del Sistema');
            
            // Configurar márgenes
            $pdf->SetMargins(15, 20, 15);
            $pdf->SetHeaderMargin(10);
            $pdf->SetFooterMargin(10);
            
            // Configurar fuente
            $pdf->SetFont('helvetica', '', 10);
            
            // Agregar página
            $pdf->AddPage();
            
            // Encabezado
            $this->generarEncabezadoPDF($pdf);
            
            // Estadísticas
            $this->generarEstadisticasPDF($pdf, $estadisticas);
            
            // Tabla de evaluaciones
            $this->generarTablaEvaluacionesPDF($pdf, $evaluaciones);
            
            // Pie de página
            $this->generarPiePaginaPDF($pdf);
            
            // Generar nombre del archivo
            $nombreArchivo = 'reporte_evaluaciones_' . date('Y-m-d_H-i-s') . '.pdf';
            
            // Descargar PDF
            $pdf->Output($nombreArchivo, 'D');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar a Excel
     */
    public function exportarExcel()
    {
        try {
            $filtros = $this->obtenerFiltros();
            $evaluaciones = $this->evaluacionesModel->obtenerConFiltros($filtros);
            
            // Cargar helper de Excel
            helper('ExcelHelper');
            
            // Crear archivo Excel
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Configurar encabezados
            $sheet->setTitle('Evaluaciones');
            
            // Crear encabezado estándar con logo
            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet, 
                'REPORTE DE EVALUACIONES', 
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'D1'
            );
            
            // Encabezados de columnas
            $headers = [
                'ID',
                'Nombre de Evaluación',
                'Tipo',
                'Curso',
                'Estado',
                'Fecha Creación',
                'Fecha Vencimiento',
                'Respuestas',
                'Enlace'
            ];
            
            // Crear encabezados de columnas con estilo
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');
            
            // Llenar datos
            $row = 6; // Empezar después del encabezado
            foreach ($evaluaciones as $eval) {
                $sheet->setCellValue('A' . $row, $eval['ID_EVALUACION_ENLACE']);
                $sheet->setCellValue('B' . $row, $eval['NOMBRE_EVALUACION']);
                $sheet->setCellValue('C' . $row, $eval['TIPO_EVALUACION']);
                $sheet->setCellValue('D' . $row, $eval['NOMBRE_ACTIVIDAD'] ?? 'Sin curso');
                $sheet->setCellValue('E' . $row, $eval['ESTADO']);
                $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($eval['FECHA_CREACION'])));
                $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($eval['FECHA_VENCIMIENTO'])));
                $sheet->setCellValue('H' . $row, $eval['NUMERO_RESPUESTAS']);
                $sheet->setCellValue('I' . $row, $eval['ENLACE_FORMULARIO']);
                $row++;
            }
            
            // Aplicar estilo a los datos
            if ($row > 6) {
                \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:I' . ($row - 1));
            }
            
            // Autoajustar ancho de columnas
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'I');
            
            // Generar nombre del archivo
            $nombreArchivo = 'evaluaciones_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Configurar headers para descarga
            \App\Helpers\ExcelHelper::setDownloadHeaders($nombreArchivo);
            
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

    /**
     * Exportar a CSV
     */
    public function exportarCSV()
    {
        try {
            $filtros = $this->obtenerFiltros();
            $evaluaciones = $this->evaluacionesModel->obtenerConFiltros($filtros);
            
            // Configurar headers
            $nombreArchivo = 'evaluaciones_' . date('Y-m-d_H-i-s') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
            
            // Crear archivo CSV
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($output, [
                'ID',
                'Nombre de Evaluación',
                'Tipo',
                'Curso',
                'Estado',
                'Fecha Creación',
                'Fecha Vencimiento',
                'Respuestas',
                'Enlace'
            ]);
            
            // Datos
            foreach ($evaluaciones as $eval) {
                fputcsv($output, [
                    $eval['ID_EVALUACION_ENLACE'],
                    $eval['NOMBRE_EVALUACION'],
                    $eval['TIPO_EVALUACION'],
                    $eval['NOMBRE_ACTIVIDAD'] ?? 'Sin curso',
                    $eval['ESTADO'],
                    date('d/m/Y', strtotime($eval['FECHA_CREACION'])),
                    date('d/m/Y', strtotime($eval['FECHA_VENCIMIENTO'])),
                    $eval['NUMERO_RESPUESTAS'],
                    $eval['ENLACE_FORMULARIO']
                ]);
            }
            
            fclose($output);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar CSV: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener datos para gráficos
     */
    public function obtenerDatosGraficos()
    {
        try {
            $filtros = $this->obtenerFiltros();
            
            // Datos para gráfico de barras - Evaluaciones por tipo
            $evaluacionesPorTipo = $this->evaluacionesModel->obtenerConFiltros($filtros);
            $tipos = [];
            foreach ($evaluacionesPorTipo as $eval) {
                $tipo = $eval['TIPO_EVALUACION'];
                $tipos[$tipo] = ($tipos[$tipo] ?? 0) + 1;
            }
            
            // Datos para gráfico de líneas - Evaluaciones por mes
            $evaluacionesPorMes = $this->obtenerEvaluacionesPorMes($filtros);
            
            // Datos para gráfico de dona - Estados
            $estados = [];
            foreach ($evaluacionesPorTipo as $eval) {
                $estado = $eval['ESTADO'];
                $estados[$estado] = ($estados[$estado] ?? 0) + 1;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'por_tipo' => $tipos,
                    'por_mes' => $evaluacionesPorMes,
                    'por_estado' => $estados
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Métodos privados para PDF
     */
    private function generarEncabezadoPDF($pdf)
    {
        // Agregar logo usando el helper
        helper('PdfHelper');
        \App\Helpers\PdfHelper::addLogoToPdf($pdf, 'Logo PDF.jpg', 15, 10, 30);
        
        // Título del reporte
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'REPORTE DE EVALUACIONES', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Sistema de Gestión ITSI', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Fecha de generación: ' . \App\Helpers\PdfHelper::getCurrentDateTime(), 0, 1, 'C');
        $pdf->Ln(10);
    }

    private function generarEstadisticasPDF($pdf, $estadisticas)
    {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'ESTADÍSTICAS GENERALES', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->Cell(60, 6, 'Total de Evaluaciones:', 0, 0);
        $pdf->Cell(30, 6, $estadisticas['total'], 0, 1);
        
        $pdf->Cell(60, 6, 'Evaluaciones Activas:', 0, 0);
        $pdf->Cell(30, 6, $estadisticas['activas'], 0, 1);
        
        $pdf->Cell(60, 6, 'Total de Respuestas:', 0, 0);
        $pdf->Cell(30, 6, $estadisticas['total_respuestas'], 0, 1);
        
        $pdf->Cell(60, 6, 'Promedio de Respuestas:', 0, 0);
        $pdf->Cell(30, 6, $estadisticas['promedio_respuestas'], 0, 1);
        
        $pdf->Ln(10);
    }

    private function generarTablaEvaluacionesPDF($pdf, $evaluaciones)
    {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'LISTADO DE EVALUACIONES', 0, 1);
        $pdf->SetFont('helvetica', '', 8);
        
        // Encabezados de tabla
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(15, 6, 'ID', 1, 0, 'C', true);
        $pdf->Cell(50, 6, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(25, 6, 'Tipo', 1, 0, 'C', true);
        $pdf->Cell(20, 6, 'Estado', 1, 0, 'C', true);
        $pdf->Cell(25, 6, 'Vencimiento', 1, 0, 'C', true);
        $pdf->Cell(20, 6, 'Respuestas', 1, 1, 'C', true);
        
        // Datos
        foreach ($evaluaciones as $eval) {
            $pdf->Cell(15, 6, $eval['ID_EVALUACION_ENLACE'], 1, 0, 'C');
            $pdf->Cell(50, 6, substr($eval['NOMBRE_EVALUACION'], 0, 30), 1, 0);
            $pdf->Cell(25, 6, $eval['TIPO_EVALUACION'], 1, 0, 'C');
            $pdf->Cell(20, 6, $eval['ESTADO'], 1, 0, 'C');
            $pdf->Cell(25, 6, date('d/m/Y', strtotime($eval['FECHA_VENCIMIENTO'])), 1, 0, 'C');
            $pdf->Cell(20, 6, $eval['NUMERO_RESPUESTAS'], 1, 1, 'C');
        }
    }

    private function generarPiePaginaPDF($pdf)
    {
        $pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Página ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, 0, 'C');
    }

    /**
     * Métodos auxiliares
     */
    private function obtenerFiltros()
    {
        return [
            'tipo' => $this->request->getGet('tipo'),
            'estado' => $this->request->getGet('estado'),
            'fecha_desde' => $this->request->getGet('fecha_desde'),
            'fecha_hasta' => $this->request->getGet('fecha_hasta'),
            'curso' => $this->request->getGet('curso')
        ];
    }

    private function obtenerTiposEvaluacion()
    {
        return [
            'satisfaccion' => 'Satisfacción del Participante',
            'instructores' => 'Evaluación del Instructor',
            'contenido' => 'Evaluación del Contenido',
            'metodologia' => 'Evaluación de la Metodología',
            'recursos' => 'Evaluación de Recursos',
            'general' => 'Evaluación General'
        ];
    }

    private function obtenerEvaluacionesPorMes($filtros)
    {
        $builder = $this->evaluacionesModel->db->table('TAB_EVALUACIONES_ENLACES e');
        $builder->select('DATE_FORMAT(e.FECHA_CREACION, "%Y-%m") as mes, COUNT(*) as total');
        $builder->where('e.ACTIVO', true);
        
        // Aplicar filtros
        if (!empty($filtros['tipo'])) {
            $builder->where('e.TIPO_EVALUACION', $filtros['tipo']);
        }
        if (!empty($filtros['estado'])) {
            $builder->where('e.ESTADO', $filtros['estado']);
        }
        
        $builder->groupBy('mes');
        $builder->orderBy('mes', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
