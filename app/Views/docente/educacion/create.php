<?= $this->extend('docente/layouts/mainDocente') ?>

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
                        <i class="fas fa-plus-circle me-2"></i>
                        Nueva Actividad Educativa
                    </h3>
                    <a href="<?= base_url('docente/actividades-educacion') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Información de la Actividad
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('docente/actividades-educacion/guardar') ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Actividad<span class="text-danger">*</span></label>
                                        <select class="form-select" name="tipo_actividad" required>
                                            <option value="">Seleccionar...</option>
                                            <?php if (!empty($tipos_actividades)): ?>
                                                <?php foreach ($tipos_actividades as $tipo): ?>
                                                    <option value="<?= $tipo['ID_TIPO_ACTIVIDAD'] ?>" <?= old('tipo_actividad') == $tipo['ID_TIPO_ACTIVIDAD'] ? 'selected' : '' ?>>
                                                        <?= $tipo['ACTIVIDAD'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre de la Actividad<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nombre_actividad" value="<?= old('nombre_actividad') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Instructor<span class="text-danger">*</span></label>
                                        <select class="form-select" name="instructor" required>
                                            <option value="">Seleccionar...</option>
                                            <?php if (!empty($instructores)): ?>
                                                <?php foreach ($instructores as $instructor): ?>
                                                    <option value="<?= $instructor['ID_INSTRUCTOR'] ?>" <?= old('instructor') == $instructor['ID_INSTRUCTOR'] ? 'selected' : '' ?>>
                                                        <?= $instructor['NOMBRES'] . ' ' . $instructor['APELLIDOS'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Modalidad<span class="text-danger">*</span></label>
                                        <select class="form-select" name="modalidad" required>
                                            <option value="">Seleccionar...</option>
                                            <?php if (!empty($modalidades)): ?>
                                                <?php foreach ($modalidades as $modalidad): ?>
                                                    <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>" <?= old('modalidad') == $modalidad['ID_TIPO_MODALIDAD'] ? 'selected' : '' ?>>
                                                        <?= $modalidad['MODALIDAD'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="descripcion" rows="3" required><?= old('descripcion') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Objetivos<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="objetivos" rows="3" required><?= old('objetivos') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Duración (horas)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="duracion_horas" value="<?= old('duracion_horas') ?>" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Inicio<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= old('fecha_inicio') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Fin<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha_fin" value="<?= old('fecha_fin') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lugar<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lugar" value="<?= old('lugar') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Horario<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="horario" value="<?= old('horario') ?>" placeholder="Ej: Lunes a Viernes 8:00 - 12:00" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Programa Detallado</label>
                                <textarea class="form-control" name="programa_detallado" rows="4"><?= old('programa_detallado') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluye_certificado" id="incluye_certificado" value="1" <?= old('incluye_certificado') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="incluye_certificado">
                                        Incluye Certificado
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('docente/actividades-educacion') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Guardar Actividad
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Establecer fecha de hoy como fecha de inicio por defecto
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
        if (fechaInicioInput && !fechaInicioInput.value) {
            const today = new Date().toISOString().split('T')[0];
            fechaInicioInput.value = today;
        }

        // Validar que la fecha de fin sea posterior a la fecha de inicio
        const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
        const fechaFin = document.querySelector('input[name="fecha_fin"]');
        
        fechaInicio.addEventListener('change', function() {
            if (fechaFin.value && fechaFin.value <= this.value) {
                fechaFin.value = '';
                alert('La fecha de fin debe ser posterior a la fecha de inicio');
            }
        });

        fechaFin.addEventListener('change', function() {
            if (fechaInicio.value && this.value <= fechaInicio.value) {
                alert('La fecha de fin debe ser posterior a la fecha de inicio');
                this.value = '';
            }
        });
    });
</script>

<?= $this->endSection() ?>
