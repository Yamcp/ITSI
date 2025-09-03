<?php
// Script de prueba para verificar la conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Probando conexión a la base de datos...\n";

try {
    // Cargar configuración de CodeIgniter
    require_once 'app/Config/Database.php';
    
    $config = new \Config\Database();
    $dbConfig = $config->default;
    
    echo "Configuración de DB cargada:\n";
    echo "Host: " . $dbConfig['hostname'] . "\n";
    echo "Database: " . $dbConfig['database'] . "\n";
    echo "Username: " . $dbConfig['username'] . "\n";
    
    // Intentar conectar
    $dsn = "mysql:host={$dbConfig['hostname']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    
    echo "Conexión exitosa!\n";
    
    // Probar consulta simple
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM TAB_ESTADOS_REVISIONES");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Estados de revisión encontrados: " . $result['count'] . "\n";
    
    // Probar consulta de tipos de documentos
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM TAB_TIPOS_DOCUMENTOS_PRACTICAS");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Tipos de documentos encontrados: " . $result['count'] . "\n";
    
    // Probar consulta de usuarios
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM TAB_USUARIOS");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Usuarios encontrados: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "Error fatal: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}
