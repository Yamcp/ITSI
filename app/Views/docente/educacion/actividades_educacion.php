<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<style>
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        text-transform: capitalize !important;
    }
    .fc-button {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
    }
    .fc-day-today { background-color: #e3f2fd !important; }
    .fc .fc-daygrid-event .fc-event-title {
        display: block !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Educación Continua: Cursos y Actividades
                </h3>
                <p class="text-center text-muted mb-4">Consulta las actividades vigentes. La gestión de cursos la realiza coordinación.</p>
            </div>
        </div>

        <div class="row mb-4 align-items-stretch g-3">
            <div class="col-md-6 col-sm-6">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Actividades Vigentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                        <a href="#" onclick="verCalendario(); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #007bff;"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php $insc = $actividadesInscritas ?? []; ?>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="actividadesTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles" type="button" role="tab">
                                    <i class="fas fa-book me-2"></i>Disponibles
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="inscritas-tab" data-bs-toggle="tab" data-bs-target="#inscritas" type="button" role="tab">
                                    <i class="fas fa-user-check me-2"></i>Mis Inscripciones
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <div class="tab-pane fade show active" id="disponibles" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Actividad</th>
                                                <th>Instructor</th>
                                                <th>Tipo</th>
                                                <th>Modalidad</th>
                                                <th>Período</th>
                                                <th>Duración</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $hayDisponibles = false;
                                            $contador = 1;
                                            if (!empty($actividades)):
                                                foreach ($actividades as $actividad):
                                                    $idAct = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
                                                    if (!empty($insc[$idAct])) continue;
                                                    $hayDisponibles = true;
                                                    $tipo = $actividad['ACTIVIDAD'];
                                            ?>
                                                <tr data-actividad-id="<?= $idAct ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                    <td><?= $contador++ ?></td>
                                                    <td>
                                                        <div class="fw-semibold"><?= esc($actividad['NOMBRE_ACTIVIDAD']) ?></div>
                                                        <small class="text-muted"><?= esc($actividad['DESCRIPCION']) ?></small>
                                                    </td>
                                                    <td><?= esc(($actividad['NOMBRE'] ?? '') . ' ' . ($actividad['APELLIDO'] ?? '')) ?></td>
                                                    <td><span class="badge bg-primary"><?= esc($tipo) ?></span></td>
                                                    <td><span class="badge bg-info"><?= esc($actividad['MODALIDAD']) ?></span></td>
                                                    <td>
                                                        <div><?= date('d M Y', strtotime($actividad['FECHA_INICIO'])) ?></div>
                                                        <small class="text-muted">al <?= date('d M Y', strtotime($actividad['FECHA_FIN'])) ?></small>
                                                    </td>
                                                    <td><span class="badge bg-secondary"><?= (int) $actividad['DURACION_HORAS'] ?>h</span></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $idAct ?>)" title="Ver detalle">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button data-accion-inscribir="true" class="btn btn-outline-success" onclick="inscribirseActividad(<?= $idAct ?>)" title="Inscribirse">
                                                                <i class="fas fa-user-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; endif; ?>
                                            <?php if (!$hayDisponibles): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-4">
                                                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                                                        No hay actividades disponibles para inscripción
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="inscritas" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Actividad</th>
                                                <th>Instructor</th>
                                                <th>Tipo</th>
                                                <th>Modalidad</th>
                                                <th>Período</th>
                                                <th>Duración</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $hayInscritas = false;
                                            $contadorI = 1;
                                            if (!empty($actividades)):
                                                foreach ($actividades as $actividad):
                                                    $idAct = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
                                                    if (empty($insc[$idAct])) continue;
                                                    $hayInscritas = true;
                                            ?>
                                                <tr data-actividad-id="<?= $idAct ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                    <td><?= $contadorI++ ?></td>
                                                    <td>
                                                        <div class="fw-semibold"><?= esc($actividad['NOMBRE_ACTIVIDAD']) ?></div>
                                                        <small class="text-muted"><?= esc($actividad['DESCRIPCION']) ?></small>
                                                    </td>
                                                    <td><?= esc(($actividad['NOMBRE'] ?? '') . ' ' . ($actividad['APELLIDO'] ?? '')) ?></td>
                                                    <td><span class="badge bg-primary"><?= esc($actividad['ACTIVIDAD']) ?></span></td>
                                                    <td><span class="badge bg-info"><?= esc($actividad['MODALIDAD']) ?></span></td>
                                                    <td>
                                                        <div><?= date('d M Y', strtotime($actividad['FECHA_INICIO'])) ?></div>
                                                        <small class="text-muted">al <?= date('d M Y', strtotime($actividad['FECHA_FIN'])) ?></small>
                                                    </td>
                                                    <td><span class="badge bg-secondary"><?= (int) $actividad['DURACION_HORAS'] ?>h</span></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $idAct ?>)" title="Ver detalle">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <span class="btn btn-success btn-sm disabled" title="Ya inscrito">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; endif; ?>
                                            <?php if (!$hayInscritas): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-4">
                                                        <i class="fas fa-user-plus fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                                                        Aún no te has inscrito en ninguna actividad vigente
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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<div class="modal fade" id="modalDetalleActividad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalle de la Actividad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Actividad:</strong> <span id="detalleNombre">-</span></p>
                        <p><strong>Tipo:</strong> <span id="detalleTipoActividad">-</span></p>
                        <p><strong>Instructor:</strong> <span id="detalleInstructor">-</span></p>
                        <p><strong>Modalidad:</strong> <span id="detalleModalidad">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                        <p><strong>Duración:</strong> <span id="detalleDuracion">-</span></p>
                        <p id="wrapDetalleLugar"><strong>Lugar:</strong> <span id="detalleLugar">-</span></p>
                        <p id="wrapDetalleEnlace" class="d-none"><strong>Enlace:</strong> <a id="detalleEnlace" href="#" target="_blank" rel="noopener"></a></p>
                        <p><strong>Horario:</strong> <span id="detalleHorario">-</span></p>
                    </div>
                    <div class="col-12 mt-2">
                        <p><strong>Descripción:</strong></p>
                        <p class="text-muted" id="detalleDescripcion">-</p>
                        <p><strong>Objetivos:</strong></p>
                        <p class="text-muted" id="detalleObjetivos">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCalendario" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-alt me-2"></i>Calendario de Actividades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="calendario" style="background:#fff;border-radius:8px;padding:20px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarInscripcion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2 text-success"></i>Confirmar inscripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-0" id="textoConfirmarInscripcion">¿Confirmas que deseas inscribirte en esta actividad?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarInscripcion" onclick="ejecutarInscripcionConfirmada()">
                    <i class="fas fa-check me-1"></i>Sí, inscribirme
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<script>
    let actividadIdInscripcionPendiente = null;

    function showModal(modalId) {
        const el = document.getElementById(modalId);
        if (!el || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(el).show();
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }

    function verCalendario() {
        showModal('modalCalendario');
        setTimeout(cargarDatosCalendario, 300);
    }

    async function cargarDatosCalendario() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/calendario') ?>');
            const eventos = await response.json();
            inicializarCalendario(eventos);
        } catch (e) {
            showNotification('Error al cargar el calendario', 'danger');
        }
    }

    function inicializarCalendario(eventos) {
        const calendarEl = document.getElementById('calendario');
        if (!calendarEl) return;
        if (window.calendario) {
            try { window.calendario.destroy(); } catch (e) {}
            window.calendario = null;
        }
        calendarEl.innerHTML = '';
        window.calendario = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
            events: eventos || [],
            height: 'auto',
            buttonText: { today: 'Hoy', month: 'Mes', list: 'Lista' }
        });
        window.calendario.render();
    }

    function verDetalleActividad(id) {
        fetch(`<?= base_url('docente/actividades-educacion/detalle/') ?>${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    showNotification(data.message || 'No se pudo cargar el detalle', 'danger');
                    return;
                }
                const a = data.data;
                document.getElementById('detalleNombre').textContent = a.NOMBRE_ACTIVIDAD || '-';
                document.getElementById('detalleTipoActividad').textContent = a.ACTIVIDAD || a.TIPO_ACTIVIDAD || '-';
                document.getElementById('detalleInstructor').textContent = `${a.NOMBRE || ''} ${a.APELLIDO || ''}`.trim() || '-';
                document.getElementById('detalleModalidad').textContent = a.MODALIDAD || '-';
                document.getElementById('detallePeriodo').textContent = `${a.FECHA_INICIO} - ${a.FECHA_FIN}`;
                document.getElementById('detalleDuracion').textContent = `${a.DURACION_HORAS} horas`;
                const lugar = (a.LUGAR || '').trim();
                document.getElementById('detalleLugar').textContent = lugar || '—';
                document.getElementById('wrapDetalleLugar').classList.toggle('d-none', !lugar);
                const enlace = (a.ENLACE || '').trim();
                const wrapEnlace = document.getElementById('wrapDetalleEnlace');
                const linkEnlace = document.getElementById('detalleEnlace');
                if (enlace) {
                    linkEnlace.href = /^https?:\/\//i.test(enlace) ? enlace : 'https://' + enlace;
                    linkEnlace.textContent = enlace;
                    wrapEnlace.classList.remove('d-none');
                } else {
                    wrapEnlace.classList.add('d-none');
                }
                document.getElementById('detalleHorario').textContent = a.HORARIO || '-';
                document.getElementById('detalleDescripcion').textContent = a.DESCRIPCION || '-';
                document.getElementById('detalleObjetivos').textContent = a.OBJETIVOS || '-';
                showModal('modalDetalleActividad');
            })
            .catch(() => showNotification('Error de conexión', 'danger'));
    }

    function inscribirseActividad(id) {
        actividadIdInscripcionPendiente = parseInt(id, 10);
        if (!actividadIdInscripcionPendiente) return;
        showModal('modalConfirmarInscripcion');
    }

    async function ejecutarInscripcionConfirmada() {
        const id = actividadIdInscripcionPendiente;
        const modalEl = document.getElementById('modalConfirmarInscripcion');
        const inst = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
        if (inst) inst.hide();
        if (!id) return;

        const btn = document.getElementById('btnConfirmarInscripcion');
        if (btn) btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('id_actividad', id);
            const response = await fetch('<?= base_url('docente/actividades-educacion/inscribirse') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (data.success) {
                showNotification(data.message || 'Inscripción registrada.', 'success');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showNotification(data.message || 'No se pudo inscribir.', 'danger');
            }
        } catch (e) {
            showNotification('Error de conexión', 'danger');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    async function cargarEstadisticas() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/api/estadisticas') ?>');
            const stats = await response.json();
            const el = document.getElementById('totalActividades');
            if (el) el.textContent = stats.totalActividades || 0;
        } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', cargarEstadisticas);
</script>
<?= $this->endSection() ?>
