<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<style>
    .estudiante-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .estado-badge {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
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
        background: #007bff;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 1rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #dee2e6;
    }

    /* Panel notificaciones (integrado en prácticas) */
    #panel-notificaciones-practicas .notif-prac-item {
        transition: background 0.2s ease, box-shadow 0.2s ease;
        border-left: 4px solid transparent;
    }

    #panel-notificaciones-practicas .notif-prac-unread {
        border-left-color: #28a745;
        background-color: #f8fff8;
    }

    #panel-notificaciones-practicas .notif-prac-read {
        border-left-color: #dee2e6;
        background-color: #f8f9fa;
    }

    #panel-notificaciones-practicas .notif-prac-priority {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    #panel-notificaciones-practicas .notif-prac-priority-alta {
        background-color: #dc3545;
    }

    #panel-notificaciones-practicas .notif-prac-priority-media {
        background-color: #ffc107;
    }

    #panel-notificaciones-practicas .notif-prac-priority-baja {
        background-color: #28a745;
    }

    #panel-notificaciones-practicas .notif-prac-type {
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 999px;
        font-weight: 500;
    }

    #panel-notificaciones-practicas .notif-prac-type-asignacion_practica {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    #panel-notificaciones-practicas .notif-prac-type-tutoria_asignada {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }

    #panel-notificaciones-practicas .notif-prac-type-recordatorio {
        background-color: #fff3e0;
        color: #f57c00;
    }

    #panel-notificaciones-practicas .notif-prac-type-general {
        background-color: #e8f5e9;
        color: #388e3c;
    }

    #panel-notificaciones-practicas .notif-prac-actions {
        opacity: 0.65;
        transition: opacity 0.2s ease;
    }

    #panel-notificaciones-practicas .notif-prac-item:hover .notif-prac-actions {
        opacity: 1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-user-graduate me-2"></i>
                    Supervisión de Prácticas
                </h3>
            </div>
        </div>

        <!-- Resumen y acciones (una fila en pantallas grandes; cards misma altura/ancho de columna) -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4 align-items-stretch">
            <div class="col d-flex">
                <div class="card text-center shadow-sm h-100 w-100 d-flex flex-column border-0" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center flex-grow-1 py-4 px-2">
                        <h2 class="card-title mb-2" style="font-size: clamp(1.75rem, 4vw, 2.5rem);"><?= $estadisticas['estudiantesAsignados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold small mb-0 text-center" style="color: #e0e0e0;">Estudiantes asignados</p>
                    </div>
                </div>
            </div>
            <div class="col d-flex">
                <div class="card text-center shadow-sm h-100 w-100 d-flex flex-column border-0" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center flex-grow-1 py-4 px-2">
                        <h2 class="card-title mb-2" style="font-size: clamp(1.75rem, 4vw, 2.5rem);"><?= $estadisticas['practicasActivas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold small mb-0 text-center" style="color: #e0e0e0;">Prácticas activas</p>
                    </div>
                </div>
            </div>
            <div class="col d-flex">
                <div class="card text-center shadow-sm h-100 w-100 d-flex flex-column border">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center flex-grow-1 py-4 px-2">
                        <a href="#" onclick="abrirGenerarReportePracticas(); return false;" class="text-decoration-none text-dark">
                            <i class="fas fa-chart-bar fa-2x mb-2 d-block" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <span class="fw-bold">Generar reporte</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col d-flex">
                <div class="card text-center shadow-sm h-100 w-100 d-flex flex-column border">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center flex-grow-1 py-4 px-2">
                        <a href="#" onclick="verCalendario(); return false;" class="text-decoration-none text-dark">
                            <i class="fas fa-calendar-alt fa-2x mb-2 d-block" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <span class="fw-bold">Ver calendario</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->include('docente/practicas/partials/panel_notificaciones_practicas') ?>

        <!-- Mis estudiantes -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <span><i class="fas fa-users me-2"></i>Mis estudiantes</span>
                    </div>
                    <div class="card-body">
                                        <?php if (!empty($estudiantesAsignados)): ?>
                                            <?php foreach ($estudiantesAsignados as $idx => $estudiante): ?>
                                                <?php
                                                $nivelCumpl = $estudiante['CUMPLIMIENTO_NIVEL'] ?? 'secondary';
                                                $badgeCumpl = match ($nivelCumpl) {
                                                    'success' => 'bg-success',
                                                    'warning' => 'bg-warning text-dark',
                                                    'danger' => 'bg-danger',
                                                    'info' => 'bg-info text-dark',
                                                    default => 'bg-secondary',
                                                };
                                                ?>
                                                <div class="card border shadow-sm mb-3">
                                                    <div class="card-header bg-primary text-white py-2">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-8">
                                                                <div class="d-flex align-items-center">
                                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($estudiante['NOMBRE_COMPLETO']) ?>&background=fff&color=0d6efd&size=50" class="estudiante-avatar me-3" alt="<?= substr($estudiante['NOMBRE_COMPLETO'], 0, 2) ?>">
                                                                    <div>
                                                                        <h6 class="mb-0"><?= $estudiante['NOMBRE_COMPLETO'] ?></h6>
                                                                        <small class="opacity-75"><?= esc($estudiante['CARRERA']) ?> — <?= esc($estudiante['INSTITUCION_NOMBRE']) ?></small>
                                                                        <div class="mt-1">
                                                                            <span class="badge bg-light text-primary me-1"><?= esc($estudiante['TIPO']) ?></span>
                                                                            <span class="badge <?= $badgeCumpl ?>" title="<?= esc($estudiante['CUMPLIMIENTO_DESCRIPCION'] ?? '') ?>"><?= esc($estudiante['CUMPLIMIENTO_ETIQUETA'] ?? '—') ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 text-md-end">
                                                                <?php
                                                                $estadoClass = '';
                                                                switch ($estudiante['ESTADO_PRACTICA']) {
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
                                                                <span class="estado-badge <?= $estadoClass ?>"><?= $estudiante['ESTADO_PRACTICA'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <strong>Período:</strong><br>
                                                                        <small class="text-muted">
                                                                            <?= date('d/m/Y', strtotime($estudiante['FECHA_INICIO'])) ?> - <?= date('d/m/Y', strtotime($estudiante['FECHA_FIN'])) ?>
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Horas:</strong><br>
                                                                        <span class="badge bg-info"><?= $estudiante['HORAS_CUMPLIDAS'] ?>/<?= $estudiante['HORAS_TOTALES'] ?>h</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <strong>Última actividad registrada:</strong><br>
                                                                        <small class="text-muted"><?= esc($estudiante['ULTIMA_ACTIVIDAD'] ?? 'Sin actividades') ?></small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Progreso de horas:</strong><br>
                                                                        <div class="progress" style="height: 8px;">
                                                                            <div class="progress-bar bg-success" style="width: <?= min(100, (float) $estudiante['PORCENTAJE_PROGRESO']) ?>%"></div>
                                                                        </div>
                                                                        <small class="text-muted"><?= esc((string) $estudiante['PORCENTAJE_PROGRESO']) ?>% de la meta de horas</small>
                                                                    </div>
                                                                </div>
                                                                <?php if (!empty($estudiante['CUMPLIMIENTO_DESCRIPCION'])): ?>
                                                                    <div class="row mt-2">
                                                                        <div class="col-12">
                                                                            <div class="alert alert-light border small mb-0 py-2">
                                                                                <strong class="text-dark"><i class="fas fa-clipboard-check me-1"></i>Cumplimiento:</strong>
                                                                                <?= esc($estudiante['CUMPLIMIENTO_DESCRIPCION']) ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="progreso-circular">
                                                                    <canvas class="progreso-est-canvas" id="progresoEstCanvas<?= (int) $idx ?>" width="80" height="80" data-pct="<?= esc((string) $estudiante['PORCENTAJE_PROGRESO']) ?>"></canvas>
                                                                    <div class="progreso-texto"><?= esc((string) $estudiante['PORCENTAJE_PROGRESO']) ?>%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="btn-group w-100 btn-group-sm" role="group">
                                                            <button class="btn btn-outline-primary" onclick="verDetalleEstudiante(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                                            </button>
                                                            <button class="btn btn-outline-info" onclick="enviarMensaje(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-comment me-1"></i>Mensaje
                                                            </button>
                                                            <button class="btn btn-outline-warning" onclick="verActividades(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-list me-1"></i>Actividades
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No tienes estudiantes asignados</p>
                                                <small class="text-muted">Contacta con el coordinador para asignaciones</small>
                                            </div>
                                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Detalle de Estudiante -->
    <div class="modal fade" id="modalDetalleEstudiante" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-graduate me-2"></i>
                        Detalle del Estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Información del Estudiante</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nombre:</strong> <span id="detalleNombre">-</span></p>
                                            <p><strong>Carrera:</strong> <span id="detalleCarrera">-</span></p>
                                            <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                            <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                            <p><strong>Progreso:</strong> <span id="detalleProgreso">-</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Registro de asistencias</h6>
                                    <small class="text-muted">Lo que el estudiante ha registrado</small>
                                </div>
                                <div class="card-body">
                                    <div id="listaAsistenciasEstudiante" class="timeline">
                                        <p class="text-muted small mb-0">Cargando...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <h6 class="text-muted text-uppercase small mb-0"><i class="fas fa-chart-pie me-1"></i>Progreso y cumplimiento</h6>
                                <p class="small text-muted mb-2">Horas registradas (asistencias y seguimiento) frente a la meta y al período.</p>
                            </div>
                            <div id="panelProgresosDetalle"></div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Acciones Rápidas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success" onclick="enviarMensaje()">
                                            <i class="fas fa-comment me-1"></i>Enviar Mensaje
                                        </button>
                                        <button class="btn btn-info" onclick="verActividades()">
                                            <i class="fas fa-list me-1"></i>Ver Actividades
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Calendario de Prácticas -->
    <div class="modal fade" id="modalCalendario" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-alt me-2"></i>Calendario de Prácticas y Servicio Comunitario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="calendario" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-height: 400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal generar reporte (acción rápida) -->
    <div class="modal fade" id="modalGenerarReportePracticas" tabindex="-1" aria-labelledby="modalGenerarReportePracticasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGenerarReportePracticasLabel">
                        <i class="fas fa-chart-bar me-2"></i>Generar reporte
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formGenerarReportePracticas" novalidate>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Tipo de reporte</label>
                            <select class="form-select" name="tipo_reporte" required>
                                <option value="">Seleccionar…</option>
                                <option value="progreso_estudiantes">Progreso de estudiantes</option>
                                <option value="actividades_realizadas">Actividades realizadas</option>
                                <option value="documentos_entregados">Documentos entregados</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control" name="fecha_desde" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control" name="fecha_hasta" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Formato</label>
                            <select class="form-select" name="formato" required>
                                <option value="pdf">PDF (vista para imprimir)</option>
                                <option value="excel">Excel (CSV)</option>
                                <option value="word">Word (vista para imprimir)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-download me-1"></i>Generar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
    <script>
        const baseUrlPracticas = '<?= base_url("docente/practicas") ?>';
        const baseUrlNotificaciones = '<?= rtrim(base_url("notificaciones"), "/") ?>';

        function escHtmlModal(s) {
            if (s === null || s === undefined) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function fmtRangoPractica(ini, fin) {
            if (!ini && !fin) return '—';
            return (ini || '—') + ' → ' + (fin || '—');
        }

        function renderPanelProgresosDetalle(progresos) {
            var host = document.getElementById('panelProgresosDetalle');
            if (!host) return;
            if (!progresos || !progresos.length) {
                host.innerHTML = '<p class="text-muted small mb-0">Sin datos de progreso.</p>';
                return;
            }
            var mapBadge = { success: 'bg-success', warning: 'bg-warning text-dark', danger: 'bg-danger', info: 'bg-info text-dark', secondary: 'bg-secondary' };
            host.innerHTML = progresos.map(function(p, i) {
                var c = p.cumplimiento || {};
                var bc = mapBadge[c.nivel] || 'bg-secondary';
                var pct = parseFloat(p.porcentaje) || 0;
                var hc = parseFloat(p.horas_cumplidas) || 0;
                var ht = parseFloat(p.horas_totales) || 0;
                return (
                    '<div class="card mb-3 border-0 shadow-sm">' +
                    '<div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap gap-1">' +
                    '<span class="fw-semibold small">' + escHtmlModal(p.tipo_etiqueta || '') + '</span>' +
                    '<span class="badge ' + bc + '">' + escHtmlModal(c.etiqueta || '') + '</span></div>' +
                    '<div class="card-body text-center pt-3">' +
                    '<div class="position-relative d-inline-block">' +
                    '<canvas id="detalleProgressChart' + i + '" width="110" height="110"></canvas>' +
                    '<div class="position-absolute top-50 start-50 translate-middle fw-bold small">' + pct + '%</div></div>' +
                    '<h6 class="mt-2 mb-2">' + hc + ' h de ' + ht + ' h</h6>' +
                    '<p class="small text-muted text-start mb-2">' + escHtmlModal(c.descripcion || '') + '</p>' +
                    (p.institucion ? '<p class="small text-start mb-1"><strong>Entidad:</strong> ' + escHtmlModal(p.institucion) + '</p>' : '') +
                    '<p class="small text-start mb-1"><strong>Período:</strong> ' + escHtmlModal(fmtRangoPractica(p.fecha_inicio, p.fecha_fin)) + '</p>' +
                    '<p class="small text-start mb-0"><strong>Estado registro:</strong> ' + escHtmlModal(p.estado || '—') + '</p>' +
                    '</div></div>'
                );
            }).join('');
            progresos.forEach(function(p, i) {
                var pct = parseFloat(p.porcentaje) || 0;
                setTimeout(function() { drawProgressChartOnCanvas('detalleProgressChart' + i, pct, 52, 8); }, 40 * (i + 1));
            });
        }

        function verDetalleEstudiante(id) {
            const modalEl = document.getElementById('modalDetalleEstudiante');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            document.getElementById('detalleNombre').textContent = 'Cargando...';
            document.getElementById('detalleCarrera').textContent = '';
            document.getElementById('detalleInstitucion').textContent = '';
            document.getElementById('detallePeriodo').textContent = '';
            document.getElementById('detalleEstado').textContent = '';
            document.getElementById('detalleProgreso').textContent = '';
            document.getElementById('listaAsistenciasEstudiante').innerHTML = '<p class="text-muted small mb-0">Cargando...</p>';
            var panelP = document.getElementById('panelProgresosDetalle');
            if (panelP) panelP.innerHTML = '<p class="text-muted small mb-0">Cargando progreso...</p>';
            fetch(baseUrlPracticas + '/detalle-estudiante/' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.data) {
                        const e = data.data.estudiante || {};
                        const progresos = data.data.progresos || [];
                        const actividades = data.data.actividades || [];
                        document.getElementById('detalleNombre').textContent = e.NOMBRE_COMPLETO || e.NOMBRE || '—';
                        document.getElementById('detalleCarrera').textContent = e.CARRERA_NOMBRE || '—';

                        if (progresos.length === 1) {
                            const p0 = progresos[0];
                            document.getElementById('detalleInstitucion').textContent = p0.institucion || e.INSTITUCION_NOMBRE || '—';
                            document.getElementById('detallePeriodo').textContent = fmtRangoPractica(p0.fecha_inicio, p0.fecha_fin);
                            document.getElementById('detalleEstado').textContent = p0.estado || '—';
                            document.getElementById('detalleProgreso').textContent =
                                (p0.porcentaje || 0) + '% · ' + (parseFloat(p0.horas_cumplidas) || 0) + ' h / ' + (parseFloat(p0.horas_totales) || 0) + ' h';
                        } else if (progresos.length > 1) {
                            document.getElementById('detalleInstitucion').textContent = e.INSTITUCION_NOMBRE || 'Varias entidades';
                            document.getElementById('detallePeriodo').textContent = 'Práctica preprofesional y servicio comunitario';
                            document.getElementById('detalleEstado').textContent = 'Ver cada bloque al lado';
                            document.getElementById('detalleProgreso').textContent = 'Resumen por modalidad →';
                        } else {
                            document.getElementById('detalleInstitucion').textContent = e.INSTITUCION_NOMBRE || '—';
                            document.getElementById('detallePeriodo').textContent = fmtRangoPractica(e.FECHA_INICIO, e.FECHA_FIN);
                            document.getElementById('detalleEstado').textContent = e.ESTADO_PRACTICA || e.ESTADO_SERVICIO || '—';
                            document.getElementById('detalleProgreso').textContent = (data.data.progreso || 0) + '%';
                        }

                        renderPanelProgresosDetalle(progresos);

                        const cont = document.getElementById('listaAsistenciasEstudiante');
                        if (actividades.length === 0) {
                            cont.innerHTML = '<p class="text-muted small mb-0">Aún no hay asistencias registradas.</p>';
                        } else {
                            function esc(s) {
                                if (!s) return '';
                                var d = document.createElement('div');
                                d.textContent = s;
                                return d.innerHTML;
                            }
                            cont.innerHTML = actividades.map(function(a) {
                                var fecha = a.FECHA_ASISTENCIA || '';
                                var ent = (a.HORA_ENTRADA || '').substring(0, 5);
                                var sal = (a.HORA_SALIDA || '').substring(0, 5);
                                var horas = (ent && sal) ? (ent + ' - ' + sal) : '';
                                var tipoEt = a.TIPO_REGISTRO_ETIQUETA || '';
                                var act = (a.ACTIVIDADES_DIA || '').substring(0, 120);
                                if ((a.ACTIVIDADES_DIA || '').length > 120) act += '...';
                                var obs = (a.OBSERVACIONES || '').trim();
                                var obsHtml = obs ? '<div class="text-muted small mt-1"><strong>Obs.:</strong> ' + esc(obs.substring(0, 80)) + (obs.length > 80 ? '...' : '') + '</div>' : '';
                                var tipoHtml = tipoEt ? '<span class="badge bg-light text-secondary border me-1">' + esc(tipoEt) + '</span>' : '';
                                return '<div class="timeline-item"><div class="timeline-marker"></div><div><div class="fw-semibold">' + tipoHtml + esc(fecha) + (horas ? ' · ' + esc(horas) : '') + '</div><div class="small">' + (act ? esc(act) : '—') + '</div>' + obsHtml + '</div></div>';
                            }).join('');
                        }
                    } else {
                        document.getElementById('detalleNombre').textContent = 'Error al cargar';
                        document.getElementById('listaAsistenciasEstudiante').innerHTML = '<p class="text-muted small mb-0">No se pudieron cargar las asistencias.</p>';
                        if (panelP) panelP.innerHTML = '<p class="text-muted small mb-0">—</p>';
                    }
                })
                .catch(() => {
                    document.getElementById('detalleNombre').textContent = 'Error al cargar';
                    document.getElementById('listaAsistenciasEstudiante').innerHTML = '<p class="text-muted small mb-0">Error al cargar.</p>';
                    if (panelP) panelP.innerHTML = '<p class="text-muted small mb-0">—</p>';
                });
        }

        function showModal(modalId) {
            const el = document.getElementById(modalId);
            if (el) {
                const modal = new bootstrap.Modal(el);
                modal.show();
            }
        }

        function abrirGenerarReportePracticas() {
            var f = document.getElementById('formGenerarReportePracticas');
            if (f) {
                f.classList.remove('was-validated');
            }
            showModal('modalGenerarReportePracticas');
        }

        function mostrarReporteEnVentana(data) {
            var col = data.columnas || [];
            var filas = data.filas || [];
            var html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr>';
            col.forEach(function(c) {
                html += '<th>' + String(c).replace(/</g, '&lt;') + '</th>';
            });
            html += '</tr></thead><tbody>';
            filas.forEach(function(fila) {
                html += '<tr>';
                (Array.isArray(fila) ? fila : []).forEach(function(celda) {
                    html += '<td>' + (celda !== undefined && celda !== null ? String(celda).replace(/</g, '&lt;') : '') + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody></table></div>';
            var ventana = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
            ventana.document.write('<html><head><title>' + (data.titulo || 'Reporte') + '</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4"><h4>' + (data.titulo || 'Reporte') + '</h4><p>Período: ' + (data.fecha_desde || '') + ' a ' + (data.fecha_hasta || '') + '</p>' + html + '<p class="mt-3"><button onclick="window.print()" class="btn btn-primary">Imprimir / Guardar como PDF</button></p></body></html>');
            ventana.document.close();
        }

        function verCalendario() {
            showModal('modalCalendario');
            setTimeout(function() {
                cargarDatosCalendario();
            }, 300);
        }

        async function cargarDatosCalendario() {
            try {
                const response = await fetch(baseUrlPracticas + '/calendario');
                const eventos = await response.json();
                inicializarCalendario(Array.isArray(eventos) ? eventos : []);
            } catch (e) {
                console.error('Error al cargar calendario:', e);
                showNotification('Error al cargar el calendario', 'error');
                inicializarCalendario([]);
            }
        }

        function inicializarCalendario(eventos) {
            const calendarEl = document.getElementById('calendario');
            if (!calendarEl) return;
            calendarEl.innerHTML = '';
            if (typeof FullCalendar === 'undefined') {
                calendarEl.innerHTML = '<p class="text-muted">Cargando calendario...</p>';
                return;
            }
            try {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    events: eventos || [],
                    height: 'auto',
                    dayMaxEvents: true,
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
                window.calendario = calendar;
            } catch (err) {
                console.error(err);
                calendarEl.innerHTML = '<p class="text-danger">Error al mostrar el calendario.</p>';
            }
        }

        function enviarMensaje(id) {
            showNotification('Abriendo chat con el estudiante...', 'info');
        }

        function verActividades(id) {
            showNotification('Mostrando actividades del estudiante...', 'info');
        }

        function drawProgressChartOnCanvas(canvasId, percentage, radius, lineWidth) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            radius = radius || 50;
            lineWidth = lineWidth || 8;
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const pct = Math.max(0, Math.min(100, parseFloat(percentage) || 0));

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.strokeStyle = '#e9ecef';
            ctx.lineWidth = lineWidth;
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * pct / 100));
            ctx.strokeStyle = '#667eea';
            ctx.lineWidth = lineWidth;
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

        function initNotificacionesPracticasPanel() {
            var panel = document.getElementById('panel-notificaciones-practicas');
            if (!panel) return;

            function actualizarStatsNotifPrac() {
                var items = panel.querySelectorAll('.notif-prac-item');
                var total = items.length;
                var noLeidas = 0;
                items.forEach(function(el) {
                    if (el.getAttribute('data-leida') === '0') noLeidas++;
                });
                var elT = document.getElementById('notifPracStatTotal');
                var elN = document.getElementById('notifPracStatNoLeidas');
                var elL = document.getElementById('notifPracStatLeidas');
                if (elT) elT.textContent = total;
                if (elN) elN.textContent = noLeidas;
                if (elL) elL.textContent = Math.max(0, total - noLeidas);
            }

            function filtrarNotifPrac(filtro) {
                panel.querySelectorAll('.notif-prac-item').forEach(function(elemento) {
                    var mostrar = true;
                    if (filtro === 'no_leidas') {
                        mostrar = elemento.getAttribute('data-leida') === '0';
                    } else if (filtro === 'tutoria_asignada' || filtro === 'asignacion_practica' || filtro === 'recordatorio' || filtro === 'general') {
                        mostrar = elemento.getAttribute('data-tipo') === filtro;
                    }
                    elemento.style.display = mostrar ? '' : 'none';
                });
                panel.querySelectorAll('.notif-prac-filter-btn').forEach(function(btn) {
                    btn.classList.toggle('active', btn.getAttribute('data-filter') === filtro);
                });
            }

            panel.addEventListener('click', function(e) {
                var t = e.target;
                var btnLeida = t.closest && t.closest('.notif-prac-btn-leida');
                var btnElim = t.closest && t.closest('.notif-prac-btn-eliminar');
                var btnFilt = t.closest && t.closest('.notif-prac-filter-btn');

                if (btnFilt) {
                    filtrarNotifPrac(btnFilt.getAttribute('data-filter') || 'todas');
                    return;
                }

                if (btnLeida) {
                    var id = btnLeida.getAttribute('data-id');
                    if (!id) return;
                    fetch(baseUrlNotificaciones + '/marcar-leida/' + id, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                var elemento = panel.querySelector('.notif-prac-item[data-id="' + id + '"]');
                                if (elemento) {
                                    elemento.classList.remove('notif-prac-unread');
                                    elemento.classList.add('notif-prac-read');
                                    elemento.setAttribute('data-leida', '1');
                                    var b = elemento.querySelector('.notif-prac-btn-leida');
                                    if (b) b.remove();
                                }
                                actualizarStatsNotifPrac();
                                showNotification('Notificación marcada como revisada', 'success');
                            } else {
                                showNotification('No se pudo marcar la notificación', 'error');
                            }
                        })
                        .catch(function() {
                            showNotification('Error de conexión', 'error');
                        });
                    return;
                }

                if (btnElim) {
                    var idE = btnElim.getAttribute('data-id');
                    if (!idE || !confirm('¿Eliminar esta notificación?')) return;
                    fetch(baseUrlNotificaciones + '/eliminar/' + idE, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                var elemento = panel.querySelector('.notif-prac-item[data-id="' + idE + '"]');
                                if (elemento) elemento.remove();
                                actualizarStatsNotifPrac();
                                showNotification('Notificación eliminada', 'success');
                            } else {
                                showNotification('No se pudo eliminar', 'error');
                            }
                        })
                        .catch(function() {
                            showNotification('Error de conexión', 'error');
                        });
                }
            });

            var btnTodas = document.getElementById('notifPracBtnMarcarTodas');
            if (btnTodas) {
                btnTodas.addEventListener('click', function() {
                    if (!confirm('¿Marcar todas las notificaciones como revisadas?')) return;
                    fetch(baseUrlNotificaciones + '/marcar-todas-leidas', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                panel.querySelectorAll('.notif-prac-item.notif-prac-unread').forEach(function(elemento) {
                                    elemento.classList.remove('notif-prac-unread');
                                    elemento.classList.add('notif-prac-read');
                                    elemento.setAttribute('data-leida', '1');
                                    var b = elemento.querySelector('.notif-prac-btn-leida');
                                    if (b) b.remove();
                                });
                                actualizarStatsNotifPrac();
                                showNotification('Listo: todas marcadas como revisadas', 'success');
                            } else {
                                showNotification('No se pudo completar la acción', 'error');
                            }
                        })
                        .catch(function() {
                            showNotification('Error de conexión', 'error');
                        });
                });
            }
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initNotificacionesPracticasPanel();

            var params = new URLSearchParams(window.location.search);
            if (window.location.hash === '#panel-notificaciones-practicas' || params.get('ver') === 'notificaciones') {
                var elPanel = document.getElementById('panel-notificaciones-practicas');
                if (elPanel) {
                    setTimeout(function() {
                        elPanel.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 150);
                }
                if (params.get('ver') === 'notificaciones') {
                    history.replaceState(null, '', window.location.pathname + window.location.hash);
                }
            }

            var formRep = document.getElementById('formGenerarReportePracticas');
            if (formRep) {
                formRep.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!formRep.checkValidity()) {
                        formRep.classList.add('was-validated');
                        return;
                    }
                    var btn = formRep.querySelector('button[type="submit"]');
                    var txt = btn ? btn.innerHTML : '';
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generando…';
                    }
                    var fd = new FormData(formRep);
                    fetch(baseUrlPracticas + '/generar-reporte', {
                            method: 'POST',
                            body: fd,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(res) {
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = txt;
                            }
                            if (res.success) {
                                var modalEl = document.getElementById('modalGenerarReportePracticas');
                                var modalInst = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                                if (res.csv && res.formato === 'excel') {
                                    var blob = new Blob(['\ufeff' + res.csv], { type: 'text/csv;charset=utf-8' });
                                    var a = document.createElement('a');
                                    a.href = URL.createObjectURL(blob);
                                    a.download = res.nombre_archivo || 'reporte_practicas.csv';
                                    a.click();
                                    URL.revokeObjectURL(a.href);
                                    showNotification('Reporte descargado correctamente', 'success');
                                    if (modalInst) modalInst.hide();
                                } else if (res.data && (res.formato === 'pdf' || res.formato === 'word')) {
                                    mostrarReporteEnVentana(res.data);
                                    showNotification('Reporte generado. Puede imprimir desde la ventana.', 'success');
                                    if (modalInst) modalInst.hide();
                                } else {
                                    showNotification(res.message || 'Reporte generado', 'success');
                                    if (modalInst) modalInst.hide();
                                }
                            } else {
                                showNotification(res.message || 'Error al generar el reporte', 'error');
                            }
                        })
                        .catch(function() {
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = txt;
                            }
                            showNotification('Error de conexión al generar el reporte', 'error');
                        });
                });
            }

            setTimeout(function() {
                document.querySelectorAll('canvas.progreso-est-canvas').forEach(function(canvas) {
                    var pct = parseFloat(canvas.getAttribute('data-pct')) || 0;
                    var ctx = canvas.getContext('2d');
                    var centerX = canvas.width / 2;
                    var centerY = canvas.height / 2;
                    var radius = 30;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                    ctx.strokeStyle = '#e9ecef';
                    ctx.lineWidth = 6;
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * Math.min(100, pct) / 100));
                    ctx.strokeStyle = '#28a745';
                    ctx.lineWidth = 6;
                    ctx.lineCap = 'round';
                    ctx.stroke();
                });
            }, 150);
        });
    </script>
    <?= $this->endSection() ?>