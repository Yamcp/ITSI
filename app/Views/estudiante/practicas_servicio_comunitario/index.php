<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    :root {
        --dashboard-radius: 0.5rem;
        --dashboard-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        --dashboard-shadow-hover: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .practicas-page {
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .page-header-practicas {
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
    }

    .page-header-practicas .title-page {
        font-weight: 700;
        font-size: 1.5rem;
        color: #212529;
    }

    .metric-card-practicas {
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
        transition: box-shadow 0.2s ease;
        overflow: hidden;
    }

    .metric-card-practicas:hover {
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
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
    }

    .card-dash .card-header {
        padding: 1rem 1.35rem;
        font-weight: 600;
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
        border-radius: var(--dashboard-radius);
        box-shadow: var(--dashboard-shadow);
        transition: box-shadow 0.2s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .practica-card:hover {
        box-shadow: var(--dashboard-shadow-hover);
    }

    .practica-header {
        color: #fff;
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
        filter: brightness(0.97);
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
        background: #ec4899;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper practicas-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <!-- Header (mismo estilo que dashboard) -->
        <div class="page-header-practicas">
            <h1 class="title-page mb-0">
                <i class="fas fa-hands-helping me-2 text-primary"></i>
                Servicio Comunitario
            </h1>
            <p class="text-muted mb-0 mt-1" style="font-size: 0.95rem;">Gestiona tu documentación y progreso.</p>
        </div>

        <!-- Métricas (mismo diseño que dashboard) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas">
                    <div class="card-body text-center">
                        <div class="metric-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['totalPracticas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Total</p>
                        <small class="metric-sub"><?= (int)($horas_requeridas_servicio ?? 60) ?> h (una sola vez)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas">
                    <div class="card-body text-center">
                        <div class="metric-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['practicasActivas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">En progreso</p>
                        <small class="metric-sub">Activas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas">
                    <div class="card-body text-center">
                        <div class="metric-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['practicasFinalizadas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Finalizadas</p>
                        <small class="metric-sub">Completadas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card metric-card-practicas">
                    <div class="card-body text-center">
                        <div class="metric-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="mb-0"><?= $estadisticas['horasCompletadas'] ?? 0 ?></h3>
                        <p class="metric-label mb-0">Horas</p>
                        <small class="metric-sub">Completadas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
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

        <!-- Formatos de las prácticas de Servicio Comunitario -->
        <div class="card card-dash">
            <div class="card-header">
                <i class="fas fa-file-alt me-2 text-primary"></i>Formatos de las prácticas de Servicio Comunitario
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle me-1"></i>Descargue los documentos de formato que necesite para sus prácticas de servicio comunitario.
                </p>
                <?php $documentos_formatos_serv = $documentos_formatos_servicio ?? []; ?>
                <?php if (!empty($documentos_formatos_serv)): ?>
                    <ul class="list-group">
                        <?php foreach ($documentos_formatos_serv as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-pdf me-2 text-danger"></i><?= esc($item['nombre'] ?? 'Documento') ?></span>
                                <a href="<?= base_url('estudiante/practicas/servicio-comunitario/formatos/descargar/' . rawurlencode($item['archivo'] ?? '')) ?>" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i>Descargar
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No hay documentos de formato publicados aún.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Alerta: plazo 15 días para documento final (servicio comunitario) -->
        <?php $alertaServ = $alerta_documento_final_servicio ?? ['mostrar' => false]; ?>
        <?php if (!empty($alertaServ['mostrar'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert <?= !empty($alertaServ['superado_plazo']) ? 'alert-danger' : 'alert-warning' ?> alert-dismissible fade show shadow-sm mb-0" role="alert">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>Documento final – plazo de 15 días
                        </h6>
                        <p class="mb-0"><?= $alertaServ['mensaje'] ?? '' ?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Apartado: Subir documento final (servicio comunitario) -->
        <?php
        $idTipoFinalServ = (int)($id_tipo_documento_final_servicio ?? 0);
        $docFinalServRow = null;
        if ($idTipoFinalServ > 0 && !empty($progreso_documentos_servicio ?? [])) {
            foreach ($progreso_documentos_servicio as $doc) {
                $docTipoId = (int)($doc['ID_TIPO_DOCUMENTO_SERVICIO'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? 0);
                if ($docTipoId === $idTipoFinalServ && !empty($doc['ID_DOCUMENTO_SERVICIO'] ?? null)) {
                    $docFinalServRow = $doc;
                    break;
                }
            }
        }
        $estadoFinalServ = $docFinalServRow ? ($docFinalServRow['ESTADO_REVISION'] ?? 'Pendiente') : 'Pendiente';
        $idDocFinalServ = $docFinalServRow['ID_DOCUMENTO_SERVICIO'] ?? null;
        $idServicioParaModal = !empty($serviciosComunitarios) ? (int)$serviciosComunitarios[0]['ID_SERVICIO_COMUNITARIO'] : 0;
        ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white panel-bar-trad">
                        <h5 class="mb-0">
                            <i class="fas fa-file-export me-2"></i>
                            Documento final
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Una vez completadas las 60 horas de servicio comunitario, sube aquí el <strong>documento final</strong>. Tienes un máximo de 15 días desde la fecha de culminación. Será revisado por el coordinador.
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <?php if ($idTipoFinalServ > 0): ?>
                                <?php if ($docFinalServRow && $idDocFinalServ): ?>
                                    <span class="badge <?= $estadoFinalServ === 'Aprobado' ? 'bg-success' : ($estadoFinalServ === 'Rechazado' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                        <i class="fas fa-<?= $estadoFinalServ === 'Aprobado' ? 'check-circle' : ($estadoFinalServ === 'Rechazado' ? 'times-circle' : 'clock') ?> me-1"></i><?= $estadoFinalServ ?>
                                    </span>
                                    <span class="text-muted small">Subido: <?= isset($docFinalServRow['FECHA_SUBIDA']) ? date('d/m/Y', strtotime($docFinalServRow['FECHA_SUBIDA'])) : '-' ?></span>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="verDocumentoServicio(<?= (int)$idDocFinalServ ?>)" title="Ver"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="descargarDocumentoServicio(<?= (int)$idDocFinalServ ?>)" title="Descargar"><i class="fas fa-download"></i></button>
                                    <?php if ($estadoFinalServ !== 'Aprobado'): ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocumentoServicio(<?= (int)$idDocFinalServ ?>)"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary" onclick="mostrarModalSubirDocServicio(<?= $idTipoFinalServ ?>, <?= $idServicioParaModal ?>)"><i class="fas fa-cloud-upload-alt me-1"></i>Subir documento final</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">No hay tipo de documento configurado para documento final. Contacte al administrador.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Prácticas de Servicio Comunitario -->
        <div class="card card-dash">
            <div class="card-header">
                <i class="fas fa-hands-helping me-2 text-primary"></i>Mis prácticas de servicio comunitario
            </div>
            <div class="card-body">
                <?php if (!empty($serviciosComunitarios)): ?>
                    <?php foreach ($serviciosComunitarios as $servicio): ?>
                        <?php
                        $progresoServ = $progresoServicios[$servicio['ID_SERVICIO_COMUNITARIO']] ?? 0;
                        ?>
                        <div class="practica-card">
                            <div class="practica-header">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-1">
                                            <i class="fas fa-hands-helping me-2"></i>
                                            <?= esc($servicio['INSTITUCION_NOMBRE']) ?>
                                        </h5>
                                        <p class="mb-0 opacity-75"><?= esc($servicio['PROYECTO_SOCIAL'] ?? 'Sin descripción específica') ?></p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <?php
                                        $estadoClass = '';
                                        switch ($servicio['ESTADO_SERVICIO']) {
                                            case 'Completado':
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
                                        <span class="estado-badge <?= $estadoClass ?>"><?= esc($servicio['ESTADO_SERVICIO']) ?></span>
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
                                                    <?= date('d/m/Y', strtotime($servicio['FECHA_INICIO'])) ?> -
                                                    <?= date('d/m/Y', strtotime($servicio['FECHA_FIN'])) ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Horas Totales:</strong><br>
                                                <span class="badge bg-info"><?= (int)($servicio['HORAS_SERVICIO'] ?? 0) ?> h</span>
                                                <small class="d-block text-muted mt-1">Meta: <?= (int)($horas_requeridas_servicio ?? 60) ?> h (una sola vez por estudiante)</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Supervisor:</strong><br>
                                                <small class="text-muted"><?= esc($servicio['SUPERVISOR_NOMBRE'] ?? 'No asignado') ?></small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Progreso:</strong><br>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: <?= $progresoServ ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= $progresoServ ?>% completado</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="progreso-circular">
                                            <canvas id="progresoServ<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>" width="80" height="80" data-porcentaje="<?= $progresoServ ?>"></canvas>
                                            <div class="progreso-texto"><?= $progresoServ ?>%</div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn-group w-100 flex-wrap" role="group">
                                            <button class="btn btn-outline-primary accion-btn" onclick="verDetallePractica(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                            </button>
                                            <button class="btn btn-outline-success accion-btn" onclick="registrarActividadPractica(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                <i class="fas fa-plus me-1"></i>Registrar Actividad
                                            </button>
                                            <button class="btn btn-outline-warning accion-btn" onclick="abrirModalAsistencia(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                <i class="fas fa-clock me-1"></i>Registrar Asistencia
                                            </button>
                                            <button class="btn btn-outline-info accion-btn" onclick="verDocumentos(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
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
                        <h5 class="text-muted">No tienes prácticas de servicio comunitario asignadas</h5>
                        <p class="text-muted">Contacta con tu coordinador para más información.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Documento Final (servicio comunitario) -->
<div class="modal fade" id="modalSubirDocumentoServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-cloud-upload-alt me-2"></i>Subir documento final</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoServicio" enctype="multipart/form-data">
                    <input type="hidden" name="tipo_documento" id="tipo_documento_servicio_id">
                    <input type="hidden" name="id_servicio" id="id_servicio_modal">
                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" class="form-control" id="tipo_documento_servicio_nombre" value="Documento final (servicio comunitario)" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="archivo" id="archivoDocumentoServicio" accept=".pdf,application/pdf" required>
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
                <button type="button" class="btn btn-primary" onclick="subirDocumentoServicio()"><i class="fas fa-upload me-1"></i>Subir</button>
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
                                        <p><strong>Tipo de Práctica:</strong> <span id="detalleTipo">Servicio Comunitario</span></p>
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Supervisor:</strong> <span id="detalleSupervisor">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Horas:</strong> <span id="detalleHoras">-</span></p>
                                    </div>
                                </div>
                                <p><strong>Descripción:</strong></p>
                                <p class="text-muted" id="detalleDescripcion">-</p>
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
                                            <div class="fw-semibold">Actividad de servicio comunitario</div>
                                            <div class="text-muted small">-</div>
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
                                    <div class="progreso-texto" style="font-size: 1.1rem;">0%</div>
                                </div>
                                <h5 class="mt-3" id="progressHours">-</h5>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Contacto</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Supervisor:</strong><br><small class="text-muted" id="contactoSupervisor">-</small></p>
                                <button class="btn btn-primary btn-sm w-100 mt-2"><i class="fas fa-envelope me-1"></i>Enviar Mensaje</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="registrarActividadPractica()"><i class="fas fa-plus me-1"></i>Registrar Actividad</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clock me-2"></i>Registrar Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAsistencia" novalidate>
                    <input type="hidden" name="practica_id" id="asistencia_practica_id" value="">
                    <input type="hidden" name="tipo_practica" id="asistencia_tipo_practica" value="">
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="fecha_asistencia" required>
                        <div class="invalid-feedback">Selecciona una fecha válida.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Entrada <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_entrada" required>
                                <div class="invalid-feedback">Hora de entrada requerida.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Salida <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_salida" required>
                                <div class="invalid-feedback">Hora de salida requerida.</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actividades del Día <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="actividades_dia" rows="4" placeholder="Describe las actividades realizadas durante el día..." minlength="10" maxlength="300" required></textarea>
                        <div class="invalid-feedback">Describe las actividades (mínimo 10 caracteres).</div>
                        <div class="form-text"><span id="asistencia-actividades-count">0</span>/300 caracteres</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones adicionales..." maxlength="200"></textarea>
                        <div class="form-text"><span id="asistencia-observaciones-count">0</span>/200 caracteres</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarAsistencia()"><i class="fas fa-save me-1"></i>Registrar Asistencia</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Actividad -->
<div class="modal fade" id="modalRegistrarActividad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Registrar Actividad</h5>
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
                <button type="button" class="btn btn-success" onclick="guardarActividad()"><i class="fas fa-save me-1"></i>Guardar Actividad</button>
            </div>
        </div>
    </div>
</div>

<script>
    let practicaActual = null;
    const baseUrlDocumentosServicio = '<?= base_url('estudiante/documentos-servicio-comunitario') ?>';

    function mostrarModalSubirDocServicio(tipoId, servicioId) {
        document.getElementById('tipo_documento_servicio_id').value = tipoId || '';
        document.getElementById('id_servicio_modal').value = servicioId || '';
        new bootstrap.Modal(document.getElementById('modalSubirDocumentoServicio')).show();
    }

    function subirDocumentoServicio() {
        const form = document.getElementById('formSubirDocumentoServicio');
        const archivo = document.getElementById('archivoDocumentoServicio').files[0];
        if (!archivo) {
            showNotification('Selecciona un archivo PDF', 'warning');
            return;
        }
        const formData = new FormData(form);
        const btn = form.closest('.modal').querySelector('.btn-primary');
        const txt = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...';
        btn.disabled = true;
        fetch(baseUrlDocumentosServicio + '/subir', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumentoServicio')).hide();
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

    function verDocumentoServicio(id) {
        window.open(baseUrlDocumentosServicio + '/descargar/' + id, '_blank');
    }

    function descargarDocumentoServicio(id) {
        window.location.href = baseUrlDocumentosServicio + '/descargar/' + id;
    }

    function eliminarDocumentoServicio(id) {
        if (!confirm('¿Eliminar este documento?')) return;
        fetch(baseUrlDocumentosServicio + '/eliminar/' + id, {
                method: 'POST'
            })
            .then(r => r.json())
            .then(data => {
                showNotification(data.message || (data.success ? 'Eliminado' : 'Error'), data.success ? 'success' : 'error');
                if (data.success) setTimeout(() => location.reload(), 1200);
            })
            .catch(() => showNotification('Error al eliminar', 'error'));
    }

    function verDetallePractica(id, tipo) {
        practicaActual = {
            id: id,
            tipo: tipo
        };
        document.getElementById('detalleTipo').textContent = 'Servicio Comunitario';
        const modal = new bootstrap.Modal(document.getElementById('modalDetallePractica'));
        modal.show();
        setTimeout(() => drawProgressChart(practicaActual ? 50 : 0), 100);
    }

    function registrarActividad() {
        new bootstrap.Modal(document.getElementById('modalRegistrarActividad')).show();
    }

    function registrarActividadPractica(id, tipo) {
        practicaActual = {
            id: id,
            tipo: tipo
        };
        registrarActividad();
    }

    function abrirModalAsistencia(id, tipo) {
        document.getElementById('asistencia_practica_id').value = id;
        document.getElementById('asistencia_tipo_practica').value = tipo;
        document.getElementById('formAsistencia').reset();
        document.getElementById('asistencia_practica_id').value = id;
        document.getElementById('asistencia_tipo_practica').value = tipo;
        var c1 = document.getElementById('asistencia-actividades-count');
        var c2 = document.getElementById('asistencia-observaciones-count');
        if (c1) c1.textContent = '0';
        if (c2) c2.textContent = '0';
        new bootstrap.Modal(document.getElementById('modalAsistencia')).show();
    }

    function guardarAsistencia() {
        var form = document.getElementById('formAsistencia');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        var horaEntrada = form.querySelector('input[name="hora_entrada"]').value;
        var horaSalida = form.querySelector('input[name="hora_salida"]').value;
        if (horaEntrada && horaSalida && horaSalida <= horaEntrada) {
            showNotification('La hora de salida debe ser posterior a la de entrada', 'error');
            return;
        }
        var url = '<?= base_url('estudiante/practicas/registrar-asistencia') ?>';
        var formData = new FormData(form);
        formData.append('practica_id', document.getElementById('asistencia_practica_id').value);
        formData.append('tipo_practica', document.getElementById('asistencia_tipo_practica').value);
        fetch(url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showNotification(data.message || 'Asistencia registrada exitosamente', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalAsistencia')).hide();
                    form.reset();
                    form.classList.remove('was-validated');
                } else {
                    showNotification(data.message || 'Error al registrar', 'error');
                }
            })
            .catch(function() { showNotification('Error de conexión', 'error'); });
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
        showNotification('Actividad registrada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalRegistrarActividad')).hide();
        form.reset();
        form.classList.remove('was-validated');
    }

    function drawProgressChart(percentage) {
        const canvas = document.getElementById('progressChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2,
            centerY = canvas.height / 2,
            radius = 50;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 8;
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * (percentage || 0) / 100));
        ctx.strokeStyle = '#667eea';
        ctx.lineWidth = 8;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    function showNotification(message, type) {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };
        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = '<div class="alert alert-dismissible fade show" role="alert" style="background:' + (colors[type] || colors.info) + '; color: white; border: none; border-radius: 10px;"><i class="fas fa-info-circle me-2"></i>' + message + '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div>';
        document.body.appendChild(notification);
        setTimeout(() => notification.parentNode && notification.remove(), 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const fechaInput = document.querySelector('input[name="fecha_actividad"]');
        if (fechaInput) fechaInput.value = today;
        var actividadesDia = document.querySelector('#formAsistencia textarea[name="actividades_dia"]');
        var observacionesAsist = document.querySelector('#formAsistencia textarea[name="observaciones"]');
        if (actividadesDia) actividadesDia.addEventListener('input', function() { document.getElementById('asistencia-actividades-count').textContent = this.value.length; });
        if (observacionesAsist) observacionesAsist.addEventListener('input', function() { document.getElementById('asistencia-observaciones-count').textContent = this.value.length; });
        document.querySelectorAll('[id^="progresoServ"]').forEach(function(canvas) {
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2,
                centerY = canvas.height / 2,
                radius = 30;
            const percentage = parseInt(canvas.getAttribute('data-porcentaje'), 10) || 0;
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.strokeStyle = '#e9ecef';
            ctx.lineWidth = 6;
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
            ctx.strokeStyle = '#28a745';
            ctx.lineWidth = 6;
            ctx.lineCap = 'round';
            ctx.stroke();
        });
    });
</script>
<?= $this->endSection() ?>