<?php
// Archivo para insertar datos de prueba en el dashboard
// Este archivo debe eliminarse después de las pruebas

require_once 'vendor/autoload.php';

// Configurar CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "<h2>Insertando Datos de Prueba para el Dashboard</h2>";
    
    $db = \Config\Database::connect();
    
    echo "<h3>1. Insertando carreras de prueba:</h3>";
    
    // Insertar carreras si no existen
    $carreras = [
        ['NOMBRE' => 'Ingeniería en Sistemas'],
        ['NOMBRE' => 'Ingeniería Industrial'],
        ['NOMBRE' => 'Administración de Empresas'],
        ['NOMBRE' => 'Contabilidad']
    ];
    
    foreach ($carreras as $carrera) {
        $existe = $db->table('TAB_CARRERAS')->where('NOMBRE', $carrera['NOMBRE'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_CARRERAS')->insert($carrera);
            echo "✅ Carrera insertada: " . $carrera['NOMBRE'] . "<br>";
        } else {
            echo "⚠️ Carrera ya existe: " . $carrera['NOMBRE'] . "<br>";
        }
    }
    
    echo "<h3>2. Insertando estados de prácticas:</h3>";
    
    // Insertar estados de prácticas si no existen
    $estados = [
        ['ESTADO' => 'En Proceso'],
        ['ESTADO' => 'Completada'],
        ['ESTADO' => 'Pendiente'],
        ['ESTADO' => 'Cancelada']
    ];
    
    foreach ($estados as $estado) {
        $existe = $db->table('TAB_ESTADO_PRACTICAS')->where('ESTADO', $estado['ESTADO'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_ESTADO_PRACTICAS')->insert($estado);
            echo "✅ Estado insertado: " . $estado['ESTADO'] . "<br>";
        } else {
            echo "⚠️ Estado ya existe: " . $estado['ESTADO'] . "<br>";
        }
    }
    
    echo "<h3>3. Insertando tipos de actividades:</h3>";
    
    // Insertar tipos de actividades si no existen
    $tiposActividades = [
        ['ACTIVIDAD' => 'Taller'],
        ['ACTIVIDAD' => 'Seminario'],
        ['ACTIVIDAD' => 'Conferencia'],
        ['ACTIVIDAD' => 'Curso']
    ];
    
    foreach ($tiposActividades as $tipo) {
        $existe = $db->table('TAB_TIPOS_ACTIVIDADES')->where('ACTIVIDAD', $tipo['ACTIVIDAD'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_TIPOS_ACTIVIDADES')->insert($tipo);
            echo "✅ Tipo de actividad insertado: " . $tipo['ACTIVIDAD'] . "<br>";
        } else {
            echo "⚠️ Tipo de actividad ya existe: " . $tipo['ACTIVIDAD'] . "<br>";
        }
    }
    
    echo "<h3>4. Insertando tipos de prácticas:</h3>";
    
    // Insertar tipos de prácticas si no existen
    $tiposPracticas = [
        ['PRACTICA' => 'Práctica Preprofesional'],
        ['PRACTICA' => 'Servicio Comunitario']
    ];
    
    foreach ($tiposPracticas as $tipo) {
        $existe = $db->table('TAB_TIPOS_PRACTICAS')->where('PRACTICA', $tipo['PRACTICA'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_TIPOS_PRACTICAS')->insert($tipo);
            echo "✅ Tipo de práctica insertado: " . $tipo['PRACTICA'] . "<br>";
        } else {
            echo "⚠️ Tipo de práctica ya existe: " . $tipo['PRACTICA'] . "<br>";
        }
    }
    
    echo "<h3>5. Insertando tipos de modalidades:</h3>";
    
    // Insertar tipos de modalidades si no existen
    $modalidades = [
        ['MODALIDAD' => 'Presencial'],
        ['MODALIDAD' => 'Virtual'],
        ['MODALIDAD' => 'Híbrida']
    ];
    
    foreach ($modalidades as $modalidad) {
        $existe = $db->table('TAB_TIPOS_MODALIDADES')->where('MODALIDAD', $modalidad['MODALIDAD'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_TIPOS_MODALIDADES')->insert($modalidad);
            echo "✅ Modalidad insertada: " . $modalidad['MODALIDAD'] . "<br>";
        } else {
            echo "⚠️ Modalidad ya existe: " . $modalidad['MODALIDAD'] . "<br>";
        }
    }
    
    echo "<h3>6. Insertando tipos de instructores:</h3>";
    
    // Insertar tipos de instructores si no existen
    $tiposInstructores = [
        ['TIPO' => 'Interno'],
        ['TIPO' => 'Externo']
    ];
    
    foreach ($tiposInstructores as $tipo) {
        $existe = $db->table('TAB_TIPOS_INSTRUCTORES')->where('TIPO', $tipo['TIPO'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_TIPOS_INSTRUCTORES')->insert($tipo);
            echo "✅ Tipo de instructor insertado: " . $tipo['TIPO'] . "<br>";
        } else {
            echo "⚠️ Tipo de instructor ya existe: " . $tipo['TIPO'] . "<br>";
        }
    }
    
    echo "<h3>7. Insertando tipos de estados:</h3>";
    
    // Insertar tipos de estados si no existen
    $tiposEstados = [
        ['ESTADO' => 'Activo'],
        ['ESTADO' => 'Inactivo']
    ];
    
    foreach ($tiposEstados as $tipo) {
        $existe = $db->table('TAB_TIPOS_ESTADOS')->where('ESTADO', $tipo['ESTADO'])->countAllResults();
        if ($existe == 0) {
            $db->table('TAB_TIPOS_ESTADOS')->insert($tipo);
            echo "✅ Tipo de estado insertado: " . $tipo['ESTADO'] . "<br>";
        } else {
            echo "⚠️ Tipo de estado ya existe: " . $tipo['ESTADO'] . "<br>";
        }
    }
    
    echo "<br><strong>✅ Datos de prueba insertados. Ahora puedes probar el dashboard.</strong>";
    echo "<br><strong>⚠️ IMPORTANTE: Elimina este archivo después de las pruebas por seguridad.</strong>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
