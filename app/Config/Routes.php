<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');                           // Página principal de login
$routes->post('/auth/autenticar', 'AuthController::autenticar');     // Acción de autenticar al usuario
$routes->get('/auth/cerrar-sesion', 'AuthController::cerrarSesion'); // Acción para cerrar sesión

// Rutas para instructores (AJAX)
$routes->get('instructores/getInstructores', 'admin\InstructoresAdminController::getInstructores');
$routes->get('instructores/getInstructor/(:num)', 'admin\InstructoresAdminController::getInstructor/$1');
$routes->post('instructores/crear', 'admin\InstructoresAdminController::crear');
$routes->post('instructores/actualizar/(:num)', 'admin\InstructoresAdminController::actualizar/$1');
$routes->delete('instructores/eliminar/(:num)', 'admin\InstructoresAdminController::eliminar/$1');
$routes->get('instructores/generarReporte', 'admin\InstructoresAdminController::generarReporte');
$routes->get('instructores/exportarExcel', 'admin\InstructoresAdminController::exportarExcel');
$routes->get('instructores/exportarCSV', 'admin\InstructoresAdminController::exportarCSV');
$routes->get('instructores/getEstadisticas', 'admin\InstructoresAdminController::getEstadisticas');
$routes->get('instructores/getTiposInstructores', 'admin\InstructoresAdminController::getTiposInstructores');

//----------------------------------------------------------------------------------------------------------------------
//RUTAS ADMINISTRADOR
$routes->group('admin', ['namespace' => 'App\Controllers\admin'], function ($routes) {
     $routes->get('dashboard', 'DashboardAdminController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardAdminController::index');    // El dashboard del administrador
    $routes->get('perfil', 'PerfilAdminController::index');          // Ver el perfil del administrador
    $routes->post('perfil/update', 'PerfilAdminController::update'); // Actualizar el perfil del administrador
    $routes->get('cuenta', 'CuentaAdminController::index');          // Ver la cuenta del administrador
    $routes->post('cuenta/cambiar-password', 'CuentaAdminController::cambiarPassword'); // Cambiar contraseña
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('actividades-educacion', 'ActividadesEducacionController::index');    // Ver actividades educativas
    $routes->get('actividades-educacion/crear', 'ActividadesEducacionController::create');    // Crear actividad
    $routes->post('actividades-educacion/guardar', 'ActividadesEducacionController::store');    // Guardar actividad
    $routes->get('actividades-educacion/ver/(:num)', 'ActividadesEducacionController::show/$1');    // Ver actividad
    $routes->get('actividades-educacion/editar/(:num)', 'ActividadesEducacionController::edit/$1');    // Editar actividad
    $routes->post('actividades-educacion/actualizar/(:num)', 'ActividadesEducacionController::update/$1');    // Actualizar actividad
    $routes->get('actividades-educacion/eliminar/(:num)', 'ActividadesEducacionController::delete/$1');    // Eliminar actividad
    $routes->get('actividades-educacion/calendario', 'ActividadesEducacionController::calendario');    // Calendario de actividades
    $routes->get('actividades-educacion/api/actividades', 'ActividadesEducacionController::getActividades');    // API actividades
    $routes->get('actividades-educacion/api/estadisticas', 'ActividadesEducacionController::getEstadisticas');    // API estadísticas
    
    // Rutas para reportes de actividades educativas
    $routes->get('actividades-educacion/reportes', 'ActividadesEducacionController::reportes');    // Vista de reportes
    $routes->get('actividades-educacion/exportar/pdf', 'ActividadesEducacionController::exportarPDF');    // Exportar PDF
    $routes->get('actividades-educacion/exportar/excel', 'ActividadesEducacionController::exportarExcel');    // Exportar Excel
    $routes->get('actividades-educacion/exportar/csv', 'ActividadesEducacionController::exportarCSV');    // Exportar CSV
   
    //Rutas para la gestión de convenios	
    $routes->get('convenios', 'ConveniosAdminController::index');        // Ver la sección de convenios
    $routes->post('convenios/store', 'ConveniosAdminController::store'); // Guardar nuevo convenio
    $routes->post('convenios/storeInstitucion', 'ConveniosAdminController::storeInstitucion'); // Guardar nueva institución
    $routes->get('convenios/getInstituciones', 'ConveniosAdminController::getInstituciones'); // Obtener instituciones
    $routes->get('convenios/getConvenios', 'ConveniosAdminController::getConvenios'); // Obtener convenios
    $routes->get('convenios/generarReporte', 'ConveniosAdminController::generarReporte'); // Generar reporte
    $routes->get('convenios/vencimientos', 'ConveniosAdminController::vencimientos');
    $routes->get('convenios/reportes', 'ConveniosAdminController::reportes'); // Ver convenios por vencer

    //Rutas para la gestión de prácticas
    $routes->get('practicas', 'PracticasAdminController::index');                     // Ver la lista de prácticas
    
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

    // Rutas para la gestión de estudiantes
    $routes->get('estudiantes', 'EstudiantesController::index');                     // Ver la lista de estudiantes
    
    // Rutas para la gestión de documentos
    $routes->get('documentos', 'DocumentosAdminController::index');                  // Ver la gestión de documentos
    $routes->get('documentos/practicas', 'DocumentosAdminController::documentosPracticas'); // Ver documentos de prácticas
    $routes->get('documentos/servicio-comunitario', 'DocumentosAdminController::documentosServicioComunitario'); // Ver documentos de servicio comunitario   
    $routes->post('documentos/subir', 'DocumentosAdminController::subirDocumento'); // Subir documento
    $routes->get('documentos/obtener', 'DocumentosAdminController::obtenerDocumentos'); // Obtener documentos
    $routes->post('documentos/eliminar/(:num)', 'DocumentosAdminController::eliminarDocumento/$1'); // Eliminar documento
    $routes->get('documentos/descargar/(:num)', 'DocumentosAdminController::descargarDocumento/$1'); // Descargar documento
    $routes->post('documentos/crear-carpeta', 'DocumentosAdminController::crearCarpeta'); // Crear carpeta
    
    //Rutas para la gestión de evaluaciones
    $routes->get('evaluaciones', 'EvaluacionesAdminController::index'); // Ver evaluaciones
    $routes->post('evaluaciones/agregar', 'EvaluacionesAdminController::agregarEvaluacion'); // Agregar evaluación
    $routes->get('evaluaciones/obtener', 'EvaluacionesAdminController::obtenerEvaluaciones'); // Obtener evaluaciones
    $routes->get('evaluaciones/cursos', 'EvaluacionesAdminController::obtenerCursos'); // Obtener cursos para evaluaciones
    $routes->post('evaluaciones/eliminar/(:num)', 'EvaluacionesAdminController::eliminarEvaluacion/$1'); // Eliminar evaluación
    $routes->post('evaluaciones/cambiar-estado/(:num)', 'EvaluacionesAdminController::cambiarEstadoEvaluacion/$1'); // Cambiar estado evaluación
    $routes->get('evaluaciones/estadisticas', 'EvaluacionesAdminController::obtenerEstadisticas'); // Obtener estadísticas
    $routes->post('evaluaciones/filtros', 'EvaluacionesAdminController::aplicarFiltros'); // Aplicar filtros
    
    // Rutas para reportes de evaluaciones
    $routes->get('reportes-evaluaciones', 'ReportesEvaluacionesController::index'); // Vista de reportes
    $routes->get('reportes-evaluaciones/pdf', 'ReportesEvaluacionesController::generarPDF'); // Generar PDF
    $routes->get('reportes-evaluaciones/excel', 'ReportesEvaluacionesController::exportarExcel'); // Exportar Excel
    $routes->get('reportes-evaluaciones/csv', 'ReportesEvaluacionesController::exportarCSV'); // Exportar CSV
    $routes->get('reportes-evaluaciones/graficos', 'ReportesEvaluacionesController::obtenerDatosGraficos'); // Datos para gráficos
    
    // Rutas para la gestión de backups
    $routes->get('backup', 'BackupAdminController::index');                         // Ver lista de backups
    $routes->post('backup/crear', 'BackupAdminController::crear');                  // Crear nuevo backup
    $routes->get('backup/detalle/(:num)', 'BackupAdminController::detalle/$1');     // Ver detalles de backup
    $routes->post('backup/descargar/(:num)', 'BackupAdminController::descargar/$1'); // Descargar backup
    $routes->delete('backup/eliminar/(:num)', 'BackupAdminController::eliminar/$1'); // Eliminar backup
    $routes->post('backup/restaurar/(:num)', 'BackupAdminController::restaurar/$1'); // Restaurar desde backup
    $routes->get('backup/exportar-historial', 'BackupAdminController::exportarHistorial'); // Exportar historial
    $routes->post('backup/filtrar', 'BackupAdminController::filtrar');              // Aplicar filtros
    $routes->get('backup/estadisticas', 'BackupAdminController::estadisticas');     // Obtener estadísticas

});
//----------------------------------------------------------------------------------------------------------------------
//RUTAS DOCENTE
$routes->group('docente', ['namespace' => 'App\Controllers\docente'], function ($routes) {
     $routes->get('dashboard', 'DashboardDocenteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardDocenteController::index');    // El dashboard del docente
    $routes->get('perfil', 'PerfilDocenteController::index');          // Ver el perfil del docente
    $routes->post('perfil/update', 'PerfilDocenteController::update'); // Actualizar el perfil del docente
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
});

//----------------------------------------------------------------------------------------------------------------------
//RUTAS ESTUDIANTE
$routes->group('estudiante', ['namespace' => 'App\Controllers\estudiante'], function ($routes) {
     $routes->get('dashboard', 'DashboardEstudianteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardEstudianteController::index');    // El dashboard del estudiante
    $routes->get('perfil', 'PerfilEstudianteController::index');          // Ver el perfil del estudiante
    $routes->post('perfil/update', 'PerfilEstudianteController::update'); // Actualizar el perfil del estudiante
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
});
