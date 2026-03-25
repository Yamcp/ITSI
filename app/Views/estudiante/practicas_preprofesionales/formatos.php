<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .page-header-formatos {
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .page-header-formatos .title-page {
        font-weight: 700;
        font-size: 1.5rem;
        color: #212529;
        margin: 0;
        white-space: nowrap;
        overflow: visible;
    }

    .formatos-aviso {
        color: #dc3545;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .formatos-acciones .btn {
        white-space: nowrap;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper practicas-page">
    <div class="container-fluid px-3 px-md-4 pb-4">
    <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-file-alt me-2"></i>
                    Formatos (Prácticas Preprofesionales)
                </h3>
            </div>
        </div>

        <p class="formatos-aviso">
            <i class="fas fa-info-circle me-1"></i>Visualice o descargue los documentos de formato que necesite para sus prácticas preprofesionales.
        </p>

        <?php
        $documentos_formatos = $documentos_formatos ?? [];
        $modalIdVisorFormatos = 'modalVisorFormatoPracticas';
        ?>
        <?php if (!empty($documentos_formatos)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white panel-bar-trad">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Documentos disponibles</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($documentos_formatos as $item):
                            $arch = $item['archivo'] ?? '';
                            $ext = strtolower(pathinfo($arch, PATHINFO_EXTENSION));
                            $urlVer = base_url('estudiante/practicas/formatos/ver/' . rawurlencode($arch));
                            $urlDesc = base_url('estudiante/practicas/formatos/descargar/' . rawurlencode($arch));
                            ?>
                            <li class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2">
                                <span class="d-flex align-items-center"><i class="fas fa-file-pdf me-2 text-danger"></i><?= esc($item['nombre'] ?? 'Documento') ?></span>
                                <div class="formatos-acciones d-flex flex-wrap gap-2 justify-content-sm-end">
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-ver-formato"
                                        data-formato-modal-id="<?= esc($modalIdVisorFormatos, 'attr') ?>"
                                        data-formato-ver-url="<?= esc($urlVer, 'attr') ?>"
                                        data-formato-desc-url="<?= esc($urlDesc, 'attr') ?>"
                                        data-formato-titulo="<?= esc($item['nombre'] ?? 'Documento', 'attr') ?>"
                                        data-formato-ext="<?= esc($ext, 'attr') ?>">
                                        <i class="fas fa-eye me-1"></i>Ver documento
                                    </button>
                                    <a href="<?= esc($urlDesc) ?>" class="btn btn-primary btn-sm" download>
                                        <i class="fas fa-download me-1"></i>Descargar
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No hay documentos de formato publicados aún. Consulte con el departamento de vinculación.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->include('estudiante/partials/visor_formatos_modal', ['modalId' => $modalIdVisorFormatos]) ?>
<?= $this->endSection() ?>