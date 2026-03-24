<?= $this->extend('admin/layouts/mainAdmin') ?>

<?php

$p                       = obtener_periodo_academico_para_ui();
$periodoNombreDashboard  = $p['nombre'];
$periodoRangoDashboard   = $p['rango'];

?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    :root {
        --dashboard-radius: 16px;
        --dashboard-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        --dashboard-shadow-hover: 0 12px 32px rgba(0, 0, 0, 0.12);
        --gradient-pre: linear-gradient(145deg, #0ea5e9 0%, #06b6d4 100%);
        --gradient-serv: linear-gradient(145deg, #ec4899 0%, #f59e0b 100%);
        --gradient-active: linear-gradient(145deg, #10b981 0%, #14b8a6 100%);
        --gradient-actividades: linear-gradient(145deg, #6366f1 0%, #8b5cf6 100%);
    }

    .dashboard-page {
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: var(--dashboard-radius);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .dashboard-header .title-dash {
        font-weight: 700;
        font-size: 1.6rem;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .dashboard-header .subtitle-dash {
        color: #64748b;
        font-size: 0.95rem;
    }

    .dashboard-header .badge-rol {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 600;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
    }

    .dashboard-header .date-time-box {
        background: #fff;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        color: #475569;
    }

    .metric-card {
        border: none;
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--dashboard-shadow-hover);
    }

    .metric-card .card-body {
        padding: 1.35rem 1.25rem;
        position: relative;
    }

    .metric-card .metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #fff;
        opacity: 0.95;
    }

    .metric-card h3 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.2rem;
    }

    .metric-card .metric-label {
        font-weight: 600;
        font-size: 0.9rem;
        opacity: 0.95;
    }

    .metric-card .metric-sub {
        font-size: 0.8rem;
        opacity: 0.85;
    }

    .card-dash {
        border: none;
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
    }

    .card-dash .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 1.35rem;
        font-weight: 600;
        color: #0f172a;
        font-size: 1.05rem;
    }

    .quick-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 12px;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        text-decoration: none;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: inherit;
    }

    /* Progress cards*/
    .progress-card-dash {
        border-radius: var(--dashboard-radius);
        padding: 1.35rem 1.5rem;
        border: none;
        box-shadow: var(--dashboard-shadow);
        color: #fff;
    }

    .progress-card-dash .progress {
        height: 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.25);
    }

    .progress-card-dash .progress-bar {
        border-radius: 999px;
    }

    .progress-card-dash h5 {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.75rem;
        opacity: 0.98;
    }

    .progress-card-dash small {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    /* Activity cards */
    .activity-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
    }

    .activity-card:hover {
        box-shadow: var(--dashboard-shadow);
        border-color: #cbd5e1;
    }

    .activity-card .activity-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }

    .activity-card h6 {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .activity-card p {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .chart-container {
        position: relative;
        height: 280px;
        margin: 1rem 0;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        .chart-container {
            height: 250px;
        }
    }

    .table-dash {
        margin-bottom: 0;
    }

    .table-dash thead th {
        font-weight: 600;
        color: #475569;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1rem;
    }

    .table-dash tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table-dash tbody tr {
        transition: background 0.15s ease;
    }

    .table-dash tbody tr:hover {
        background: #f8fafc;
    }

    .table-dash .badge {
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        font-size: 0.75rem;
    }

    .table-dash .btn-sm {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
    }

    .empty-state {
        padding: 3rem 1rem;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper dashboard-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <!-- Header del Dashboard -->
        <div class="dashboard-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="title-dash mb-1">
                    <i class="fas fa-compass me-2 text-primary"></i>Panel de Control
                </h1>
                <p class="subtitle-dash mb-0">Bienvenido al Sistema del Departamento de Vinculación</p>
                <div class="dashboard-period-box mt-2">
                    <h5 class="mb-0" style="color: var(--primary); font-weight: 600;">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        Período académico actual:
                        <?php if (!empty($periodoNombreDashboard)): ?>
                            <span class="ms-1 fw-bold" style="color: #0f172a;">
                                <?= esc($periodoNombreDashboard) ?>
                            </span>
                            <?php if (!empty($periodoRangoDashboard)): ?>
                                <span class="text-muted fs-6 fw-normal ms-1" style="font-weight: 500;">
                                    (<?= esc($periodoRangoDashboard) ?>)
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted fw-normal ms-1" style="font-weight: 500;">
                                No hay período configurado
                            </span>
                        <?php endif; ?>
                    </h5>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-rol">Coordinador</span>
                <div class="date-time-box d-flex flex-column align-items-end">
                    <span><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y') ?></span>
                    <span><i class="fas fa-clock me-1"></i><span id="currentTime"></span></span>
                </div>
            </div>
        </div>

        <!-- Métricas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <a href="<?= base_url('admin/estudiantes') ?>" style="text-decoration: none;">
                    <div class="card metric-card text-white" style="background: var(--gradient-actividades);">
                        <div class="card-body">
                            <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="mb-0" id="totalEstudiantes"><?= number_format($metricas['totalEstudiantes'] ?? 0) ?></h3>
                            <p class="metric-label mb-0">Total Estudiantes</p>
                            <small class="metric-sub">Registrados</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?= base_url('admin/instructores') ?>" style="text-decoration: none;">
                    <div class="card metric-card text-white" style="background: var(--gradient-serv);">
                        <div class="card-body">
                            <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h3 class="mb-0" id="totalInstructores"><?= number_format($metricas['totalInstructores'] ?? 0) ?></h3>
                            <p class="metric-label mb-0">Instructores</p>
                            <small class="metric-sub"><?= (int)($metricas['instructoresInternos'] ?? 0) ?> internos, <?= (int)($metricas['instructoresExternos'] ?? 0) ?> externos</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?= base_url('admin/educacion') ?>" style="text-decoration: none;">
                    <div class="card metric-card text-white" style="background: var(--gradient-pre);">
                        <div class="card-body">
                            <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3 class="mb-0" id="actividadesActivas"><?= number_format($metricas['actividadesActivas'] ?? 0) ?></h3>
                            <p class="metric-label mb-0">Actividades Activas</p>
                            <small class="metric-sub">En curso</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="<?= base_url('admin/convenios') ?>" style="text-decoration: none;">
                    <div class="card metric-card text-white" style="background: var(--gradient-active);">
                        <div class="card-body">
                            <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h3 class="mb-0" id="conveniosPorCaducar"><?= number_format($metricas['conveniosPorCaducar'] ?? 0) ?></h3>
                            <p class="metric-label mb-0">Convenios por caducar</p>
                            <small class="metric-sub">Próximos 3 meses</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Estudiantes en prácticas por mes
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="actividadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prácticas y servicio comunitario por carrera -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Prácticas preprofesionales y servicio comunitario por carrera
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 320px;">
                            <canvas id="practicasPorCarreraChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximos Vencimientos -->
        <div class="card card-dash mb-4">
            <div class="card-header">
                <i class="fas fa-list-check me-2 text-primary"></i>Próximos Vencimientos
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dash table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Elemento</th>
                                <th>Fecha vencimiento</th>
                                <th>Días restantes</th>
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
                                    <td colspan="4" class="empty-state text-center">
                                        <div><i class="fas fa-folder-open d-block"></i></div>
                                        <span>No hay convenios próximos a vencer</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
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

        // Gráfica: Estudiantes en prácticas preprofesionales y servicio comunitario por mes
        const actividadesCtx = document.getElementById('actividadesChart').getContext('2d');

        <?php
        $estadisticasMensuales = $datosGraficas['estadisticasMensuales'] ?? [];
        $meses = array_column($estadisticasMensuales, 'mes');
        $datosPreprofesionales = array_column($estadisticasMensuales, 'preprofesionales');
        $datosServicioComunitario = array_column($estadisticasMensuales, 'servicioComunitario');
        ?>

        if (<?= json_encode($meses) ?> && <?= json_encode($meses) ?>.length > 0) {
            const actividadesChart = new Chart(actividadesCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($meses) ?>,
                    datasets: [{
                        label: 'Prácticas preprofesionales',
                        data: <?= json_encode($datosPreprofesionales) ?>,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Servicio comunitario',
                        data: <?= json_encode($datosServicioComunitario) ?>,
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
            console.log('No hay datos para mostrar en el gráfico de prácticas');
        }

        // Gráfica de barras: Prácticas preprofesionales y servicio comunitario por carrera
        const practicasPorCarreraCtx = document.getElementById('practicasPorCarreraChart');
        if (practicasPorCarreraCtx) {
            <?php
            $practicasPorCarrera = $practicasPorCarrera ?? [];
            $carrerasPracticas = array_column($practicasPorCarrera, 'CARRERA');
            $datosPreprofCarrera = array_column($practicasPorCarrera, 'PREPROFESIONALES');
            $datosServCarrera = array_column($practicasPorCarrera, 'SERVICIO_COMUNITARIO');
            ?>
            if (<?= json_encode($carrerasPracticas) ?> && <?= json_encode($carrerasPracticas) ?>.length > 0) {
                new Chart(practicasPorCarreraCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($carrerasPracticas) ?>,
                        datasets: [{
                            label: 'Prácticas preprofesionales',
                            data: <?= json_encode($datosPreprofCarrera) ?>,
                            backgroundColor: 'rgba(102, 126, 234, 0.8)',
                            borderColor: '#667eea',
                            borderWidth: 1
                        }, {
                            label: 'Servicio comunitario',
                            data: <?= json_encode($datosServCarrera) ?>,
                            backgroundColor: 'rgba(240, 147, 251, 0.8)',
                            borderColor: '#f093fb',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.08)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                const ctx = practicasPorCarreraCtx.getContext('2d');
                ctx.font = '16px Arial';
                ctx.fillStyle = '#666';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos de prácticas por carrera', practicasPorCarreraCtx.canvas.width / 2, practicasPorCarreraCtx.canvas.height / 2);
            }
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