<?= $this->extend('coord/layouts/mainCoord') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Detalles de la Actividad
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="<?= base_url('coord/actividades-educacion') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $encuestaSatisfaccionAlert = $encuestaSatisfaccion ?? null;
        $fechaFinAlert = new DateTime($actividad['FECHA_FIN']);
        $fechaFinAlert->setTime(0, 0, 0);
        $hoyAlert = new DateTime('today');
        $estaFinalizadaSinEncuesta = ($fechaFinAlert <= $hoyAlert) && empty($encuestaSatisfaccionAlert);
        ?>
        <?php if ($estaFinalizadaSinEncuesta): ?>
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <strong>Actividad finalizada.</strong> Por favor agregue el enlace de la encuesta de satisfacción en esta actividad para que los participantes puedan evaluarla.
                </div>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEncuestaSatisfaccion">
                    <i class="fas fa-plus me-1"></i>Agregar enlace
                </button>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <!-- Información de la Actividad -->
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?= $actividad['NOMBRE_ACTIVIDAD'] ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tipo de Actividad:</strong>
                                    <span class="badge bg-info"><?= $actividad['TIPO_ACTIVIDAD'] ?></span>
                                </p>
                                <p><strong>Instructor:</strong> <?= $actividad['NOMBRE'] ?> <?= $actividad['APELLIDO'] ?></p>
                                <p><strong>Especialidad:</strong> <?= $actividad['ESPECIALIDAD'] ?></p>
                                <p><strong>Modalidad:</strong>
                                    <span class="badge bg-secondary"><?= $actividad['MODALIDAD'] ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Período:</strong>
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_INICIO'])) ?> -
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_FIN'])) ?>
                                </p>
                                <p><strong>Duración:</strong>
                                    <span class="badge bg-warning text-dark"><?= $actividad['DURACION_HORAS'] ?> horas</span>
                                </p>
                                <?php if (trim((string) ($actividad['LUGAR'] ?? '')) !== ''): ?>
                                    <p><strong>Lugar:</strong> <?= esc($actividad['LUGAR']) ?></p>
                                <?php endif; ?>
                                <?php
                                $enlaceAct = trim((string) ($actividad['ENLACE'] ?? ''));
                                if ($enlaceAct !== ''):
                                    $hrefEnlaceAct = preg_match('#^https?://#i', $enlaceAct) ? $enlaceAct : 'https://' . $enlaceAct;
                                    ?>
                                    <p><strong>Enlace:</strong> <a href="<?= esc($hrefEnlaceAct, 'attr') ?>" target="_blank" rel="noopener"><?= esc($enlaceAct) ?></a></p>
                                <?php endif; ?>
                                <p><strong>Horario:</strong> <?= esc($actividad['HORARIO']) ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Descripción:</strong></h6>
                                <p class="text-muted"><?= nl2br($actividad['DESCRIPCION']) ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Objetivos:</strong></h6>
                                <p class="text-muted"><?= nl2br($actividad['OBJETIVOS']) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($actividad['PROGRAMA_DETALLADO'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <h6><strong>Programa Detallado:</strong></h6>
                                    <p class="text-muted"><?= nl2br($actividad['PROGRAMA_DETALLADO']) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Estado de la Actividad -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Estado de la Actividad</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $fechaFin = new DateTime($actividad['FECHA_FIN']);
                        $hoy = new DateTime();
                        if ($fechaFin >= $hoy) {
                            echo '<h4 class="text-success">Activa</h4>';
                            echo '<p class="text-muted">La actividad está en curso</p>';
                        } else {
                            echo '<h4 class="text-secondary">Finalizada</h4>';
                            echo '<p class="text-muted">La actividad ha concluido</p>';
                        }
                        ?>


                    </div>
                </div>

                <!-- Encuesta de satisfacción (solo cuando el curso ha finalizado) -->
                <?php
                $fechaFin = new DateTime($actividad['FECHA_FIN']);
                $fechaFin->setTime(0, 0, 0);
                $hoy = new DateTime('today');
                $estaFinalizada = $fechaFin <= $hoy;
                $encuestaSatisfaccion = $encuestaSatisfaccion ?? null;
                ?>
                <?php if ($estaFinalizada): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-clipboard-check me-1"></i>Encuesta de satisfacción</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($encuestaSatisfaccion)): ?>
                                <p class="small text-muted mb-2">Enlace para que los participantes evalúen el curso.</p>
                                <a href="<?= esc($encuestaSatisfaccion['ENLACE_FORMULARIO']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Abrir encuesta
                                </a>
                                <span class="text-muted small ms-2"><?= (int)($encuestaSatisfaccion['NUMERO_RESPUESTAS'] ?? 0) ?> respuestas</span>
                            <?php else: ?>
                                <p class="small text-muted mb-2">Al finalizar el curso puedes publicar el enlace de la encuesta de satisfacción.</p>
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEncuestaSatisfaccion">
                                    <i class="fas fa-plus me-1"></i>Agregar enlace de encuesta
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Acciones Rápidas -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)">
                                <i class="fas fa-users me-1"></i>Gestionar Participantes
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="generarReporte(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)">
                                <i class="fas fa-file-alt me-1"></i>Reporte de Asistencia
                            </button>
                            <a href="<?= base_url('coord/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>"
                                class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta actividad?')">
                                <i class="fas fa-trash me-1"></i>Eliminar Actividad
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Agregar encuesta de satisfacción -->
<div class="modal fade" id="modalEncuestaSatisfaccion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-clipboard-check me-2"></i>Encuesta de satisfacción</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEncuestaSatisfaccion">
                    <?= csrf_field() ?>
                    <input type="hidden" name="curso_id" value="<?= (int)$actividad['ID_ACTIVIDAD_EDUCACION'] ?>">
                    <input type="hidden" name="tipo_evaluacion" value="satisfaccion">
                    <div class="mb-3">
                        <label class="form-label">Curso</label>
                        <input type="text" class="form-control" value="<?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de la evaluación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_evaluacion" value="Encuesta de satisfacción - <?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enlace del formulario <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="enlace_formulario" placeholder="https://forms.google.com/..." required>
                        <small class="text-muted">Enlace de Google Forms, Microsoft Forms, etc.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="2" placeholder="Opcional"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de vencimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_vencimiento" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="borrador">Borrador</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarEncuesta">
                    <i class="fas fa-save me-1"></i>Guardar encuesta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnGuardarEncuesta')?.addEventListener('click', function() {
        var form = document.getElementById('formEncuestaSatisfaccion');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
        var fd = new FormData(form);
        fetch('<?= base_url('coord/evaluaciones/agregar') ?>', {
            method: 'POST',
            body: fd,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function(r) {
            return r.json();
        }).then(function(data) {
            if (data.success) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalEncuestaSatisfaccion'));
                if (modal) modal.hide();
                showNotification(data.message || 'Encuesta guardada. Recargando...', 'success');
                setTimeout(function() {
                    window.location.reload();
                }, 1200);
            } else {
                showNotification(data.message || 'Error al guardar', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar encuesta';
            }
        }).catch(function() {
            showNotification('Error de conexión', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar encuesta';
        });
    });

    function gestionarParticipantes(id) {
        showNotification('Función de gestión de participantes en desarrollo', 'info');
    }

    function generarReporte(id) {
        showNotification('Generando reporte...', 'info');
        setTimeout(() => {
            showNotification('Reporte generado exitosamente', 'success');
        }, 2000);
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
</script>

<?= $this->endSection() ?>