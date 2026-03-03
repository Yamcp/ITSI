<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .practica-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    .practica-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .practica-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }
    .practica-body {
        padding: 1.5rem;
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
    .accion-btn {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .accion-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        background: #667eea;
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
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
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
                    <i class="fas fa-hands-helping me-2"></i>
                    Prácticas de Servicio Comunitario
                </h3>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['totalPracticas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Prácticas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['practicasActivas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">En Progreso</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0c5460 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['practicasFinalizadas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Finalizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['horasCompletadas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Horas Completadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="registrarActividad()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745;"></i>
                            <div class="fw-bold">Registrar Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="subirDocumento()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-upload fa-2x mb-2" style="color: #007bff;"></i>
                            <div class="fw-bold">Subir Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verProgreso()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-line fa-2x mb-2" style="color: #ffc107;"></i>
                            <div class="fw-bold">Ver Progreso</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="https://wa.me/593995298537" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-comments fa-2x mb-2" style="color: #dc3545;"></i>
                            <div class="fw-bold">Contactar Supervisor</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Prácticas de Servicio Comunitario -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">
                            <i class="fas fa-hands-helping me-2"></i>
                            Mis prácticas de servicio comunitario
                        </h5>
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
                                                switch($servicio['ESTADO_SERVICIO']) {
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
                                                        <span class="badge bg-info"><?= (int)($servicio['HORAS_SERVICIO'] ?? 0) ?>h</span>
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
                                                <div class="btn-group w-100" role="group">
                                                    <button class="btn btn-outline-primary accion-btn" onclick="verDetallePractica(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                        <i class="fas fa-eye me-1"></i>Ver Detalle
                                                    </button>
                                                    <button class="btn btn-outline-success accion-btn" onclick="registrarActividadPractica(<?= (int)$servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                        <i class="fas fa-plus me-1"></i>Registrar
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
                                <p class="text-muted">Contacta con tu coordinador para más información</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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
                            <div class="card-header"><h6 class="mb-0">Información General</h6></div>
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
                            <div class="card-header"><h6 class="mb-0">Actividades Recientes</h6></div>
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
                            <div class="card-header"><h6 class="mb-0">Progreso</h6></div>
                            <div class="card-body text-center">
                                <div class="progreso-circular">
                                    <canvas id="progressChart" width="120" height="120"></canvas>
                                    <div class="progreso-texto" style="font-size: 1.1rem;">0%</div>
                                </div>
                                <h5 class="mt-3" id="progressHours">-</h5>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h6 class="mb-0">Contacto</h6></div>
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

    function verDetallePractica(id, tipo) {
        practicaActual = { id: id, tipo: tipo };
        document.getElementById('detalleTipo').textContent = 'Servicio Comunitario';
        const modal = new bootstrap.Modal(document.getElementById('modalDetallePractica'));
        modal.show();
        setTimeout(() => drawProgressChart(practicaActual ? 50 : 0), 100);
    }

    function registrarActividad() {
        new bootstrap.Modal(document.getElementById('modalRegistrarActividad')).show();
    }

    function registrarActividadPractica(id, tipo) {
        practicaActual = { id: id, tipo: tipo };
        registrarActividad();
    }

    function subirDocumento() { showNotification('Función de subida de documentos en desarrollo', 'info'); }
    function verProgreso() { showNotification('Mostrando progreso detallado...', 'info'); }
    function verDocumentos(id, tipo) { showNotification('Mostrando documentos de la práctica...', 'info'); }

    function guardarActividad() {
        const form = document.getElementById('formRegistrarActividad');
        if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
        showNotification('Actividad registrada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalRegistrarActividad')).hide();
        form.reset();
        form.classList.remove('was-validated');
    }

    function drawProgressChart(percentage) {
        const canvas = document.getElementById('progressChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2, centerY = canvas.height / 2, radius = 50;
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
        const colors = { success: '#27ae60', error: '#e74c3c', warning: '#f39c12', info: '#3498db' };
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
        document.querySelectorAll('[id^="progresoServ"]').forEach(function(canvas) {
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2, centerY = canvas.height / 2, radius = 30;
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
