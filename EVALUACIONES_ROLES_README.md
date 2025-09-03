# Sistema de Evaluaciones por Roles

## Descripción

Se ha implementado un sistema de evaluaciones que permite a diferentes roles (Administrador, Docente, Estudiante) acceder a formularios de evaluación con diferentes niveles de funcionalidad.

## Estructura Implementada

### 1. Controladores

#### Administrador
- **Archivo**: `app/Controllers/admin/EvaluacionesAdminController.php`
- **Funcionalidades**: 
  - Gestión completa de evaluaciones (crear, editar, eliminar)
  - Visualización de estadísticas detalladas
  - Filtros avanzados
  - Exportación de reportes

#### Docente
- **Archivo**: `app/Controllers/docente/EvaluacionesDocenteController.php`
- **Funcionalidades**:
  - Visualización de evaluaciones activas y no vencidas
  - Acceso directo a formularios de evaluación
  - Estadísticas básicas

#### Estudiante
- **Archivo**: `app/Controllers/estudiante/EvaluacionesEstudianteController.php`
- **Funcionalidades**:
  - Visualización de evaluaciones activas y no vencidas
  - Acceso directo a formularios de evaluación
  - Alertas para evaluaciones próximas a vencer
  - Estadísticas básicas

### 2. Vistas

#### Administrador
- **Archivo**: `app/Views/admin/evaluaciones/evaluaciones_views.php`
- **Características**:
  - Interfaz completa de gestión
  - Modal para crear nuevas evaluaciones
  - Filtros y búsquedas
  - Vista grid y lista

#### Docente
- **Archivo**: `app/Views/docente/evaluaciones/evaluaciones_docente.php`
- **Características**:
  - Interfaz simplificada
  - Solo visualización de enlaces
  - Botones para acceder a formularios
  - Vista grid y lista

#### Estudiante
- **Archivo**: `app/Views/estudiante/evaluaciones/evaluaciones_estudiante.php`
- **Características**:
  - Interfaz simplificada
  - Solo visualización de enlaces
  - Alertas para evaluaciones urgentes
  - Botones para acceder a formularios
  - Vista grid y lista

### 3. Modelo Actualizado

#### EvaluacionesEnlacesModel
- **Archivo**: `app/Models/EvaluacionesEnlacesModel.php`
- **Nuevos métodos**:
  - `obtenerEvaluacionesParaDocentes()`
  - `obtenerEvaluacionesParaEstudiantes()`
  - `obtenerEstadisticasParaDocentes()`
  - `obtenerEstadisticasParaEstudiantes()`
  - `obtenerProximasAVencer()`

## Características por Rol

### Administrador
- ✅ Crear nuevas evaluaciones
- ✅ Editar evaluaciones existentes
- ✅ Eliminar evaluaciones
- ✅ Ver todas las evaluaciones (activas e inactivas)
- ✅ Estadísticas completas
- ✅ Filtros avanzados
- ✅ Exportación de reportes
- ✅ Gestión de cursos

### Docente
- ✅ Ver evaluaciones activas y no vencidas
- ✅ Acceder a formularios de evaluación
- ✅ Estadísticas básicas
- ✅ Vista grid y lista
- ❌ No puede crear/editar/eliminar evaluaciones
- ❌ No puede ver evaluaciones inactivas o vencidas

### Estudiante
- ✅ Ver evaluaciones activas y no vencidas
- ✅ Acceder a formularios de evaluación
- ✅ Alertas para evaluaciones próximas a vencer (7 días)
- ✅ Estadísticas básicas
- ✅ Vista grid y lista
- ❌ No puede crear/editar/eliminar evaluaciones
- ❌ No puede ver evaluaciones inactivas o vencidas

## Rutas Necesarias

Para que el sistema funcione correctamente, asegúrate de agregar estas rutas en tu archivo de configuración de rutas:

```php
// Rutas para Administrador
$routes->group('admin/evaluaciones', ['namespace' => 'App\Controllers\admin'], function($routes) {
    $routes->get('/', 'EvaluacionesAdminController::index');
    $routes->get('obtener', 'EvaluacionesAdminController::obtenerEvaluaciones');
    $routes->post('agregar', 'EvaluacionesAdminController::agregarEvaluacion');
    $routes->get('cursos', 'EvaluacionesAdminController::obtenerCursos');
    $routes->post('eliminar/(:num)', 'EvaluacionesAdminController::eliminarEvaluacion/$1');
    $routes->post('estadisticas', 'EvaluacionesAdminController::obtenerEstadisticas');
    $routes->post('filtros', 'EvaluacionesAdminController::aplicarFiltros');
});

// Rutas para Docente
$routes->group('docente/evaluaciones', ['namespace' => 'App\Controllers\docente'], function($routes) {
    $routes->get('/', 'EvaluacionesDocenteController::index');
    $routes->get('obtener', 'EvaluacionesDocenteController::obtenerEvaluaciones');
    $routes->get('estadisticas', 'EvaluacionesDocenteController::obtenerEstadisticas');
});

// Rutas para Estudiante
$routes->group('estudiante/evaluaciones', ['namespace' => 'App\Controllers\estudiante'], function($routes) {
    $routes->get('/', 'EvaluacionesEstudianteController::index');
    $routes->get('obtener', 'EvaluacionesEstudianteController::obtenerEvaluaciones');
    $routes->get('estadisticas', 'EvaluacionesEstudianteController::obtenerEstadisticas');
});
```

## Seguridad

- Cada controlador verifica la autenticación y el rol del usuario
- Los docentes y estudiantes solo pueden ver evaluaciones activas y no vencidas
- No tienen acceso a funciones de administración
- Las evaluaciones vencidas se ocultan automáticamente

## Base de Datos

El sistema utiliza la tabla `TAB_EVALUACIONES_ENLACES` con la siguiente estructura:

```sql
CREATE TABLE TAB_EVALUACIONES_ENLACES (
    ID_EVALUACION_ENLACE INT AUTO_INCREMENT PRIMARY KEY,
    ID_ACTIVIDAD_EDUCACION INT,
    ID_USUARIO_CREADOR INT,
    NOMBRE_EVALUACION VARCHAR(200) NOT NULL,
    TIPO_EVALUACION VARCHAR(50) NOT NULL,
    ENLACE_FORMULARIO VARCHAR(500) NOT NULL,
    DESCRIPCION TEXT,
    FECHA_VENCIMIENTO DATE NOT NULL,
    ESTADO ENUM('activo', 'inactivo', 'borrador') DEFAULT 'activo',
    NUMERO_RESPUESTAS INT DEFAULT 0,
    ACTIVO BOOLEAN DEFAULT TRUE,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Uso

1. **Administrador**: Accede a `/admin/evaluaciones` para gestionar todas las evaluaciones
2. **Docente**: Accede a `/docente/evaluaciones` para ver y completar evaluaciones
3. **Estudiante**: Accede a `/estudiante/evaluaciones` para ver y completar evaluaciones

## Notas Importantes

- Los formularios de evaluación deben ser creados externamente (Google Forms, Microsoft Forms, etc.)
- Solo se almacenan los enlaces a los formularios, no el contenido
- Las evaluaciones vencidas se ocultan automáticamente para docentes y estudiantes
- El sistema incluye alertas visuales para evaluaciones próximas a vencer
