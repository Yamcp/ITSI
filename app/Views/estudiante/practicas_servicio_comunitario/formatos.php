<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .page-header-formatos {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .page-header-formatos .title-page {
        font-weight: 700;
        font-size: 1.5rem;
        color: #0f172a;
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

    .formatos-qr-wrap {
        max-width: 600px;
        margin: 0;
    }

    .formatos-qr-img {
        display: block;
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid px-3 px-md-4 pb-4">
        <div class="page-header-formatos">
            <h1 class="title-page">
                <i class="fas fa-file-alt me-2 text-primary"></i>Formatos (Servicio Comunitario)
            </h1>
        </div>

        <p class="formatos-aviso">
            <i class="fas fa-info-circle me-1"></i>Descargue los documentos de formato que necesite para sus prácticas de servicio comunitario.
        </p>

        <?php $documentos_formatos = $documentos_formatos_servicio ?? []; ?>
        <?php if (!empty($documentos_formatos)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Documentos disponibles</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($documentos_formatos as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-pdf me-2 text-danger"></i><?= esc($item['nombre'] ?? 'Documento') ?></span>
                                <a href="<?= base_url('estudiante/practicas/servicio-comunitario/formatos/descargar/' . rawurlencode($item['archivo'] ?? '')) ?>" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i>Descargar
                                </a>
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
<?= $this->endSection() ?>