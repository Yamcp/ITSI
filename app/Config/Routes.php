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
// RUTAS COORDINACIÓN / VINCULACIÓN
$routes->group('coord', ['namespace' => 'App\Controllers\coord'], function ($routes) {
     $routes->get('dashboard', 'DashboardCoordController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardCoordController::index');    // El dashboard del coordinador
    
    //Rutas para el perfil
    $routes->get('perfil', 'PerfilCoordController::index');          // Ver el perfil del coordinador
    $routes->post('perfil/update', 'PerfilCoordController::update'); // Actualizar el perfil del coordinador
    $routes->post('perfil/upload-image', 'PerfilCoordController::uploadImage'); // Subir imagen de perfil
    $routes->post('perfil/delete-image', 'PerfilCoordController::deleteImage'); // Eliminar imagen de perfil
    
    //Rutas para la cuenta
        $routes->get('cuenta', 'CuentaCoordController::index');          // Ver la cuenta del coordinador
    $routes->post('cuenta/cambiar-password', 'CuentaCoordController::cambiarPassword'); // Cambiar contraseña
    $routes->get('educacion', 'ActividadesEducacionCoordController::index');    // Ver la sección de educación
    
    //Rutas para la gestión de actividades educativas
    $routes->get('actividades-educacion', 'ActividadesEducacionCoordController::index');    // Ver actividades educativas
    $routes->get('actividades-educacion/crear', 'ActividadesEducacionCoordController::create');    // Crear actividad
    $routes->post('actividades-educacion/guardar', 'ActividadesEducacionCoordController::store');    // Guardar actividad
    $routes->get('actividades-educacion/ver/(:num)', 'ActividadesEducacionCoordController::show/$1');    // Ver actividad
    $routes->get('actividades-educacion/editar/(:num)', 'ActividadesEducacionCoordController::edit/$1');    // Editar actividad
    $routes->post('actividades-educacion/actualizar/(:num)', 'ActividadesEducacionCoordController::update/$1');    // Actualizar actividad
    $routes->get('actividades-educacion/eliminar/(:num)', 'ActividadesEducacionCoordController::delete/$1');    // Eliminar actividad
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionCoordController::calendario');    // Calendario de actividades
    $routes->get('actividades-educacion/api/actividades', 'ActividadesEducacionCoordController::getActividades');    // API actividades
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionCoordController::getEstadisticas');    // API estadísticas
    
    // Rutas para reportes de actividades educativas
    $routes->get('actividades-educacion/reportes', 'ActividadesEducacionCoordController::reportes');    // Vista de reportes
    $routes->get('actividades-educacion/exportar/pdf', 'ActividadesEducacionCoordController::exportarPDF');    // Exportar PDF
    $routes->get('actividades-educacion/exportar/excel', 'ActividadesEducacionCoordController::exportarExcel');    // Exportar Excel
    $routes->get('actividades-educacion/exportar/csv', 'ActividadesEducacionCoordController::exportarCSV');    // Exportar CSV
   
    //Rutas para la gestión de convenios	
    $routes->get('convenios', 'ConveniosCoordController::index');        // Ver la sección de convenios
    $routes->post('convenios/store', 'ConveniosCoordController::store'); // Guardar nuevo convenio
    $routes->post('convenios/storeInstitucion', 'ConveniosCoordController::storeInstitucion'); // Guardar nueva institución
    $routes->post('convenios/actualizarPlazas/(:num)', 'ConveniosCoordController::actualizarPlazas/$1'); // Actualizar plazas disponibles
    $routes->get('convenios/getConvenio/(:num)', 'ConveniosCoordController::getConvenio/$1'); // Obtener un convenio (edición)
    $routes->post('convenios/update/(:num)', 'ConveniosCoordController::update/$1'); // Actualizar convenio
    $routes->get('convenios/getInstituciones', 'ConveniosCoordController::getInstituciones'); // Obtener instituciones
    $routes->get('convenios/getConvenios', 'ConveniosCoordController::getConvenios'); // Obtener convenios
    $routes->get('convenios/generarReporte', 'ConveniosCoordController::generarReporte'); // Generar reporte
    $routes->get('convenios/vencimientos', 'ConveniosCoordController::vencimientos');
    $routes->get('convenios/reportes', 'ConveniosCoordController::reportes'); // Ver convenios por vencer

    //Rutas para la gestión de prácticas
    $routes->get('practicas', 'PracticasCoordController::index');                     // Ver la lista de prácticas
    $routes->get('practicas/getDatosModal', 'PracticasCoordController::getDatosModal'); // Obtener datos para modal
    $routes->get('practicas/buscarEstudiantes', 'PracticasCoordController::buscarEstudiantes'); // Buscar estudiantes por nombre
    $routes->get('practicas/institucionesPorCarrera', 'PracticasCoordController::getInstitucionesPorCarrera'); // Instituciones con convenio por carrera
    $routes->post('practicas/crear', 'PracticasCoordController::crearPractica');      // Crear nueva práctica
    $routes->get('practicas/detalle/(:num)/(:alpha)', 'PracticasCoordController::getDetallePractica/$1/$2'); // Obtener detalle
    $routes->post('practicas/registrar-asistencia', 'PracticasCoordController::registrarAsistencia'); // Registrar asistencia
    $routes->get('practicas/generar-reporte', 'PracticasCoordController::generarReporte'); // Generar reporte
    $routes->get('practicas/exportar-datos/(:alpha)', 'PracticasCoordController::exportarDatos/$1'); // Exportar datos por formato
    $routes->get('practicas/reportes', 'PracticasCoordController::reportes'); // Vista de reportes
    
    // Rutas para la gestión de instructores
    $routes->get('instructores', 'InstructoresCoordController::index'); // Ver la lista de instructores
    $routes->get('docentes', 'InstructoresCoordController::docentes'); // Listado de docentes/tutores internos
    $routes->get('instructores/getInstructores', 'InstructoresCoordController::getInstructores');
    $routes->get('instructores/getInstructor/(:num)', 'InstructoresCoordController::getInstructor/$1');
    $routes->post('instructores/crear', 'InstructoresCoordController::crear');
    $routes->post('instructores/actualizar/(:num)', 'InstructoresCoordController::actualizar/$1');
    $routes->delete('instructores/eliminar/(:num)', 'InstructoresCoordController::eliminar/$1');
    $routes->get('instructores/generarReporte', 'InstructoresCoordController::generarReporte');
    $routes->get('instructores/exportarExcel', 'InstructoresCoordController::exportarExcel');
    $routes->get('instructores/exportarCSV', 'InstructoresCoordController::exportarCSV');
    $routes->get('instructores/getEstadisticas', 'InstructoresCoordController::getEstadisticas');
    $routes->get('instructores/getTiposInstructores', 'InstructoresCoordController::getTiposInstructores');

    // Rutas para la gestión de empleados-instructores
    $routes->get('empleados-instructores', 'EmpleadosInstructoresCoordController::index');
    $routes->get('empleados-instructores/crear', 'EmpleadosInstructoresCoordController::create');
    $routes->post('empleados-instructores/guardar', 'EmpleadosInstructoresCoordController::store');
    $routes->get('empleados-instructores/ver/(:num)', 'EmpleadosInstructoresCoordController::show/$1');
    $routes->get('empleados-instructores/editar/(:num)', 'EmpleadosInstructoresCoordController::edit/$1');
    $routes->post('empleados-instructores/actualizar/(:num)', 'EmpleadosInstructoresCoordController::update/$1');
    $routes->get('empleados-instructores/eliminar/(:num)', 'EmpleadosInstructoresCoordController::delete/$1');
    $routes->post('empleados-instructores/verificar-empleado', 'EmpleadosInstructoresCoordController::verificarEmpleadoInstructor');
    $routes->post('empleados-instructores/instructores-empleado', 'EmpleadosInstructoresCoordController::getInstructoresEmpleado');
    $routes->post('empleados-instructores/empleados-instructor', 'EmpleadosInstructoresCoordController::getEmpleadosInstructor');

    // Rutas para la gestión de estudiantes
    $routes->get('estudiantes', 'EstudiantesCoordController::index');                     // Ver la lista de estudiantes
    
    // Rutas para la gestión de documentos
    $routes->get('documentos', 'DocumentosCoordController::index');                  // Ver la gestión de documentos
    $routes->get('documentos/servicio-comunitario', 'DocumentosCoordController::documentosServicioComunitario'); // Ver documentos de servicio comunitario   
    $routes->post('documentos/subir', 'DocumentosCoordController::subirDocumento'); // Subir documento
    $routes->get('documentos/obtener', 'DocumentosCoordController::obtenerDocumentos'); // Obtener documentos
    $routes->post('documentos/eliminar/(:num)', 'DocumentosCoordController::eliminarDocumento/$1'); // Eliminar documento
    $routes->get('documentos/descargar/(:num)', 'DocumentosCoordController::descargarDocumento/$1'); // Descargar documento
    
    // Rutas específicas para documentos de prácticas
    $routes->get('documentos/practicas', 'DocumentosPracticasCoordController::index'); // Ver documentos de prácticas
    $routes->get('documentos/practicas/obtenerDocumentos', 'DocumentosPracticasCoordController::obtenerDocumentos'); // Obtener documentos de prácticas
    $routes->get('documentos/practicas/test-datos', 'DocumentosPracticasCoordController::testDatos'); // Prueba de datos
    $routes->post('documentos/practicas/crear-tipo', 'DocumentosPracticasCoordController::crearTipo'); // Crear nuevo tipo PPR
    $routes->post('documentos/practicas/actualizar-tipo/(:num)', 'DocumentosPracticasCoordController::actualizarTipo/$1'); // Editar tipo PPR (descripción, etc.)
    $routes->post('documentos/practicas/subir', 'DocumentosPracticasCoordController::store'); // Subir documento de práctica
    $routes->get('documentos/practicas/ver/(:num)', 'DocumentosPracticasCoordController::ver/$1'); // Ver documento
    $routes->get('documentos/practicas/download/(:num)', 'DocumentosPracticasCoordController::descargar/$1'); // Descargar documento
    $routes->post('documentos/practicas/eliminar/(:num)', 'DocumentosPracticasCoordController::eliminar/$1'); // Eliminar documento
    $routes->post('documentos/practicas/cambiar-estado/(:num)', 'DocumentosPracticasCoordController::cambiarEstado/$1'); // Cambiar estado
    $routes->get('documentos/practicas/reportes', 'DocumentosPracticasCoordController::reportes'); // Reportes de prácticas
    $routes->post('documentos/practicas/subir-formato', 'DocumentosPracticasCoordController::subirDocumentoFormato');
    $routes->post('documentos/practicas/actualizar-nombre-formato', 'DocumentosPracticasCoordController::actualizarNombreDocumentoFormato');
    $routes->post('documentos/practicas/eliminar-formato/(:segment)', 'DocumentosPracticasCoordController::eliminarDocumentoFormato/$1');
    
    // Rutas específicas para documentos de servicio comunitario
    $routes->get('documentos/servicio', 'DocumentosServicioComunitarioCoordController::index'); // Ver documentos de servicio
    $routes->get('documentos/servicio/obtenerDocumentos', 'DocumentosServicioComunitarioCoordController::obtenerDocumentos'); // Obtener documentos de servicio
    $routes->post('documentos/servicio/subir', 'DocumentosServicioComunitarioCoordController::store'); // Subir documento de servicio
    $routes->get('documentos/servicio/ver/(:num)', 'DocumentosServicioComunitarioCoordController::ver/$1'); // Ver documento
    $routes->get('documentos/servicio/download/(:num)', 'DocumentosServicioComunitarioCoordController::descargar/$1'); // Descargar documento
    $routes->post('documentos/servicio/eliminar/(:num)', 'DocumentosServicioComunitarioCoordController::eliminar/$1'); // Eliminar documento
    $routes->post('documentos/servicio/cambiar-estado/(:num)', 'DocumentosServicioComunitarioCoordController::cambiarEstado/$1'); // Cambiar estado
    $routes->get('documentos/servicio/reportes', 'DocumentosServicioComunitarioCoordController::reportes'); // Reportes de servicio
    $routes->post('documentos/servicio/subir-formato', 'DocumentosServicioComunitarioCoordController::subirDocumentoFormato');
    $routes->post('documentos/servicio/actualizar-nombre-formato', 'DocumentosServicioComunitarioCoordController::actualizarNombreDocumentoFormato');
    $routes->post('documentos/servicio/eliminar-formato/(:segment)', 'DocumentosServicioComunitarioCoordController::eliminarDocumentoFormato/$1');
    $routes->post('documentos/servicio/crear-tipo', 'DocumentosServicioComunitarioCoordController::crearTipo');
    $routes->post('documentos/servicio/actualizar-tipo/(:num)', 'DocumentosServicioComunitarioCoordController::actualizarTipo/$1');
    $routes->post('documentos/crear-carpeta', 'DocumentosCoordController::crearCarpeta'); // Crear carpeta
    
    //Rutas para la gestión de evaluaciones
    $routes->get('evaluaciones', 'EvaluacionesCoordController::index'); // Ver evaluaciones
    $routes->post('evaluaciones/agregar', 'EvaluacionesCoordController::agregarEvaluacion'); // Agregar evaluación
    $routes->get('evaluaciones/obtener', 'EvaluacionesCoordController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/obtener/(:num)', 'EvaluacionesCoordController::obtenerEvaluacion/$1'); // Obtener evaluación específica
    $routes->post('evaluaciones/actualizar/(:num)', 'EvaluacionesCoordController::actualizarEvaluacion/$1'); // Actualizar evaluación
    $routes->get('evaluaciones/cursos', 'EvaluacionesCoordController::obtenerCursos'); // Obtener cursos para evaluaciones
    $routes->post('evaluaciones/eliminar/(:num)', 'EvaluacionesCoordController::eliminarEvaluacion/$1'); // Eliminar evaluación
    $routes->post('evaluaciones/cambiar-estado/(:num)', 'EvaluacionesCoordController::cambiarEstadoEvaluacion/$1'); // Cambiar estado evaluación
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesCoordController::obtenerEstadisticas'); // Obtener estadísticas
    $routes->post('evaluaciones/filtros', 'EvaluacionesCoordController::aplicarFiltros'); // Aplicar filtros
    
    // Rutas para reportes de evaluaciones
    $routes->get('reportes-evaluaciones', 'ReportesEvaluacionesCoordController::index'); // Vista de reportes
    $routes->get('reportes-evaluaciones/pdf', 'ReportesEvaluacionesCoordController::generarPDF'); // Generar PDF
    $routes->get('reportes-evaluaciones/excel', 'ReportesEvaluacionesCoordController::exportarExcel'); // Exportar Excel
    $routes->get('reportes-evaluaciones/csv', 'ReportesEvaluacionesCoordController::exportarCSV'); // Exportar CSV
    $routes->get('reportes-evaluaciones/graficos', 'ReportesEvaluacionesCoordController::obtenerDatosGraficos'); // Datos para gráficos
    
    // Rutas para la gestión de backups
    $routes->get('backup', 'BackupCoordController::index');                         // Ver lista de backups
    $routes->post('backup/crear', 'BackupCoordController::crear');                  // Crear nuevo backup
    $routes->get('backup/detalle/(:num)', 'BackupCoordController::detalle/$1');     // Ver detalles de backup
    $routes->get('backup/logs/(:num)', 'BackupCoordController::logs/$1');           // Ver logs de un backup
    $routes->post('backup/descargar/(:num)', 'BackupCoordController::descargar/$1'); // Descargar backup
    $routes->delete('backup/eliminar/(:num)', 'BackupCoordController::eliminar/$1'); // Eliminar backup
    $routes->post('backup/restaurar/(:num)', 'BackupCoordController::restaurar/$1'); // Restaurar desde backup
    $routes->get('backup/exportar-historial', 'BackupCoordController::exportarHistorial'); // Exportar historial
    $routes->post('backup/filtrar', 'BackupCoordController::filtrar');              // Aplicar filtros
    $routes->get('backup/estadisticas', 'BackupCoordController::estadisticas');     // Obtener estadísticas

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
  
    //Rutas para la educación continua
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
    $routes->get('actividades-educacion/api/encuestas-satisfaccion', 'ActividadesEducacionDocenteController::apiEncuestasSatisfaccion');    // API enlaces satisfacción
    $routes->get('actividades-educacion/reportes', 'ActividadesEducacionDocenteController::reportes');    // Vista de reportes
    $routes->get('actividades-educacion/exportar/pdf', 'ActividadesEducacionDocenteController::exportarPDF');
    $routes->get('actividades-educacion/exportar/excel', 'ActividadesEducacionDocenteController::exportarExcel');
    $routes->get('actividades-educacion/exportar/csv', 'ActividadesEducacionDocenteController::exportarCSV');
    $routes->get('actividades-educacion/participantes/(:num)', 'ActividadesEducacionDocenteController::participantes/$1');    // Gestionar participantes
    $routes->post('actividades-educacion/participantes/agregar', 'ActividadesEducacionDocenteController::agregarParticipante');
    $routes->post('actividades-educacion/participantes/quitar', 'ActividadesEducacionDocenteController::quitarParticipante');
    $routes->get('actividades-educacion/test-insert', 'ActividadesEducacionDocenteController::testInsert');    // Prueba de inserción
    
    //Rutas para la gestión de convenios
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
    
    // Notificaciones del docente integradas en Prácticas; URL antigua redirige allí
    $routes->get('notificaciones', static function () {
        return redirect()->to('docente/practicas?ver=notificaciones');
    });
    
    // Rutas para evaluaciones
    $routes->get('evaluaciones', 'EvaluacionesDocenteController::index');        // Ver evaluaciones del docente
    $routes->get('evaluaciones/obtener', 'EvaluacionesDocenteController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesDocenteController::obtenerEstadisticas'); // Obtener estadísticas
    
    // Rutas para prácticas (tutorías)
    $routes->get('practicas', 'PracticasDocenteController::index');              // Ver prácticas del docente
    $routes->get('practicas/detalle-estudiante/(:num)', 'PracticasDocenteController::detalleEstudiante/$1'); // Detalle de estudiante
    $routes->get('practicas/alertas', 'PracticasDocenteController::obtenerAlertas'); // Obtener alertas
    $routes->get('practicas/calendario', 'PracticasDocenteController::calendario'); // Calendario de prácticas
    $routes->post('practicas/generar-reporte', 'PracticasDocenteController::generarReporte'); // Generar reporte (acción rápida)
    $routes->get('actividades', 'ActividadesDocenteController::index');          // Ver actividades del docente
    $routes->get('estudiantes', 'EstudiantesDocenteController::index');          // Ver estudiantes del docente
});

//----------------------------------------------------------------------------------------------------------------------
//RUTAS ESTUDIANTE
$routes->group('estudiante', ['namespace' => 'App\Controllers\estudiante', 'filter' => 'estudiante_asistencia'], function ($routes) {
     $routes->get('dashboard', 'DashboardEstudianteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardEstudianteController::index');    // El dashboard del estudiante
    $routes->get('perfil', 'PerfilEstudianteController::index');          // Ver el perfil del estudiante
    $routes->post('perfil/update', 'PerfilEstudianteController::update'); // Actualizar el perfil del estudiante
    $routes->post('perfil/upload-image', 'PerfilEstudianteController::uploadImage'); // Subir imagen de perfil
    
    //Rutas para la cuenta
    $routes->get('cuenta', 'CuentaEstudianteController::index');          // Ver la cuenta del estudiante
    $routes->post('cuenta/cambiar-password', 'CuentaEstudianteController::cambiarPassword'); // Cambiar contraseña
    
    //Rutas para la educación continua
    $routes->get('educacion', 'ActividadesEducacionEstudianteController::index');    // Ver la sección de educación (Mis cursos)
    $routes->get('actividades-educacion/detalle/(:num)', 'ActividadesEducacionEstudianteController::detalle/$1');
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionEstudianteController::calendario');
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionEstudianteController::getEstadisticas');
    $routes->get('actividades-educacion/api/encuestas-satisfaccion', 'ActividadesEducacionEstudianteController::apiEncuestasSatisfaccion'); // API enlaces satisfacción
    $routes->post('actividades-educacion/inscribirse', 'ActividadesEducacionEstudianteController::inscribirse'); // Autoinscripción del estudiante
    $routes->get('evaluaciones', 'EvaluacionesEstudianteController::index');        // Ver evaluaciones
    $routes->get('evaluaciones/obtener', 'EvaluacionesEstudianteController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesEstudianteController::obtenerEstadisticas'); // Estadísticas
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
    
    // Rutas para prácticas (más específicas primero)
    $routes->get('practicas/servicio-comunitario/formatos/descargar/(:segment)', 'PracticasEstudianteController::descargarFormatoServicio/$1');
    $routes->get('practicas/servicio-comunitario/formatos', 'PracticasEstudianteController::formatosServicioComunitario');
    $routes->get('practicas/formatos', 'PracticasEstudianteController::formatos');
    $routes->get('practicas/formatos/descargar/(:segment)', 'PracticasEstudianteController::descargarFormatoPracticas/$1');
    $routes->get('practicas', 'PracticasEstudianteController::index'); // Prácticas preprofesionales
    $routes->get('practicas/detalle/(:num)/(:alpha)', 'PracticasEstudianteController::detalle/$1/$2'); // Detalle de práctica
    $routes->post('practicas/registrar-actividad', 'PracticasEstudianteController::registrarActividad'); // Registrar actividad
    $routes->post('practicas/registrar-asistencia', 'PracticasEstudianteController::registrarAsistencia'); // Registrar asistencia (estudiante)
    $routes->post('practicas/subir-documento', 'PracticasEstudianteController::subirDocumento'); // Subir documento
    $routes->get('practicas/actividades/(:num)/(:alpha)', 'PracticasEstudianteController::obtenerActividades/$1/$2'); // Obtener actividades
    
    // Rutas para documentos de prácticas
    $routes->get('documentos-practicas', 'DocumentosPracticasEstudianteController::index'); // Ver documentos de prácticas
    $routes->get('documentos-servicio-comunitario', 'DocumentosServicioComunitarioEstudianteController::index'); // Documentos PSC
    $routes->post('documentos-practicas/subir', 'DocumentosPracticasEstudianteController::subirDocumento'); // Subir documento
    $routes->get('documentos-practicas/mis-documentos', 'DocumentosPracticasEstudianteController::misDocumentos'); // Mis documentos
    $routes->get('documentos-practicas/progreso', 'DocumentosPracticasEstudianteController::verProgreso'); // Ver progreso
    $routes->get('documentos-practicas/descargar/(:num)', 'DocumentosPracticasEstudianteController::descargarDocumento/$1'); // Descargar documento
    $routes->post('documentos-practicas/eliminar/(:num)', 'DocumentosPracticasEstudianteController::eliminarDocumento/$1'); // Eliminar documento

    // Rutas para documentos de servicio comunitario (estudiante)
    $routes->post('documentos-servicio-comunitario/subir', 'PracticasEstudianteController::subirDocumentoServicioComunitario');
    $routes->get('documentos-servicio-comunitario/descargar/(:num)', 'PracticasEstudianteController::descargarDocumentoServicioComunitario/$1');
    $routes->post('documentos-servicio-comunitario/eliminar/(:num)', 'PracticasEstudianteController::eliminarDocumentoServicioComunitario/$1');
    
    // Rutas para notificaciones (controlador en App\Controllers, no en subcarpeta estudiante)
    $routes->get('notificaciones', '\App\Controllers\NotificacionesController::vistaEstudiante');    // Ver notificaciones del estudiante
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