<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<style>
    .notif-item {
        transition: background 0.2s ease;
        border-left: 4px solid transparent;
    }

    .notif-item.unread {
        border-left-color: #28a745;
        background-color: #f8fff8;
    }

    .notif-item.read {
        border-left-color: #dee2e6;
        background-color: #f8f9fa;
    }

    .notif-priority {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .notif-priority-alta { background-color: #dc3545; }
    .notif-priority-media { background-color: #ffc107; }
    .notif-priority-baja { background-color: #28a745; }

    .stats-card {
        border-radius: 12px;
        padding: 1.25rem;
        color: #fff;
    }

    #modalEstudianteNotif .modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    #modalEstudianteNotif .modal-header {
        background: linear-gradient(135deg, #00367c 0%, #0056b3 100%);
        border-bottom: none;
        padding: 1.1rem 1.35rem;
        color: #ffffff !important;
    }

    #modalEstudianteNotif .modal-header .modal-title,
    #modalEstudianteNotif .modal-header .modal-title i {
        color: #ffffff !important;
    }

    #modalEstudianteNotif .modal-body {
        padding: 1.5rem 1.35rem 1.25rem;
        background: #f7f9fc;
    }

    #modalEstudianteNotif .modal-footer {
        border-top: none;
        background: #fff;
        padding: 0.85rem 1.35rem 1.15rem;
    }

    #modalEstudianteNotif .est-hero {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fff;
        border: 1px solid #e8eef5;
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 2px rgba(0, 54, 124, 0.04);
    }

    #modalEstudianteNotif .est-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00367c, #20c997);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.15rem;
        flex-shrink: 0;
        letter-spacing: 0.5px;
    }

    #modalEstudianteNotif .est-hero-nombre {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a2b4a;
        margin: 0 0 0.2rem;
        line-height: 1.3;
    }

    #modalEstudianteNotif .est-hero-sub {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
    }

    #modalEstudianteNotif .est-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    #modalEstudianteNotif .est-dato {
        background: #fff;
        border: 1px solid #e8eef5;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        box-shadow: 0 1px 2px rgba(0, 54, 124, 0.04);
    }

    #modalEstudianteNotif .est-dato-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    #modalEstudianteNotif .est-dato-icon.carrera {
        background: #e8f1ff;
        color: #00367c;
    }

    #modalEstudianteNotif .est-dato-icon.semestre {
        background: #e8f8f0;
        color: #198754;
    }

    #modalEstudianteNotif .est-dato-icon.modalidad {
        background: #fff3e6;
        color: #d97706;
    }

    #modalEstudianteNotif .est-dato.full {
        grid-column: 1 / -1;
    }

    #modalEstudianteNotif .dato-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #8a94a6;
        margin-bottom: 0.2rem;
        font-weight: 600;
    }

    #modalEstudianteNotif .dato-valor {
        font-weight: 600;
        color: #1a2b4a;
        font-size: 0.95rem;
        line-height: 1.35;
        word-break: break-word;
    }

    #modalEstudianteNotif .btn-cerrar-modal {
        background: #00367c;
        border-color: #00367c;
        color: #fff;
        border-radius: 8px;
        padding: 0.45rem 1.25rem;
        font-weight: 500;
    }

    #modalEstudianteNotif .btn-cerrar-modal:hover {
        background: #002a61;
        border-color: #002a61;
        color: #fff;
    }

    @media (max-width: 575.98px) {
        #modalEstudianteNotif .est-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$estadisticas = $estadisticas ?? ['total' => 0, 'no_leidas' => 0, 'leidas' => 0];
$notificaciones = $notificaciones ?? [];
$baseNotif = rtrim(base_url('notificaciones'), '/');
?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="mb-1 text-primary">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Asignaciones de tutoría
                </h3>
                <p class="text-muted mb-0">
                    Aquí se te notifica cuando coordinación te asigna como tutor de un estudiante.
                </p>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" id="statTotal"><?= (int) ($estadisticas['total'] ?? 0) ?></h4>
                            <p class="mb-0">Total</p>
                        </div>
                        <i class="fas fa-bell fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" id="statPendientes"><?= (int) ($estadisticas['no_leidas'] ?? 0) ?></h4>
                            <p class="mb-0">Pendientes</p>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" id="statRevisadas"><?= (int) ($estadisticas['leidas'] ?? 0) ?></h4>
                            <p class="mb-0">Revisadas</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success" id="btnMarcarTodas">
                <i class="fas fa-check-double me-1"></i>Marcar todas revisadas
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php if (!empty($notificaciones)): ?>
                    <div id="listaNotificaciones">
                        <?php foreach ($notificaciones as $notificacion): ?>
                            <?php
                            $leida = !empty($notificacion['LEIDA']);
                            $leidaAttr = $leida ? '1' : '0';
                            $nombreEst = trim((string) ($notificacion['ESTUDIANTE_NOMBRE'] ?? ''));
                            $carreraEst = trim((string) ($notificacion['ESTUDIANTE_CARRERA'] ?? ''));
                            $semestreEst = $notificacion['ESTUDIANTE_SEMESTRE'] ?? '';
                            $modalidadEst = ($notificacion['MODALIDAD'] ?? '') === 'servicio'
                                ? 'Servicio comunitario'
                                : 'Prácticas preprofesionales';
                            ?>
                            <div class="notif-item p-3 border-bottom position-relative <?= $leida ? 'read' : 'unread' ?>"
                                data-id="<?= (int) $notificacion['ID_NOTIFICACION'] ?>"
                                data-leida="<?= $leidaAttr ?>"
                                data-nombre="<?= esc($nombreEst !== '' ? $nombreEst : '—', 'attr') ?>"
                                data-carrera="<?= esc($carreraEst !== '' ? $carreraEst : '—', 'attr') ?>"
                                data-semestre="<?= esc($semestreEst !== '' && $semestreEst !== null ? (string) $semestreEst : '—', 'attr') ?>"
                                data-modalidad="<?= esc($modalidadEst, 'attr') ?>">

                                <div class="notif-priority notif-priority-<?= esc($notificacion['PRIORIDAD'] ?? 'alta', 'attr') ?>"></div>

                                <div class="row align-items-start">
                                    <div class="col-auto pe-0">
                                        <i class="fas fa-user-graduate fa-2x text-success"></i>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                            <h6 class="mb-0 fw-bold"><?= esc($notificacion['TITULO'] ?? 'Nueva tutoría asignada') ?></h6>
                                            <?php if (!$leida): ?>
                                                <span class="badge rounded-pill" style="background: linear-gradient(45deg, #28a745, #20c997);">Nueva</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border">Revisada</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mb-2 text-body"><?= nl2br(esc($notificacion['MENSAJE'] ?? '')) ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            <?= !empty($notificacion['FECHA_CREACION']) ? date('d/m/Y H:i', strtotime($notificacion['FECHA_CREACION'])) : '—' ?>
                                        </small>
                                    </div>
                                    <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-ver-estudiante"
                                                title="Ver información del estudiante">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (!$leida): ?>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-marcar-leida"
                                                    data-id="<?= (int) $notificacion['ID_NOTIFICACION'] ?>"
                                                    title="Marcar como revisada">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3 opacity-50"></i>
                        <h5 class="fw-normal">Sin asignaciones nuevas</h5>
                        <p class="small mb-0">Cuando coordinación te asigne como tutor de un estudiante, el aviso aparecerá aquí.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal información del estudiante -->
<div class="modal fade" id="modalEstudianteNotif" tabindex="-1" aria-labelledby="modalEstudianteNotifLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="modalEstudianteNotifLabel">
                    <i class="fas fa-user-graduate me-2"></i>Información del estudiante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="est-hero">
                    <div class="est-avatar" id="notifEstAvatar">—</div>
                    <div>
                        <p class="est-hero-nombre" id="notifEstNombre">—</p>
                        <p class="est-hero-sub">Estudiante asignado a tu tutoría</p>
                    </div>
                </div>
                <div class="est-grid">
                    <div class="est-dato full">
                        <div class="est-dato-icon carrera"><i class="fas fa-graduation-cap"></i></div>
                        <div>
                            <div class="dato-label">Carrera</div>
                            <div class="dato-valor" id="notifEstCarrera">—</div>
                        </div>
                    </div>
                    <div class="est-dato">
                        <div class="est-dato-icon semestre"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <div class="dato-label">Semestre</div>
                            <div class="dato-valor" id="notifEstSemestre">—</div>
                        </div>
                    </div>
                    <div class="est-dato">
                        <div class="est-dato-icon modalidad"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="dato-label">Modalidad</div>
                            <div class="dato-valor" id="notifEstModalidad">—</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cerrar-modal" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
    var baseUrl = <?= json_encode($baseNotif) ?>;
    var lista = document.getElementById('listaNotificaciones');
    var modalEl = document.getElementById('modalEstudianteNotif');
    var modalInst = null;

    function actualizarStats() {
        var items = document.querySelectorAll('.notif-item');
        var total = items.length;
        var pendientes = 0;
        items.forEach(function(el) {
            if (el.getAttribute('data-leida') === '0') pendientes++;
        });
        var elT = document.getElementById('statTotal');
        var elP = document.getElementById('statPendientes');
        var elR = document.getElementById('statRevisadas');
        if (elT) elT.textContent = total;
        if (elP) elP.textContent = pendientes;
        if (elR) elR.textContent = Math.max(0, total - pendientes);
    }

    function marcarItemRevisado(elemento) {
        if (!elemento) return;
        elemento.classList.remove('unread');
        elemento.classList.add('read');
        elemento.setAttribute('data-leida', '1');
        var btn = elemento.querySelector('.btn-marcar-leida');
        if (btn) btn.remove();
        var badge = elemento.querySelector('.badge');
        if (badge) {
            badge.className = 'badge bg-light text-muted border';
            badge.textContent = 'Revisada';
        }
    }

    function inicialesNombre(nombre) {
        var partes = String(nombre || '').trim().split(/\s+/).filter(Boolean);
        if (!partes.length || nombre === '—') return '?';
        var ini = partes[0].charAt(0);
        if (partes.length > 1) ini += partes[partes.length - 1].charAt(0);
        return ini.toUpperCase();
    }

    function abrirDetalleEstudiante(item) {
        if (!item || !modalEl) return;
        var nombre = item.getAttribute('data-nombre') || '—';
        var semestre = item.getAttribute('data-semestre') || '—';
        document.getElementById('notifEstNombre').textContent = nombre;
        document.getElementById('notifEstCarrera').textContent = item.getAttribute('data-carrera') || '—';
        document.getElementById('notifEstSemestre').textContent =
            semestre !== '—' ? ('Semestre ' + semestre) : '—';
        document.getElementById('notifEstModalidad').textContent = item.getAttribute('data-modalidad') || '—';
        document.getElementById('notifEstAvatar').textContent = inicialesNombre(nombre);

        if (!modalInst) {
            modalInst = new bootstrap.Modal(modalEl);
        }
        modalInst.show();
    }

    if (lista) {
        lista.addEventListener('click', function(e) {
            var btnVer = e.target.closest && e.target.closest('.btn-ver-estudiante');
            if (btnVer) {
                var item = btnVer.closest('.notif-item');
                abrirDetalleEstudiante(item);
                return;
            }

            var btn = e.target.closest && e.target.closest('.btn-marcar-leida');
            if (!btn) return;
            var id = btn.getAttribute('data-id');
            if (!id) return;

            fetch(baseUrl + '/marcar-leida/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) {
                    if (typeof showNotification === 'function') showNotification('No se pudo marcar la notificación', 'error');
                    return;
                }
                marcarItemRevisado(document.querySelector('.notif-item[data-id="' + id + '"]'));
                actualizarStats();
                if (typeof showNotification === 'function') showNotification('Asignación marcada como revisada', 'success');
            })
            .catch(function() {
                if (typeof showNotification === 'function') showNotification('Error de conexión', 'error');
            });
        });
    }

    var btnTodas = document.getElementById('btnMarcarTodas');
    if (btnTodas) {
        btnTodas.addEventListener('click', function() {
            fetch(baseUrl + '/marcar-todas-leidas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) {
                    if (typeof showNotification === 'function') showNotification('No se pudieron marcar las notificaciones', 'error');
                    return;
                }
                document.querySelectorAll('.notif-item').forEach(marcarItemRevisado);
                actualizarStats();
                if (typeof showNotification === 'function') showNotification('Todas las asignaciones fueron marcadas como revisadas', 'success');
            })
            .catch(function() {
                if (typeof showNotification === 'function') showNotification('Error de conexión', 'error');
            });
        });
    }
})();
</script>
<?= $this->endSection() ?>
