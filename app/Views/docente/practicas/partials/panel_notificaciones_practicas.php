<?php
$notificaciones_lista = $notificaciones_lista ?? [];
$estadisticas_notificaciones = $estadisticas_notificaciones ?? ['total' => 0, 'no_leidas' => 0, 'leidas' => 0];
$estN = $estadisticas_notificaciones;
?>
<div id="panel-notificaciones-practicas" class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-bell me-2"></i>Avisos y notificaciones
                </h5>
                <p class="text-muted small mb-0 mt-1">Asignaciones de tutoría, prácticas y recordatorios vinculados a tu rol.</p>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4" id="panelNotifPracticasStats">
                    <div class="col-md-4">
                        <div class="notif-prac-stats-card rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1" id="notifPracStatTotal"><?= (int) ($estN['total'] ?? 0) ?></h4>
                                    <p class="mb-0 small opacity-90">Total</p>
                                </div>
                                <i class="fas fa-bell fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="notif-prac-stats-card rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1" id="notifPracStatNoLeidas"><?= (int) ($estN['no_leidas'] ?? 0) ?></h4>
                                    <p class="mb-0 small opacity-90">Pendientes</p>
                                </div>
                                <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="notif-prac-stats-card rounded-3 p-3 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1" id="notifPracStatLeidas"><?= (int) ($estN['leidas'] ?? 0) ?></h4>
                                    <p class="mb-0 small opacity-90">Revisadas</p>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center g-2">
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap gap-2 notif-prac-filters">
                            <button type="button" class="btn btn-sm btn-outline-success active notif-prac-filter-btn" data-filter="todas">
                                <i class="fas fa-list me-1"></i>Todas
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning notif-prac-filter-btn" data-filter="no_leidas">
                                <i class="fas fa-exclamation-circle me-1"></i>Pendientes
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary notif-prac-filter-btn" data-filter="tutoria_asignada">
                                <i class="fas fa-chalkboard-teacher me-1"></i>Tutorías
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info notif-prac-filter-btn" data-filter="asignacion_practica">
                                <i class="fas fa-briefcase me-1"></i>Prácticas
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary notif-prac-filter-btn" data-filter="recordatorio">
                                <i class="fas fa-clock me-1"></i>Recordatorios
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <button type="button" class="btn btn-sm btn-success" id="notifPracBtnMarcarTodas">
                            <i class="fas fa-check-double me-1"></i>Marcar todas revisadas
                        </button>
                    </div>
                </div>

                <div class="card border shadow-sm mb-0">
                    <div class="card-body p-0">
                        <?php if (!empty($notificaciones_lista)): ?>
                            <div id="notificacionesListaPracticas">
                                <?php foreach ($notificaciones_lista as $notificacion): ?>
                                    <?php
                                    $leida = !empty($notificacion['LEIDA']);
                                    $leidaAttr = $leida ? '1' : '0';
                                    ?>
                                    <div class="notif-prac-item p-3 border-bottom position-relative <?= $leida ? 'notif-prac-read' : 'notif-prac-unread' ?>"
                                        data-id="<?= (int) $notificacion['ID_NOTIFICACION'] ?>"
                                        data-tipo="<?= esc($notificacion['TIPO_NOTIFICACION'] ?? '', 'attr') ?>"
                                        data-leida="<?= $leidaAttr ?>">

                                        <div class="notif-prac-priority notif-prac-priority-<?= esc($notificacion['PRIORIDAD'] ?? 'baja', 'attr') ?>"></div>

                                        <div class="row align-items-start">
                                            <div class="col-auto pe-0">
                                                <?php if (($notificacion['TIPO_NOTIFICACION'] ?? '') === 'tutoria_asignada'): ?>
                                                    <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                                                <?php elseif (($notificacion['TIPO_NOTIFICACION'] ?? '') === 'asignacion_practica'): ?>
                                                    <i class="fas fa-briefcase fa-2x text-primary"></i>
                                                <?php elseif (($notificacion['TIPO_NOTIFICACION'] ?? '') === 'recordatorio'): ?>
                                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-info-circle fa-2x text-info"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                                    <h6 class="mb-0 fw-bold"><?= esc($notificacion['TITULO'] ?? '') ?></h6>
                                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                                        <?php if (($notificacion['TIPO_NOTIFICACION'] ?? '') === 'tutoria_asignada'): ?>
                                                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #28a745, #20c997);">Nueva tutoría</span>
                                                        <?php endif; ?>
                                                        <span class="badge notif-prac-type notif-prac-type-<?= esc($notificacion['TIPO_NOTIFICACION'] ?? 'general', 'attr') ?>">
                                                            <?= esc(ucfirst(str_replace('_', ' ', (string) ($notificacion['TIPO_NOTIFICACION'] ?? 'general')))) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="mb-2 text-muted small"><?= nl2br(esc($notificacion['MENSAJE'] ?? '')) ?></p>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?= !empty($notificacion['FECHA_CREACION']) ? date('d/m/Y H:i', strtotime($notificacion['FECHA_CREACION'])) : '—' ?>
                                                    <?php if ($leida && !empty($notificacion['FECHA_LEIDA'])): ?>
                                                        | <i class="fas fa-check me-1"></i>Revisada: <?= date('d/m/Y H:i', strtotime($notificacion['FECHA_LEIDA'])) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                            <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0 notif-prac-actions">
                                                <?php if (!$leida): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success notif-prac-btn-leida" data-id="<?= (int) $notificacion['ID_NOTIFICACION'] ?>">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger notif-prac-btn-eliminar" data-id="<?= (int) $notificacion['ID_NOTIFICACION'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-bell-slash fa-3x mb-3 opacity-50"></i>
                                <h5 class="fw-normal">No hay notificaciones</h5>
                                <p class="small mb-0">Cuando te asignen tutorías o haya avisos, aparecerán aquí.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
