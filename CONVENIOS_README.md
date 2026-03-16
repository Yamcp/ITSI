# Sistema de Gestión de Convenios - ITSI

## Descripción
Sistema completo para la gestión de convenios del Instituto Tecnológico Superior Ibarra, incluyendo convenios preprofesionales, de servicio comunitario y mixtos.

## Características Implementadas

### ✅ Conexión con Base de Datos
- **Conectado a la base de datos real** usando los modelos existentes:
  - `DetallesConveniosModel`
  - `InstitucionesConveniosModel` 
  - `TiposConveniosModel`
- **Estadísticas en tiempo real** calculadas desde la base de datos
- **Datos dinámicos** en todas las tablas y formularios

### ✅ Validaciones de Campos Obligatorios
- **Validación del lado del cliente** (JavaScript) con mensajes de error en tiempo real
- **Validación del lado del servidor** (PHP) con reglas estrictas
- **Campos obligatorios marcados** con asterisco rojo (*)
- **Validaciones específicas**:
  - Fechas: Fecha fin debe ser posterior a fecha inicio
  - Duración: Entre 1 y 60 meses
  - Email: Formato válido
  - RUC: Entre 10 y 13 caracteres
  - Campos de texto: Longitud mínima y máxima

### ✅ Funcionalidad de Reportes
- **Vista de reportes dedicada** con filtros avanzados
- **Reporte PDF** con diseño profesional
- **Exportación a Excel/CSV** con todos los datos
- **Filtros múltiples**: tipo de convenio, institución, estado, fechas, renovable
- **Estadísticas incluidas** en los reportes
- **Fecha de generación** automática
- **Interfaz intuitiva** para generar reportes personalizados

### ✅ Exportación de Datos
- **Modal de opciones de exportación** con interfaz intuitiva
- **Exportación a PDF** con formato profesional
- **Exportación a Excel** con formato optimizado
- **Exportación a CSV** para análisis de datos
- **Filtros aplicables** antes de exportar
- **Datos completos** incluyendo estado calculado
- **Codificación UTF-8** para caracteres especiales

## Estructura de Archivos

```
app/
├── Controllers/admin/
│   └── ConveniosAdminController.php    # Controlador principal
├── Models/
│   ├── DetallesConveniosModel.php      # Modelo de convenios
│   ├── InstitucionesConveniosModel.php # Modelo de instituciones
│   └── TiposConveniosModel.php         # Modelo de tipos
├── Views/admin/convenios/
│   ├── convenios_views.php             # Vista principal
│   └── reportes.php                    # Vista única de reportes (filtros, tabla y exportación PDF/Excel)
└── Config/
    └── Routes.php                       # Rutas actualizadas
```

## Rutas Configuradas

```php
// Rutas para convenios
$routes->get('admin/convenios', 'ConveniosAdminController::index');
$routes->post('admin/convenios/store', 'ConveniosAdminController::store');
$routes->post('admin/convenios/storeInstitucion', 'ConveniosAdminController::storeInstitucion');
$routes->get('admin/convenios/getInstituciones', 'ConveniosAdminController::getInstituciones');
$routes->get('admin/convenios/getConvenios', 'ConveniosAdminController::getConvenios');
$routes->get('admin/convenios/generarReporte', 'ConveniosAdminController::generarReporte');
$routes->get('admin/convenios/vencimientos', 'ConveniosAdminController::vencimientos');
$routes->get('admin/convenios/reportes', 'ConveniosAdminController::reportes');
```

## Funcionalidades Principales

### 1. Gestión de Convenios
- **Crear nuevos convenios** con validación completa
- **Ver convenios por tipo** (Preprofesionales, Servicio Comunitario, Mixtos)
- **Estados automáticos** (Vigente, Por Vencer, Vencido)
- **Gestión de archivos** (subida de documentos del convenio)

### 2. Gestión de Instituciones
- **Crear nuevas instituciones** desde el formulario de convenios
- **Validación de RUC único**
- **Información completa** de contacto y representante legal
- **Integración automática** con el formulario de convenios

### 3. Reportes y Exportación
- **Reportes PDF** con diseño profesional
- **Exportación Excel/CSV** para análisis
- **Filtros por tipo** de convenio
- **Estadísticas incluidas**

### 4. Validaciones Implementadas

#### Convenios:
- Tipo de convenio (obligatorio)
- Institución (obligatorio)
- Fecha inicio (obligatorio, formato válido)
- Fecha fin (obligatorio, debe ser posterior a inicio)
- Duración (obligatorio, 1-60 meses)
- Objetivo (obligatorio, mínimo 10 caracteres)
- Renovable (obligatorio, Sí/No)

#### Instituciones:
- Tipo de institución (obligatorio)
- Nombre (obligatorio, 5-200 caracteres)
- RUC (obligatorio, 10-13 caracteres, único)
- Ciudad (obligatorio, 2-50 caracteres)
- Dirección (obligatorio, mínimo 10 caracteres)
- Teléfono (obligatorio, 7-20 caracteres)
- Email (obligatorio, formato válido)
- Representante legal (obligatorio, 5-150 caracteres)
- Persona de contacto (obligatorio, 5-150 caracteres)
- Teléfono de contacto (obligatorio, 7-20 caracteres)
- Email de contacto (obligatorio, formato válido)

## Uso del Sistema

### Acceso
1. Navegar a `/admin/convenios`
2. El sistema mostrará las estadísticas y convenios existentes

### Crear Nuevo Convenio
1. Hacer clic en "Nuevo Convenio"
2. Completar todos los campos obligatorios
3. Si la institución no existe, hacer clic en "+" para crearla
4. El sistema validará todos los campos antes de guardar

### Crear Nueva Institución
1. Desde el modal de "Nuevo Convenio" o directamente
2. Completar todos los campos obligatorios
3. El sistema validará RUC único y formatos
4. Al guardar, regresa automáticamente al formulario de convenio

### Generar Reportes
1. Hacer clic en "Ver Reportes" para acceder a la vista de reportes
2. Aplicar filtros según necesidades (tipo, estado, fechas, etc.)
3. Hacer clic en "Exportar PDF" o "Exportar Excel" para descargar
4. Los reportes incluyen estadísticas y datos completos filtrados

## Base de Datos

El sistema utiliza las siguientes tablas:
- `TAB_DETALLES_CONVENIOS` - Información de convenios
- `TAB_INSTITUCIONES_CONVENIOS` - Información de instituciones
- `TAB_TIPOS_CONVENIOS` - Tipos de convenios disponibles

## Archivos de Configuración

- **Directorio de uploads**: `writable/uploads/convenios/`
- **Reportes**: Un solo documento/vista en `app/Views/admin/convenios/reportes.php` (página con filtros, resultados y exportación; el PDF se genera por código con TCPDF).
- **Rutas**: Configuradas en `app/Config/Routes.php`

## Notas Técnicas

- **Validación dual**: Cliente y servidor
- **Manejo de archivos**: Subida segura con validación de tipos
- **Estados automáticos**: Calculados en tiempo real
- **Responsive**: Compatible con dispositivos móviles
- **AJAX**: Comunicación asíncrona para mejor UX
- **Modal dinámico**: Creado con JavaScript para opciones de exportación
- **Codificación UTF-8**: BOM incluido para compatibilidad con Excel
- **Múltiples formatos**: PDF, Excel y CSV con datos completos

## Próximas Mejoras Sugeridas

1. **Edición de convenios** existentes
2. **Renovación automática** de convenios
3. **Notificaciones** por email para vencimientos
4. **Dashboard** con gráficos avanzados
5. **Historial** de cambios en convenios
6. **Integración** con sistema de notificaciones
