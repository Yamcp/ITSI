<!-- app/Views/dashboard/dashboard.php -->
<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center">Bienvenido al Sistema del Departamento de Vinculación</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Educación Continua</h2>
                        <p>Gestiona los programas de educación continua y visualiza las estadísticas de participación.</p>
                        <a href="<?= base_url('vinculacion/educacion-continua') ?>" class="btn btn-primary">Ir a Educación Continua</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Convenios</h2>
                        <p>Administra los convenios establecidos y revisa su estado actual.</p>
                        <a href="<?= base_url('vinculacion/convenios') ?>" class="btn btn-primary">Ir a Convenios</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>