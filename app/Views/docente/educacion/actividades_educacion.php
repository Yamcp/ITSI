<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<style>
    /* Estilos para el calendario */
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
        font-weight: 500 !important;
    }

    .fc-button:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .fc-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }

    .fc-button-active {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .fc-daygrid-day-number {
        color: #2c3e50 !important;
        font-weight: 500 !important;
    }

    .fc-day-today {
        background-color: #e3f2fd !important;
    }

    .fc-event {
        border-radius: 4px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
    }

    .fc .fc-daygrid-event .fc-event-title {
        display: block !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.2;
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
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Educación Continua: Mis Actividades
                </h3>
            </div>
        </div>

        <!-- Estadísticas y Acciones Rápidas -->
        <div class="row mb-4 align-items-stretch">
            <div class="col mb-3">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Mis Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('docente/actividades-educacion/crear') ?>" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nueva Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteEvaluaciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Mis Reportes</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarMisActividades()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Mis Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Separar actividades en vigentes y expiradas
        $actividadesVigentes = [];
        $actividadesExpiradas = [];
        $hoyRef = new DateTime();
        if (!empty($actividades)) {
            foreach ($actividades as $act) {
                $fFin = new DateTime($act['FECHA_FIN']);
                if ($fFin >= $hoyRef) {
                    $actividadesVigentes[] = $act;
                } else {
                    $actividadesExpiradas[] = $act;
                }
            }
        }
        ?>

        <!-- Tabs Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="actividadesTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-success" id="vigentes-tab" data-bs-toggle="tab" data-bs-target="#vigentes" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-check-circle me-2"></i>Actividades Vigentes
                                    <span class="badge bg-success ms-1"><?= count($actividadesVigentes) ?></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-secondary" id="expiradas-tab" data-bs-toggle="tab" data-bs-target="#expiradas" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-history me-2"></i>Actividades Expiradas
                                    <span class="badge bg-secondary ms-1"><?= count($actividadesExpiradas) ?></span>
                                </button>
                            </li>

                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <!-- Actividades Vigentes -->
                            <div class="tab-pane fade show active" id="vigentes" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-check-circle me-2"></i>Actividades Vigentes</span>
                                        <span class="badge bg-light text-success"><?= count($actividadesVigentes) ?> actividades</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Tipo</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Participantes</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaVigentes">
                                                    <?php if (!empty($actividadesVigentes)): ?>
                                                        <?php $contadorV = 1; ?>
                                                        <?php foreach ($actividadesVigentes as $actividad): ?>
                                                            <?php
                                                            $tipoActividad = $actividad['ACTIVIDAD'];
                                                            $iconoTipo = 'fas fa-laptop-code';
                                                            $colorTipo = 'primary';
                                                            $bgBadgeTipo = 'bg-primary';
                                                            if ($tipoActividad === 'Taller') {
                                                                $iconoTipo = 'fas fa-wrench';
                                                                $colorTipo = 'success';
                                                                $bgBadgeTipo = 'bg-success';
                                                            } elseif ($tipoActividad === 'Seminario') {
                                                                $iconoTipo = 'fas fa-comments';
                                                                $colorTipo = 'info';
                                                                $bgBadgeTipo = 'bg-info';
                                                            }
                                                            ?>
                                                            <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                                <td><?= $contadorV++ ?></td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="<?= $iconoTipo ?> fa-2x me-2 text-<?= $colorTipo ?>"></i>
                                                                        <div>
                                                                            <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                            <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge <?= $bgBadgeTipo ?>"><?= $tipoActividad ?></span></td>
                                                                <td><span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span></td>
                                                                <td>
                                                                    <div><?= date('d M Y', strtotime($actividad['FECHA_INICIO'])) ?></div>
                                                                    <small class="text-muted">al <?= date('d M Y', strtotime($actividad['FECHA_FIN'])) ?></small>
                                                                </td>
                                                                <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                                <td>
                                                                    <span class="badge bg-primary"><?= (int)($conteoParticipantes[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? 0) ?></span>
                                                                    <small class="text-muted">inscritos</small>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="<?= base_url('docente/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <button class="btn btn-outline-info" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Participantes">
                                                                            <i class="fas fa-users"></i>
                                                                        </button>
                                                                        <a href="<?= base_url('docente/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-danger" title="Eliminar">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-check-circle fa-3x mb-3 text-success" style="opacity: 0.3;"></i>
                                                                <p>No tienes actividades vigentes en este momento</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actividades Expiradas -->
                            <div class="tab-pane fade" id="expiradas" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-history me-2"></i>Actividades Expiradas</span>
                                        <span class="badge bg-light text-secondary"><?= count($actividadesExpiradas) ?> actividades</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Tipo</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Participantes</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaExpiradas">
                                                    <?php if (!empty($actividadesExpiradas)): ?>
                                                        <?php $contadorE = 1; ?>
                                                        <?php foreach ($actividadesExpiradas as $actividad): ?>
                                                            <?php
                                                            $tipoActividad = $actividad['ACTIVIDAD'];
                                                            $iconoTipo = 'fas fa-laptop-code';
                                                            $colorTipo = 'primary';
                                                            $bgBadgeTipo = 'bg-primary';
                                                            if ($tipoActividad === 'Taller') {
                                                                $iconoTipo = 'fas fa-wrench';
                                                                $colorTipo = 'success';
                                                                $bgBadgeTipo = 'bg-success';
                                                            } elseif ($tipoActividad === 'Seminario') {
                                                                $iconoTipo = 'fas fa-comments';
                                                                $colorTipo = 'info';
                                                                $bgBadgeTipo = 'bg-info';
                                                            }
                                                            ?>
                                                            <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>" style="opacity: 0.85;">
                                                                <td><?= $contadorE++ ?></td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="<?= $iconoTipo ?> fa-2x me-2 text-<?= $colorTipo ?>" style="opacity: 0.6;"></i>
                                                                        <div>
                                                                            <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                            <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge <?= $bgBadgeTipo ?>" style="opacity: 0.7;"><?= $tipoActividad ?></span></td>
                                                                <td><span class="badge bg-info" style="opacity: 0.7;"><?= $actividad['MODALIDAD'] ?></span></td>
                                                                <td>
                                                                    <div><?= date('d M Y', strtotime($actividad['FECHA_INICIO'])) ?></div>
                                                                    <small class="text-muted">al <?= date('d M Y', strtotime($actividad['FECHA_FIN'])) ?></small>
                                                                </td>
                                                                <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                                <td>
                                                                    <span class="badge bg-secondary"><?= (int)($conteoParticipantes[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? 0) ?></span>
                                                                    <small class="text-muted">inscritos</small>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="<?= base_url('docente/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <button class="btn btn-outline-info" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Participantes">
                                                                            <i class="fas fa-users"></i>
                                                                        </button>
                                                                        <a href="<?= base_url('docente/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-danger" title="Eliminar">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-history fa-3x mb-3" style="opacity: 0.3;"></i>
                                                                <p>No tienes actividades expiradas</p>
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
    </div>
</div>

<!-- Modal Calendario de Actividades -->
<div class="modal fade" id="modalCalendario" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Mi Calendario de Actividades
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Calendario -->
                <div id="calendario" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let actividadesData = [];
    let estadisticas = {};

    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verCalendario() {
        showModal('modalCalendario');
        // Inicializar calendario después de que se abra el modal
        setTimeout(() => {
            cargarDatosCalendario();
        }, 300);
    }

    // Cargar datos del calendario desde la API
    async function cargarDatosCalendario() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/calendario') ?>');
            const eventos = await response.json();
            inicializarCalendario(eventos);
        } catch (error) {
            console.error('Error al cargar datos del calendario:', error);
            showNotification('Error al cargar el calendario', 'error');
        }
    }

    function inicializarCalendario(eventos) {
        const calendarEl = document.getElementById('calendario');

        if (!calendarEl) {
            console.error('Elemento calendario no encontrado');
            return;
        }

        if (window.calendario) {
            try {
                window.calendario.destroy();
            } catch (e) {
                /* instancia ya destruida o DOM reemplazado */
            }
            window.calendario = null;
        }

        const vistos = new Set();
        const eventosUnicos = (eventos || []).filter(e => {
            const k = String(e.id);
            if (vistos.has(k)) return false;
            vistos.add(k);
            return true;
        });

        calendarEl.innerHTML = '';

        try {
            // Crear el calendario
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: eventosUnicos,
                eventContent: function(arg) {
                    if (arg.view.type !== 'dayGridMonth') {
                        return;
                    }
                    const text = arg.event.title;
                    if (!text) {
                        return;
                    }
                    const main = document.createElement('div');
                    main.className = 'fc-event-main';
                    const tit = document.createElement('div');
                    tit.className = 'fc-event-title';
                    tit.appendChild(document.createTextNode(text));
                    main.appendChild(tit);
                    return { domNodes: [main] };
                },
                eventClick: function(info) {
                    mostrarDetalleEvento(info.event);
                },
                height: 'auto',
                dayMaxEvents: true,
                moreLinkClick: 'popover',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                }
            });

            calendar.render();

            // Guardar referencia global del calendario
            window.calendario = calendar;

            console.log('Calendario inicializado correctamente');

        } catch (error) {
            console.error('Error al inicializar el calendario:', error);
            calendarEl.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar el calendario: ${error.message}
                </div>
            `;
        }
    }

    function gestionarParticipantes(id) {
        window.location.href = '<?= base_url('docente/actividades-educacion/participantes/') ?>' + id;
    }

    function generarReporteEvaluaciones() {
        // Redirigir a la página de reportes del docente
        window.location.href = '<?= base_url('docente/actividades-educacion/reportes') ?>';
    }

    function exportarMisActividades() {
        window.location.href = '<?= base_url('docente/actividades-educacion/reportes') ?>';
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
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
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

    // Cargar estadísticas desde la API
    async function cargarEstadisticas() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/api/estadisticas') ?>');
            const stats = await response.json();

            // Actualizar las estadísticas en la interfaz
            const totalActividadesEl = document.getElementById('totalActividades');
            if (totalActividadesEl) totalActividadesEl.textContent = stats.totalActividades || 0;


            estadisticas = stats;
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    /**
     * Polling para que, cuando el coordinador agregue el enlace de satisfacción,
     * el docente lo vea automáticamente en la tabla (sin recargar la página).
     */
    async function actualizarEnlacesSatisfaccionDocente() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/api/encuestas-satisfaccion') ?>', { cache: 'no-store' });
            const payload = await response.json();
            if (!payload.success) return;

            const enlacesPorActividad = payload.data || {};
            const hoy = new Date().toISOString().split('T')[0];

            document.querySelectorAll('tr[data-actividad-id]').forEach(tr => {
                const idActividad = String(tr.dataset.actividadId || '');
                const fechaFin = String(tr.dataset.fechaFin || '');
                if (!idActividad || !fechaFin) return;

                const fechaFinSolo = fechaFin.slice(0, 10);
                const finalizado = fechaFinSolo < hoy;
                const enlace = enlacesPorActividad[idActividad]?.ENLACE_FORMULARIO || null;

                // Mostrar/ocultar fila: activos siempre; finalizados solo si ya hay enlace.
                tr.style.display = (!finalizado || (finalizado && enlace)) ? '' : 'none';

                const btnGroup = tr.querySelector('.btn-group');
                if (!btnGroup) return;

                const idLink = `doc-encuesta-link-${idActividad}`;
                const enlaceExistente = document.getElementById(idLink);

                if (finalizado && enlace) {
                    if (!enlaceExistente) {
                        const a = document.createElement('a');
                        a.id = idLink;
                        a.target = '_blank';
                        a.rel = 'noopener';
                        a.className = 'btn btn-outline-success btn-sm';
                        a.innerHTML = '<i class="fas fa-external-link-alt me-1"></i>Abrir encuesta';
                        btnGroup.appendChild(a);
                    }
                    const link = document.getElementById(idLink);
                    link.href = enlace;
                } else {
                    if (enlaceExistente) {
                        enlaceExistente.remove();
                    }
                }
            });
        } catch (e) {
            console.error('Error al actualizar enlaces satisfacción (docente):', e);
        }
    }

    function iniciarPollingEncuestasSatisfaccionDocente() {
        actualizarEnlacesSatisfaccionDocente();
        setInterval(actualizarEnlacesSatisfaccionDocente, 15000); // 15s
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar estadísticas al cargar la página
        cargarEstadisticas();
        iniciarPollingEncuestasSatisfaccionDocente();
    });
</script>

<!-- Incluir FullCalendar CSS y JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>