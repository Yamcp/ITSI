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
                        <i class="fas fa-info-circle me-2"></i>
                        Detalles de la Actividad
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="<?= base_url('docente/actividades-educacion') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Actividad -->
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tipo de Actividad:</strong>
                                    <span class="badge bg-info"><?= esc($actividad['TIPO_ACTIVIDAD']) ?></span>
                                </p>
                                <p><strong>Instructor:</strong> <?= esc($actividad['NOMBRE'] ?? '') ?> <?= esc($actividad['APELLIDO'] ?? '') ?></p>
                                <p><strong>Especialidad:</strong> <?= esc($actividad['ESPECIALIDAD'] ?? '-') ?></p>
                                <p><strong>Modalidad:</strong>
                                    <span class="badge bg-secondary"><?= esc($actividad['MODALIDAD']) ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Período:</strong>
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_INICIO'])) ?> -
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_FIN'])) ?>
                                </p>
                                <p><strong>Duración:</strong>
                                    <span class="badge bg-warning text-dark"><?= (int)($actividad['DURACION_HORAS']) ?> horas</span>
                                </p>
                                <?php if (trim((string) ($actividad['LUGAR'] ?? '')) !== ''): ?>
                                    <p><strong>Lugar:</strong> <?= esc($actividad['LUGAR']) ?></p>
                                <?php endif; ?>
                                <?php if (trim((string) ($actividad['ENLACE'] ?? '')) !== ''): ?>
                                    <?php $hrefEn = preg_match('#^https?://#i', $actividad['ENLACE']) ? $actividad['ENLACE'] : 'https://' . $actividad['ENLACE']; ?>
                                    <p><strong>Enlace:</strong> <a href="<?= esc($hrefEn, 'attr') ?>" target="_blank" rel="noopener"><?= esc($actividad['ENLACE']) ?></a></p>
                                <?php endif; ?>
                                <p><strong>Horario:</strong> <?= esc($actividad['HORARIO']) ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Descripción:</strong></h6>
                                <p class="text-muted"><?= nl2br(esc($actividad['DESCRIPCION'])) ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Objetivos:</strong></h6>
                                <p class="text-muted"><?= nl2br(esc($actividad['OBJETIVOS'] ?? '')) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($actividad['PROGRAMA_DETALLADO'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <h6><strong>Programa Detallado:</strong></h6>
                                    <p class="text-muted"><?= nl2br(esc($actividad['PROGRAMA_DETALLADO'])) ?></p>
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

                <!-- Acciones -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('docente/actividades-educacion/participantes/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-users me-1"></i>Gestionar Participantes
                            </a>
                            <a href="<?= base_url('docente/actividades-educacion/reportes') ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-file-alt me-1"></i>Mis Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>