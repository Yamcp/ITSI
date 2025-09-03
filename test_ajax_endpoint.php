<?php
// Script de prueba para verificar el endpoint AJAX
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Iniciando prueba...\n";

try {
    require_once 'vendor/autoload.php';
    echo "Autoload cargado correctamente\n";
    
    // Configurar CodeIgniter
    $app = \Config\Services::codeigniter();
    echo "CodeIgniter inicializado\n";
    
    // Simular una petición AJAX
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    
    // Crear instancia del controlador
    echo "Creando instancia del controlador...\n";
    $controller = new \App\Controllers\admin\DocumentosPracticasAdminController();
    echo "Controlador creado correctamente\n";
    
    // Probar el método apiEstudiantes
    echo "Probando apiEstudiantes...\n";
    $response = $controller->apiEstudiantes();
    echo "Respuesta: " . $response->getBody() . "\n\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "Error fatal: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
