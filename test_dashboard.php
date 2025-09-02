<?php
// Archivo de prueba para verificar la conexión del dashboard
// Este archivo debe eliminarse después de las pruebas

require_once 'vendor/autoload.php';

// Configurar CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "<h2>Prueba de Conexión del Dashboard</h2>";
    
    // Inicializar modelos
    $estudiantesModel = new \App\Models\EstudiantesModel();
    $instructoresModel = new \App\Models\InstructoresModel();
    $actividadesModel = new \App\Models\ActividadesEducacionModel();
    $conveniosModel = new \App\Models\DetallesConveniosModel();
    $practicasModel = new \App\Models\AsignacionesPracticasModel();
    $carrerasModel = new \App\Models\CarrerasModel();
    
    echo "<h3>Métricas Básicas:</h3>";
    
    // Total de estudiantes
    try {
        $totalEstudiantes = $estudiantesModel->countAllResults();
        echo "✅ Total Estudiantes: " . $totalEstudiantes . "<br>";
    } catch (Exception $e) {
        echo "❌ Error en estudiantes: " . $e->getMessage() . "<br>";
    }
    
    // Total de instructores
    try {
        $totalInstructores = $instructoresModel->countAllResults();
        echo "✅ Total Instructores: " . $totalInstructores . "<br>";
    } catch (Exception $e) {
        echo "❌ Error en instructores: " . $e->getMessage() . "<br>";
    }
    
    // Actividades activas
    try {
        $actividadesActivas = $actividadesModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();
        echo "✅ Actividades Activas: " . $actividadesActivas . "<br>";
    } catch (Exception $e) {
        echo "❌ Error en actividades: " . $e->getMessage() . "<br>";
    }
    
    // Convenios vigentes
    try {
        $conveniosVigentes = $conveniosModel->where('FECHA_FIN >=', date('Y-m-d'))->countAllResults();
        echo "✅ Convenios Vigentes: " . $conveniosVigentes . "<br>";
    } catch (Exception $e) {
        echo "❌ Error en convenios: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>Distribución por Carreras:</h3>";
    try {
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
    } catch (Exception $e) {
        echo "❌ Error en distribución de carreras: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>Prácticas por Estado:</h3>";
    try {
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
            echo "⚠️ No se encontraron datos de prácticas<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error en prácticas por estado: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>Estadísticas Mensuales (últimos 3 meses):</h3>";
    for ($i = 2; $i >= 0; $i--) {
        $fecha = date('Y-m', strtotime("-$i months"));
        $mes = date('M Y', strtotime("-$i months"));
        
        try {
            $actividades = $actividadesModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
            
            $practicas = $practicasModel
                ->where('DATE_FORMAT(FECHA_INICIO, "%Y-%m")', $fecha)
                ->countAllResults(false);
            
            echo "✅ " . $mes . " - Actividades: " . $actividades . ", Prácticas: " . $practicas . "<br>";
        } catch (Exception $e) {
            echo "❌ Error en estadísticas de " . $mes . ": " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<br><strong>✅ Prueba completada. Si ves errores, revisa la configuración de la base de datos.</strong>";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage();
}
?>
