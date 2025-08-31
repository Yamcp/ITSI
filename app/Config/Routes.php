<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');                           // Página principal de login
$routes->post('/auth/autenticar', 'AuthController::autenticar');     // Acción de autenticar al usuario
$routes->get('/auth/cerrar-sesion', 'AuthController::cerrarSesion'); // Acción para cerrar sesión

$routes->get('educacion', 'EstudiantesController::index');
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
    $routes->get('convenios', 'InstitucionesConveniosAdminController::index');        // Ver la sección de convenios

    //Rutas para la gestión de prácticas
    $routes->get('practicas', 'PracticasAdminController::index');                     // Ver la lista de prácticas
    
    // Rutas para la gestión de instructores
    $routes->get('instructores', 'InstructoresAdminController::index');               // Ver la lista de instructores
    
    // Rutas para la gestión de estudiantes
    $routes->get('estudiantes', 'EstudiantesController::index');                     // Ver la lista de estudiantes
    
    // Rutas para la gestión de backups
    $routes->get('backup', 'BackupAdminController::index');                    // Ver lista de backups
    $routes->post('backup/create', 'BackupAdminController::create');           // Crear nuevo backup
    $routes->get('backup/download/(:num)', 'BackupAdminController::download/$1'); // Descargar backup
    $routes->get('backup/delete/(:num)', 'BackupAdminController::delete/$1'); // Eliminar backup

});

//RUTAS DOCENTE
$routes->group('docente', ['namespace' => 'App\Controllers\docente'], function ($routes) {
     $routes->get('dashboard', 'DashboardDocenteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardDocenteController::index');    // El dashboard del docente
    $routes->get('perfil', 'PerfilDocenteController::index');          // Ver el perfil del docente
    $routes->post('perfil/update', 'PerfilDocenteController::update'); // Actualizar el perfil del docente
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
});

//RUTAS ESTUDIANTE
$routes->group('estudiante', ['namespace' => 'App\Controllers\estudiante'], function ($routes) {
     $routes->get('dashboard', 'DashboardEstudianteController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardEstudianteController::index');    // El dashboard del estudiante
    $routes->get('perfil', 'PerfilEstudianteController::index');          // Ver el perfil del estudiante
    $routes->post('perfil/update', 'PerfilEstudianteController::update'); // Actualizar el perfil del estudiante
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('convenios', 'InstitucionesConveniosController::index');        // Ver la sección de convenios
});
