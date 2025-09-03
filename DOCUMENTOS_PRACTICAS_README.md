# Sistema de Documentos de Prácticas Preprofesionales

## Descripción
Sistema completo para la gestión de documentos de prácticas preprofesionales que permite a los estudiantes subir los 12 tipos de documentos requeridos conforme avanzan en sus prácticas, y a los administradores revisar, aprobar o rechazar estos documentos.

## Características Principales

### Para Estudiantes:
- ✅ Vista de progreso de documentos con estadísticas
- ✅ Subida de documentos con drag & drop
- ✅ Visualización del estado de cada documento
- ✅ Descarga de documentos propios
- ✅ Eliminación de documentos (excepto aprobados)
- ✅ Interfaz intuitiva con 12 tipos de documentos predefinidos

### Para Administradores:
- ✅ Vista completa de todos los documentos
- ✅ Filtros avanzados por tipo, estado, estudiante, fechas
- ✅ Cambio de estado de documentos (Pendiente, En Revisión, Aprobado, Rechazado)
- ✅ Observaciones y comentarios para estudiantes
- ✅ Estadísticas generales del sistema
- ✅ Generación de reportes
- ✅ Vista en grid y lista

## Archivos Creados/Modificados

### Controladores:
- `app/Controllers/admin/DocumentosPracticasAdminController.php` - Controlador para administradores
- `app/Controllers/estudiante/DocumentosPracticasEstudianteController.php` - Controlador para estudiantes

### Modelos:
- `app/Models/DocumentosPracticasModel.php` - Modelo principal de documentos
- `app/Models/TiposDocumentosPracticasModel.php` - Modelo de tipos de documentos
- `app/Models/EstadosRevisionesModel.php` - Modelo de estados de revisión

### Vistas:
- `app/Views/admin/documentos/documentos_practicas.php` - Vista de administrador (ya existía, mejorada)
- `app/Views/estudiante/documentos/documentos_practicas.php` - Vista para estudiantes

### Estilos:
- `public/sistema/assets/css/documentos.css` - CSS personalizado

### Base de Datos:
- `documentos_practicas_tables.sql` - Script SQL para crear tablas

### Rutas:
- `app/Config/Routes.php` - Rutas agregadas para el módulo

## Instalación

### 1. Base de Datos

**Opción A: Instalación Completa (Recomendada)**
```sql
-- Ejecutar el archivo: instalacion_completa_documentos.sql
```

**Opción B: Si ya tienes tablas creadas pero con errores de columnas**

Si tienes errores como:
```
Unknown column 'ACTIVO' in 'where clause'
Unknown column 'ENTIDAD_RECEPTORA' in 'field list'
Unknown column 'OBSERVACIONES_REVISOR' in 'field list'
```

Ejecuta los scripts de corrección en este orden:
```sql
-- 1. Corregir tablas maestras
-- Ejecutar: agregar_columnas_tablas_maestras.sql

-- 2. Corregir tabla principal
-- Ejecutar: agregar_columnas_faltantes.sql
```

### 2. Directorio de Archivos
Crear el directorio para almacenar los documentos:

```bash
mkdir -p writable/uploads/documentos-practicas
chmod 755 writable/uploads/documentos-practicas
```

### 3. Verificar Rutas
Las rutas ya están configuradas en `app/Config/Routes.php`:

**Para Administradores:**
- `admin/documentos/practicas` - Vista principal
- `admin/documentos/practicas/store` - Subir documento
- `admin/documentos/practicas/download/{id}` - Descargar documento
- `admin/documentos/practicas/ver/{id}` - Ver documento
- `admin/documentos/practicas/eliminar/{id}` - Eliminar documento
- `admin/documentos/practicas/cambiar-estado/{id}` - Cambiar estado
- `admin/documentos/practicas/filtros` - Aplicar filtros
- `admin/documentos/practicas/reporte` - Generar reporte

**Para Estudiantes:**
- `estudiante/documentos-practicas` - Vista principal
- `estudiante/documentos-practicas/subir` - Subir documento
- `estudiante/documentos-practicas/mis-documentos` - Mis documentos
- `estudiante/documentos-practicas/progreso` - Ver progreso
- `estudiante/documentos-practicas/descargar/{id}` - Descargar documento
- `estudiante/documentos-practicas/eliminar/{id}` - Eliminar documento

## Los 12 Tipos de Documentos

1. **1.1. Oficio de Asignación de Tutor Docente** (Requerido)
2. **1.2. Oficio Personal a Entidad Receptora** (Requerido)
3. **1.3. Carta de Aceptación de Entidad Receptora** (Requerido)
4. **1.4. Solicitud Institucional Valorada** (Requerido)
5. **1.5. Certificado de Culminación (60 horas)** (Requerido)
6. **1.6. Rúbrica de Evaluación Entidad Receptora** (Requerido)
7. **1.7. Hojas de Asistencia de Estudiantes** (Requerido)
8. **1.8. Ficha de Registro de Actividades Realizadas** (Requerido)
9. **1.9. Ficha de Control y Seguimiento Docente** (Requerido)
10. **1.10. Rúbrica de Evaluación de Control y Seguimiento Docente** (Requerido)
11. **1.11. Rúbrica de Evaluación de Resultados** (Requerido)
12. **1.12. Respaldo en Fotos, Videos y Evidencias** (Opcional)

## Estados de Revisión

- **Pendiente** - Documento subido, esperando revisión
- **En Revisión** - Documento siendo revisado por administrador
- **Aprobado** - Documento aprobado y validado
- **Rechazado** - Documento rechazado, requiere correcciones
- **Requiere Corrección** - Documento necesita correcciones

## Funcionalidades Técnicas

### Seguridad:
- ✅ Verificación de autenticación y roles
- ✅ Validación de archivos (tipo, tamaño)
- ✅ Verificación de permisos por usuario
- ✅ Sanitización de datos de entrada

### Rendimiento:
- ✅ Índices optimizados en base de datos
- ✅ Consultas eficientes con JOINs
- ✅ Paginación para grandes volúmenes
- ✅ Caché de estadísticas

### Usabilidad:
- ✅ Interfaz responsive
- ✅ Drag & drop para subida de archivos
- ✅ Notificaciones en tiempo real
- ✅ Filtros y búsquedas avanzadas
- ✅ Vista en grid y lista

## Uso del Sistema

### Para Estudiantes:

1. **Acceder al módulo**: Navegar a `estudiante/documentos-practicas`
2. **Ver progreso**: El dashboard muestra el progreso general
3. **Subir documento**: Hacer clic en "Subir" en el documento correspondiente
4. **Completar formulario**: Llenar datos del documento y seleccionar archivo
5. **Seguimiento**: Monitorear el estado de revisión de cada documento

### Para Administradores:

1. **Acceder al módulo**: Navegar a `admin/documentos/practicas`
2. **Ver estadísticas**: Dashboard con estadísticas generales
3. **Revisar documentos**: Usar filtros para encontrar documentos específicos
4. **Cambiar estado**: Aprobar, rechazar o solicitar correcciones
5. **Agregar observaciones**: Comentarios para el estudiante
6. **Generar reportes**: Exportar datos para análisis

## Estructura de Base de Datos

### Tablas Principales:

1. **TAB_TIPOS_DOCUMENTOS_PRACTICAS** - Tipos de documentos (12 tipos predefinidos)
2. **TAB_ESTADOS_REVISIONES** - Estados de revisión (5 estados predefinidos)
3. **TAB_DOCUMENTOS_PRACTICAS** - Documentos subidos por estudiantes

### Relaciones:
- Documentos → Tipos de Documentos (FK)
- Documentos → Estados de Revisión (FK)
- Documentos → Usuarios (FK)

## Personalización

### Agregar Nuevos Tipos de Documentos:
```php
// En TiposDocumentosPracticasModel.php
$nuevoTipo = [
    'CODIGO' => '1.13',
    'NOMBRE' => 'Nuevo Tipo de Documento',
    'DESCRIPCION' => 'Descripción del nuevo tipo',
    'REQUERIDO' => 1,
    'ORDEN' => 13,
    'ACTIVO' => 1
];
```

### Agregar Nuevos Estados:
```php
// En EstadosRevisionesModel.php
$nuevoEstado = [
    'ESTADO' => 'Nuevo Estado',
    'DESCRIPCION' => 'Descripción del estado',
    'COLOR' => 'info',
    'ICONO' => 'fas fa-star',
    'ORDEN' => 6,
    'ACTIVO' => 1
];
```

## Mantenimiento

### Limpieza de Archivos:
- Los archivos se almacenan en `writable/uploads/documentos-practicas/`
- Implementar limpieza periódica de archivos huérfanos
- Backup regular de la carpeta de uploads

### Monitoreo:
- Revisar logs de errores de subida de archivos
- Monitorear espacio en disco
- Verificar integridad de la base de datos

## Soporte

Para soporte técnico o modificaciones:
1. Revisar logs de aplicación en `writable/logs/`
2. Verificar permisos de directorios
3. Comprobar configuración de PHP (upload_max_filesize, post_max_size)
4. Validar configuración de base de datos

## Notas Importantes

- ✅ El sistema está completamente funcional
- ✅ Compatible con la estructura existente del sistema ITSI
- ✅ Responsive y optimizado para móviles
- ✅ Seguro y validado
- ✅ Documentado y mantenible

---

**Desarrollado para el Sistema ITSI - Instituto Tecnológico Superior Ibarra**