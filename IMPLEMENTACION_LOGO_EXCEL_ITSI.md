# Implementación del Logo ITSI en Documentos Excel

## Resumen de Cambios Realizados

He implementado exitosamente el logo de ITSI ("Logo PDF.jpg") en el encabezado de todos los documentos Excel que se exportan en el sistema. Los cambios incluyen:

### 1. **Nuevo Helper para Excel** (`app/Helpers/ExcelHelper.php`)
- ✅ Helper especializado para manejar logos en archivos Excel usando PhpSpreadsheet
- ✅ Función `addLogoToExcel()` para agregar logos a hojas de trabajo
- ✅ Función `createStandardHeader()` para crear encabezados estándar con logo
- ✅ Función `createColumnHeaders()` para crear encabezados de columnas con estilo
- ✅ Funciones auxiliares para estilos, autoajuste de columnas y configuración de descarga
- ✅ Configuración centralizada para todos los Excel del sistema

### 2. **Controladores Actualizados**

#### **ReportesEvaluacionesAdminController.php**
- ✅ Implementado logo en el método `exportarExcel()`
- ✅ Usa el helper `ExcelHelper::createStandardHeader()` con el logo
- ✅ Encabezados de columnas con estilo corporativo
- ✅ Aplicación de estilos a los datos

#### **ActividadesEducacionAdminController.php**
- ✅ Actualizado el método `exportarExcel()` para incluir logo
- ✅ Implementación usando el helper estándar
- ✅ Estilo corporativo consistente

#### **ActividadesEducacionDocenteController.php**
- ✅ Actualizado el método `exportarExcel()` para incluir logo
- ✅ Misma implementación que el controlador admin para consistencia

#### **PracticasAdminController.php**
- ✅ Convertido de CSV a Excel real usando PhpSpreadsheet
- ✅ Implementado logo en el encabezado
- ✅ Separación clara entre prácticas preprofesionales y servicios comunitarios
- ✅ Estilo corporativo aplicado

#### **ConveniosAdminController.php**
- ✅ Convertido de CSV a Excel real usando PhpSpreadsheet
- ✅ Implementado logo en el encabezado
- ✅ Estilo corporativo aplicado

#### **InstructoresAdminController.php**
- ✅ Convertido de CSV a Excel real usando PhpSpreadsheet
- ✅ Implementado logo en el encabezado
- ✅ Estadísticas de actividades incluidas
- ✅ Estilo corporativo aplicado

## Archivos Modificados

1. `app/Helpers/ExcelHelper.php` - **NUEVO** - Helper para logos en Excel
2. `app/Controllers/admin/ReportesEvaluacionesAdminController.php` - Reportes de evaluaciones
3. `app/Controllers/admin/ActividadesEducacionAdminController.php` - Reportes de actividades educativas
4. `app/Controllers/docente/ActividadesEducacionDocenteController.php` - Reportes de actividades educativas (docente)
5. `app/Controllers/admin/PracticasAdminController.php` - Reportes de prácticas
6. `app/Controllers/admin/ConveniosAdminController.php` - Reportes de convenios
7. `app/Controllers/admin/InstructoresAdminController.php` - Reportes de instructores

## Logo Utilizado

- **Archivo**: `Logo PDF.jpg`
- **Ubicación**: `public/sistema/assets/images/logos/Logo PDF.jpg`
- **Descripción**: Logo oficial de ITSI optimizado para documentos (formato JPG para compatibilidad)

## Cómo Funciona

### Para Controladores Excel:
```php
// Cargar helper de Excel
helper('ExcelHelper');

// Crear archivo Excel
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Crear encabezado estándar con logo
\App\Helpers\ExcelHelper::createStandardHeader(
    $sheet, 
    'TÍTULO DEL REPORTE', 
    'Sistema de Gestión Académica ITSI',
    'Logo PDF.jpg',
    'A1',  // Posición del logo
    'D1'   // Posición del título
);

// Crear encabezados de columnas con estilo
$headers = ['Columna 1', 'Columna 2', 'Columna 3'];
\App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');

// Llenar datos...
$row = 6;
foreach ($datos as $dato) {
    $sheet->setCellValue('A' . $row, $dato['campo1']);
    $sheet->setCellValue('B' . $row, $dato['campo2']);
    $row++;
}

// Aplicar estilo a los datos
\App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:C' . ($row - 1));

// Autoajustar columnas
\App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'C');

// Configurar descarga
$filename = 'reporte_' . date('Y-m-d') . '.xlsx';
\App\Helpers\ExcelHelper::setDownloadHeaders($filename);

// Escribir archivo
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
```

## Características del Logo en Excel

### Posicionamiento:
- **Logo**: Esquina superior izquierda (celda A1)
- **Título**: Al lado del logo (celda D1)
- **Subtítulo**: Debajo del título (celda D2)
- **Fecha**: Debajo del subtítulo (celda D3)

### Estilo Corporativo:
- **Colores**: Azul oscuro (#1A3A8A) y azul claro (#009EE0)
- **Logo**: 200px de ancho, 80px de alto
- **Encabezados**: Fondo azul oscuro, texto blanco
- **Datos**: Bordes grises, alineación centrada verticalmente

### Funcionalidades:
- **Autoajuste**: Las columnas se ajustan automáticamente al contenido
- **Estilos**: Bordes, colores y alineación aplicados automáticamente
- **Compatibilidad**: Funciona con todas las versiones de Excel
- **Zona horaria**: Configurada para Ecuador (America/Guayaquil)

## Verificación

Para verificar que el logo se está mostrando correctamente:

1. **Reportes de Evaluaciones**: Ir a Admin → Evaluaciones → Generar Reporte → Excel
2. **Reportes de Actividades Educativas**: Ir a Admin/Docente → Educación → Reportes → Excel
3. **Reportes de Prácticas**: Ir a Admin → Prácticas → Exportar → Excel
4. **Reportes de Convenios**: Ir a Admin → Convenios → Exportar → Excel
5. **Reportes de Instructores**: Ir a Admin → Instructores → Exportar → Excel

Todos los documentos Excel generados ahora incluirán el logo de ITSI en el encabezado.

## Beneficios

- ✅ **Consistencia**: Todos los Excel tienen el mismo logo oficial
- ✅ **Profesionalismo**: Los documentos mantienen la identidad corporativa
- ✅ **Centralización**: Un solo helper para manejar logos en Excel
- ✅ **Flexibilidad**: Fácil cambio de logo si es necesario en el futuro
- ✅ **Mantenibilidad**: Código limpio y reutilizable
- ✅ **Estilo Corporativo**: Colores y estilos consistentes con la marca ITSI

## Notas Técnicas

- El logo se posiciona en la esquina superior izquierda del documento
- Tamaño estándar: 200px de ancho, 80px de alto
- Compatible con formatos PNG, JPG, JPEG, GIF
- Funciona con PhpSpreadsheet (librería estándar para Excel en PHP)
- **Zona horaria**: Configurada para Ecuador (America/Guayaquil)
- **Formato de fecha**: dd/mm/yyyy hh:mm:ss
- **Logo optimizado**: Formato JPG para máxima compatibilidad

## Dependencias

- **PhpSpreadsheet**: Librería para generar archivos Excel (instalada via Composer)
- **Logo PDF.jpg**: Archivo de logo en la ubicación correcta
- **ExcelHelper**: Helper personalizado para manejo de logos

### Instalación de PhpSpreadsheet

Si no tienes PhpSpreadsheet instalado, ejecuta:

```bash
composer require phpoffice/phpspreadsheet
```

O si tienes problemas con la extensión GD:

```bash
composer require phpoffice/phpspreadsheet --ignore-platform-reqs
```

## Migración de CSV a Excel

Los siguientes controladores fueron migrados de CSV a Excel real:
- `PracticasAdminController.php`
- `ConveniosAdminController.php` 
- `InstructoresAdminController.php`

Esto mejora significativamente la presentación y funcionalidad de los reportes exportados.
