<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');                           // Página principal de login
$routes->post('/auth/autenticar', 'AuthController::autenticar');     // Acción de autenticar al usuario
$routes->get('/auth/recuperar-contrasena', 'AuthController::recuperarContrasena');   // Formulario recuperar contraseña
$routes->post('/auth/solicitar-recuperacion', 'AuthController::solicitarRecuperacion'); // Solicitar recuperación
$routes->get('/auth/restablecer-contrasena', 'AuthController::restablecerContrasena'); // Formulario nueva contraseña (token en URL)
$routes->post('/auth/restablecer-contrasena', 'AuthController::restablecerContrasenaPost'); // Guardar nueva contraseña
$routes->get('/auth/cerrar-sesion', 'AuthController::cerrarSesion'); // Acción para cerrar sesión
$routes->post('/auth/cerrar-sesion', 'AuthController::cerrarSesion'); // Acción para cerrar sesión (POST)

// Redirección para URL antigua de convenios
$routes->get('vinculacion/convenios', function() {
    return redirect()->to('estudiante/convenios');
});

//----------------------------------------------------------------------------------------------------------------------
//RUTAS ADMINISTRADOR
$routes->group('admin', ['namespace' => 'App\Controllers\admin'], function ($routes) {
     $routes->get('dashboard', 'DashboardAdminController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardAdminController::index');    // El dashboard del administrador
    
    //Rutas para el perfil
    $routes->get('perfil', 'PerfilAdminController::index');          // Ver el perfil del administrador
    $routes->post('perfil/update', 'PerfilAdminController::update'); // Actualizar el perfil del administrador
    $routes->post('perfil/upload-image', 'PerfilAdminController::uploadImage'); // Subir imagen de perfil
    $routes->post('perfil/delete-image', 'PerfilAdminController::deleteImage'); // Eliminar imagen de perfil
    
    //Rutas para la cuenta
    $routes->get('cuenta', 'CuentaAdminController::index');          // Ver la cuenta del administrador
    $routes->post('cuenta/cambiar-password', 'CuentaAdminController::cambiarPassword'); // Cambiar contraseña
    $routes->get('educacion', 'ActividadesEducacionAdminController::index');    // Ver la sección de educación
    
    //Rutas para la gestión de actividades educativas
    $routes->get('actividades-educacion', 'ActividadesEducacionAdminController::index');    // Ver actividades educativas
    $routes->get('actividades-educacion/crear', 'ActividadesEducacionAdminController::create');    // Crear actividad
    $routes->post('actividades-educacion/guardar', 'ActividadesEducacionAdminController::store');    // Guardar actividad
    $routes->get('actividades-educacion/ver/(:num)', 'ActividadesEducacionAdminController::show/$1');    // Ver actividad
    $routes->get('actividades-educacion/editar/(:num)', 'ActividadesEducacionAdminController::edit/$1');    // Editar actividad
    $routes->post('actividades-educacion/actualizar/(:num)', 'ActividadesEducacionAdminController::update/$1');    // Actualizar actividad
    $routes->get('actividades-educacion/eliminar/(:num)', 'ActividadesEducacionAdminController::delete/$1');    // Eliminar actividad
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionAdminController::calendario');    // Calendario de actividades
    $routes->get('actividades-educacion/api/actividades', 'ActividadesEducacionAdminController::getActividades');    // API actividades
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionAdminController::getEstadisticas');    // API estadísticas
    
    // Rutas para reportes de actividades educativas
    $routes->get('actividades-educacion/reportes', 'ActividadesEducacionAdminController::reportes');    // Vista de reportes
    $routes->get('actividades-educacion/exportar/pdf', 'ActividadesEducacionAdminController::exportarPDF');    // Exportar PDF
    $routes->get('actividades-educacion/exportar/excel', 'ActividadesEducacionAdminController::exportarExcel');    // Exportar Excel
    $routes->get('actividades-educacion/exportar/csv', 'ActividadesEducacionAdminController::exportarCSV');    // Exportar CSV
   
    //Rutas para la gestión de convenios	
    $routes->get('convenios', 'ConveniosAdminController::index');        // Ver la sección de convenios
    $routes->post('convenios/store', 'ConveniosAdminController::store'); // Guardar nuevo convenio
    $routes->post('convenios/storeInstitucion', 'ConveniosAdminController::storeInstitucion'); // Guardar nueva institución
    $routes->post('convenios/actualizarPlazas/(:num)', 'ConveniosAdminController::actualizarPlazas/$1'); // Actualizar plazas disponibles
    $routes->get('convenios/getConvenio/(:num)', 'ConveniosAdminController::getConvenio/$1'); // Obtener un convenio (edición)
    $routes->post('convenios/update/(:num)', 'ConveniosAdminController::update/$1'); // Actualizar convenio
    $routes->get('convenios/getInstituciones', 'ConveniosAdminController::getInstituciones'); // Obtener instituciones
    $routes->get('convenios/getConvenios', 'ConveniosAdminController::getConvenios'); // Obtener convenios
    $routes->get('convenios/generarReporte', 'ConveniosAdminController::generarReporte'); // Generar reporte
    $routes->get('convenios/vencimientos', 'ConveniosAdminController::vencimientos');
    $routes->get('convenios/reportes', 'ConveniosAdminController::reportes'); // Ver convenios por vencer

    //Rutas para la gestión de prácticas
    $routes->get('practicas', 'PracticasAdminController::index');                     // Ver la lista de prácticas
    $routes->get('practicas/getDatosModal', 'PracticasAdminController::getDatosModal'); // Obtener datos para modal
    $routes->get('practicas/buscarEstudiantes', 'PracticasAdminController::buscarEstudiantes'); // Buscar estudiantes por nombre
    $routes->get('practicas/institucionesPorCarrera', 'PracticasAdminController::getInstitucionesPorCarrera'); // Instituciones con convenio por carrera
    $routes->post('practicas/crear', 'PracticasAdminController::crearPractica');      // Crear nueva práctica
    $routes->get('practicas/detalle/(:num)/(:alpha)', 'PracticasAdminController::getDetallePractica/$1/$2'); // Obtener detalle
    $routes->post('practicas/registrar-asistencia', 'PracticasAdminController::registrarAsistencia'); // Registrar asistencia
    $routes->get('practicas/generar-reporte', 'PracticasAdminController::generarReporte'); // Generar reporte
    $routes->get('practicas/exportar-datos/(:alpha)', 'PracticasAdminController::exportarDatos/$1'); // Exportar datos por formato
    $routes->get('practicas/reportes', 'PracticasAdminController::reportes'); // Vista de reportes
    
    // Rutas para la gestión de instructores
    $routes->get('instructores', 'InstructoresAdminController::index'); // Ver la lista de instructores
    $routes->get('instructores/getInstructores', 'InstructoresAdminController::getInstructores');
    $routes->get('instructores/getInstructor/(:num)', 'InstructoresAdminController::getInstructor/$1');
    $routes->post('instructores/crear', 'InstructoresAdminController::crear');
    $routes->post('instructores/actualizar/(:num)', 'InstructoresAdminController::actualizar/$1');
    $routes->delete('instructores/eliminar/(:num)', 'InstructoresAdminController::eliminar/$1');
    $routes->get('instructores/generarReporte', 'InstructoresAdminController::generarReporte');
    $routes->get('instructores/exportarExcel', 'InstructoresAdminController::exportarExcel');
    $routes->get('instructores/exportarCSV', 'InstructoresAdminController::exportarCSV');
    $routes->get('instructores/getEstadisticas', 'InstructoresAdminController::getEstadisticas');
    $routes->get('instructores/getTiposInstructores', 'InstructoresAdminController::getTiposInstructores');

    // Rutas para la gestión de empleados-instructores
    $routes->get('empleados-instructores', 'EmpleadosInstructoresAdminController::index');
    $routes->get('empleados-instructores/crear', 'EmpleadosInstructoresAdminController::create');
    $routes->post('empleados-instructores/guardar', 'EmpleadosInstructoresAdminController::store');
    $routes->get('empleados-instructores/ver/(:num)', 'EmpleadosInstructoresAdminController::show/$1');
    $routes->get('empleados-instructores/editar/(:num)', 'EmpleadosInstructoresAdminController::edit/$1');
    $routes->post('empleados-instructores/actualizar/(:num)', 'EmpleadosInstructoresAdminController::update/$1');
    $routes->get('empleados-instructores/eliminar/(:num)', 'EmpleadosInstructoresAdminController::delete/$1');
    $routes->post('empleados-instructores/verificar-empleado', 'EmpleadosInstructoresAdminController::verificarEmpleadoInstructor');
    $routes->post('empleados-instructores/instructores-empleado', 'EmpleadosInstructoresAdminController::getInstructoresEmpleado');
    $routes->post('empleados-instructores/empleados-instructor', 'EmpleadosInstructoresAdminController::getEmpleadosInstructor');

    // Rutas para la gestión de estudiantes
    $routes->get('estudiantes', 'EstudiantesAdminController::index');                     // Ver la lista de estudiantes
    
    // Rutas para la gestión de documentos
    $routes->get('documentos', 'DocumentosAdminController::index');                  // Ver la gestión de documentos
    $routes->get('documentos/servicio-comunitario', 'DocumentosAdminController::documentosServicioComunitario'); // Ver documentos de servicio comunitario   
    $routes->post('documentos/subir', 'DocumentosAdminController::subirDocumento'); // Subir documento
    $routes->get('documentos/obtener', 'DocumentosAdminController::obtenerDocumentos'); // Obtener documentos
    $routes->post('documentos/eliminar/(:num)', 'DocumentosAdminController::eliminarDocumento/$1'); // Eliminar documento
    $routes->get('documentos/descargar/(:num)', 'DocumentosAdminController::descargarDocumento/$1'); // Descargar documento
    
    // Rutas específicas para documentos de prácticas
    $routes->get('documentos/practicas', 'DocumentosPracticasAdminController::index'); // Ver documentos de prácticas
    $routes->get('documentos/practicas/obtenerDocumentos', 'DocumentosPracticasAdminController::obtenerDocumentos'); // Obtener documentos de prácticas
    $routes->get('documentos/practicas/test-datos', 'DocumentosPracticasAdminController::testDatos'); // Prueba de datos
    $routes->post('documentos/practicas/crear-tipo', 'DocumentosPracticasAdminController::crearTipo'); // Crear nuevo tipo PPR
    $routes->post('documentos/practicas/subir', 'DocumentosPracticasAdminController::store'); // Subir documento de práctica
    $routes->get('documentos/practicas/ver/(:num)', 'DocumentosPracticasAdminController::ver/$1'); // Ver documento
    $routes->get('documentos/practicas/download/(:num)', 'DocumentosPracticasAdminController::descargar/$1'); // Descargar documento
    $routes->post('documentos/practicas/eliminar/(:num)', 'DocumentosPracticasAdminController::eliminar/$1'); // Eliminar documento
    $routes->post('documentos/practicas/cambiar-estado/(:num)', 'DocumentosPracticasAdminController::cambiarEstado/$1'); // Cambiar estado
    $routes->get('documentos/practicas/reportes', 'DocumentosPracticasAdminController::reportes'); // Reportes de prácticas
    $routes->post('documentos/practicas/subir-formato', 'DocumentosPracticasAdminController::subirDocumentoFormato');
    $routes->post('documentos/practicas/eliminar-formato/(:segment)', 'DocumentosPracticasAdminController::eliminarDocumentoFormato/$1');
    
    // Rutas específicas para documentos de servicio comunitario
    $routes->get('documentos/servicio', 'DocumentosServicioComunitarioAdminController::index'); // Ver documentos de servicio
    $routes->get('documentos/servicio/obtenerDocumentos', 'DocumentosServicioComunitarioAdminController::obtenerDocumentos'); // Obtener documentos de servicio
    $routes->post('documentos/servicio/subir', 'DocumentosServicioComunitarioAdminController::store'); // Subir documento de servicio
    $routes->get('documentos/servicio/ver/(:num)', 'DocumentosServicioComunitarioAdminController::ver/$1'); // Ver documento
    $routes->get('documentos/servicio/download/(:num)', 'DocumentosServicioComunitarioAdminController::descargar/$1'); // Descargar documento
    $routes->post('documentos/servicio/eliminar/(:num)', 'DocumentosServicioComunitarioAdminController::eliminar/$1'); // Eliminar documento
    $routes->post('documentos/servicio/cambiar-estado/(:num)', 'DocumentosServicioComunitarioAdminController::cambiarEstado/$1'); // Cambiar estado
    $routes->get('documentos/servicio/reportes', 'DocumentosServicioComunitarioAdminController::reportes'); // Reportes de servicio
    $routes->post('documentos/servicio/subir-formato', 'DocumentosServicioComunitarioAdminController::subirDocumentoFormato');
    $routes->post('documentos/servicio/eliminar-formato/(:segment)', 'DocumentosServicioComunitarioAdminController::eliminarDocumentoFormato/$1');
    $routes->post('documentos/crear-carpeta', 'DocumentosAdminController::crearCarpeta'); // Crear carpeta

    
    //Rutas para la gestión de evaluaciones
    $routes->get('evaluaciones', 'EvaluacionesAdminController::index'); // Ver evaluaciones
    $routes->post('evaluaciones/agregar', 'EvaluacionesAdminController::agregarEvaluacion'); // Agregar evaluación
    $routes->get('evaluaciones/obtener', 'EvaluacionesAdminController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/obtener/(:num)', 'EvaluacionesAdminController::obtenerEvaluacion/$1'); // Obtener evaluación específica
    $routes->post('evaluaciones/actualizar/(:num)', 'EvaluacionesAdminController::actualizarEvaluacion/$1'); // Actualizar evaluación
    $routes->get('evaluaciones/cursos', 'EvaluacionesAdminController::obtenerCursos'); // Obtener cursos para evaluaciones
    $routes->post('evaluaciones/eliminar/(:num)', 'EvaluacionesAdminController::eliminarEvaluacion/$1'); // Eliminar evaluación
    $routes->post('evaluaciones/cambiar-estado/(:num)', 'EvaluacionesAdminController::cambiarEstadoEvaluacion/$1'); // Cambiar estado evaluación
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesAdminController::obtenerEstadisticas'); // Obtener estadísticas
    $routes->post('evaluaciones/filtros', 'EvaluacionesAdminController::aplicarFiltros'); // Aplicar filtros
    
    // Rutas para reportes de evaluaciones
    $routes->get('reportes-evaluaciones', 'ReportesEvaluacionesAdminController::index'); // Vista de reportes
    $routes->get('reportes-evaluaciones/pdf', 'ReportesEvaluacionesAdminController::generarPDF'); // Generar PDF
    $routes->get('reportes-evaluaciones/excel', 'ReportesEvaluacionesAdminController::exportarExcel'); // Exportar Excel
    $routes->get('reportes-evaluaciones/csv', 'ReportesEvaluacionesAdminController::exportarCSV'); // Exportar CSV
    $routes->get('reportes-evaluaciones/graficos', 'ReportesEvaluacionesAdminController::obtenerDatosGraficos'); // Datos para gráficos
    
    // Rutas para la gestión de backups
    $routes->get('backup', 'BackupAdminController::index');                         // Ver lista de backups
    $routes->post('backup/crear', 'BackupAdminController::crear');                  // Crear nuevo backup
    $routes->get('backup/detalle/(:num)', 'BackupAdminController::detalle/$1');     // Ver detalles de backup
    $routes->get('backup/logs/(:num)', 'BackupAdminController::logs/$1');           // Ver logs de un backup
    $routes->post('backup/descargar/(:num)', 'BackupAdminController::descargar/$1'); // Descargar backup
    $routes->delete('backup/eliminar/(:num)', 'BackupAdminController::eliminar/$1'); // Eliminar backup
    $routes->post('backup/restaurar/(:num)', 'BackupAdminController::restaurar/$1'); // Restaurar desde backup
    $routes->get('backup/exportar-historial', 'BackupAdminController::exportarHistorial'); // Exportar historial
    $routes->post('backup/filtrar', 'BackupAdminController::filtrar');              // Aplicar filtros
    $routes->get('backup/estadisticas', 'BackupAdminController::estadisticas');     // Obtener estadísticas

});

//----------------------------------------------------------------------------------------------------------------------
//RUTAS API PARA NOTIFICACIONES
$routes->group('notificaciones', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'NotificacionesController::index');                           // Obtener notificaciones del usuario
    $routes->get('no-leidas', 'NotificacionesController::noLeidas');               // Obtener notificaciones no leídas
    $routes->post('marcar-leida/(:num)', 'NotificacionesController::marcarLeida/$1'); // Marcar notificación como leída
    $routes->post('marcar-todas-leidas', 'NotificacionesController::marcarTodasLeidas'); // Marcar todas como leídas
    $routes->post('eliminar/(:num)', 'NotificacionesController::eliminar/$1');     // Eliminar notificación
    $routes->get('contador', 'NotificacionesController::contador');                // Obtener contador de no leídas
    $routes->get('por-tipo/(:alpha)', 'NotificacionesController::porTipo/$1');     // Obtener por tipo
});
//----------------------------------------------------------------------------------------------------------------------
//RUTAS DOCENTE
$routes->group('docente', ['namespace' => 'App\Controllers\docente'], function ($routes) {
     $routes->get('dashboard', 'DashboardDocenteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardDocenteController::index');    // El dashboard del docente
    $routes->get('perfil', 'PerfilDocenteController::index');          // Ver el perfil del docente
    $routes->post('perfil/update', 'PerfilDocenteController::update'); // Actualizar el perfil del docente
    $routes->post('perfil/upload-image', 'PerfilDocenteController::uploadImage'); // Subir imagen de perfil
    
    //Rutas para la cuenta
    $routes->get('cuenta', 'CuentaDocenteController::index');          // Ver la cuenta del docente
    $routes->post('cuenta/cambiar-password', 'CuentaDocenteController::cambiarPassword'); // Cambiar contraseña
    
    $routes->get('educacion', 'ActividadesEducacionDocenteController::index');    // Ver la sección de educación
    
    // Rutas para actividades educativas del docente
    $routes->get('actividades-educacion', 'ActividadesEducacionDocenteController::index');    // Ver actividades educativas
    $routes->get('actividades-educacion/crear', 'ActividadesEducacionDocenteController::create');    // Crear actividad
    $routes->post('actividades-educacion/guardar', 'ActividadesEducacionDocenteController::store');    // Guardar actividad
    $routes->get('actividades-educacion/ver/(:num)', 'ActividadesEducacionDocenteController::show/$1');    // Ver actividad
    $routes->get('actividades-educacion/editar/(:num)', 'ActividadesEducacionDocenteController::edit/$1');    // Editar actividad
    $routes->post('actividades-educacion/actualizar/(:num)', 'ActividadesEducacionDocenteController::update/$1');    // Actualizar actividad
    $routes->get('actividades-educacion/eliminar/(:num)', 'ActividadesEducacionDocenteController::delete/$1');    // Eliminar actividad
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionDocenteController::calendario');    // Calendario de actividades
    $routes->get('actividades-educacion/api/actividades', 'ActividadesEducacionDocenteController::getActividades');    // API actividades
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionDocenteController::getEstadisticas');    // API estadísticas
    $routes->get('actividades-educacion/reportes', 'ActividadesEducacionDocenteController::reportes');    // Vista de reportes
    $routes->get('actividades-educacion/exportar/pdf', 'ActividadesEducacionDocenteController::exportarPDF');
    $routes->get('actividades-educacion/exportar/excel', 'ActividadesEducacionDocenteController::exportarExcel');
    $routes->get('actividades-educacion/exportar/csv', 'ActividadesEducacionDocenteController::exportarCSV');
    $routes->get('actividades-educacion/participantes/(:num)', 'ActividadesEducacionDocenteController::participantes/$1');    // Gestionar participantes
    $routes->post('actividades-educacion/participantes/agregar', 'ActividadesEducacionDocenteController::agregarParticipante');
    $routes->post('actividades-educacion/participantes/quitar', 'ActividadesEducacionDocenteController::quitarParticipante');
    $routes->get('actividades-educacion/test-insert', 'ActividadesEducacionDocenteController::testInsert');    // Prueba de inserción
    
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
    
    // Rutas para notificaciones
    $routes->get('notificaciones', 'NotificacionesController::vistaDocente');    // Ver notificaciones del docente
    
    // Rutas para evaluaciones
    $routes->get('evaluaciones', 'EvaluacionesDocenteController::index');        // Ver evaluaciones del docente
    $routes->get('evaluaciones/obtener', 'EvaluacionesDocenteController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesDocenteController::obtenerEstadisticas'); // Obtener estadísticas
    
    // Rutas para prácticas (tutorías)
    $routes->get('practicas', 'PracticasDocenteController::index');              // Ver prácticas del docente
    $routes->get('practicas/detalle-estudiante/(:num)', 'PracticasDocenteController::detalleEstudiante/$1'); // Detalle de estudiante
    $routes->post('practicas/evaluar-estudiante', 'PracticasDocenteController::evaluarEstudiante'); // Evaluar estudiante
    $routes->post('practicas/generar-reporte', 'PracticasDocenteController::generarReporte'); // Generar reporte
    $routes->get('practicas/alertas', 'PracticasDocenteController::obtenerAlertas'); // Obtener alertas
    $routes->get('practicas/calendario', 'PracticasDocenteController::calendario'); // Calendario de prácticas
    $routes->get('actividades', 'ActividadesDocenteController::index');          // Ver actividades del docente
    $routes->get('estudiantes', 'EstudiantesDocenteController::index');          // Ver estudiantes del docente
});

//----------------------------------------------------------------------------------------------------------------------
//RUTAS ESTUDIANTE
$routes->group('estudiante', ['namespace' => 'App\Controllers\estudiante'], function ($routes) {
     $routes->get('dashboard', 'DashboardEstudianteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardEstudianteController::index');    // El dashboard del estudiante
    $routes->get('perfil', 'PerfilEstudianteController::index');          // Ver el perfil del estudiante
    $routes->post('perfil/update', 'PerfilEstudianteController::update'); // Actualizar el perfil del estudiante
    $routes->post('perfil/upload-image', 'PerfilEstudianteController::uploadImage'); // Subir imagen de perfil
    
    //Rutas para la cuenta
    $routes->get('cuenta', 'CuentaEstudianteController::index');          // Ver la cuenta del estudiante
    $routes->post('cuenta/cambiar-password', 'CuentaEstudianteController::cambiarPassword'); // Cambiar contraseña
    
    $routes->get('educacion', 'ActividadesEducacionEstudianteController::index');    // Ver la sección de educación (Mis cursos)
    $routes->get('actividades-educacion/detalle/(:num)', 'ActividadesEducacionEstudianteController::detalle/$1');
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionEstudianteController::calendario');
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionEstudianteController::getEstadisticas');
    $routes->get('evaluaciones', 'EvaluacionesEstudianteController::index');        // Ver evaluaciones
    $routes->get('evaluaciones/obtener', 'EvaluacionesEstudianteController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesEstudianteController::obtenerEstadisticas'); // Estadísticas
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
    
    // Rutas para prácticas (más específicas primero)
    $routes->get('practicas/servicio-comunitario/formatos', 'PracticasEstudianteController::formatosServicioComunitario');
    $routes->get('practicas/formatos', 'PracticasEstudianteController::formatos');
    $routes->get('practicas/formatos/descargar/(:segment)', 'PracticasEstudianteController::descargarFormatoPracticas/$1');
    $routes->get('practicas/servicio-comunitario/formatos/descargar/(:segment)', 'PracticasEstudianteController::descargarFormatoServicio/$1');
    $routes->get('practicas', 'PracticasEstudianteController::index'); // Prácticas preprofesionales
    $routes->get('practicas/servicio-comunitario', 'PracticasEstudianteController::servicioComunitario'); // Prácticas de servicio comunitario
    $routes->get('practicas/detalle/(:num)/(:alpha)', 'PracticasEstudianteController::detalle/$1/$2'); // Detalle de práctica
    $routes->post('practicas/registrar-actividad', 'PracticasEstudianteController::registrarActividad'); // Registrar actividad
    $routes->post('practicas/registrar-asistencia', 'PracticasEstudianteController::registrarAsistencia'); // Registrar asistencia (estudiante)
    $routes->post('practicas/subir-documento', 'PracticasEstudianteController::subirDocumento'); // Subir documento
    $routes->get('practicas/actividades/(:num)/(:alpha)', 'PracticasEstudianteController::obtenerActividades/$1/$2'); // Obtener actividades
    
    // Rutas para documentos de prácticas
    $routes->get('documentos-practicas', 'DocumentosPracticasEstudianteController::index'); // Ver documentos de prácticas
    $routes->post('documentos-practicas/subir', 'DocumentosPracticasEstudianteController::subirDocumento'); // Subir documento
    $routes->get('documentos-practicas/mis-documentos', 'DocumentosPracticasEstudianteController::misDocumentos'); // Mis documentos
    $routes->get('documentos-practicas/progreso', 'DocumentosPracticasEstudianteController::verProgreso'); // Ver progreso
    $routes->get('documentos-practicas/descargar/(:num)', 'DocumentosPracticasEstudianteController::descargarDocumento/$1'); // Descargar documento
    $routes->post('documentos-practicas/eliminar/(:num)', 'DocumentosPracticasEstudianteController::eliminarDocumento/$1'); // Eliminar documento

    // Rutas para documentos de servicio comunitario (estudiante)
    $routes->post('documentos-servicio-comunitario/subir', 'PracticasEstudianteController::subirDocumentoServicioComunitario');
    $routes->get('documentos-servicio-comunitario/descargar/(:num)', 'PracticasEstudianteController::descargarDocumentoServicioComunitario/$1');
    $routes->post('documentos-servicio-comunitario/eliminar/(:num)', 'PracticasEstudianteController::eliminarDocumentoServicioComunitario/$1');
    
    // Rutas para notificaciones
    $routes->get('notificaciones', 'NotificacionesController::vistaEstudiante');    // Ver notificaciones del estudiante
});
