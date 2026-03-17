<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para convenios -->
<style>
    .convenio-card {
        transition: transform 0.2s ease-in-out;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .convenio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .estado-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }

    .institucion-card {
        border-left: 4px solid #007bff;
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
                    <i class="fas fa-handshake me-2 text-primary"></i>
                    Instituciones y Convenios
                </h3>
                <p class="text-center text-muted">Solo se muestran convenios vigentes e instituciones correspondientes a tu carrera</p>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="conveniosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="convenios-tab" data-bs-toggle="tab" data-bs-target="#convenios" type="button" role="tab">
                            <i class="fas fa-file-contract me-2"></i>Convenios
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="instituciones-tab" data-bs-toggle="tab" data-bs-target="#instituciones" type="button" role="tab">
                            <i class="fas fa-building me-2"></i>Instituciones
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="conveniosTabsContent">
            <!-- Pestaña de Convenios -->
            <div class="tab-pane fade show active" id="convenios" role="tabpanel">
                <div class="row">
                    <?php if (!empty($convenios)): ?>
                        <?php foreach ($convenios as $convenio): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card convenio-card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-file-contract me-2"></i>
                                            <?= esc($convenio['TIPO_CONVENIO'] ?? 'Convenio') ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <i class="fas fa-building me-1"></i>
                                            <?= esc($convenio['NOMBRE_INSTITUCION'] ?? 'Institución') ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Desde: <?= date('d/m/Y', strtotime($convenio['FECHA_INICIO'] ?? '')) ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Hasta: <?= date('d/m/Y', strtotime($convenio['FECHA_FIN'] ?? '')) ?>
                                            </small>
                                        </p>
                                        <p class="card-text">
                                            <small>
                                                <strong>Duración:</strong> <?= esc($convenio['DURACION'] ?? '') ?> meses
                                            </small>
                                        </p>
                                        <?php if (isset($convenio['OBJETIVO'])): ?>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <?= esc(substr($convenio['OBJETIVO'], 0, 100)) ?>
                                                    <?= strlen($convenio['OBJETIVO']) > 100 ? '...' : '' ?>
                                                </small>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <?php
                                        $estado = 'Vigente';
                                        $badgeClass = 'bg-success';

                                        if (isset($convenio['FECHA_FIN'])) {
                                            $fechaFin = strtotime($convenio['FECHA_FIN']);
                                            $hoy = time();
                                            $diasRestantes = ($fechaFin - $hoy) / (60 * 60 * 24);

                                            if ($diasRestantes < 0) {
                                                $estado = 'Vencido';
                                                $badgeClass = 'bg-danger';
                                            } elseif ($diasRestantes <= 30) {
                                                $estado = 'Por Vencer';
                                                $badgeClass = 'bg-warning';
                                            }
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?> estado-badge">
                                            <?= $estado ?>
                                        </span>
                                        <?php if (isset($convenio['RENOVABLE']) && $convenio['RENOVABLE'] == 1): ?>
                                            <span class="badge bg-info estado-badge ms-1">
                                                Renovable
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay convenios disponibles en este momento.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pestaña de Instituciones -->
            <div class="tab-pane fade" id="instituciones" role="tabpanel">
                <div class="row">
                    <?php if (!empty($instituciones)): ?>
                        <?php foreach ($instituciones as $institucion): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card institucion-card h-100">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-building me-2"></i>
                                            <?= esc($institucion['NOMBRE'] ?? 'Institución') ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>
                                                <strong>Tipo:</strong> <?= esc($institucion['TIPO'] ?? '') ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>
                                                <strong>RUC:</strong> <?= esc($institucion['RUC'] ?? '') ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <strong>Dirección:</strong> <?= esc($institucion['DIRECCION'] ?? '') ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                <strong>Teléfono:</strong> <?= esc($institucion['TELEFONO'] ?? '') ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-1"></i>
                                                <strong>Email:</strong> <?= esc($institucion['EMAIL'] ?? '') ?>
                                            </small>
                                        </p>
                                        <?php if (isset($institucion['AREA_INTERES'])): ?>
                                            <p class="card-text">
                                                <small>
                                                    <strong>Área de Interés:</strong> <?= esc($institucion['AREA_INTERES']) ?>
                                                </small>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <strong>Representante:</strong> <?= esc($institucion['REPRESENTANTE_LEGAL'] ?? '') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay instituciones disponibles en este momento.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Inicializar tooltips si es necesario
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>