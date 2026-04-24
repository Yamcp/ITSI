<?= $this->extend($layout ?? 'admin/layouts/mainAdmin') ?>

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
                <span class="badge badge-rol">Administrador</span>
                <div class="date-time-box d-flex flex-column align-items-end">
                    <span><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y') ?></span>
                    <span><i class="fas fa-clock me-1"></i><span id="currentTime"></span></span>
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
    }
</script>
<?= $this->endSection() ?>