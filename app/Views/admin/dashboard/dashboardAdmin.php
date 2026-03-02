<!-- app/Views/dashboard/dashboard.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?php
use Config\Database;

// Fallback: si el controlador no envía el período, lo obtenemos aquí
$periodoNombreDashboard = $periodoAcademicoNombre ?? session('periodo_academico_nombre');
$periodoRangoDashboard  = $periodoAcademicoRango ?? session('periodo_academico_rango');

if (!$periodoNombreDashboard) {
    try {
        $db = Database::connect();
        $row = $db->query("SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL LIMIT 1")->getRowArray();

        if ($row) {
            $periodoNombreDashboard = $row['NOMBRE_PERIODO'] ?? null;
            $periodoRangoDashboard  = ($row['FECHA_INICIO'] ?? '') . ' - ' . ($row['FECHA_FIN'] ?? '');

            // Persistir en sesión para reutilizarlo en otras vistas
            if ($periodoNombreDashboard) {
                session()->set('periodo_academico_nombre', $periodoNombreDashboard);
            }
            if ($periodoRangoDashboard) {
                session()->set('periodo_academico_rango', $periodoRangoDashboard);
            }
        }
    } catch (\Throwable $e) {
        log_message('error', 'Dashboard admin view - error obteniendo período académico actual: ' . $e->getMessage());
    }
}
?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para el dashboard -->
<style>
    .metric-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    
    .chart-container {
        position: relative;
        height: 350px;
        margin: 20px 0;
        padding: 10px;
    }
    
    .chart-container canvas {
        max-height: 100%;
    }
    
    /* Mejoras específicas para la gráfica de carreras */
    #carrerasChart {
        max-width: 100%;
        height: auto !important;
    }
    
    /* Ajustes para el layout de dos columnas en la gráfica de carreras */
    .carreras-container .chart-container {
        height: 300px;
    }
    
    .carreras-container .legend-table {
        margin-top: 0;
    }
    
    /* Responsive para la gráfica de carreras */
    @media (max-width: 768px) {
        .chart-container {
            height: 300px;
        }
        
        .chart-container canvas {
            max-height: 250px;
        }
        
        .carreras-container .chart-container {
            height: 250px;
        }
    }
    
    @media (max-width: 576px) {
        .chart-container {
            height: 250px;
        }
        
        .chart-container canvas {
            max-height: 200px;
        }
        
        .carreras-container .chart-container {
            height: 200px;
        }
        
        .carreras-container .legend-table {
            margin-top: 20px;
        }
    }
    
    /* Estilos para la tabla de leyenda */
    .legend-table {
        font-size: 0.9rem;
    }
    
    .legend-table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
        border-radius: 4px;
    }
    
    .legend-table .color-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .legend-table .carrera-name {
        font-weight: 500;
        color: #495057;
    }
    
    .legend-table .estudiantes-count {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .legend-table .percentage-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .timeline-item {
        position: relative;
        padding: 15px 0 15px 30px;
        border-left: 2px solid #e9ecef;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 20px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #007bff;
    }
    
    .timeline-item.success::before {
        background: #28a745;
    }
    
    .timeline-item.warning::before {
        background: #ffc107;
    }
    
    .timeline-item.info::before {
        background: #17a2b8;
    }
    
    .quick-action-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .progress-ring-circle {
        stroke-linecap: round;
        transition: stroke-dasharray 0.5s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header del Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                            Panel de Control
                        </h2>
                        <p class="text-muted mb-0">Bienvenido al Sistema del Departamento de Vinculación</p>
                        <div class="mt-2">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-calendar-check me-2"></i>
                                Período académico actual:
                                <?php if (!empty($periodoNombreDashboard)): ?>
                                    <strong><?= esc($periodoNombreDashboard) ?></strong>
                                    <?php if (!empty($periodoRangoDashboard)): ?>
                                        <span class="text-muted fw-normal fs-6">(<?= esc($periodoRangoDashboard) ?>)</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted fw-normal">No hay período configurado</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="text-end">
                    <span class="badge bg-light text-dark fs-6 mb-2">Administrador</span>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y') ?>
                        </p>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <span id="currentTime"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/estudiantes') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #667eea 80%, #764ba2 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="totalEstudiantes"><?= number_format($metricas['totalEstudiantes'] ?? 0) ?></h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Total Estudiantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/instructores') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #f093fb 80%, #f5576c 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="totalInstructores"><?= number_format($metricas['totalInstructores'] ?? 0) ?></h3>
                                    <p class="mb-0" style="color: #ffe6e6;">Instructores</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/educacion') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #4facfe 80%, #00f2fe 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="actividadesActivas"><?= number_format($metricas['actividadesActivas'] ?? 0) ?></h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Actividades Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/convenios') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #43e97b 80%, #38f9d7 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="conveniosVigentes"><?= number_format($metricas['conveniosVigentes'] ?? 0) ?></h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Convenios Vigentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Gráfica de Actividades por Mes -->
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Actividades Educativas por Mes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="actividadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfica de Distribución por Carrera -->
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-success"></i>
                            Distribución por Carrera
                        </h5>
                    </div>
                    <div class="card-body carreras-container">
                        <div class="row">
                            <!-- Gráfica -->
                            <div class="col-lg-6 col-md-6">
                                <div class="chart-container">
                                    <canvas id="carrerasChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Tabla de leyenda -->
                            <div class="col-lg-6 col-md-6">
                                <?php if (!empty($distribucionCarreras)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless legend-table">
                                        <tbody>
                                            <?php 
                                            $colores = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3', '#54a0ff', '#5f27cd'];
                                            $totalEstudiantes = array_sum(array_column($distribucionCarreras, 'TOTAL'));
                                            foreach ($distribucionCarreras as $index => $carrera): 
                                                $porcentaje = $totalEstudiantes > 0 ? round(($carrera['TOTAL'] / $totalEstudiantes) * 100, 1) : 0;
                                            ?>
                                            <tr>
                                                <td class="text-center" style="width: 20px;">
                                                    <div class="color-indicator" style="background-color: <?= $colores[$index % count($colores)] ?>;"></div>
                                                </td>
                                                <td class="carrera-name">
                                                    <?= esc($carrera['CARRERA']) ?>
                                                </td>
                                                <td class="text-end estudiantes-count">
                                                    <?= number_format($carrera['TOTAL']) ?> estudiantes
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-light text-dark percentage-badge"><?= $porcentaje ?>%</span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Detalladas -->
        <div class="row mb-4">
            <!-- Próximos Vencimientos -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            Próximos Vencimientos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Elemento</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Días Restantes</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($vencimientos['convenios'] ?? [])): ?>
                                        <?php foreach ($vencimientos['convenios'] as $convenio): ?>
                                            <?php 
                                            $fechaVencimiento = new DateTime($convenio['FECHA_FIN']);
                                            $fechaActual = new DateTime();
                                            $diasRestantes = $fechaActual->diff($fechaVencimiento)->days;
                                            
                                            $badgeClass = 'bg-success';
                                            $estadoClass = 'bg-success';
                                            $estado = 'Seguro';
                                            
                                            if ($diasRestantes <= 7) {
                                                $badgeClass = 'bg-danger';
                                                $estadoClass = 'bg-danger';
                                                $estado = 'Crítico';
                                            } elseif ($diasRestantes <= 15) {
                                                $badgeClass = 'bg-warning text-dark';
                                                $estadoClass = 'bg-warning text-dark';
                                                $estado = 'Pendiente';
                                            } elseif ($diasRestantes <= 30) {
                                                $badgeClass = 'bg-info';
                                                $estadoClass = 'bg-info';
                                                $estado = 'Normal';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= esc($convenio['INSTITUCION']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($convenio['FECHA_FIN'])) ?></td>
                                                <td><span class="badge <?= $badgeClass ?>"><?= $diasRestantes ?> días</span></td>
                                                <td><span class="badge <?= $estadoClass ?>"><?= $estado ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No hay convenios próximos a vencer</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen de Actividades -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            Resumen de Actividades
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1"><?= number_format($metricas['totalEstudiantes'] ?? 0) ?></h4>
                                    <p class="text-muted mb-0">Total Estudiantes</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success mb-1"><?= number_format($metricas['actividadesActivas'] ?? 0) ?></h4>
                                <p class="text-muted mb-0">Actividades Activas</p>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1"><?= number_format($metricas['conveniosVigentes'] ?? 0) ?></h4>
                                <p class="text-muted mb-0">Convenios Vigentes</p>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning mb-1"><?= number_format($metricas['totalInstructores'] ?? 0) ?></h4>
                                <p class="text-muted mb-0">Instructores</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Cargar Chart.js y crear gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Esperar a que Chart.js se cargue antes de crear los gráficos
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no se pudo cargar');
            return;
        }
        
        console.log('Chart.js cargado correctamente, creando gráficos...');
        
        // Mover todo el código de gráficos aquí
        crearGraficos();
    });
    
    function crearGraficos() {
        // Actualizar hora en tiempo real
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-EC', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        
        setInterval(updateTime, 1000);
        updateTime();

        // Gráfica de Actividades por Mes
        const actividadesCtx = document.getElementById('actividadesChart').getContext('2d');
        
        // Preparar datos mensuales
        <?php 
        $estadisticasMensuales = $datosGraficas['estadisticasMensuales'] ?? [];
        $meses = array_column($estadisticasMensuales, 'mes');
        $datosActividades = array_column($estadisticasMensuales, 'actividades');
        $datosPracticas = array_column($estadisticasMensuales, 'practicas');
        
        // Debug: mostrar datos en consola
        echo "console.log('Datos mensuales:', " . json_encode($estadisticasMensuales) . ");";
        echo "console.log('Meses:', " . json_encode($meses) . ");";
        echo "console.log('Actividades:', " . json_encode($datosActividades) . ");";
        echo "console.log('Prácticas:', " . json_encode($datosPracticas) . ");";
        ?>
        
        // Verificar que tenemos datos antes de crear el gráfico
        if (<?= json_encode($meses) ?> && <?= json_encode($meses) ?>.length > 0) {
            const actividadesChart = new Chart(actividadesCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($meses) ?>,
                    datasets: [{
                        label: 'Actividades Educativas',
                        data: <?= json_encode($datosActividades) ?>,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Prácticas Asignadas',
                        data: <?= json_encode($datosPracticas) ?>,
                        borderColor: '#f093fb',
                        backgroundColor: 'rgba(240, 147, 251, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        } else {
            // Mostrar mensaje si no hay datos
            actividadesCtx.font = '16px Arial';
            actividadesCtx.fillStyle = '#666';
            actividadesCtx.textAlign = 'center';
            actividadesCtx.fillText('No hay datos disponibles', actividadesCtx.canvas.width / 2, actividadesCtx.canvas.height / 2);
            console.log('No hay datos para mostrar en el gráfico de actividades');
        }

        // Gráfica de Distribución por Carrera
        const carrerasCtx = document.getElementById('carrerasChart').getContext('2d');
        
        <?php 
        $carrerasLabels = array_column($distribucionCarreras ?? [], 'CARRERA');
        $carrerasData = array_column($distribucionCarreras ?? [], 'TOTAL');
        echo "console.log('Distribución carreras completa:', " . json_encode($distribucionCarreras ?? []) . ");";
        echo "console.log('Carreras labels:', " . json_encode($carrerasLabels) . ");";
        echo "console.log('Carreras data:', " . json_encode($carrerasData) . ");";
        ?>
        
        if (<?= json_encode($carrerasLabels) ?> && <?= json_encode($carrerasLabels) ?>.length > 0) {
            const carrerasChart = new Chart(carrerasCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($carrerasLabels) ?>,
                    datasets: [{
                        label: 'Estudiantes por Carrera',
                        data: <?= json_encode($carrerasData) ?>,
                        backgroundColor: [
                            '#667eea',
                            '#f093fb',
                            '#4facfe',
                            '#43e97b',
                            '#ff6b6b',
                            '#4ecdc4',
                            '#45b7d1',
                            '#96ceb4',
                            '#feca57',
                            '#ff9ff3',
                            '#54a0ff',
                            '#5f27cd'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Ocultamos la leyenda de Chart.js
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + ' estudiantes';
                                }
                            }
                        }
                    }
                }
            });
        } else {
            // Mostrar mensaje si no hay datos
            carrerasCtx.font = '16px Arial';
            carrerasCtx.fillStyle = '#666';
            carrerasCtx.textAlign = 'center';
            carrerasCtx.fillText('No hay datos disponibles', carrerasCtx.canvas.width / 2, carrerasCtx.canvas.height / 2);
            console.log('No hay datos para mostrar en el gráfico de carreras');
        }

        

        // Función para navegar a diferentes secciones
        function navegarA(seccion) {
            const rutas = {
                'practicas': '/admin/practicas',
                'convenios': '/admin/convenios',
                'educacion': '/admin/educacion',
                'instructores': '/admin/instructores',
                'documentos-practicas': '/admin/documentos-practicas',
                'reportes': '/admin/reportes'
            };
            
            if (rutas[seccion]) {
                window.location.href = rutas[seccion];
            }
        }
    }
</script>
<?= $this->endSection() ?>