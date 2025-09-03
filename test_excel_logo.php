<?php

// Test simple para verificar que PhpSpreadsheet funciona con el logo
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Crear un nuevo spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Configurar título
    $sheet->setTitle('Test Logo ITSI');
    
    // Verificar que el logo existe
    $logoPath = __DIR__ . '/public/sistema/assets/images/logos/Logo PDF.jpg';
    if (file_exists($logoPath)) {
        echo "✅ Logo encontrado en: $logoPath\n";
        
        // Crear objeto Drawing para la imagen
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo ITSI');
        $drawing->setDescription('Logo del Instituto Superior Tecnológico Ibarra');
        $drawing->setPath($logoPath);
        $drawing->setWidth(200);
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        
        // Agregar la imagen a la hoja
        $sheet->getDrawingCollection()->append($drawing);
        
        // Configurar título
        $sheet->setCellValue('D1', 'REPORTE DE PRUEBA');
        $sheet->getStyle('D1')->getFont()
            ->setBold(true)
            ->setSize(16)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1A3A8A'));
        
        // Ajustar altura de la fila
        $sheet->getRowDimension(1)->setRowHeight(90);
        
        // Agregar algunos datos de prueba
        $sheet->setCellValue('A3', 'ID');
        $sheet->setCellValue('B3', 'Nombre');
        $sheet->setCellValue('C3', 'Fecha');
        
        $sheet->setCellValue('A4', '1');
        $sheet->setCellValue('B4', 'Prueba ITSI');
        $sheet->setCellValue('C4', date('d/m/Y'));
        
        // Autoajustar columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Guardar archivo de prueba
        $filename = 'test_logo_itsi_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        
        echo "✅ Archivo Excel creado exitosamente: $filename\n";
        echo "✅ PhpSpreadsheet funciona correctamente con el logo de ITSI\n";
        
    } else {
        echo "❌ Logo no encontrado en: $logoPath\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
