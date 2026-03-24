<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?php

$p                       = obtener_periodo_academico_para_ui();
$periodoNombreDashboard  = $p['nombre'];
$periodoRangoDashboard   = $p['rango'];

?>

<?= $this->section('styles') ?>
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

    /* Header */
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

    /* Metric cards */
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

    /* Progress cards */
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

    /* Quick actions */
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

    /* Charts */

    /* Table */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper dashboard-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('warning')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($asistencia_pendiente)): ?>
            <div class="alert alert-danger border-0 shadow-sm mt-3 mb-0" role="alert" style="border-radius: 12px;">
                <h5 class="alert-heading mb-2"><i class="fas fa-user-clock me-2"></i>Asistencia obligatoria</h5>
                <p class="mb-2">Debes registrar la asistencia de <strong>hoy</strong> para cada práctica o servicio comunitario que tengas en estado <strong>En progreso</strong>. Hasta entonces el acceso al resto del portal (cursos, perfil, convenios, etc.) permanece restringido; puedes usar el panel, la sección de prácticas y la documentación.</p>
                <p class="mb-0 small">Usa el formulario que se muestra a continuación o entra a <a href="<?= site_url('estudiante/practicas') ?>" class="alert-link">Prácticas preprofesionales</a> o a <a href="<?= site_url('estudiante/documentos-servicio-comunitario') ?>" class="alert-link">Documentación de servicio comunitario</a> para registrar asistencia si aplica.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($asistencia_items)): ?>
            <?= $this->include('estudiante/partials/asistencia_registro_estudiante') ?>
        <?php endif; ?>

        <!-- Header -->
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
                <span class="badge badge-rol">Estudiante</span>
                <div class="date-time-box d-flex flex-column align-items-end">
                    <span><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y') ?></span>
                    <span><i class="fas fa-clock me-1"></i><span id="currentTime"></span></span>
                </div>
            </div>
        </div>

        <!-- Progreso por tipo -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="progress-card-dash" style="background: var(--gradient-pre);">
                    <h5><i class="fas fa-user-graduate me-2"></i>Prácticas preprofesionales</h5>
                    <div class="progress mb-2">
                        <?php
                        $totPre = (int)($total_preprofesionales ?? 0);
                        $actPre = (int)($preprofesionales_activas ?? 0);
                        $pctPre = $totPre > 0 ? round((($totPre - $actPre) / $totPre) * 100) : 0;
                        ?>
                        <div class="progress-bar bg-white" role="progressbar" style="width: <?= $pctPre ?>%" aria-valuenow="<?= $pctPre ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small><?= $totPre - $actPre ?> finalizadas · <?= $actPre ?> en progreso</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="progress-card-dash" style="background: var(--gradient-serv);">
                    <h5><i class="fas fa-hands-helping me-2"></i>Servicio comunitario</h5>
                    <div class="progress mb-2">
                        <?php
                        $totSc = (int)($total_servicio_comunitario ?? 0);
                        $actSc = (int)($servicio_comunitario_activos ?? 0);
                        $pctSc = $totSc > 0 ? round((($totSc - $actSc) / $totSc) * 100) : 0;
                        ?>
                        <div class="progress-bar bg-white" role="progressbar" style="width: <?= $pctSc ?>%" aria-valuenow="<?= $pctSc ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small><?= $totSc - $actSc ?> finalizadas · <?= $actSc ?> en progreso</small>
                </div>
            </div>
        </div>

        <!-- Mis prácticas -->
        <div class="card card-dash mb-4">
            <div class="card-header">
                <i class="fas fa-list-check me-2 text-primary"></i>Mis prácticas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dash table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Institución</th>
                                <th>Fecha inicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hayPracticas = false;
                            if (!empty($practicas_preprofesionales)):
                                foreach ($practicas_preprofesionales as $p):
                                    $hayPracticas = true;
                                    $fechaInicio = !empty($p['FECHA_INICIO']) ? date('d/m/Y', strtotime($p['FECHA_INICIO'])) : '—';
                            ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><i class="fas fa-user-graduate me-1"></i>Preprofesional</span></td>
                                        <td><?= esc($p['INSTITUCION_NOMBRE'] ?? '—') ?></td>
                                        <td><?= $fechaInicio ?></td>
                                        <td><span class="badge bg-<?= (isset($p['ESTADO_PRACTICA']) && $p['ESTADO_PRACTICA'] === 'En Progreso') ? 'success' : 'secondary' ?>"><?= esc($p['ESTADO_PRACTICA'] ?? '—') ?></span></td>
                                        <td><a href="<?= site_url('estudiante/practicas') ?>" class="btn btn-sm btn-outline-primary">Ver</a></td>
                                    </tr>
                                <?php endforeach;
                            endif;
                            if (!empty($servicios_comunitarios)):
                                foreach ($servicios_comunitarios as $s):
                                    $hayPracticas = true;
                                    $fechaInicio = !empty($s['FECHA_INICIO']) ? date('d/m/Y', strtotime($s['FECHA_INICIO'])) : '—';
                                ?>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark"><i class="fas fa-hands-helping me-1"></i>Servicio comunitario</span></td>
                                        <td><?= esc($s['INSTITUCION_NOMBRE'] ?? '—') ?></td>
                                        <td><?= $fechaInicio ?></td>
                                        <td><span class="badge bg-<?= (isset($s['ESTADO_SERVICIO']) && $s['ESTADO_SERVICIO'] === 'En Progreso') ? 'success' : 'secondary' ?>"><?= esc($s['ESTADO_SERVICIO'] ?? '—') ?></span></td>
                                        <td><a href="<?= site_url('estudiante/documentos-servicio-comunitario') ?>" class="btn btn-sm btn-outline-warning text-dark">Ver</a></td>
                                    </tr>
                                <?php endforeach;
                            endif;
                            if (!$hayPracticas): ?>
                                <tr>
                                    <td colspan="5" class="empty-state text-center">
                                        <div><i class="fas fa-folder-open d-block"></i></div>
                                        <span>No tienes prácticas preprofesionales ni de servicio comunitario asignadas aún.</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actividades disponibles -->
        <div class="card card-dash">
            <div class="card-header">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Actividades disponibles
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="activity-card">
                            <div class="activity-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h6>Curso de Programación</h6>
                            <p class="mb-0">Desarrollo web con PHP</p>
                            <span class="badge bg-primary">Disponible</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="activity-card">
                            <div class="activity-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-database"></i>
                            </div>
                            <h6>Taller de Bases de Datos</h6>
                            <p class="mb-0">MySQL y PostgreSQL</p>
                            <span class="badge bg-success">Inscrito</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="activity-card">
                            <div class="activity-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h6>Seminario de Análisis</h6>
                            <p class="mb-0">Estadística aplicada</p>
                            <span class="badge bg-info">Próximamente</span>
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
    // Actualizar hora en tiempo real (el elemento currentTime existe en la vista)
    function actualizarHora() {
        const span = document.getElementById('currentTime');
        if (span) {
            const ahora = new Date();
            span.textContent = ahora.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
    setInterval(actualizarHora, 1000);
    actualizarHora();
</script>
<?= $this->include('estudiante/partials/asistencia_registro_estudiante_script') ?>
<?= $this->endSection() ?>