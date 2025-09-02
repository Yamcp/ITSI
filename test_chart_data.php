<?php
// Archivo simple para probar los datos del gráfico
// Este archivo debe eliminarse después de las pruebas

require_once 'vendor/autoload.php';

// Configurar CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "<h2>Prueba de Datos para Gráficos</h2>";
    
    // Inicializar modelos
    $actividadesModel = new \App\Models\ActividadesEducacionModel();
    $practicasModel = new \App\Models\AsignacionesPracticasModel();
    $estudiantesModel = new \App\Models\EstudiantesModel();
    
    echo "<h3>1. Verificar si hay datos en las tablas:</h3>";
    
    // Verificar actividades
    $totalActividades = $actividadesModel->countAllResults();
    echo "Total de actividades en la base de datos: " . $totalActividades . "<br>";
    
    // Verificar prácticas
    $totalPracticas = $practicasModel->countAllResults();
    echo "Total de prácticas en la base de datos: " . $totalPracticas . "<br>";
    
    // Verificar estudiantes
    $totalEstudiantes = $estudiantesModel->countAllResults();
    echo "Total de estudiantes en la base de datos: " . $totalEstudiantes . "<br>";
    
    echo "<h3>2. Probar consulta de estadísticas mensuales:</h3>";
    
    $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    // Probar solo los últimos 3 meses
    for ($i = 2; $i >= 0; $i--) {
        $fecha = date('Y-m', strtotime("-$i months"));
        $mes = (int)date('m', strtotime("-$i months"));
        
        echo "<strong>" . $mesesNombres[$mes - 1] . " " . date('Y', strtotime("-$i months")) . ":</strong><br>";
        
        // Contar actividades en este mes
        $actividades = $actividadesModel
            ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
            ->countAllResults(false);
        echo "- Actividades: " . $actividades . "<br>";
        
        // Contar prácticas en este mes
        $practicas = $practicasModel
            ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
            ->countAllResults(false);
        echo "- Prácticas: " . $practicas . "<br><br>";
    }
    
    echo "<h3>3. Probar consulta de carreras:</h3>";
    
    $distribucionCarreras = $estudiantesModel
        ->select('c.NOMBRE as CARRERA, COUNT(*) as TOTAL')
        ->join('TAB_CARRERAS c', 'c.ID_CARRERA = TAB_ESTUDIANTES.ID_CARRERA', 'left')
        ->groupBy('c.NOMBRE')
        ->orderBy('TOTAL', 'DESC')
        ->findAll();
    
    if (!empty($distribucionCarreras)) {
        foreach ($distribucionCarreras as $carrera) {
            echo "✅ " . $carrera['CARRERA'] . ": " . $carrera['TOTAL'] . " estudiantes<br>";
        }
    } else {
        echo "⚠️ No se encontraron datos de carreras<br>";
    }
    
    echo "<h3>4. Probar consulta de prácticas por estado:</h3>";
    
    $practicasPorEstado = $practicasModel
        ->select('EP.ESTADO, COUNT(*) as total')
        ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = TAB_ASIGNACIONES_PRACTICAS.ID_ESTADO_PRACTICAS')
        ->groupBy('EP.ESTADO')
        ->findAll();
    
    if (!empty($practicasPorEstado)) {
        foreach ($practicasPorEstado as $estado) {
            echo "✅ " . $estado['ESTADO'] . ": " . $estado['total'] . " prácticas<br>";
        }
    } else {
        echo "⚠️ No se encontraron datos de prácticas por estado<br>";
    }
    
    echo "<br><strong>✅ Prueba completada. Si todos los números son 0, significa que no hay datos en las tablas.</strong>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
