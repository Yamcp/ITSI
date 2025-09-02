<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use TCPDF;

/**
 * Controlador base para PDFs con funcionalidades comunes
 */
class BasePdfController extends Controller
{
    /**
     * Configuración de PDF
     * 
     * @var \App\Config\PdfConfig
     */
    protected $pdfConfig;
    
    public function __construct()
    {
        $this->pdfConfig = new \App\Config\PdfConfig();
    }
    
    /**
     * Crear una instancia de TCPDF con configuración básica
     * 
     * @param string $title Título del documento
     * @param string $subject Asunto del documento
     * @return TCPDF
     */
    protected function createPdfInstance($title = 'Reporte', $subject = 'Reporte del Sistema')
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Configuración básica
        $pdf->SetCreator('Sistema ITSI');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle($title);
        $pdf->SetSubject($subject);
        
        // Configurar márgenes
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        
        // Configurar fuente
        $pdf->SetFont('helvetica', '', 10);
        
        return $pdf;
    }
    
    /**
     * Agregar logo al PDF
     * 
     * @param TCPDF $pdf
     * @param string|null $logoName
     * @param array|null $position
     * @return void
     */
    protected function addLogoToPdf($pdf, $logoName = null, $position = null)
    {
        $logoName = $logoName ?? $this->pdfConfig->defaultLogo;
        $position = $position ?? $this->pdfConfig->logoPosition;
        
        $logoPath = $this->pdfConfig->getLogoPath($logoName);
        
        if (file_exists($logoPath)) {
            $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                $pdf->Image(
                    $logoPath, 
                    $position['x'], 
                    $position['y'], 
                    $position['width'], 
                    $position['height'], 
                    strtoupper($extension)
                );
            }
        }
    }
    
    /**
     * Generar encabezado estándar con logo
     * 
     * @param TCPDF $pdf
     * @param string $title
     * @param string|null $subtitle
     * @param string|null $logoName
     * @return void
     */
    protected function generateStandardHeader($pdf, $title, $subtitle = null, $logoName = null)
    {
        // Agregar logo
        $this->addLogoToPdf($pdf, $logoName);
        
        // Título principal
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, strtoupper($title), 0, 1, 'C');
        
        // Subtítulo
        if ($subtitle) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, $subtitle, 0, 1, 'C');
        }
        
        // Fecha de generación
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Fecha de generación: ' . \App\Helpers\PdfHelper::getCurrentDateTime(), 0, 1, 'C');
        
        $pdf->Ln(10);
    }
    
    /**
     * Generar pie de página estándar
     * 
     * @param TCPDF $pdf
     * @return void
     */
    protected function generateStandardFooter($pdf)
    {
        $pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Página ' . $pdf->getAliasNumPage() . ' de ' . $pdf->getAliasNbPages(), 0, 0, 'C');
    }
    
    /**
     * Obtener datos del logo para vistas HTML
     * 
     * @param string|null $logoName
     * @return array
     */
    protected function getLogoData($logoName = null)
    {
        $logoName = $logoName ?? $this->pdfConfig->defaultLogo;
        
        return [
            'logo_url' => $this->pdfConfig->getLogoUrl($logoName),
            'logo_exists' => $this->pdfConfig->logoExists($logoName),
            'logo_style' => $this->pdfConfig->logoHtmlStyle
        ];
    }
}
