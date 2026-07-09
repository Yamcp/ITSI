<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class PdfConfig extends BaseConfig
{
    /**
     * Configuración por defecto para logos en PDFs
     */
    
    /**
     * Logo por defecto para usar en PDFs
     * 
     * @var string
     */
    public $defaultLogo = 'Logo PDF.jpg';
    
    /**
     * Ruta base de los logos
     * 
     * @var string
     */
    public $logoPath = 'sistema/assets/images/logos/';
    
    /**
     * Configuración de posición del logo en TCPDF
     * 
     * @var array
     */
    public $logoPosition = [
        'x' => 15,      // Posición X
        'y' => 10,      // Posición Y
        'width' => 30,  // Ancho
        'height' => 0   // Alto (0 = automático)
    ];
    
    /**
     * Configuración de estilo para logos en HTML
     * 
     * @var array
     */
    public $logoHtmlStyle = [
        'height' => '60px',
        'max-width' => '200px'
    ];
    
    /**
     * Logos disponibles en el sistema
     * 
     * @var array
     */
    public $availableLogos = [
        'Logo PDF.jpg' => 'Logo ITSI (PDF)',
        'Logo PDF.png' => 'Logo ITSI (PDF PNG)',
        'ITSI-nuevo-logo.png' => 'Logo ITSI (Nuevo)',
        'logo.png' => 'Logo Principal',
        'logo.svg' => 'Logo SVG',
        'logo-light.svg' => 'Logo Claro SVG'
    ];
    
    /**
     * Obtener la ruta completa del logo
     * 
     * @param string|null $logoName
     * @return string
     */
    public function getLogoPath($logoName = null)
    {
        $logoName = $logoName ?? $this->defaultLogo;
        return FCPATH . $this->logoPath . $logoName;
    }
    
    /**
     * Obtener la URL del logo
     * 
     * @param string|null $logoName
     * @return string
     */
    public function getLogoUrl($logoName = null)
    {
        $logoName = $logoName ?? $this->defaultLogo;
        return base_url($this->logoPath . $logoName);
    }
    
    /**
     * Verificar si un logo existe
     * 
     * @param string|null $logoName
     * @return bool
     */
    public function logoExists($logoName = null)
    {
        $logoName = $logoName ?? $this->defaultLogo;
        return file_exists($this->getLogoPath($logoName));
    }
}
