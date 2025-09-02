<!-- app/Views/dashboard/dashboard.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- Chart.js para gráficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        height: 300px;
        margin: 20px 0;
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

        <!-- Gráficas y Estadísticas -->
        <div class="row mb-4">
            <!-- Gráfica de Actividades por Mes -->
            <div class="col-xl-8 col-lg-7 mb-4">
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
            
            <!-- Distribución por Carrera -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-success"></i>
                            Distribución por Carrera
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="carrerasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Fila de Gráficas -->
        <div class="row mb-4">
            <!-- Rendimiento de Prácticas -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase me-2 text-info"></i>
                            Rendimiento de Prácticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="practicasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Evaluación de Instructores -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Evaluación de Instructores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="evaluacionChart"></canvas>
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

<script>
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
    echo "console.log('Carreras labels:', " . json_encode($carrerasLabels) . ");";
    echo "console.log('Carreras data:', " . json_encode($carrerasData) . ");";
    ?>
    
    if (<?= json_encode($carrerasLabels) ?> && <?= json_encode($carrerasLabels) ?>.length > 0) {
        const carrerasChart = new Chart(carrerasCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($carrerasLabels) ?>,
                datasets: [{
                    data: <?= json_encode($carrerasData) ?>,
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b',
                        '#ff6b6b',
                        '#4ecdc4',
                        '#45b7d1',
                        '#96ceb4'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
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

    // Gráfica de Rendimiento de Prácticas
    const practicasCtx = document.getElementById('practicasChart').getContext('2d');
    
    // Preparar datos de prácticas por estado
    <?php 
    $practicasPorEstado = $datosGraficas['practicasPorEstado'] ?? [];
    $estadosPracticas = [];
    $datosPracticas = [];
    $coloresPracticas = ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'];
    
    foreach ($practicasPorEstado as $index => $estado) {
        $estadosPracticas[] = $estado['ESTADO'];
        $datosPracticas[] = (int)$estado['total'];
    }
    
    echo "console.log('Estados prácticas:', " . json_encode($estadosPracticas) . ");";
    echo "console.log('Datos prácticas:', " . json_encode($datosPracticas) . ");";
    ?>
    
    if (<?= json_encode($estadosPracticas) ?> && <?= json_encode($estadosPracticas) ?>.length > 0) {
        const practicasChart = new Chart(practicasCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($estadosPracticas) ?>,
                datasets: [{
                    label: 'Prácticas por Estado',
                    data: <?= json_encode($datosPracticas) ?>,
                    backgroundColor: <?= json_encode($coloresPracticas) ?>,
                    borderRadius: 5
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
        practicasCtx.font = '16px Arial';
        practicasCtx.fillStyle = '#666';
        practicasCtx.textAlign = 'center';
        practicasCtx.fillText('No hay datos disponibles', practicasCtx.canvas.width / 2, practicasCtx.canvas.height / 2);
        console.log('No hay datos para mostrar en el gráfico de prácticas');
    }

    // Gráfica de Evaluación de Instructores
    const evaluacionCtx = document.getElementById('evaluacionChart').getContext('2d');
    
    // Datos simulados para evaluación (puedes reemplazar con datos reales cuando tengas el modelo de evaluaciones)
    const evaluacionChart = new Chart(evaluacionCtx, {
        type: 'radar',
        data: {
            labels: ['Conocimiento Técnico', 'Comunicación', 'Puntualidad', 'Metodología', 'Evaluación', 'Disponibilidad'],
            datasets: [{
                label: 'Promedio General',
                data: [4.2, 4.1, 4.3, 4.0, 4.1, 4.2],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                pointBackgroundColor: '#ffc107',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#ffc107'
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
                r: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

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

    // Los datos ahora se cargan desde la base de datos
    // No es necesario simular datos en tiempo real
</script>
<?= $this->endSection() ?>