# Sistema de Notificaciones ITSI

## Descripción
Sistema completo de notificaciones para el Instituto Tecnológico Superior de Ibarra (ITSI) que permite notificar a estudiantes y docentes sobre asignaciones de prácticas, tutorías y otros eventos importantes.

## Características Implementadas

### ✅ Notificaciones Automáticas
- **Asignación de Prácticas**: Notificación automática cuando se asigna una nueva práctica a un estudiante
- **Tutoría Asignada**: Notificación al docente cuando se le asigna como tutor de una práctica
- **Emails de Confirmación**: Envío automático de emails con detalles completos

### ✅ Tipos de Notificaciones
- `asignacion_practica`: Nueva práctica asignada al estudiante
- `tutoria_asignada`: Nueva tutoria asignada al docente
- `recordatorio`: Recordatorios y avisos importantes
- `general`: Notificaciones generales del sistema

### ✅ Prioridades
- `alta`: Notificaciones críticas (documentos vencidos, etc.)
- `media`: Notificaciones importantes (asignaciones, etc.)
- `baja`: Notificaciones informativas

### ✅ Interfaces de Usuario
- **Vista de Estudiante**: Panel de notificaciones con filtros y acciones
- **Vista de Docente**: Panel especializado para tutores
- **Header Component**: Dropdown de notificaciones en el header
- **Responsive Design**: Adaptable a dispositivos móviles

## Archivos Creados/Modificados

### Modelos
- `app/Models/NotificacionesModel.php` - Modelo principal de notificaciones

### Controladores
- `app/Controllers/NotificacionesController.php` - Controlador de notificaciones
- `app/Controllers/admin/PracticasAdminController.php` - Modificado para enviar notificaciones

### Vistas
- `app/Views/estudiante/notificaciones/notificaciones.php` - Vista para estudiantes
- `app/Views/docente/notificaciones/notificaciones.php` - Vista para docentes
- `app/Views/partials/notificaciones_header.php` - Componente del header

### Librerías y Helpers
- `app/Libraries/EmailNotificaciones.php` - Librería para envío de emails
- `app/Helpers/NotificacionesHelper.php` - Helper con funciones utilitarias

### Base de Datos
- `crear_tabla_notificaciones.sql` - Script para crear la tabla de notificaciones

### Rutas
- `app/Config/Routes.php` - Rutas agregadas para notificaciones

## Instalación

### 1. Crear la Tabla de Notificaciones
```sql
-- Ejecutar el script SQL
source crear_tabla_notificaciones.sql
```

### 2. Configurar Email (Opcional)
Editar `app/Config/Email.php` para configurar el servidor de email:
```php
public $fromEmail = 'noreply@itsi.edu.ec';
public $fromName = 'Sistema ITSI';
// ... otras configuraciones
```

### 3. Incluir el Componente de Header
Agregar en los layouts principales:
```php
<?= $this->include('partials/notificaciones_header') ?>
```

## Uso del Sistema

### Para Administradores

#### Asignar Nueva Práctica
```php
// El sistema automáticamente envía notificaciones cuando se crea una práctica
$practicaController = new PracticasAdminController();
$practicaController->crearPractica(); // Envía notificaciones automáticamente
```

#### Crear Notificación Personalizada
```php
use App\Helpers\NotificacionesHelper;

// Notificación simple
NotificacionesHelper::crearNotificacion(
    $idUsuario,
    'Título de la notificación',
    'Mensaje de la notificación',
    'general',
    'media'
);

// Notificación de recordatorio
NotificacionesHelper::crearRecordatorio(
    $idUsuario,
    'Recordatorio importante',
    'No olvides completar tu documento'
);

// Notificación masiva por rol
NotificacionesHelper::enviarNotificacionPorRol(
    'estudiante',
    'Aviso general',
    'Mensaje para todos los estudiantes'
);
```

### Para Desarrolladores

#### API de Notificaciones
```javascript
// Obtener notificaciones
fetch('/notificaciones/')
    .then(response => response.json())
    .then(data => console.log(data));

// Marcar como leída
fetch('/notificaciones/marcar-leida/123', {method: 'POST'})
    .then(response => response.json());

// Obtener contador
fetch('/notificaciones/contador')
    .then(response => response.json())
    .then(data => console.log(data.contador));
```

#### Uso del Helper
```php
use App\Helpers\NotificacionesHelper;

// Obtener estadísticas
$estadisticas = NotificacionesHelper::obtenerEstadisticas($idUsuario);

// Obtener notificaciones recientes
$notificaciones = NotificacionesHelper::obtenerNotificacionesUsuario($idUsuario, 10);

// Marcar como leída
NotificacionesHelper::marcarComoLeida($idNotificacion, $idUsuario);
```

## Configuración de Emails

### Configuración SMTP
```php
// app/Config/Email.php
public $protocol = 'smtp';
public $SMTPHost = 'smtp.gmail.com';
public $SMTPUser = 'tu-email@gmail.com';
public $SMTPPass = 'tu-password';
public $SMTPPort = 587;
public $SMTPCrypto = 'tls';
```

### Plantillas de Email
Las plantillas están en `app/Libraries/EmailNotificaciones.php`:
- Email para estudiantes (nueva práctica)
- Email para tutores (nueva tutoria)
- Email genérico (recordatorios)

## Personalización

### Agregar Nuevos Tipos de Notificación
1. Actualizar el enum en la tabla: `ALTER TABLE TAB_NOTIFICACIONES MODIFY TIPO_NOTIFICACION ENUM(...)`
2. Agregar iconos en las vistas
3. Actualizar el helper si es necesario

### Personalizar Plantillas de Email
Modificar los métodos en `EmailNotificaciones.php`:
- `generarHTMLEmailEstudiante()`
- `generarHTMLEmailTutor()`
- `generarHTMLGenerico()`

### Agregar Filtros Personalizados
En las vistas, agregar botones de filtro:
```html
<button class="btn btn-outline-custom" data-filter="mi_tipo">
    Mi Tipo de Notificación
</button>
```

## Monitoreo y Mantenimiento

### Logs
El sistema registra todas las actividades en los logs de CodeIgniter:
- Creación de notificaciones
- Envío de emails
- Errores del sistema

### Limpieza Automática
```php
// Limpiar notificaciones antiguas (ejecutar periódicamente)
NotificacionesHelper::limpiarNotificacionesAntiguas();
```

### Estadísticas
```php
// Obtener resumen para dashboard
$resumen = NotificacionesHelper::obtenerResumenDashboard($idUsuario);
```

## Solución de Problemas

### Emails No Se Envían
1. Verificar configuración SMTP
2. Revisar logs de error
3. Probar con `EmailNotificaciones::verificarConfiguracion()`

### Notificaciones No Aparecen
1. Verificar que la tabla existe
2. Revisar permisos de usuario
3. Verificar que el usuario tiene ID válido

### JavaScript No Funciona
1. Verificar que jQuery/Bootstrap están cargados
2. Revisar consola del navegador
3. Verificar rutas API

## Próximas Mejoras

- [ ] Notificaciones push para móviles
- [ ] Plantillas de email más avanzadas
- [ ] Sistema de preferencias de notificación
- [ ] Notificaciones programadas
- [ ] Integración con WhatsApp/SMS
- [ ] Dashboard de estadísticas de notificaciones

## Soporte

Para soporte técnico o preguntas sobre el sistema de notificaciones, contactar al equipo de desarrollo del ITSI.

---
**Versión**: 1.0  
**Fecha**: <?= date('Y-m-d') ?>  
**Desarrollado por**: Equipo ITSI
