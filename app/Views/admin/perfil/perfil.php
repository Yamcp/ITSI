<!-- app/Views/admin/perfil/perfil.php -->
<?= $this->extend('layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('css/profile.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="mt-4">Perfil de Usuario</h1>


<div class="row">
    <div class="col-xl-4">
        <!-- Tarjeta de información básica -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user mr-1"></i>
                Información de la Cuenta
            </div>
            <div class="card-body text-center">
                <img src="<?= base_url('assets/img/avatar.png') ?>" alt="Avatar" class="rounded-circle img-fluid mb-3" style="width: 150px;">
                <h5 class="my-3"><?= $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'] ?></h5>
                <p class="text-muted mb-1">
                    <span class="badge badge-primary"><?= $usuario['ROL'] ?></span>
                </p>
                <p class="text-muted mb-4">Estado:
                    <?php if ($usuario['ESTADO'] == 'A'): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactivo</span>
                    <?php endif; ?>
                </p>
                <p class="text-muted">Usuario desde: <?= date('d/m/Y', strtotime($usuario['FECHA_REGISTRO'] ?? 'now')) ?></p>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Formulario de edición de perfil -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit mr-1"></i>
                Editar Información Personal
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/perfil/update') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('nombre')) ? 'is-invalid' : '' ?>"
                                id="nombre" name="nombre" value="<?= $usuario['NOMBRE'] ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('nombre')) ? $validation->getError('nombre') : '' ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('apellido')) ? 'is-invalid' : '' ?>"
                                id="apellido" name="apellido" value="<?= $usuario['APELLIDO'] ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('apellido')) ? $validation->getError('apellido') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cedula">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" value="<?= $usuario['CEDULA'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('celular')) ? 'is-invalid' : '' ?>"
                                id="celular" name="celular" value="<?= $usuario['CELULAR'] ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('celular')) ? $validation->getError('celular') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea class="form-control <?= (isset($validation) && $validation->hasError('direccion')) ? 'is-invalid' : '' ?>"
                            id="direccion" name="direccion" rows="3"><?= $usuario['DIRECCION'] ?></textarea>
                        <div class="invalid-feedback">
                            <?= (isset($validation) && $validation->hasError('direccion')) ? $validation->getError('direccion') : '' ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="usuario">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $usuario['USUARIO'] ?>" readonly>
                        </div>

                    </div>

                    <hr>
                    <h5>Cambiar Contraseña</h5>
                    <small class="text-muted mb-3 d-block">Deja en blanco si no deseas cambiar la contraseña</small>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password_actual">Contraseña Actual</label>
                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_actual')) ? 'is-invalid' : '' ?>"
                                id="password_actual" name="password_actual">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('password_actual')) ? $validation->getError('password_actual') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password_nuevo">Nueva Contraseña</label>
                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_nuevo')) ? 'is-invalid' : '' ?>"
                                id="password_nuevo" name="password_nuevo">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('password_nuevo')) ? $validation->getError('password_nuevo') : '' ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirm">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>"
                                id="password_confirm" name="password_confirm">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('password_confirm')) ? $validation->getError('password_confirm') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Si deseamos añadir validación del lado del cliente o alguna funcionalidad JS
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // Validación adicional si es necesaria
        });
    });
</script>
<?= $this->endSection() ?>