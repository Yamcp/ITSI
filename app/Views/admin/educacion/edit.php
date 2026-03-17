<?= $this->extend('admin/layouts/mainAdmin') ?>

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
                        <i class="fas fa-edit me-2"></i>
                        Editar Actividad Educativa
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('admin/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i>Ver Detalles
                        </a>
                        <a href="<?= base_url('admin/actividades-educacion') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Editar Información de la Actividad
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

                        <form action="<?= base_url('admin/actividades-educacion/actualizar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Actividad<span class="text-danger">*</span></label>
                                        <select class="form-select" name="tipo_actividad" required>
                                            <option value="">Seleccionar...</option>
                                            <?php if (!empty($tipos_actividades)): ?>
                                                <?php foreach ($tipos_actividades as $tipo): ?>
                                                    <option value="<?= $tipo['ID_TIPO_ACTIVIDAD'] ?>"
                                                        <?= (old('tipo_actividad', $actividad['ID_TIPO_ACTIVIDAD']) == $tipo['ID_TIPO_ACTIVIDAD']) ? 'selected' : '' ?>>
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
                                        <input type="text" class="form-control" name="nombre_actividad"
                                            value="<?= old('nombre_actividad', $actividad['NOMBRE_ACTIVIDAD']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Instructor<span class="text-danger">*</span></label>
                                        <select class="form-select" name="instructor" required>
                                            <option value="">Seleccionar instructor...</option>
                                            <?php if (!empty($instructores)): ?>
                                                <?php foreach ($instructores as $instructor): ?>
                                                    <option value="<?= $instructor['ID_INSTRUCTOR'] ?>"
                                                        <?= (old('instructor', $actividad['ID_INSTRUCTOR']) == $instructor['ID_INSTRUCTOR']) ? 'selected' : '' ?>>
                                                        <?= $instructor['NOMBRE'] ?> <?= $instructor['APELLIDO'] ?> - <?= $instructor['ESPECIALIDAD'] ?>
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
                                            <option value="">Seleccionar modalidad...</option>
                                            <?php if (!empty($modalidades)): ?>
                                                <?php foreach ($modalidades as $modalidad): ?>
                                                    <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>"
                                                        <?= (old('modalidad', $actividad['ID_TIPO_MODALIDAD']) == $modalidad['ID_TIPO_MODALIDAD']) ? 'selected' : '' ?>>
                                                        <?= $modalidad['MODALIDAD'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha Inicio<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha_inicio"
                                            value="<?= old('fecha_inicio', $actividad['FECHA_INICIO']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha Fin<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fecha_fin"
                                            value="<?= old('fecha_fin', $actividad['FECHA_FIN']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Duración (horas)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="duracion_horas"
                                            value="<?= old('duracion_horas', $actividad['DURACION_HORAS']) ?>" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Lugar<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lugar"
                                            value="<?= old('lugar', $actividad['LUGAR']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Horario<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="horario"
                                            value="<?= old('horario', $actividad['HORARIO']) ?>"
                                            placeholder="Ej: Lunes a Viernes 8:00-12:00" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="descripcion" rows="3" required><?= old('descripcion', $actividad['DESCRIPCION']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Objetivos<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="objetivos" rows="3" required><?= old('objetivos', $actividad['OBJETIVOS']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Programa Detallado</label>
                                <textarea class="form-control" name="programa_detallado" rows="4"><?= old('programa_detallado', $actividad['PROGRAMA_DETALLADO']) ?></textarea>
                            </div>



                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('admin/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>Actualizar Actividad
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>