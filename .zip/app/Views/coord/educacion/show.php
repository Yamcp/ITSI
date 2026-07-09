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
                <button type="button"
                    class="btn btn-warning btn-sm btn-modal-encuesta-coord"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEncuestaSatisfaccion"
                    data-encuesta-modo="nuevo">
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
                        <div class="card-header bg-success text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="mb-0"><i class="fas fa-clipboard-check me-1"></i>Encuesta de satisfacción</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($encuestaSatisfaccion)): ?>
                                <?php
                                $hrefEnc = esc($encuestaSatisfaccion['ENLACE_FORMULARIO'], 'attr');
                                $fvEnc = !empty($encuestaSatisfaccion['FECHA_VENCIMIENTO'])
                                    ? substr((string) $encuestaSatisfaccion['FECHA_VENCIMIENTO'], 0, 10)
                                    : '';
                                ?>
                                <p class="small text-muted mb-2">
                                    Como <strong>coordinador</strong>, usted define el enlace del formulario (Google Forms, Microsoft Forms, etc.).
                                    Docentes y estudiantes verán esta misma URL para evaluar el curso.
                                </p>
                                <p class="small mb-2 text-break font-monospace bg-light rounded px-2 py-1 border">
                                    <?= esc($encuestaSatisfaccion['ENLACE_FORMULARIO']) ?>
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <a href="<?= $hrefEnc ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>Abrir encuesta
                                    </a>
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm btn-modal-encuesta-coord"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEncuestaSatisfaccion"
                                        data-encuesta-modo="nuevo-reemplazo"
                                        data-evaluacion-id="<?= (int) ($encuestaSatisfaccion['ID_EVALUACION_ENLACE'] ?? 0) ?>">
                                        <i class="fas fa-plus me-1"></i>Agregar enlace
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-secondary btn-sm btn-modal-encuesta-coord"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEncuestaSatisfaccion"
                                        data-encuesta-modo="editar"
                                        data-evaluacion-id="<?= (int) ($encuestaSatisfaccion['ID_EVALUACION_ENLACE'] ?? 0) ?>"
                                        data-nombre-evaluacion="<?= esc($encuestaSatisfaccion['NOMBRE_EVALUACION'] ?? '', 'attr') ?>"
                                        data-enlace-formulario="<?= esc($encuestaSatisfaccion['ENLACE_FORMULARIO'] ?? '', 'attr') ?>"
                                        data-descripcion="<?= esc($encuestaSatisfaccion['DESCRIPCION'] ?? '', 'attr') ?>"
                                        data-fecha-vencimiento="<?= esc($fvEnc, 'attr') ?>"
                                        data-estado="<?= esc($encuestaSatisfaccion['ESTADO'] ?? 'activo', 'attr') ?>">
                                        <i class="fas fa-edit me-1"></i>Cambiar enlace
                                    </button>
                                </div>
                                <p class="small text-muted mb-0">
                                    <strong>Agregar enlace:</strong> formulario limpio para registrar una URL nueva (sustituye la actual).
                                    <strong>Cambiar enlace:</strong> edita los datos ya guardados.
                                </p>
                                <p class="text-muted small mb-0">
                                    <strong><?= (int) ($encuestaSatisfaccion['NUMERO_RESPUESTAS'] ?? 0) ?></strong> respuestas registradas
                                </p>
                            <?php else: ?>
                                <p class="small text-muted mb-2">
                                    El curso ha finalizado. Agregue aquí el <strong>enlace público</strong> de la encuesta de satisfacción para que instructores y participantes puedan evaluar.
                                </p>
                                <button type="button"
                                    class="btn btn-success btn-sm btn-modal-encuesta-coord"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEncuestaSatisfaccion"
                                    data-encuesta-modo="nuevo">
                                    <i class="fas fa-link me-1"></i>Agregar enlace de encuesta
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
                                >
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
                <h5 class="modal-title" id="modalEncuestaSatisfaccionTitulo"><i class="fas fa-clipboard-check me-2"></i><span id="modalEncuestaSatisfaccionTituloTexto">Encuesta de satisfacción</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3" id="modalEncuestaSatisfaccionAyuda">
                    Pegue la URL completa del formulario (debe incluir <code>https://</code>). Este enlace es el que verán docentes y estudiantes.
                </p>
                <form id="formEncuestaSatisfaccion">
                    <?= csrf_field() ?>
                    <input type="hidden" id="encuestaEvaluacionIdEdicion" value="">
                    <input type="hidden" name="curso_id" value="<?= (int) $actividad['ID_ACTIVIDAD_EDUCACION'] ?>">
                    <input type="hidden" name="tipo_evaluacion" value="satisfaccion">
                    <div class="mb-3">
                        <label class="form-label">Actividad</label>
                        <input type="text" class="form-control" value="<?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de la evaluación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_evaluacion" id="encuesta_input_nombre" value="Encuesta de satisfacción - <?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enlace del formulario <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="enlace_formulario" id="encuesta_input_enlace" placeholder="https://forms.google.com/..." required autocomplete="off">
                        <small class="text-muted">Google Forms, Microsoft Forms u otra plataforma.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="encuesta_input_descripcion" rows="2" placeholder="Opcional"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de vencimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_vencimiento" id="encuesta_input_fecha_vencimiento" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="encuesta_input_estado">
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
                    <i class="fas fa-save me-1"></i><span id="btnGuardarEncuestaTexto">Guardar enlace</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var modalEl = document.getElementById('modalEncuestaSatisfaccion');
        var nombreDefaultEvaluacion = <?= json_encode('Encuesta de satisfacción - ' . $actividad['NOMBRE_ACTIVIDAD'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
        /** @type {'nuevo'|'editar'|'nuevo-reemplazo'} */
        var modoEncuestaActual = 'nuevo';

        function labelBotonGuardar(modo) {
            if (modo === 'editar') {
                return 'Guardar cambios';
            }
            return 'Guardar enlace';
        }

        function restaurarBotonGuardar(btn, modo) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i><span id="btnGuardarEncuestaTexto">' + labelBotonGuardar(modo) + '</span>';
        }

        function rellenarFormularioEncuestaComoNuevo(hoy, fechaDefNueva) {
            document.getElementById('encuesta_input_nombre').value = nombreDefaultEvaluacion;
            document.getElementById('encuesta_input_enlace').value = '';
            document.getElementById('encuesta_input_descripcion').value = '';
            document.getElementById('encuesta_input_fecha_vencimiento').min = hoy;
            document.getElementById('encuesta_input_fecha_vencimiento').value = fechaDefNueva;
            document.getElementById('encuesta_input_estado').value = 'activo';
        }

        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', function(ev) {
                var btn = ev.relatedTarget;
                var idEd = document.getElementById('encuestaEvaluacionIdEdicion');
                var form = document.getElementById('formEncuestaSatisfaccion');
                if (!form || !idEd) {
                    return;
                }

                var hoy = new Date().toISOString().slice(0, 10);
                var fin6 = new Date();
                fin6.setMonth(fin6.getMonth() + 6);
                var fechaDefNueva = fin6.toISOString().slice(0, 10);

                var modoBtn = btn && btn.classList && btn.classList.contains('btn-modal-encuesta-coord')
                    ? (btn.getAttribute('data-encuesta-modo') || 'nuevo')
                    : 'nuevo';

                if (modoBtn === 'editar') {
                    modoEncuestaActual = 'editar';
                    idEd.value = btn.getAttribute('data-evaluacion-id') || '';
                    document.getElementById('encuesta_input_nombre').value = btn.getAttribute('data-nombre-evaluacion') || '';
                    document.getElementById('encuesta_input_enlace').value = btn.getAttribute('data-enlace-formulario') || '';
                    document.getElementById('encuesta_input_descripcion').value = btn.getAttribute('data-descripcion') || '';
                    var fv = btn.getAttribute('data-fecha-vencimiento') || '';
                    document.getElementById('encuesta_input_fecha_vencimiento').value = fv;
                    document.getElementById('encuesta_input_fecha_vencimiento').min = hoy;
                    document.getElementById('encuesta_input_estado').value = btn.getAttribute('data-estado') || 'activo';
                    document.getElementById('modalEncuestaSatisfaccionTituloTexto').textContent = 'Cambiar enlace de encuesta';
                    document.getElementById('btnGuardarEncuestaTexto').textContent = labelBotonGuardar('editar');
                } else if (modoBtn === 'nuevo-reemplazo') {
                    modoEncuestaActual = 'nuevo-reemplazo';
                    idEd.value = btn.getAttribute('data-evaluacion-id') || '';
                    rellenarFormularioEncuestaComoNuevo(hoy, fechaDefNueva);
                    document.getElementById('modalEncuestaSatisfaccionTituloTexto').textContent = 'Agregar enlace de encuesta';
                    document.getElementById('btnGuardarEncuestaTexto').textContent = labelBotonGuardar('nuevo-reemplazo');
                } else {
                    modoEncuestaActual = 'nuevo';
                    idEd.value = '';
                    rellenarFormularioEncuestaComoNuevo(hoy, fechaDefNueva);
                    document.getElementById('modalEncuestaSatisfaccionTituloTexto').textContent = 'Agregar enlace de encuesta';
                    document.getElementById('btnGuardarEncuestaTexto').textContent = labelBotonGuardar('nuevo');
                }
            });
        }

        document.getElementById('btnGuardarEncuesta')?.addEventListener('click', function() {
            var form = document.getElementById('formEncuestaSatisfaccion');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            var btn = this;
            var idEv = (document.getElementById('encuestaEvaluacionIdEdicion') || {}).value || '';
            var usaActualizar = idEv !== '';
            var url = usaActualizar
                ? '<?= base_url('coord/evaluaciones/actualizar') ?>/' + encodeURIComponent(idEv)
                : '<?= base_url('coord/evaluaciones/agregar') ?>';

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
            var fd = new FormData(form);
            fetch(url, {
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
                    if (modal) {
                        modal.hide();
                    }
                    showNotification(data.message || 'Enlace guardado. Recargando...', 'success');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1200);
                } else {
                    showNotification(data.message || 'Error al guardar', 'error');
                    restaurarBotonGuardar(btn, modoEncuestaActual);
                }
            }).catch(function() {
                showNotification('Error de conexión', 'error');
                restaurarBotonGuardar(btn, modoEncuestaActual);
            });
        });
    })();

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