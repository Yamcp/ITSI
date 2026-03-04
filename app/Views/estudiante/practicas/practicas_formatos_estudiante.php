<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .page-header-formatos {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.04);
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
<div class="page-header-formatos">
    <h1 class="title-page">
        <i class="fas fa-file-alt me-2 text-primary"></i>Formatos (Prácticas Laborales)
    </h1>
</div>

<p class="formatos-aviso">
    <i class="fas fa-info-circle me-1"></i>Para visualizar todo este contenido, es importante que inicien sesión con su correo institucional en SharePoint.
</p>

<div class="formatos-qr-wrap">
    <img src="<?= esc($qr_url ?? base_url('sistema/assets/images/practicas/formatos-practicas-laborales-qr.png')) ?>" alt="Formatos: Modelo informe prácticas laborales, Fichas de seguimiento, Base de datos, Video" class="img-fluid formatos-qr-img" />
</div>
<?= $this->endSection() ?>
