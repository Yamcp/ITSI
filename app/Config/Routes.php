<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LoginController::index');                           // Página principal de login
$routes->post('/login/autenticar', 'LoginController::autenticar');     // Acción de autenticar al usuario
$routes->get('/login/cerrar-sesion', 'LoginController::cerrarSesion'); // Acción para cerrar sesión

$routes->get('educacion', 'EstudiantesController::index');
//----------------------------------------------------------------------------------------------------------------------
//RUTAS ADMINISTRADOR
$routes->group('admin', ['namespace' => 'App\Controllers\admin'], function ($routes) {
     $routes->get('dashboard', 'DashboardAdminController::index');     // Permitir GET
    $routes->post('dashboard', 'DashboardAdminController::index');    // El dashboard del administrador
    $routes->get('perfil', 'PerfilAdminController::index');          // Ver el perfil del administrador
    $routes->post('perfil/update', 'PerfilAdminController::update'); // Actualizar el perfil del administrador
    $routes->get('educacion', 'ActividadesEducacionController::index');    // Ver la sección de educación
    $routes->get('convenios', 'ConveniosController::index');        // Ver la sección de convenios
});
