# Configuración de Logos en PDFs - Sistema ITSI

## Resumen de Cambios Realizados

He configurado el sistema para que todos los PDFs exportados incluyan el logo del ITSI. Los cambios incluyen:

### 1. **Helper para PDFs** (`app/Helpers/PdfHelper.php`)
- Funciones para agregar logos a PDFs TCPDF
- Funciones para generar HTML de logos en vistas
- Listado de logos disponibles

### 2. **Configuración Centralizada** (`app/Config/PdfConfig.php`)
- Configuración por defecto del logo
- Posiciones y estilos configurables
- Lista de logos disponibles

### 3. **Controlador Base** (`app/Controllers/BasePdfController.php`)
- Funcionalidades comunes para todos los PDFs
- Métodos estándar para encabezados y pies de página

### 4. **Controladores Actualizados**
- `ReportesEvaluacionesAdminController.php` - Ahora incluye logo
- Vistas de PDF actualizadas con logos

## Logos Disponibles

En la carpeta `public/sistema/assets/images/logos/` tienes estos logos:

- **ITSI-nuevo-logo.png** (Logo principal - por defecto)
- **logo.png** (Logo alternativo)
- **logo.svg** (Logo en formato SVG)
- **logo-light.svg** (Logo claro en SVG)

## Cómo Usar el Sistema

### Para Controladores TCPDF:

```php
<?php

namespace App\Controllers;

use App\Controllers\BasePdfController;

class MiControlador extends BasePdfController
{
    public function exportarPDF()
    {
        // Crear instancia de PDF
        $pdf = $this->createPdfInstance('Mi Reporte', 'Reporte del Sistema');
        
        // Agregar página
        $pdf->AddPage();
        
        // Generar encabezado con logo
        $this->generateStandardHeader($pdf, 'MI REPORTE', 'Sistema ITSI');
        
        // Tu contenido aquí...
        
        // Generar pie de página
        $this->generateStandardFooter($pdf);
        
        // Descargar
        $pdf->Output('mi_reporte.pdf', 'D');
    }
}
```

### Para Vistas HTML de PDF:

```php
<!-- En tu vista PHP -->
<div class="header">
    <div style="text-align: center; margin-bottom: 15px;">
        <?php 
        helper('PdfHelper');
        echo \App\Helpers\PdfHelper::getLogoHtml('ITSI-nuevo-logo.png', [
            'style' => 'height: 60px; max-width: 200px;'
        ]);
        ?>
    </div>
    <h1>MI REPORTE</h1>
</div>
```

### Cambiar el Logo por Defecto:

Edita `app/Config/PdfConfig.php`:

```php
public $defaultLogo = 'logo.png'; // Cambiar por el logo que prefieras
```

## Controladores que Necesitan Actualización

Los siguientes controladores tienen comentarios indicando que necesitan implementar PDFs:

1. **ReportesController.php** - Líneas 124, 149
2. **ActividadesEducacionAdminController.php** - Línea 470
3. **PracticasAdminController.php** - Línea 536

### Ejemplo de Implementación Completa:

```php
public function exportarPDF()
{
    try {
        // Obtener datos
        $datos = $this->obtenerDatos();
        
        // Crear PDF
        $pdf = $this->createPdfInstance('Reporte de Prácticas', 'Sistema ITSI');
        $pdf->AddPage();
        
        // Encabezado con logo
        $this->generateStandardHeader($pdf, 'REPORTE DE PRÁCTICAS', 'Sistema de Gestión ITSI');
        
        // Contenido
        $this->generarContenidoPDF($pdf, $datos);
        
        // Pie de página
        $this->generateStandardFooter($pdf);
        
        // Descargar
        $nombreArchivo = 'reporte_practicas_' . date('Y-m-d') . '.pdf';
        $pdf->Output($nombreArchivo, 'D');
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al generar PDF: ' . $e->getMessage()
        ]);
    }
}
```

## Verificación

Para verificar que todo funciona:

1. **Accede a los reportes de evaluaciones** (ya actualizado)
2. **Exporta un PDF** y verifica que aparezca el logo
3. **Revisa las vistas HTML** de PDF (ya actualizadas)

## Personalización Adicional

### Cambiar Posición del Logo:

En `app/Config/PdfConfig.php`:

```php
public $logoPosition = [
    'x' => 20,      // Más a la derecha
    'y' => 5,       // Más arriba
    'width' => 40,  // Más grande
    'height' => 0   // Automático
];
```

### Usar Diferente Logo por Tipo de Reporte:

```php
// En tu controlador
$logoName = 'logo.png'; // Logo diferente para este reporte
$this->generateStandardHeader($pdf, 'REPORTE ESPECIAL', 'Sistema ITSI', $logoName);
```

## Archivos Modificados

- ✅ `app/Controllers/admin/ReportesEvaluacionesAdminController.php`
- ✅ `app/Views/admin/educacion/pdf/reportes.php`
- ✅ `app/Views/docente/educacion/reportes/pdf/reportes.php`
- ✅ `app/Helpers/PdfHelper.php` (nuevo)
- ✅ `app/Config/PdfConfig.php` (nuevo)
- ✅ `app/Controllers/BasePdfController.php` (nuevo)

## Próximos Pasos

1. **Probar** la exportación de PDFs existente
2. **Implementar** PDFs en los controladores pendientes usando el `BasePdfController`
3. **Personalizar** logos según necesidades específicas
4. **Agregar** más logos si es necesario

¡El sistema ahora está configurado para mostrar logos en todos los PDFs exportados!
