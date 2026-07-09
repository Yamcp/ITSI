<?php

namespace App\Helpers;

class PdfHelper
{
    /**
     * Agregar logo al PDF usando TCPDF
     * 
     * @param \TCPDF $pdf Instancia de TCPDF
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @param int $x Posición X (opcional)
     * @param int $y Posición Y (opcional)
     * @param int $width Ancho del logo (opcional)
     * @return void
     */
    public static function addLogoToPdf($pdf, $logoName = 'Logo PDF.jpg', $x = 15, $y = 10, $width = 30)
    {
        $logoPath = FCPATH . 'sistema/assets/images/logos/' . $logoName;
        
        if (file_exists($logoPath)) {
            // Obtener extensión del archivo
            $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            
            // Validar que sea una imagen válida
            if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                $pdf->Image($logoPath, $x, $y, $width, 0, strtoupper($extension));
            }
        }
    }
    
    /**
     * Obtener la ruta del logo para usar en vistas HTML
     * 
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @return string URL del logo
     */
    public static function getLogoUrl($logoName = 'Logo PDF.jpg')
    {
        return base_url('sistema/assets/images/logos/' . $logoName);
    }
    
    /**
     * Generar HTML para logo en vistas PDF
     * 
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @param array $attributes Atributos adicionales para la imagen
     * @return string HTML del logo
     */
    public static function getLogoHtml($logoName = 'Logo PDF.jpg', $attributes = [])
    {
        $defaultAttributes = [
            'alt' => 'Logo ITSI',
            'style' => 'height: 60px; max-width: 200px;'
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= $key . '="' . htmlspecialchars($value) . '" ';
        }
        
        return '<img src="' . self::getLogoUrl($logoName) . '" ' . trim($attrString) . '>';
    }
    
    /**
     * Listar logos disponibles
     * 
     * @return array Lista de logos disponibles
     */
    public static function getAvailableLogos()
    {
        $logosPath = FCPATH . 'sistema/assets/images/logos/';
        $logos = [];
        
        if (is_dir($logosPath)) {
            $files = scandir($logosPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(png|jpg|jpeg|gif|svg)$/i', $file)) {
                    $logos[] = $file;
                }
            }
        }
        
        return $logos;
    }
    
    /**
     * Obtener fecha y hora actual formateada para PDFs
     * 
     * @param string $format Formato de fecha (opcional)
     * @return string Fecha y hora formateada
     */
    public static function getCurrentDateTime($format = 'd/m/Y H:i:s')
    {
        // Configurar zona horaria de Ecuador
        $timezone = new \DateTimeZone('America/Guayaquil');
        $date = new \DateTime('now', $timezone);
        
        return $date->format($format);
    }
}
