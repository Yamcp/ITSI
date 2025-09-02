# Sistema de Gestión de Actividades Educativas

## Descripción
Sistema completo para la gestión de actividades educativas (cursos, talleres, seminarios) integrado con la base de datos del sistema ITSI.

## Funcionalidades Implementadas

### 1. Gestión de Actividades
- ✅ **Listar actividades** por tipo (Cursos, Talleres, Seminarios)
- ✅ **Crear nuevas actividades** con formulario completo
- ✅ **Ver detalles** de cada actividad
- ✅ **Editar actividades** existentes
- ✅ **Eliminar actividades** con confirmación
- ✅ **Estadísticas en tiempo real** (total, activos por tipo)

### 2. Calendario de Actividades
- ✅ **Vista de calendario** con FullCalendar
- ✅ **Eventos por tipo** con colores diferenciados
- ✅ **Filtros por tipo** de actividad
- ✅ **Vista mensual, semanal y diaria**
- ✅ **Detalles de eventos** al hacer clic

### 3. Formularios Dinámicos
- ✅ **Selección de instructores** desde la base de datos
- ✅ **Tipos de actividades** dinámicos
- ✅ **Modalidades** (Presencial, Virtual, Híbrida)
- ✅ **Validación de fechas** (fin >= inicio)


## Archivos Creados/Modificados

### Controladores
- `app/Controllers/admin/ActividadesEducacionController.php` - Controlador principal

### Modelos
- `app/Models/ActividadesEducacionModel.php` - Modelo de actividades (ya existía, actualizado)

### Vistas
- `app/Views/admin/educacion/actividades_educacion_views.php` - Vista principal (actualizada)
- `app/Views/admin/educacion/create.php` - Formulario de creación
- `app/Views/admin/educacion/show.php` - Vista de detalles
- `app/Views/admin/educacion/edit.php` - Formulario de edición

### Rutas
- `app/Config/Routes.php` - Rutas agregadas para el CRUD completo

### Base de Datos
- `datos_ejemplo_actividades.sql` - Datos de ejemplo para pruebas

## Instalación y Configuración

### 1. Ejecutar la Base de Datos
```sql
-- Ejecutar el archivo principal de la base de datos
source bddITSI.sql;

-- Ejecutar los datos de ejemplo
source datos_ejemplo_actividades.sql;
```

### 2. Verificar Rutas
Las siguientes rutas están configuradas:
- `GET /admin/actividades-educacion` - Lista principal
- `GET /admin/actividades-educacion/crear` - Formulario de creación
- `POST /admin/actividades-educacion/guardar` - Guardar nueva actividad
- `GET /admin/actividades-educacion/ver/{id}` - Ver detalles
- `GET /admin/actividades-educacion/editar/{id}` - Formulario de edición
- `POST /admin/actividades-educacion/actualizar/{id}` - Actualizar actividad
- `GET /admin/actividades-educacion/eliminar/{id}` - Eliminar actividad
- `GET /admin/actividades-educacion/calendario` - API del calendario
- `GET /admin/actividades-educacion/api/actividades` - API de actividades
- `GET /admin/actividades-educacion/api/estadisticas` - API de estadísticas

### 3. Acceder al Sistema
1. Iniciar sesión como administrador
2. Navegar a la sección "Educación" en el menú
3. O acceder directamente a `/admin/actividades-educacion`

## Características Técnicas

### Base de Datos
- **Tabla principal**: `TAB_ACTIVIDADES_EDUCACION`
- **Relaciones**: Instructores, Tipos de Actividades, Modalidades, Usuarios
- **Validaciones**: Fechas, campos obligatorios, integridad referencial

### Frontend
- **Framework**: Bootstrap 5
- **Calendario**: FullCalendar 6.1.10
- **Iconos**: Font Awesome
- **Responsive**: Diseño adaptable a móviles

### Backend
- **Framework**: CodeIgniter 4
- **Patrón**: MVC (Model-View-Controller)
- **Validación**: Server-side y client-side
- **API**: Endpoints JSON para AJAX

## Funcionalidades Futuras (Pendientes)

### Gestión de Participantes
- [ ] Inscripción de estudiantes
- [ ] Lista de participantes por actividad
- [ ] Control de asistencia
- [ ] Generación de reportes de asistencia



### Evaluaciones
- [ ] Sistema de evaluaciones por actividad
- [ ] Rúbricas de evaluación
- [ ] Reportes de calificaciones
- [ ] Historial académico

### Notificaciones
- [ ] Recordatorios de actividades
- [ ] Notificaciones de cambios
- [ ] Alertas de vencimiento
- [ ] Sistema de mensajería

## Estructura de la Base de Datos

### Tablas Principales
```sql
TAB_ACTIVIDADES_EDUCACION
├── ID_ACTIVIDAD_EDUCACION (PK)
├── ID_INSTRUCTOR (FK → TAB_INSTRUCTORES)
├── ID_TIPO_MODALIDAD (FK → TAB_TIPOS_MODALIDADES)
├── ID_TIPO_ACTIVIDAD (FK → TAB_TIPOS_ACTIVIDADES)
├── ID_USUARIO (FK → TAB_USUARIOS)
├── NOMBRE_ACTIVIDAD
├── DESCRIPCION
├── OBJETIVOS
├── DURACION_HORAS
├── FECHA_INICIO
├── FECHA_FIN
├── LUGAR
├── HORARIO
└── PROGRAMA_DETALLADO
```

### Tablas de Referencia
- `TAB_TIPOS_ACTIVIDADES` - Curso, Taller, Seminario, etc.
- `TAB_TIPOS_MODALIDADES` - Presencial, Virtual, Híbrida
- `TAB_INSTRUCTORES` - Instructores con datos personales
- `TAB_DATOS_PERSONAS` - Información personal de instructores

## Soporte y Mantenimiento

### Logs y Debugging
- Los errores se registran en `writable/logs/`
- Usar `log_message()` para debugging
- Verificar permisos de escritura en `writable/`

### Actualizaciones
- Mantener CodeIgniter actualizado
- Revisar dependencias de JavaScript
- Backup regular de la base de datos

### Seguridad
- Validación de entrada en servidor
- Sanitización de datos
- Protección CSRF habilitada
- Control de acceso por roles

## Contacto
Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo del ITSI.
