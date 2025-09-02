<?php
// Archivo de depuración para el dashboard
// Este archivo debe eliminarse después de las pruebas

require_once 'vendor/autoload.php';

// Configurar CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "<h2>Depuración del Dashboard - Datos de Gráficas</h2>";
    
    // Simular el controlador
    $estudiantesModel = new \App\Models\EstudiantesModel();
    $instructoresModel = new \App\Models\InstructoresModel();
    $actividadesModel = new \App\Models\ActividadesEducacionModel();
    $conveniosModel = new \App\Models\DetallesConveniosModel();
    $practicasModel = new \App\Models\AsignacionesPracticasModel();
    $carrerasModel = new \App\Models\CarrerasModel();
    
    echo "<h3>1. Estadísticas Mensuales:</h3>";
    
    $meses = [];
    $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    // Obtener datos de los últimos 12 meses
    for ($i = 11; $i >= 0; $i--) {
        $fecha = date('Y-m', strtotime("-$i months"));
        $mes = (int)date('m', strtotime("-$i months"));
        
        try {
            // Contar actividades en este mes
            $actividades = $actividadesModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
        } catch (\Exception $e) {
            $actividades = 0;
        }
        
        try {
            // Contar prácticas en este mes
            $practicas = $practicasModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
        } catch (\Exception $e) {
            $practicas = 0;
        }
        
        $meses[] = [
            'mes' => $mesesNombres[$mes - 1],
            'actividades' => $actividades,
            'practicas' => $practicas
        ];
        
        echo "Mes: " . $mesesNombres[$mes - 1] . " - Actividades: " . $actividades . ", Prácticas: " . $practicas . "<br>";
    }
    
    echo "<h3>2. Datos JSON para JavaScript:</h3>";
    echo "<strong>Meses:</strong> " . json_encode(array_column($meses, 'mes')) . "<br>";
    echo "<strong>Actividades:</strong> " . json_encode(array_column($meses, 'actividades')) . "<br>";
    echo "<strong>Prácticas:</strong> " . json_encode(array_column($meses, 'practicas')) . "<br>";
    
    echo "<h3>3. Distribución por Carreras:</h3>";
    try {
        $distribucionCarreras = $estudiantesModel
            ->select('c.NOMBRE as CARRERA, COUNT(*) as TOTAL')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = TAB_ESTUDIANTES.ID_CARRERA', 'left')
            ->groupBy('c.NOMBRE')
            ->orderBy('TOTAL', 'DESC')
            ->findAll();
        
        echo "<strong>Carreras:</strong> " . json_encode(array_column($distribucionCarreras, 'CARRERA')) . "<br>";
        echo "<strong>Totales:</strong> " . json_encode(array_column($distribucionCarreras, 'TOTAL')) . "<br>";
        
        if (!empty($distribucionCarreras)) {
            foreach ($distribucionCarreras as $carrera) {
                echo "✅ " . $carrera['CARRERA'] . ": " . $carrera['TOTAL'] . " estudiantes<br>";
            }
        } else {
            echo "⚠️ No se encontraron datos de carreras<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en distribución de carreras: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>4. Prácticas por Estado:</h3>";
    try {
        $practicasPorEstado = $practicasModel
            ->select('EP.ESTADO, COUNT(*) as total')
            ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = TAB_ASIGNACIONES_PRACTICAS.ID_ESTADO_PRACTICAS')
            ->groupBy('EP.ESTADO')
            ->findAll();
        
        echo "<strong>Estados:</strong> " . json_encode(array_column($practicasPorEstado, 'ESTADO')) . "<br>";
        echo "<strong>Totales:</strong> " . json_encode(array_column($practicasPorEstado, 'total')) . "<br>";
        
        if (!empty($practicasPorEstado)) {
            foreach ($practicasPorEstado as $estado) {
                echo "✅ " . $estado['ESTADO'] . ": " . $estado['total'] . " prácticas<br>";
            }
        } else {
            echo "⚠️ No se encontraron datos de prácticas<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en prácticas por estado: " . $e->getMessage() . "<br>";
    }
    
    echo "<br><strong>✅ Depuración completada. Revisa los datos JSON para el JavaScript.</strong>";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage();
}
?>
