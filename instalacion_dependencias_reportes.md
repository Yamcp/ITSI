# Instalación de Dependencias para Reportes

Para que funcionen correctamente los reportes y exportaciones, necesitas instalar las siguientes dependencias:

## 1. TCPDF (Para generar PDFs)

```bash
composer require tecnickcom/tcpdf
```

## 2. PhpSpreadsheet (Para exportar a Excel)

```bash
composer require phpoffice/phpspreadsheet
```

## 3. Verificar instalación

Después de instalar las dependencias, verifica que estén disponibles:

```php
// En tu controlador, verifica que las clases estén disponibles
if (class_exists('\TCPDF')) {
    echo "TCPDF está instalado correctamente";
}

if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
    echo "PhpSpreadsheet está instalado correctamente";
}
```

## 4. Configuración adicional

### Para TCPDF:
- Asegúrate de que la carpeta `writable/` tenga permisos de escritura
- TCPDF generará archivos temporales en esta carpeta

### Para PhpSpreadsheet:
- No requiere configuración adicional
- Funciona directamente con la instalación via Composer

## 5. Comandos de instalación completos

Ejecuta estos comandos en la raíz de tu proyecto:

```bash
# Instalar TCPDF
composer require tecnickcom/tcpdf

# Instalar PhpSpreadsheet
composer require phpoffice/phpspreadsheet

# Actualizar autoloader
composer dump-autoload
```

## 6. Verificación final

Una vez instaladas las dependencias, puedes probar los reportes accediendo a:

- **Vista de reportes**: `http://tu-dominio/admin/reportes-evaluaciones`
- **Exportar PDF**: `http://tu-dominio/admin/reportes-evaluaciones/pdf`
- **Exportar Excel**: `http://tu-dominio/admin/reportes-evaluaciones/excel`
- **Exportar CSV**: `http://tu-dominio/admin/reportes-evaluaciones/csv`

## 7. Solución de problemas

### Error: "Class 'TCPDF' not found"
- Ejecuta: `composer require tecnickcom/tcpdf`
- Verifica que el autoloader esté actualizado: `composer dump-autoload`

### Error: "Class 'PhpOffice\PhpSpreadsheet\Spreadsheet' not found"
- Ejecuta: `composer require phpoffice/phpspreadsheet`
- Verifica que el autoloader esté actualizado: `composer dump-autoload`

### Error de permisos en PDF
- Asegúrate de que la carpeta `writable/` tenga permisos 755 o 777
- En Linux: `chmod -R 755 writable/`

## 8. Funcionalidades disponibles

Una vez instaladas las dependencias, tendrás acceso a:

### Reportes PDF:
- ✅ Encabezado con información del sistema
- ✅ Estadísticas generales
- ✅ Tabla detallada de evaluaciones
- ✅ Pie de página con numeración
- ✅ Filtros aplicados

### Exportación Excel:
- ✅ Múltiples hojas de trabajo
- ✅ Formato profesional
- ✅ Encabezados con estilo
- ✅ Auto-ajuste de columnas
- ✅ Datos formateados

### Exportación CSV:
- ✅ Codificación UTF-8
- ✅ Separadores correctos
- ✅ Compatible con Excel
- ✅ Datos limpios

### Gráficos:
- ✅ Gráfico de barras (por tipo)
- ✅ Gráfico de dona (por estado)
- ✅ Gráfico de líneas (por mes)
- ✅ Interactivos con Chart.js
