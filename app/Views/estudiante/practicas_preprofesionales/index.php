<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
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

    .practicas-page {
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .page-header-practicas {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: var(--dashboard-radius);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .page-header-practicas .title-page {
        font-weight: 700;
        font-size: 1.5rem;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .metric-card-practicas {
        border: none;
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }

    .metric-card-practicas:hover {
        transform: translateY(-4px);
        box-shadow: var(--dashboard-shadow-hover);
    }

    .metric-card-practicas .card-body {
        padding: 1.35rem 1.25rem;
    }

    .metric-card-practicas .metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #fff;
        opacity: 0.95;
        margin: 0 auto 0.5rem;
    }

    .metric-card-practicas h3 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.2rem;
    }

    .metric-card-practicas .metric-label {
        font-weight: 600;
        font-size: 0.9rem;
        opacity: 0.95;
        white-space: nowrap;
    }

    .metric-card-practicas .metric-sub {
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

    .action-card-practicas {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
        text-align: center;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .action-card-practicas:hover {
        box-shadow: var(--dashboard-shadow);
        border-color: #cbd5e1;
        color: inherit;
    }

    .action-card-practicas .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 0.75rem;
    }

    .action-card-practicas .action-label {
        font-weight: 600;
        font-size: 0.95rem;
        color: #0f172a;
    }

    .practica-card {
        border: none;
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .practica-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--dashboard-shadow-hover);
    }

    .practica-header {
        background: var(--gradient-actividades);
        color: white;
        border-radius: 0;
        padding: 1.25rem 1.5rem;
    }

    .practica-body {
        padding: 1.5rem;
    }

    .estado-badge {
        border-radius: 999px;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .progreso-circular {
        width: 80px;
        height: 80px;
        position: relative;
        margin: 0 auto;
    }

    .progreso-texto {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 0.9rem;
    }

    .accion-btn {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .accion-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6366f1;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 1rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #e2e8f0;
    }

    .documento-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .documento-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper practicas-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <!-- Header (mismo estilo que dashboard) -->
        <div class="page-header-practicas">
            <h1 class="title-page mb-0">
                <i class="fas fa-user-graduate me-2 text-primary"></i>
                Prácticas Preprofesionales
            </h1>
            <p class="text-muted mb-0 mt-1" style="font-size: 0.95rem;">Gestiona tu documentación y progreso.</p>
        </div>

        <!-- Métricas (mismo diseño que dashboard: icono arriba, número, etiqueta) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas text-white" style="background: var(--gradient-actividades);">
                    <div class="card-body text-center">
                        <div class="metric-icon" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['totalPracticas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Total</p>
                        <small class="metric-sub"><?= (int)($horas_requeridas_preprof ?? 240) ?> h (una vez en la carrera)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas text-white" style="background: var(--gradient-active);">
                    <div class="card-body text-center">
                        <div class="metric-icon" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['practicasActivas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">En progreso</p>
                        <small class="metric-sub">Activas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas text-white" style="background: var(--gradient-pre);">
                    <div class="card-body text-center">
                        <div class="metric-icon" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['practicasFinalizadas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Finalizadas</p>
                        <small class="metric-sub">Completadas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas text-white" style="background: var(--gradient-serv);">
                    <div class="card-body text-center">
                        <div class="metric-icon" style="background: rgba(255,255,255,0.25);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['horasCompletadas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Horas</p>
                        <small class="metric-sub">Completadas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas (mismo estilo que dashboard) -->
        <div class="card card-dash mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2 text-warning"></i>Acciones rápidas
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="#" onclick="registrarActividad(); return false;" class="action-card-practicas">
                            <div class="action-icon bg-success bg-opacity-10 text-success mx-auto">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <span class="action-label">Registrar Actividad</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="#" onclick="subirDocumento(); return false;" class="action-card-practicas">
                            <div class="action-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="fas fa-upload"></i>
                            </div>
                            <span class="action-label">Subir Documento</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="#" onclick="verProgreso(); return false;" class="action-card-practicas">
                            <div class="action-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="action-label">Ver Progreso</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="https://wa.me/593995298537" target="_blank" rel="noopener noreferrer" class="action-card-practicas">
                            <div class="action-icon bg-danger bg-opacity-10 text-danger mx-auto">
                                <i class="fas fa-comments"></i>
                            </div>
                            <span class="action-label">Contactar Supervisor</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Prácticas Preprofesionales -->
        <div class="card card-dash">
            <div class="card-header">
                <i class="fas fa-building me-2 text-primary"></i>Mis prácticas preprofesionales
            </div>
            <div class="card-body">
                <?php if (!empty($practicasPreprofesionales)): ?>
                    <?php foreach ($practicasPreprofesionales as $practica): ?>
                        <div class="practica-card">
                            <div class="practica-header">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-1">
                                            <i class="fas fa-building me-2"></i>
                                            <?= $practica['INSTITUCION_NOMBRE'] ?>
                                        </h5>
                                        <p class="mb-0 opacity-75"><?= $practica['PROYECTO_ESPECIFICO'] ?? 'Sin descripción específica' ?></p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <?php
                                        $estadoClass = '';
                                        switch ($practica['ESTADO_PRACTICA']) {
                                            case 'Completada':
                                                $estadoClass = 'bg-success text-white';
                                                break;
                                            case 'En Progreso':
                                                $estadoClass = 'bg-warning text-dark';
                                                break;
                                            case 'Pendiente':
                                                $estadoClass = 'bg-info text-dark';
                                                break;
                                            default:
                                                $estadoClass = 'bg-secondary text-white';
                                        }
                                        ?>
                                        <span class="estado-badge <?= $estadoClass ?>"><?= $practica['ESTADO_PRACTICA'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="practica-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Período:</strong><br>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($practica['FECHA_INICIO'])) ?> -
                                                    <?= date('d/m/Y', strtotime($practica['FECHA_FIN'])) ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Horas Totales:</strong><br>
                                                <span class="badge bg-info"><?= (int)($practica['HORAS_PRACTICAS'] ?? 0) ?> h</span>
                                                <small class="d-block text-muted mt-1">Meta: <?= (int)($horas_requeridas_preprof ?? 240) ?> h (una sola vez en la carrera)</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Supervisor:</strong><br>
                                                <small class="text-muted"><?= $practica['SUPERVISOR_NOMBRE'] ?? 'No asignado' ?></small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Progreso:</strong><br>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                                </div>
                                                <small class="text-muted">75% completado</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="progreso-circular">
                                            <canvas id="progresoPre<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>" width="80" height="80" data-porcentaje="75"></canvas>
                                            <div class="progreso-texto">75%</div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-outline-primary accion-btn" onclick="verDetallePractica(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                            </button>
                                            <button class="btn btn-outline-success accion-btn" onclick="registrarActividadPractica(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                <i class="fas fa-plus me-1"></i>Registrar
                                            </button>
                                            <button class="btn btn-outline-info accion-btn" onclick="verDocumentos(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                <i class="fas fa-file-alt me-1"></i>Documentos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes prácticas preprofesionales asignadas</h5>
                        <p class="text-muted">Contacta con tu coordinador para más información.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        $tiposArr = $tipos_documentos ?? [];
        $progreso = $progreso_documentos ?? [];
        $idx = 0;
        ?>

        <!-- Tabla 1: Informe de Prácticas Laborales -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-file-contract me-2"></i>
                            INFORME DE PRÁCTICAS LABORALES
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60" class="text-center">NUM</th>
                                        <th>DATOS</th>
                                        <th width="140" class="text-center">CHECKLIST</th>
                                        <th width="160" class="text-center">ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($checklist_informe ?? []) as $item): ?>
                                        <?php
                                        $tipo = $tiposArr[$idx] ?? $tiposArr[0] ?? null;
                                        $idTipo = $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $tipo['ID_TIPO_DOCUMENTO'] ?? 0;
                                        $docRow = null;
                                        foreach ($progreso as $doc) {
                                            $docTipoId = $doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? null;
                                            if ($docTipoId == $idTipo && !empty($doc['ID_DOCUMENTO_PRACTICA'] ?? $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? null)) {
                                                $docRow = $doc;
                                                break;
                                            }
                                        }
                                        $estado = $docRow ? ($docRow['ESTADO_REVISION'] ?? 'Pendiente') : 'Pendiente';
                                        $idDoc = $docRow['ID_DOCUMENTO_PRACTICA'] ?? $docRow['ID_DOCUMENTO_PREPROFESIONAL'] ?? null;
                                        $idx++;
                                        ?>
                                        <tr>
                                            <td class="text-center fw-bold"><?= $item['num'] ?></td>
                                            <td><small><?= esc($item['datos']) ?></small></td>
                                            <td class="text-center">
                                                <?php if ($estado === 'Aprobado'): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprobado</span>
                                                <?php elseif ($docRow): ?>
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i><?= $estado ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><i class="fas fa-minus me-1"></i>Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($docRow && $idDoc): ?>
                                                    <button type="button" class="btn btn-outline-primary btn-sm me-1" onclick="verDocumentoPractica(<?= (int)$idDoc ?>)" title="Ver"><i class="fas fa-eye"></i></button>
                                                    <button type="button" class="btn btn-outline-success btn-sm me-1" onclick="descargarDocumentoPractica(<?= (int)$idDoc ?>)" title="Descargar"><i class="fas fa-download"></i></button>
                                                    <?php if ($estado !== 'Aprobado'): ?>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-trash"></i></button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="mostrarModalSubirDoc(this)" data-tipo-id="<?= (int)$idTipo ?>" data-desc="<?= esc($item['datos']) ?>"><i class="fas fa-upload me-1"></i>Subir</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla 2: Rúbricas y hojas de asistencia para el seguimiento docente -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Rúbricas y hojas de asistencia para el seguimiento docente
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60" class="text-center">NUM</th>
                                        <th>DATOS</th>
                                        <th width="140" class="text-center">CHECKLIST</th>
                                        <th width="160" class="text-center">ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($checklist_rubricas ?? []) as $item): ?>
                                        <?php if (!empty($item['items'])): ?>
                                            <?php foreach ($item['items'] as $sub): ?>
                                                <?php
                                                $tipo = $tiposArr[$idx] ?? $tiposArr[0] ?? null;
                                                $idTipo = $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $tipo['ID_TIPO_DOCUMENTO'] ?? 0;
                                                $docRow = null;
                                                foreach ($progreso as $doc) {
                                                    $docTipoId = $doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? null;
                                                    if ($docTipoId == $idTipo && !empty($doc['ID_DOCUMENTO_PRACTICA'] ?? $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? null)) {
                                                        $docRow = $doc;
                                                        break;
                                                    }
                                                }
                                                $estado = $docRow ? ($docRow['ESTADO_REVISION'] ?? 'Pendiente') : 'Pendiente';
                                                $idDoc = $docRow['ID_DOCUMENTO_PRACTICA'] ?? $docRow['ID_DOCUMENTO_PREPROFESIONAL'] ?? null;
                                                $idx++;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><small class="text-muted"><?= $item['num'] ?></small></td>
                                                    <td><small><?= esc($sub['datos']) ?></small></td>
                                                    <td class="text-center">
                                                        <?php if ($estado === 'Aprobado'): ?>
                                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprobado</span>
                                                        <?php elseif ($docRow): ?>
                                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i><?= $estado ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary"><i class="fas fa-minus me-1"></i>Pendiente</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($docRow && $idDoc): ?>
                                                            <button type="button" class="btn btn-outline-primary btn-sm me-1" onclick="verDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-eye"></i></button>
                                                            <button type="button" class="btn btn-outline-success btn-sm me-1" onclick="descargarDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-download"></i></button>
                                                            <?php if ($estado !== 'Aprobado'): ?>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-trash"></i></button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="mostrarModalSubirDoc(this)" data-tipo-id="<?= (int)$idTipo ?>" data-desc="<?= esc($sub['datos']) ?>"><i class="fas fa-upload me-1"></i>Subir</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php
                                            $tipo = $tiposArr[$idx] ?? $tiposArr[0] ?? null;
                                            $idTipo = $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $tipo['ID_TIPO_DOCUMENTO'] ?? 0;
                                            $docRow = null;
                                            foreach ($progreso as $doc) {
                                                $docTipoId = $doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? null;
                                                if ($docTipoId == $idTipo && !empty($doc['ID_DOCUMENTO_PRACTICA'] ?? $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? null)) {
                                                    $docRow = $doc;
                                                    break;
                                                }
                                            }
                                            $estado = $docRow ? ($docRow['ESTADO_REVISION'] ?? 'Pendiente') : 'Pendiente';
                                            $idDoc = $docRow['ID_DOCUMENTO_PRACTICA'] ?? $docRow['ID_DOCUMENTO_PREPROFESIONAL'] ?? null;
                                            $idx++;
                                            ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $item['num'] ?></td>
                                                <td><small><?= esc($item['datos']) ?></small></td>
                                                <td class="text-center">
                                                    <?php if ($estado === 'Aprobado'): ?>
                                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprobado</span>
                                                    <?php elseif ($docRow): ?>
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i><?= $estado ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><i class="fas fa-minus me-1"></i>Pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($docRow && $idDoc): ?>
                                                        <button type="button" class="btn btn-outline-primary btn-sm me-1" onclick="verDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-eye"></i></button>
                                                        <button type="button" class="btn btn-outline-success btn-sm me-1" onclick="descargarDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-download"></i></button>
                                                        <?php if ($estado !== 'Aprobado'): ?>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocumentoPractica(<?= (int)$idDoc ?>)"><i class="fas fa-trash"></i></button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="mostrarModalSubirDoc(this)" data-tipo-id="<?= (int)$idTipo ?>" data-desc="<?= esc($item['datos']) ?>"><i class="fas fa-upload me-1"></i>Subir</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    <strong>ENVÍO DE DOCUMENTO FINAL Y LLENAR LA BASE DE DATOS:</strong> Una vez completados los documentos, envía el informe final y registra los datos en el sistema.
                </p>
            </div>
        </div>

        <!-- Alerta: plazo 15 días para documento final -->
        <?php $alertaDoc = $alerta_documento_final ?? ['mostrar' => false]; ?>
        <?php if (!empty($alertaDoc['mostrar'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert <?= !empty($alertaDoc['superado_plazo']) ? 'alert-danger' : 'alert-warning' ?> alert-dismissible fade show shadow-sm mb-0" role="alert">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>Documento final – plazo de 15 días
                        </h6>
                        <p class="mb-0"><?= $alertaDoc['mensaje'] ?? '' ?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Apartado: Subir documento final -->
        <?php
        $idTipoFinal = (int)($id_tipo_documento_final ?? 0);
        $docFinalRow = null;
        if ($idTipoFinal > 0 && !empty($progreso_documentos)) {
            foreach ($progreso_documentos as $doc) {
                $docTipoId = $doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? null;
                if ((int)$docTipoId === $idTipoFinal && !empty($doc['ID_DOCUMENTO_PRACTICA'] ?? $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? null)) {
                    $docFinalRow = $doc;
                    break;
                }
            }
        }
        $estadoFinal = $docFinalRow ? ($docFinalRow['ESTADO_REVISION'] ?? 'Pendiente') : 'Pendiente';
        $idDocFinal = $docFinalRow['ID_DOCUMENTO_PRACTICA'] ?? $docFinalRow['ID_DOCUMENTO_PREPROFESIONAL'] ?? null;
        ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-file-export me-2"></i>
                            Documento final
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Una vez completados todos los documentos del informe y las rúbricas, sube aquí el <strong>documento final</strong> (informe final de prácticas). Será revisado por el coordinador.
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <?php if ($idTipoFinal > 0): ?>
                                <?php if ($docFinalRow && $idDocFinal): ?>
                                    <span class="badge <?= $estadoFinal === 'Aprobado' ? 'bg-success' : ($estadoFinal === 'Rechazado' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                        <i class="fas fa-<?= $estadoFinal === 'Aprobado' ? 'check-circle' : ($estadoFinal === 'Rechazado' ? 'times-circle' : 'clock') ?> me-1"></i><?= $estadoFinal ?>
                                    </span>
                                    <span class="text-muted small">Subido: <?= isset($docFinalRow['FECHA_SUBIDA']) ? date('d/m/Y', strtotime($docFinalRow['FECHA_SUBIDA'])) : '-' ?></span>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="verDocumentoPractica(<?= (int)$idDocFinal ?>)" title="Ver"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="descargarDocumentoPractica(<?= (int)$idDocFinal ?>)" title="Descargar"><i class="fas fa-download"></i></button>
                                    <?php if ($estadoFinal !== 'Aprobado'): ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocumentoPractica(<?= (int)$idDocFinal ?>)"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary" onclick="mostrarModalSubirDoc(this)" data-tipo-id="<?= $idTipoFinal ?>" data-desc="Documento final (informe final de prácticas)"><i class="fas fa-cloud-upload-alt me-1"></i>Subir documento final</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">No hay tipo de documento configurado para documento final. Contacte al administrador.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Documento (checklist prácticas) -->
<div class="modal fade" id="modalSubirDocumentoPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoPractica" enctype="multipart/form-data">
                    <input type="hidden" name="tipo_documento" id="tipo_documento_practica_id">
                    <input type="hidden" name="id_practica" id="id_practica_modal">
                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" class="form-control" id="tipo_documento_practica_nombre" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3"><label class="form-label">Entidad Receptora</label><input type="text" class="form-control" name="entidad_receptora" id="entidad_receptora_modal" placeholder="Ej: Nombre institución"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3"><label class="form-label">Docente Tutor</label><input type="text" class="form-control" name="docente_tutor" id="docente_tutor_modal" placeholder="Nombre del tutor"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <input type="file" class="form-control" name="archivo" id="archivoDocumentoPractica" accept=".pdf,application/pdf" required>
                        <small class="text-muted">Solo PDF. Máximo 10 MB.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones (opcional)</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumentoPractica()"><i class="fas fa-upload me-1"></i>Subir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Práctica -->
<div class="modal fade" id="modalDetallePractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle de Práctica
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información General</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                        <p><strong>Tipo de Práctica:</strong> <span id="detalleTipo">-</span></p>
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Supervisor:</strong> <span id="detalleSupervisor">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Horas:</strong> <span id="detalleHoras">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Descripción:</strong></p>
                                        <p class="text-muted" id="detalleDescripcion">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Actividades Recientes</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div>
                                            <div class="fw-semibold">Desarrollo de módulo de usuarios</div>
                                            <div class="text-muted small">8 horas - 30/08/2025</div>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div>
                                            <div class="fw-semibold">Análisis de requerimientos</div>
                                            <div class="text-muted small">6 horas - 29/08/2025</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Progreso</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="progreso-circular">
                                    <canvas id="progressChart" width="120" height="120"></canvas>
                                    <div class="progreso-texto" style="font-size: 1.1rem;">75%</div>
                                </div>
                                <h5 class="mt-3" id="progressHours">180 de 240 horas</h5>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Contacto</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Supervisor:</strong><br>
                                    <small class="text-muted" id="contactoSupervisor">Juan Pérez</small>
                                </p>
                                <p><strong>Email:</strong><br>
                                    <small class="text-muted" id="contactoEmail">juan.perez@institucion.com</small>
                                </p>
                                <p><strong>Teléfono:</strong><br>
                                    <small class="text-muted" id="contactoTelefono">0987654321</small>
                                </p>
                                <button class="btn btn-primary btn-sm w-100 mt-2">
                                    <i class="fas fa-envelope me-1"></i>Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="registrarActividadPractica()">
                    <i class="fas fa-plus me-1"></i>Registrar Actividad
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Actividad -->
<div class="modal fade" id="modalRegistrarActividad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Registrar Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarActividad" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="fecha_actividad" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Entrada <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_entrada" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Salida <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_salida" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actividades Realizadas <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="actividades" rows="4" placeholder="Describe las actividades realizadas..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarActividad()">
                    <i class="fas fa-save me-1"></i>Guardar Actividad
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let practicaActual = null;
    const baseUrlDocumentos = '<?= base_url('estudiante/documentos-practicas') ?>';
    // Datos de prácticas para rellenar Entidad Receptora y Docente Tutor en el modal de subir documento
    const practicasParaModal = <?= json_encode(array_map(function ($p) {
                                    return [
                                        'id' => (int)($p['ID_PRACTICA_PREPROFESIONAL'] ?? 0),
                                        'entidad_receptora' => $p['INSTITUCION_NOMBRE'] ?? '',
                                        'docente_tutor' => trim($p['SUPERVISOR_NOMBRE'] ?? '')
                                    ];
                                }, $practicasPreprofesionales ?? [])) ?>;

    function mostrarModalSubirDoc(btn) {
        document.getElementById('tipo_documento_practica_id').value = btn.getAttribute('data-tipo-id') || '';
        document.getElementById('tipo_documento_practica_nombre').value = btn.getAttribute('data-desc') || '';
        var idPractica = btn.getAttribute('data-practica-id');
        var entidad = '';
        var tutor = '';
        if (practicasParaModal.length) {
            var datos = idPractica ? practicasParaModal.find(function(p) {
                return p.id == idPractica;
            }) : practicasParaModal[0];
            if (datos) {
                entidad = datos.entidad_receptora || '';
                tutor = datos.docente_tutor || '';
            }
        }
        var form = document.getElementById('formSubirDocumentoPractica');
        if (form.elements['entidad_receptora']) form.elements['entidad_receptora'].value = entidad;
        if (form.elements['docente_tutor']) form.elements['docente_tutor'].value = tutor;
        if (form.elements['id_practica']) form.elements['id_practica'].value = idPractica || (practicasParaModal[0] ? practicasParaModal[0].id : '');
        new bootstrap.Modal(document.getElementById('modalSubirDocumentoPractica')).show();
    }

    function subirDocumentoPractica() {
        const form = document.getElementById('formSubirDocumentoPractica');
        const archivo = document.getElementById('archivoDocumentoPractica').files[0];
        if (!archivo) {
            showNotification('Selecciona un archivo', 'warning');
            return;
        }
        const formData = new FormData(form);
        const btn = form.closest('.modal').querySelector('.btn-primary');
        const txt = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...';
        btn.disabled = true;
        fetch(baseUrlDocumentos + '/subir', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumentoPractica')).hide();
                    form.reset();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showNotification(data.message || 'Error al subir', 'error');
                }
            })
            .catch(() => showNotification('Error al subir el documento', 'error'))
            .finally(() => {
                btn.innerHTML = txt;
                btn.disabled = false;
            });
    }

    function verDocumentoPractica(id) {
        window.open(baseUrlDocumentos + '/descargar/' + id, '_blank');
    }

    function descargarDocumentoPractica(id) {
        window.location.href = baseUrlDocumentos + '/descargar/' + id;
    }

    function eliminarDocumentoPractica(id) {
        if (!confirm('¿Eliminar este documento?')) return;
        fetch(baseUrlDocumentos + '/eliminar/' + id, {
                method: 'POST'
            })
            .then(r => r.json())
            .then(data => {
                showNotification(data.message || (data.success ? 'Eliminado' : 'Error'), data.success ? 'success' : 'error');
                if (data.success) setTimeout(() => location.reload(), 1200);
            })
            .catch(() => showNotification('Error al eliminar', 'error'));
    }

    // Funciones principales
    function verDetallePractica(id, tipo) {
        practicaActual = {
            id: id,
            tipo: tipo
        };

        // Simular carga de datos
        document.getElementById('detalleInstitucion').textContent = 'Hospital San Vicente de Paúl';
        document.getElementById('detalleTipo').textContent = tipo === 'preprofesional' ? 'Preprofesional' : 'Servicio Comunitario';
        document.getElementById('detallePeriodo').textContent = '01/08/2025 - 30/11/2025';
        document.getElementById('detalleSupervisor').textContent = 'Dr. Juan Pérez';
        document.getElementById('detalleEstado').textContent = 'En Progreso';
        document.getElementById('detalleHoras').textContent = '180 de 240 horas';
        document.getElementById('detalleDescripcion').textContent = 'Desarrollo de sistema de gestión hospitalaria';

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetallePractica'));
        modal.show();

        // Dibujar gráfico de progreso
        setTimeout(() => {
            drawProgressChart(75);
        }, 100);
    }

    function registrarActividad() {
        const modal = new bootstrap.Modal(document.getElementById('modalRegistrarActividad'));
        modal.show();
    }

    function registrarActividadPractica(id, tipo) {
        practicaActual = {
            id: id,
            tipo: tipo
        };
        registrarActividad();
    }

    function subirDocumento() {
        showNotification('Función de subida de documentos en desarrollo', 'info');
    }

    function verProgreso() {
        showNotification('Mostrando progreso detallado...', 'info');
    }


    function verDocumentos(id, tipo) {
        showNotification('Mostrando documentos de la práctica...', 'info');
    }

    function guardarActividad() {
        const form = document.getElementById('formRegistrarActividad');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Simular guardado
        showNotification('Actividad registrada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalRegistrarActividad')).hide();
        form.reset();
        form.classList.remove('was-validated');
    }

    function drawProgressChart(percentage) {
        const canvas = document.getElementById('progressChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 50;

        // Limpiar canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Círculo de fondo
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 8;
        ctx.stroke();

        // Círculo de progreso
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
        ctx.strokeStyle = '#667eea';
        ctx.lineWidth = 8;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };

        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        var fechaInput = document.querySelector('input[name="fecha_actividad"]');
        if (fechaInput) fechaInput.value = today;

        // Dibujar gráficos de progreso para prácticas preprofesionales
        setTimeout(() => {
            document.querySelectorAll('[id^="progresoPre"]').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const radius = 30;
                const percentage = parseInt(canvas.getAttribute('data-porcentaje'), 10) || 75;

                // Círculo de fondo
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                ctx.strokeStyle = '#e9ecef';
                ctx.lineWidth = 6;
                ctx.stroke();

                // Círculo de progreso (mismo color verde para ambas prácticas)
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
                ctx.strokeStyle = '#28a745';
                ctx.lineWidth = 6;
                ctx.lineCap = 'round';
                ctx.stroke();
            });
        }, 100);
    });
</script>
<?= $this->endSection() ?>