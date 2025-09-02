# Sistema de Gestión de Prácticas - ITSI

## Descripción
Sistema completo para la gestión de prácticas preprofesionales y servicios comunitarios del Instituto Tecnológico Superior de Ibarra (ITSI).

## Características Implementadas

### ✅ Funcionalidades Principales
- **Dashboard con estadísticas en tiempo real**
- **Gestión de Prácticas Preprofesionales**
- **Gestión de Servicios Comunitarios**
- **Seguimiento de actividades**
- **Registro de asistencias**
- **Sistema de reportes**

### ✅ Características Técnicas
- **Conexión completa con base de datos MySQL**
- **API REST para operaciones CRUD**
- **Interfaz responsive con Bootstrap 5**
- **Validación de formularios**
- **Notificaciones en tiempo real**
- **Sistema de modales interactivos**

## Instalación

### 1. Requisitos Previos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- CodeIgniter 4
- Servidor web (Apache/Nginx)

### 2. Configuración de Base de Datos
1. Crear la base de datos `itsi`
2. Importar el archivo `bddITSI.sql`
3. Ejecutar `datos_prueba_practicas.sql` para datos de ejemplo
4. Verificar la configuración en `app/Config/Database.php`

### 3. Configuración del Proyecto
1. Clonar o copiar los archivos del proyecto
2. Configurar las rutas en `app/Config/Routes.php`
3. Verificar permisos de escritura en `writable/`

## Estructura de Archivos

### Controladores
- `app/Controllers/admin/PracticasAdminController.php` - Controlador principal

### Modelos
- `app/Models/AsignacionesPracticasModel.php`
- `app/Models/PracticasPreprofesionalesModel.php`
- `app/Models/ServicioComunitarioModel.php`
- `app/Models/AsistenciasPracticasPreprofesionalesModel.php`
- `app/Models/AsistenciasServicioComunitarioModel.php`
- `app/Models/SeguimientosPracticasPreprofesionalesModel.php`
- `app/Models/SeguimientosServicioComunitarioModel.php`

### Vistas
- `app/Views/admin/practicas/practicas_views.php` - Vista principal

## Rutas Configuradas

```php
// Rutas para la gestión de prácticas
$routes->get('practicas', 'PracticasAdminController::index');
$routes->get('practicas/getDatosModal', 'PracticasAdminController::getDatosModal');
$routes->post('practicas/crear', 'PracticasAdminController::crearPractica');
$routes->get('practicas/detalle/(:num)/(:alpha)', 'PracticasAdminController::getDetallePractica/$1/$2');
$routes->post('practicas/registrar-asistencia', 'PracticasAdminController::registrarAsistencia');
$routes->get('practicas/generar-reporte', 'PracticasAdminController::generarReporte');
$routes->get('practicas/exportar-datos', 'PracticasAdminController::exportarDatos');
```

## Uso del Sistema

### 1. Acceso al Sistema
- URL: `http://tu-dominio/admin/practicas`
- Requiere autenticación de administrador

### 2. Funcionalidades Disponibles

#### Dashboard Principal
- **Estadísticas en tiempo real**: Total de prácticas, activas, finalizadas, pendientes
- **Vista por pestañas**: Prácticas Preprofesionales, Servicio Comunitario, Seguimiento

#### Gestión de Prácticas
- **Crear nueva práctica**: Modal con formulario completo
- **Ver detalles**: Información completa de cada práctica
- **Registrar asistencias**: Control de horas trabajadas
- **Editar prácticas**: (En desarrollo)

#### Seguimiento
- **Actividades recientes**: Últimas actividades registradas
- **Progreso visual**: Barras de progreso y porcentajes
- **Reportes**: Generación de reportes en tiempo real

### 3. Flujo de Trabajo

1. **Crear Práctica**
   - Seleccionar tipo (Preprofesional/Servicio Comunitario)
   - Asignar estudiante e institución
   - Definir fechas y horas
   - Guardar en base de datos

2. **Registrar Asistencias**
   - Seleccionar práctica
   - Registrar fecha, horarios y actividades
   - Sistema calcula horas automáticamente

3. **Seguimiento**
   - Visualizar progreso en tiempo real
   - Generar reportes
   - Exportar datos

## API Endpoints

### GET `/admin/practicas/getDatosModal`
Obtiene datos para poblar los modales (estudiantes, instituciones, tipos, estados)

### POST `/admin/practicas/crear`
Crea una nueva práctica
```json
{
  "tipo_practica": "2",
  "estudiante": "1",
  "institucion": "1",
  "estado": "1",
  "fecha_inicio": "2025-06-01",
  "fecha_fin": "2025-08-30",
  "horas_total": "240",
  "cronograma": "Lunes a Viernes 8:00-17:00",
  "descripcion": "Descripción de la práctica"
}
```

### GET `/admin/practicas/detalle/{id}/{tipo}`
Obtiene detalles de una práctica específica

### POST `/admin/practicas/registrar-asistencia`
Registra una nueva asistencia
```json
{
  "practica_id": "1",
  "tipo_practica": "preprofesional",
  "fecha_asistencia": "2025-08-30",
  "hora_entrada": "08:00",
  "hora_salida": "17:00",
  "actividades_dia": "Desarrollo de módulo",
  "observaciones": "Observaciones adicionales"
}
```

## Base de Datos

### Tablas Principales
- `TAB_PRACTICAS_PREPROFESIONALES` - Prácticas preprofesionales
- `TAB_SERVICIO_COMUNITARIO` - Servicios comunitarios
- `TAB_ASIGNACIONES_PRACTICAS` - Asignaciones generales
- `TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES` - Asistencias preprofesionales
- `TAB_ASISTENCIAS_SERVICIO_COMUNITARIO` - Asistencias servicio comunitario
- `TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES` - Seguimiento preprofesionales
- `TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO` - Seguimiento servicio comunitario

## Personalización

### Agregar Nuevos Campos
1. Modificar la tabla en la base de datos
2. Actualizar el modelo correspondiente en `$allowedFields`
3. Modificar la vista para incluir el nuevo campo
4. Actualizar el controlador para manejar el nuevo campo

### Cambiar Estilos
- Los estilos están integrados en la vista principal
- Usar Bootstrap 5 para consistencia
- Personalizar colores y fuentes según necesidades

## Solución de Problemas

### Error de Conexión a Base de Datos
1. Verificar configuración en `app/Config/Database.php`
2. Confirmar que MySQL esté ejecutándose
3. Verificar credenciales de acceso

### Error 404 en Rutas
1. Verificar configuración en `app/Config/Routes.php`
2. Confirmar que el archivo `.htaccess` esté presente
3. Verificar configuración del servidor web

### Problemas de Permisos
1. Verificar permisos de escritura en `writable/`
2. Confirmar permisos de lectura en `app/`

## Próximas Mejoras

- [ ] Sistema de notificaciones por email
- [ ] Generación de reportes en PDF
- [ ] Exportación a Excel
- [ ] Sistema de evaluación de prácticas
- [ ] Dashboard con gráficos avanzados
- [ ] Sistema de documentos adjuntos
- [ ] Notificaciones push
- [ ] API para aplicación móvil

## Soporte

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo del ITSI.

## Licencia

Sistema desarrollado para el Instituto Tecnológico Superior de Ibarra (ITSI).
