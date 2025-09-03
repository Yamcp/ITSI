# Vista Unificada de Documentos - Sistema ITSI

## Descripción
Sistema unificado para la gestión de todos los documentos de prácticas preprofesionales y servicio comunitario, con clasificación por 12 tipos de documentos de prácticas y 6 tipos de documentos de servicio comunitario.

## Características Principales

### ✅ Vista Unificada
- **Gestión centralizada** de documentos de prácticas y servicio comunitario
- **Clasificación automática** por tipos de documentos
- **Estadísticas en tiempo real** de todos los documentos
- **Interfaz moderna y responsive** con diseño atractivo

### ✅ Tipos de Documentos

#### Prácticas Preprofesionales (12 tipos):
1. **1.1** - Oficio de Asignación de Tutor Docente
2. **1.2** - Oficio Personal a Entidad Receptora
3. **1.3** - Carta de Aceptación de Entidad Receptora
4. **1.4** - Solicitud Institucional Valorada
5. **1.5** - Certificado de Culminación (60 horas)
6. **1.6** - Rúbrica de Evaluación Entidad Receptora
7. **1.7** - Hojas de Asistencia de Estudiantes
8. **1.8** - Ficha de Registro de Actividades Realizadas
9. **1.9** - Ficha de Control y Seguimiento Docente
10. **1.10** - Rúbrica de Evaluación de Control y Seguimiento Docente
11. **1.11** - Rúbrica de Evaluación de Resultados
12. **1.12** - Respaldo en Fotos, Videos y Evidencias

#### Servicio Comunitario (6 tipos):
1. **SC.1** - Plan de Trabajo de Servicio Comunitario
2. **SC.2** - Cronograma de Actividades
3. **SC.3** - Informe de Actividades Realizadas
4. **SC.4** - Evidencias Fotográficas
5. **SC.5** - Evaluación de la Comunidad
6. **SC.6** - Informe Final de Servicio Comunitario

### ✅ Funcionalidades

#### Para Administradores:
- **Dashboard unificado** con estadísticas generales
- **Filtros avanzados** por tipo, estado, búsqueda
- **Vista por categorías** (Todos, Prácticas, Servicio)
- **Gestión de documentos** con acciones rápidas
- **Reportes y exportación** en PDF y Excel
- **Estadísticas detalladas** por tipo de documento

#### Características Técnicas:
- **API REST** para datos dinámicos
- **Filtros en tiempo real** con búsqueda
- **Interfaz responsive** para móviles y tablets
- **Carga asíncrona** de datos
- **Manejo de errores** robusto

## Archivos Creados/Modificados

### Nuevos Archivos:
- `app/Views/admin/documentos/vista_unificada_documentos.php` - Vista principal unificada
- `app/Controllers/admin/DocumentosUnificadosController.php` - Controlador unificado
- `app/Models/TiposDocumentosServicioComunitarioModel.php` - Modelo para tipos de servicio
- `crear_tabla_tipos_documentos_servicio.sql` - Script SQL para crear tabla

### Archivos Modificados:
- `app/Config/Routes.php` - Rutas agregadas para el módulo unificado

## Instalación

### 1. Base de Datos

**Crear tabla de tipos de documentos de servicio comunitario:**
```sql
-- Ejecutar el archivo: crear_tabla_tipos_documentos_servicio.sql
```

### 2. Verificar Estructura

Asegúrate de que existan las siguientes tablas:
- `TAB_TIPOS_DOCUMENTOS_PRACTICAS` (12 tipos predefinidos)
- `TAB_ESTADOS_REVISIONES` (5 estados predefinidos)
- `TAB_DOCUMENTOS_PRACTICAS` (documentos de prácticas)
- `TAB_DOCUMENTOS_SERVICIO_COMUNITARIO` (documentos de servicio)
- `TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO` (6 tipos predefinidos)

### 3. Permisos de Directorio

```bash
mkdir -p writable/uploads/documentos-practicas
mkdir -p writable/uploads/documentos-servicio
chmod 755 writable/uploads/documentos-practicas
chmod 755 writable/uploads/documentos-servicio
```

## Uso del Sistema

### Acceso a la Vista Unificada

**URL:** `admin/documentos/unificados`

### Navegación

1. **Dashboard Principal:**
   - Estadísticas generales en tiempo real
   - Filtros rápidos por tipo y estado
   - Búsqueda en tiempo real

2. **Pestañas de Categorías:**
   - **Todos los Documentos:** Vista completa
   - **Prácticas (12 tipos):** Solo documentos de prácticas
   - **Servicio Comunitario:** Solo documentos de servicio

3. **Filtros Avanzados:**
   - Búsqueda por texto
   - Filtro por tipo (Prácticas/Servicio)
   - Filtro por estado de revisión
   - Filtros activos visibles

4. **Acciones por Documento:**
   - **Ver:** Detalles del tipo de documento
   - **Gestionar:** Ir a la gestión específica
   - **Reporte:** Generar reporte del tipo

### API Endpoints

#### Estadísticas:
```
GET /admin/documentos/unificados/api/estadisticas
```

#### Documentos por Tipo:
```
GET /admin/documentos/unificados/api/tipos/{tipo}
```
- `tipo`: `todos`, `practicas`, `servicio`

#### Aplicar Filtros:
```
POST /admin/documentos/unificados/filtros
```

#### Exportar Reportes:
```
GET /admin/documentos/unificados/exportar/pdf
```

## Estructura de Datos

### Respuesta de API - Estadísticas:
```json
{
  "success": true,
  "data": {
    "total": 156,
    "aprobados": 89,
    "pendientes": 45,
    "rechazados": 22,
    "practicas": {
      "total": 120,
      "aprobados": 70,
      "pendientes": 35,
      "rechazados": 15
    },
    "servicio": {
      "total": 36,
      "aprobados": 19,
      "pendientes": 10,
      "rechazados": 7
    }
  }
}
```

### Respuesta de API - Documentos por Tipo:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "tipo": "practicas",
      "codigo": "1.1",
      "nombre": "Oficio de Asignación de Tutor Docente",
      "descripcion": "Documento oficial que asigna un tutor docente...",
      "estadisticas": {
        "total": 45,
        "aprobados": 32,
        "pendientes": 8,
        "rechazados": 5
      },
      "icono": "fas fa-file-alt",
      "color": "practicas"
    }
  ]
}
```

## Personalización

### Agregar Nuevos Tipos de Documentos

#### Para Prácticas:
```php
// En la base de datos
INSERT INTO TAB_TIPOS_DOCUMENTOS_PRACTICAS (CODIGO, NOMBRE, DESCRIPCION, REQUERIDO, ORDEN, ACTIVO) 
VALUES ('1.13', 'Nuevo Tipo', 'Descripción', 1, 13, 1);
```

#### Para Servicio Comunitario:
```php
// En la base de datos
INSERT INTO TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO (CODIGO, NOMBRE, DESCRIPCION, ORDEN, OBLIGATORIO, ACTIVO) 
VALUES ('SC.7', 'Nuevo Tipo', 'Descripción', 7, 1, 1);
```

### Modificar Iconos

En el controlador `DocumentosUnificadosController.php`:
```php
private function getIconoTipoDocumento($codigo)
{
    $iconos = [
        '1.1' => 'fas fa-file-alt',
        // Agregar nuevos iconos aquí
    ];
    return $iconos[$codigo] ?? 'fas fa-file-alt';
}
```

## Mantenimiento

### Limpieza de Datos
- Revisar archivos huérfanos en `writable/uploads/`
- Limpiar registros de documentos eliminados
- Backup regular de la base de datos

### Monitoreo
- Revisar logs de errores de API
- Monitorear rendimiento de consultas
- Verificar integridad de datos

## Solución de Problemas

### Error: "Tabla no encontrada"
```sql
-- Ejecutar el script de creación de tablas
SOURCE crear_tabla_tipos_documentos_servicio.sql;
```

### Error: "API no responde"
1. Verificar rutas en `app/Config/Routes.php`
2. Comprobar permisos de archivos
3. Revisar logs de aplicación

### Error: "Datos no se cargan"
1. Verificar conexión a base de datos
2. Comprobar que existan datos en las tablas
3. Revisar configuración de CORS si es necesario

## Notas Importantes

- ✅ **Compatible** con la estructura existente del sistema ITSI
- ✅ **Responsive** y optimizado para todos los dispositivos
- ✅ **Seguro** con validación de datos y permisos
- ✅ **Escalable** para agregar nuevos tipos de documentos
- ✅ **Mantenible** con código bien documentado

## Soporte

Para soporte técnico:
1. Revisar logs en `writable/logs/`
2. Verificar configuración de base de datos
3. Comprobar permisos de directorios
4. Validar configuración de PHP

---

**Desarrollado para el Sistema ITSI - Instituto Tecnológico Superior Ibarra**

*Vista Unificada de Documentos v1.0 - Gestión completa de documentos de prácticas y servicio comunitario*
