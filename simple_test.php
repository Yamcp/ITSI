<?php
// Archivo simple para probar la conexión a la base de datos
// Este archivo debe eliminarse después de las pruebas

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'itsi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Prueba Simple de Base de Datos</h2>";
    
    echo "<h3>1. Verificar tablas existentes:</h3>";
    
    // Verificar si las tablas existen
    $tablas = ['TAB_ACTIVIDADES_EDUCACION', 'TAB_ASIGNACIONES_PRACTICAS', 'TAB_ESTUDIANTES', 'TAB_CARRERAS'];
    
    foreach ($tablas as $tabla) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "✅ Tabla $tabla: " . $result['total'] . " registros<br>";
        } catch (PDOException $e) {
            echo "❌ Tabla $tabla no existe o error: " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<h3>2. Verificar datos de actividades:</h3>";
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM TAB_ACTIVIDADES_EDUCACION");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de actividades: " . $result['total'] . "<br>";
        
        if ($result['total'] > 0) {
            // Mostrar algunas actividades
            $stmt = $pdo->query("SELECT NOMBRE_ACTIVIDAD, FECHA_INICIO FROM TAB_ACTIVIDADES_EDUCACION LIMIT 5");
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<strong>Algunas actividades:</strong><br>";
            foreach ($actividades as $actividad) {
                echo "- " . $actividad['NOMBRE_ACTIVIDAD'] . " (Fecha: " . $actividad['FECHA_INICIO'] . ")<br>";
            }
        }
    } catch (PDOException $e) {
        echo "❌ Error al consultar actividades: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>3. Verificar datos de prácticas:</h3>";
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM TAB_ASIGNACIONES_PRACTICAS");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de prácticas: " . $result['total'] . "<br>";
        
        if ($result['total'] > 0) {
            // Mostrar algunas prácticas
            $stmt = $pdo->query("SELECT FECHA_INICIO FROM TAB_ASIGNACIONES_PRACTICAS LIMIT 5");
            $practicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<strong>Algunas prácticas:</strong><br>";
            foreach ($practicas as $practica) {
                echo "- Fecha: " . $practica['FECHA_INICIO'] . "<br>";
            }
        }
    } catch (PDOException $e) {
        echo "❌ Error al consultar prácticas: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>4. Probar consulta de estadísticas mensuales:</h3>";
    
    $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    // Probar solo los últimos 3 meses
    for ($i = 2; $i >= 0; $i--) {
        $fecha = date('Y-m', strtotime("-$i months"));
        $mes = (int)date('m', strtotime("-$i months"));
        
        echo "<strong>" . $mesesNombres[$mes - 1] . " " . date('Y', strtotime("-$i months")) . ":</strong><br>";
        
        try {
            // Contar actividades en este mes
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM TAB_ACTIVIDADES_EDUCACION WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?");
            $stmt->execute([$fecha]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "- Actividades: " . $result['total'] . "<br>";
            
            // Contar prácticas en este mes
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM TAB_ASIGNACIONES_PRACTICAS WHERE DATE_FORMAT(FECHA_INICIO, '%Y-%m') = ?");
            $stmt->execute([$fecha]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "- Prácticas: " . $result['total'] . "<br><br>";
        } catch (PDOException $e) {
            echo "❌ Error en consulta mensual: " . $e->getMessage() . "<br><br>";
        }
    }
    
    echo "<br><strong>✅ Prueba completada.</strong>";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
