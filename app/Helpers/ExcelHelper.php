<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelHelper
{
    /**
     * Agregar logo al archivo Excel usando PhpSpreadsheet
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet Hoja de trabajo
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @param string $cell Posición de la celda (opcional)
     * @param int $width Ancho del logo (opcional)
     * @param int $height Alto del logo (opcional)
     * @return void
     */
    public static function addLogoToExcel($sheet, $logoName = 'Logo PDF.jpg', $cell = 'A1', $width = 200, $height = 80)
    {
        $logoPath = FCPATH . 'sistema/assets/images/logos/' . $logoName;
        
        if (file_exists($logoPath) && is_readable($logoPath)) {
            try {
                // Verificar que sea una imagen válida
                $imageInfo = getimagesize($logoPath);
                if ($imageInfo === false) {
                    return false; // No es una imagen válida
                }
                
                // Crear objeto Drawing para la imagen
                $drawing = new Drawing();
                $drawing->setName('Logo ITSI');
                $drawing->setDescription('Logo del Instituto Superior Tecnológico Ibarra');
                $drawing->setPath($logoPath);
                $drawing->setWidth($width);
                $drawing->setHeight($height);
                $drawing->setCoordinates($cell);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                
                // Agregar la imagen a la hoja
                $sheet->getDrawingCollection()->append($drawing);
                
                // Ajustar la altura de la fila para acomodar el logo
                $row = (int) filter_var($cell, FILTER_SANITIZE_NUMBER_INT);
                if ($row > 0) {
                    $sheet->getRowDimension($row)->setRowHeight($height + 10);
                }
                
                return true;
            } catch (\Exception $e) {
                // Log del error pero no fallar la exportación
                log_message('error', 'Error al agregar logo a Excel: ' . $e->getMessage());
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * Crear encabezado estándar con logo para Excel
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet Hoja de trabajo
     * @param string $title Título del reporte
     * @param string|null $subtitle Subtítulo (opcional)
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @param string $logoCell Posición del logo (opcional)
     * @param string $titleCell Posición del título (opcional)
     * @return void
     */
    public static function createStandardHeader($sheet, $title, $subtitle = null, $logoName = 'Logo PDF.jpg', $logoCell = 'A1', $titleCell = 'D1')
    {
        // Intentar agregar logo
        $logoAdded = self::addLogoToExcel($sheet, $logoName, $logoCell);
        
        // Configurar título
        $sheet->setCellValue($titleCell, strtoupper($title));
        $sheet->getStyle($titleCell)->getFont()
            ->setBold(true)
            ->setSize(16)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1A3A8A'));
        
        // Configurar subtítulo si existe
        if ($subtitle) {
            $subtitleCell = str_replace('1', '2', $titleCell);
            $sheet->setCellValue($subtitleCell, $subtitle);
            $sheet->getStyle($subtitleCell)->getFont()
                ->setBold(false)
                ->setSize(12)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('009EE0'));
        }
        
        // Agregar fecha de generación
        $dateCell = str_replace('1', '3', $titleCell);
        $sheet->setCellValue($dateCell, 'Fecha de generación: ' . self::getCurrentDateTime());
        $sheet->getStyle($dateCell)->getFont()
            ->setBold(false)
            ->setSize(10)
            ->setItalic(true);
        
        // Ajustar altura de las filas del encabezado
        if ($logoAdded) {
            $sheet->getRowDimension(1)->setRowHeight(90);
        } else {
            $sheet->getRowDimension(1)->setRowHeight(25);
        }
        
        if ($subtitle) {
            $sheet->getRowDimension(2)->setRowHeight(25);
        }
        $sheet->getRowDimension(3)->setRowHeight(20);
        
        // Agregar línea separadora
        $separatorRow = $subtitle ? 4 : 3;
        $sheet->getRowDimension($separatorRow)->setRowHeight(5);
    }
    
    /**
     * Crear encabezados de columnas con estilo
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet Hoja de trabajo
     * @param array $headers Array de encabezados
     * @param int $startRow Fila de inicio (opcional)
     * @param string $startColumn Columna de inicio (opcional)
     * @return void
     */
    public static function createColumnHeaders($sheet, $headers, $startRow = 5, $startColumn = 'A')
    {
        $col = $startColumn;
        foreach ($headers as $header) {
            $cell = $col . $startRow;
            $sheet->setCellValue($cell, $header);
            
            // Aplicar estilo a los encabezados
            $sheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A3A8A']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ]
            ]);
            
            $col++;
        }
        
        // Ajustar altura de la fila de encabezados
        $sheet->getRowDimension($startRow)->setRowHeight(25);
    }
    
    /**
     * Aplicar estilo a las celdas de datos
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet Hoja de trabajo
     * @param string $range Rango de celdas
     * @param array $style Estilo a aplicar (opcional)
     * @return void
     */
    public static function applyDataStyle($sheet, $range, $style = [])
    {
        $defaultStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $finalStyle = array_merge($defaultStyle, $style);
        $sheet->getStyle($range)->applyFromArray($finalStyle);
    }
    
    /**
     * Autoajustar ancho de columnas
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet Hoja de trabajo
     * @param string $startColumn Columna de inicio
     * @param string $endColumn Columna final
     * @return void
     */
    public static function autoSizeColumns($sheet, $startColumn = 'A', $endColumn = 'Z')
    {
        foreach (range($startColumn, $endColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    /**
     * Obtener la ruta del logo
     * 
     * @param string $logoName Nombre del archivo de logo (opcional)
     * @return string Ruta completa del logo
     */
    public static function getLogoPath($logoName = 'Logo PDF.jpg')
    {
        return FCPATH . 'sistema/assets/images/logos/' . $logoName;
    }
    
    /**
     * Verificar si un logo existe
     * 
     * @param string $logoName Nombre del archivo de logo
     * @return bool True si existe, false si no
     */
    public static function logoExists($logoName = 'Logo PDF.jpg')
    {
        return file_exists(self::getLogoPath($logoName));
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
     * Obtener fecha y hora actual formateada para Excel
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
    
    /**
     * Configurar headers para descarga de Excel
     * 
     * @param string $filename Nombre del archivo
     * @return void
     */
    public static function setDownloadHeaders($filename)
    {
        // Limpiar cualquier output previo
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Configurar headers HTTP apropiados para Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }
}
