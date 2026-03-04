<?= $this->extend('docente/layouts/mainDocente') ?>

<?php
use Config\Database;
$periodoNombreDashboard = $periodoAcademicoNombre ?? session('periodo_academico_nombre');
$periodoRangoDashboard  = $periodoAcademicoRango ?? session('periodo_academico_rango');
if (!$periodoNombreDashboard) {
    try {
        $db = Database::connect();
        $row = $db->query("SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL LIMIT 1")->getRowArray();
        if ($row) {
            $periodoNombreDashboard = $row['NOMBRE_PERIODO'] ?? null;
            $periodoRangoDashboard  = ($row['FECHA_INICIO'] ?? '') . ' - ' . ($row['FECHA_FIN'] ?? '');
            if ($periodoNombreDashboard) session()->set('periodo_academico_nombre', $periodoNombreDashboard);
            if ($periodoRangoDashboard) session()->set('periodo_academico_rango', $periodoRangoDashboard);
        }
    } catch (\Throwable $e) {
        log_message('error', 'Dashboard docente - error obteniendo período: ' . $e->getMessage());
    }
}
?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Diseño igual que dashboard Estudiante */
    :root {
        --dashboard-radius: 16px;
        --dashboard-shadow: 0 4px 20px rgba(0,0,0,0.06);
        --dashboard-shadow-hover: 0 12px 32px rgba(0,0,0,0.12);
        --gradient-pre: linear-gradient(145deg, #0ea5e9 0%, #06b6d4 100%);
        --gradient-serv: linear-gradient(145deg, #ec4899 0%, #f59e0b 100%);
        --gradient-active: linear-gradient(145deg, #10b981 0%, #14b8a6 100%);
        --gradient-actividades: linear-gradient(145deg, #6366f1 0%, #8b5cf6 100%);
    }
    .dashboard-page { font-family: 'Segoe UI', system-ui, sans-serif; }

    .dashboard-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: var(--dashboard-radius);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .dashboard-header .title-dash { font-weight: 700; font-size: 1.6rem; color: #0f172a; letter-spacing: -0.02em; }
    .dashboard-header .subtitle-dash { color: #64748b; font-size: 0.95rem; }
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
    .metric-card h3 { font-weight: 700; font-size: 1.75rem; margin-bottom: 0.2rem; }
    .metric-card .metric-label { font-weight: 600; font-size: 0.9rem; opacity: 0.95; }
    .metric-card .metric-sub { font-size: 0.8rem; opacity: 0.85; }

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
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        color: inherit;
    }

    .chart-container { position: relative; height: 280px; margin: 1rem 0; }

    .table-dash { margin-bottom: 0; }
    .table-dash thead th {
        font-weight: 600;
        color: #475569;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1rem;
    }
    .table-dash tbody td { padding: 1rem; vertical-align: middle; }
    .table-dash tbody tr { transition: background 0.15s ease; }
    .table-dash tbody tr:hover { background: #f8fafc; }
    .table-dash .badge { font-weight: 600; padding: 0.35rem 0.65rem; font-size: 0.75rem; }
    .table-dash .btn-sm { border-radius: 8px; font-weight: 600; padding: 0.35rem 0.75rem; }
    .empty-state { padding: 3rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.6; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper dashboard-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <!-- Header del Dashboard (igual que Estudiante) -->
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
                <span class="badge badge-rol">Docente</span>
                <div class="date-time-box d-flex flex-column align-items-end">
                    <span><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y') ?></span>
                    <span><i class="fas fa-clock me-1"></i><span id="currentTime"></span></span>
                </div>
            </div>
        </div>

        <!-- Métricas (diseño igual que Estudiante) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card text-white" style="background: var(--gradient-actividades);">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="mb-0"><?= $total_actividades ?? 0 ?></h3>
                        <p class="metric-label mb-0">Total Actividades</p>
                        <small class="metric-sub">Cursos y talleres</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card text-white" style="background: var(--gradient-serv);">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="mb-0"><?= $actividades_activas ?? 0 ?></h3>
                        <p class="metric-label mb-0">Actividades Activas</p>
                        <small class="metric-sub">En progreso</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card text-white" style="background: var(--gradient-pre);">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="mb-0"><?= $total_estudiantes ?? 0 ?></h3>
                        <p class="metric-label mb-0">Total Estudiantes</p>
                        <small class="metric-sub">Registrados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card text-white" style="background: var(--gradient-active);">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="mb-0">4.8</h3>
                        <p class="metric-label mb-0">Calificación</p>
                        <small class="metric-sub">Promedio</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas (diseño igual que Estudiante) -->
        <div class="card card-dash mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2 text-warning"></i>Acciones rápidas
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="<?= base_url('docente/educacion') ?>" class="btn btn-primary quick-action-btn w-100">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nueva Actividad</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="<?= base_url('docente/actividades') ?>" class="quick-action-btn w-100 text-white" style="background: var(--gradient-active);">
                            <i class="fas fa-list"></i>
                            <span>Mis Actividades</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="<?= base_url('docente/estudiantes') ?>" class="btn btn-info quick-action-btn w-100 text-white">
                            <i class="fas fa-users"></i>
                            <span>Ver Estudiantes</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="<?= base_url('docente/perfil') ?>" class="btn btn-warning quick-action-btn w-100 text-dark">
                            <i class="fas fa-user-edit"></i>
                            <span>Mi Perfil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas (diseño igual que Estudiante) -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Actividades por Mes
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="actividadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Distribución de Actividades
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="distribucionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividades Recientes -->
        <div class="card card-dash mb-4">
            <div class="card-header">
                <i class="fas fa-list-check me-2 text-primary"></i>Actividades Recientes
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dash table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Tipo</th>
                                <th>Fecha Inicio</th>
                                <th>Estado</th>
                                <th>Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="empty-state text-center">
                                    <div><i class="fas fa-folder-open d-block"></i></div>
                                    <span>No hay actividades registradas aún</span>
                                </td>
                            </tr>
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
    // Actualizar hora en tiempo real (igual que Estudiante)
    function actualizarHora() {
        const span = document.getElementById('currentTime');
        if (span) {
            const ahora = new Date();
            span.textContent = ahora.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }
    setInterval(actualizarHora, 1000);
    actualizarHora();

    // Gráfica de actividades por mes
    const ctxActividades = document.getElementById('actividadesChart').getContext('2d');
    new Chart(ctxActividades, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Actividades',
                data: [2, 3, 1, 4, 2, 3, 5, 2, 4, 3, 2, 1],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
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
                        display: false
                    }
                }
            }
        }
    });

    // Gráfica de distribución
    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
    new Chart(ctxDistribucion, {
        type: 'doughnut',
        data: {
            labels: ['Cursos', 'Talleres', 'Seminarios'],
            datasets: [{
                data: [40, 35, 25],
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe'
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
</script>
<?= $this->endSection() ?>
