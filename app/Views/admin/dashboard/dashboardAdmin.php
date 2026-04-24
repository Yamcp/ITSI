<?= $this->extend($layout ?? 'admin/layouts/mainAdmin') ?>

<?php

$p = obtener_periodo_academico_para_ui();
$periodoNombreDashboard = $p['nombre'];
$periodoRangoDashboard = $p['rango'];

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
        --gradient-docentes: linear-gradient(145deg, #f59e0b 0%, #f97316 100%);
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
        padding: 1rem;
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
                <span class="badge badge-rol">Administrador</span>
                <div class="date-time-box d-flex flex-column align-items-end">
                    <span><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y') ?></span>
                    <span><i class="fas fa-clock me-1"></i><span id="currentTime"></span></span>
                </div>
            </div>
        </div>

        <!-- Métricas -->
        <div class="row g-3 mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6">
                <a href="<?= base_url('admin/estudiantes') ?>" style="text-decoration: none;">
                    <div class="card metric-card text-white" style="background: var(--gradient-actividades);">
                        <div class="card-body text-center">
                            <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h3 class="mb-0"><?= number_format($metricas['totalEstudiantes'] ?? 0) ?></h3>
                            <p class="metric-label mb-0">Total Estudiantes</p>
                            <small class="metric-sub">Registrados en el sistema</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card text-white" style="background: var(--gradient-docentes);">
                    <div class="card-body text-center">
                        <div class="metric-icon mx-auto mb-2" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="mb-0"><?= number_format($metricas['totalDocentes'] ?? 0) ?></h3>
                        <p class="metric-label mb-0">Docentes Tutores</p>
                        <small class="metric-sub">Activos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="row g-3 mb-4">
            <!-- Estudiantes por semestre -->
            <div class="col-lg-7">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Estudiantes por Semestre
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="estudiantesSemestreChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Estudiantes por carrera -->
            <div class="col-lg-5">
                <div class="card card-dash">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Estudiantes por Carrera
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 280px;">
                            <canvas id="estudiantesCarreraChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reloj en tiempo real
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-EC', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const el = document.getElementById('currentTime');
            if (el) el.textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        if (typeof Chart === 'undefined') {
            console.error('Chart.js no se pudo cargar');
            return;
        }

        // === Estudiantes por Semestre (Bar Chart) ===
        <?php
        $estudiantesPorSemestre = $estudiantesPorSemestre ?? [];
        $semestresLabels = array_map(fn($r) => 'Semestre ' . $r['semestre'], $estudiantesPorSemestre);
        $semestresData = array_map(fn($r) => (int) $r['total'], $estudiantesPorSemestre);
        ?>
        const semestreCtx = document.getElementById('estudiantesSemestreChart');
        if (semestreCtx) {
            const sLabels = <?= json_encode($semestresLabels) ?>;
            const sData = <?= json_encode($semestresData) ?>;
            if (sLabels && sLabels.length > 0) {
                new Chart(semestreCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: sLabels,
                        datasets: [{
                            label: 'Estudiantes',
                            data: sData,
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)', 'rgba(139, 92, 246, 0.8)',
                                'rgba(14, 165, 233, 0.8)', 'rgba(6, 182, 212, 0.8)',
                                'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)', 'rgba(236, 72, 153, 0.8)',
                                'rgba(168, 85, 247, 0.8)', 'rgba(59, 130, 246, 0.8)'
                            ],
                            borderColor: [
                                '#6366f1', '#8b5cf6', '#0ea5e9', '#06b6d4', '#10b981',
                                '#f59e0b', '#ef4444', '#ec4899', '#a855f7', '#3b82f6'
                            ],
                            borderWidth: 1, borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.06)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            } else {
                const ctx2 = semestreCtx.getContext('2d');
                ctx2.font = '16px Segoe UI'; ctx2.fillStyle = '#94a3b8'; ctx2.textAlign = 'center';
                ctx2.fillText('No hay datos de estudiantes por semestre', semestreCtx.width / 2, semestreCtx.height / 2);
            }
        }

        // === Estudiantes por Carrera (Doughnut Chart) ===
        <?php
        $estudiantesPorCarrera = $estudiantesPorCarrera ?? [];
        $carrerasLabels = array_column($estudiantesPorCarrera, 'CARRERA');
        $carrerasData = array_map(fn($r) => (int) $r['total'], $estudiantesPorCarrera);
        ?>
        const carreraCtx = document.getElementById('estudiantesCarreraChart');
        if (carreraCtx) {
            const cLabels = <?= json_encode($carrerasLabels) ?>;
            const cData = <?= json_encode($carrerasData) ?>;
            if (cLabels && cLabels.length > 0) {
                new Chart(carreraCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: cLabels,
                        datasets: [{
                            data: cData,
                            backgroundColor: [
                                '#6366f1', '#0ea5e9', '#10b981', '#f59e0b',
                                '#ef4444', '#ec4899', '#8b5cf6', '#06b6d4',
                                '#14b8a6', '#f97316'
                            ],
                            borderWidth: 2, borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, font: { size: 11 } } }
                        }
                    }
                });
            } else {
                const ctx3 = carreraCtx.getContext('2d');
                ctx3.font = '16px Segoe UI'; ctx3.fillStyle = '#94a3b8'; ctx3.textAlign = 'center';
                ctx3.fillText('No hay datos de estudiantes por carrera', carreraCtx.width / 2, carreraCtx.height / 2);
            }
        }


    });
</script>
<?= $this->endSection() ?>