<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<style>
    .perfil-avatar-wrap {
        width: 80px;
        height: 80px;
        overflow: hidden;
        border-radius: 50%;
        flex-shrink: 0;
        border: 2px solid #dee2e6;
    }

    .perfil-avatar-wrap img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="page-title mb-3">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="perfil-avatar-wrap">
                        <img src="<?= !empty($usuario['FOTO_URL']) ? base_url('uploads/perfiles/' . $usuario['FOTO_URL']) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><circle cx="40" cy="40" r="40" fill="#e9ecef"/><circle cx="40" cy="30" r="12" fill="#6c757d"/><path d="M20 70c0-11.046 8.954-20 20-20s20 8.954 20 20" fill="#6c757d"/></svg>') ?>" alt="Avatar" id="preview-avatar">
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1">Mi Perfil</h3>
                    <p class="text-muted mb-0">
                        <?= $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'] ?>
                        <span class="badge bg-primary ms-2"><?= $usuario['ROL'] ?></span>
                        <?php if ($usuario['ESTADO'] == 'A'): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImagen" title="Cambiar imagen">
                        <i class="fas fa-camera me-1"></i>Cambiar foto
                    </button>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($validation) && $validation->hasError('nombre')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Errores de validación:</strong>
                <ul class="mb-0 mt-2"><?php foreach ($validation->getErrors() as $error): ?><li><?= $error ?></li><?php endforeach; ?></ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Cédula:</strong> <?= $usuario['CEDULA'] ?></p>
                        <p class="mb-2"><strong>Celular:</strong> <?= $usuario['CELULAR'] ?: '—' ?></p>
                        <p class="mb-2"><strong>Email:</strong> <?= $usuario['EMAIL'] ?: '—' ?></p>
                        <p class="mb-2"><strong>Dirección:</strong> <?= $usuario['DIRECCION'] ?: '—' ?></p>
                        <p class="mb-2"><strong>Género:</strong> <?= $usuario['GENERO'] ?: '—' ?></p>
                        <p class="mb-2"><strong>Estado civil:</strong> <?= $usuario['ESTADO_CIVIL'] ?: '—' ?></p>
                        <p class="mb-2"><strong>Nacionalidad:</strong> <?= $usuario['NACIONALIDAD'] ?: '—' ?></p>
                        <p class="mb-0"><strong>Fecha ingreso:</strong> <?= $usuario['FECHA_INGRESO'] ? date('d/m/Y', strtotime($usuario['FECHA_INGRESO'])) : '—' ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-edit me-2"></i>Editar Información</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('docente/perfil/update') ?>" method="post" id="formPerfil">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['NOMBRE'] ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $usuario['APELLIDO'] ?>" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" value="<?= $usuario['CEDULA'] ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('celular')) ? 'is-invalid' : '' ?>" id="celular" name="celular" value="<?= $usuario['CELULAR'] ?>">
                                    <?php if (isset($validation) && $validation->hasError('celular')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('celular') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control <?= (isset($validation) && $validation->hasError('direccion')) ? 'is-invalid' : '' ?>" id="direccion" name="direccion" rows="2"><?= $usuario['DIRECCION'] ?></textarea>
                                <?php if (isset($validation) && $validation->hasError('direccion')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('direccion') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $usuario['EMAIL'] ?>">
                                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="genero" class="form-label">Género</label>
                                    <select class="form-select" id="genero" name="genero">
                                        <option value="">Seleccionar...</option>
                                        <option value="Masculino" <?= $usuario['GENERO'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                        <option value="Femenino" <?= $usuario['GENERO'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                                        <option value="No binario" <?= $usuario['GENERO'] == 'No binario' ? 'selected' : '' ?>>No binario</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="estado_civil" class="form-label">Estado Civil</label>
                                    <select class="form-select" id="estado_civil" name="estado_civil">
                                        <option value="">Seleccionar...</option>
                                        <option value="Soltero/a" <?= $usuario['ESTADO_CIVIL'] == 'Soltero/a' ? 'selected' : '' ?>>Soltero/a</option>
                                        <option value="Casado/a" <?= $usuario['ESTADO_CIVIL'] == 'Casado/a' ? 'selected' : '' ?>>Casado/a</option>
                                        <option value="Divorciado/a" <?= $usuario['ESTADO_CIVIL'] == 'Divorciado/a' ? 'selected' : '' ?>>Divorciado/a</option>
                                        <option value="Viudo/a" <?= $usuario['ESTADO_CIVIL'] == 'Viudo/a' ? 'selected' : '' ?>>Viudo/a</option>
                                        <option value="Unión Libre" <?= $usuario['ESTADO_CIVIL'] == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?= $usuario['NACIONALIDAD'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $usuario['USUARIO'] ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= $usuario['FECHA_INGRESO'] ?>" readonly>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('formPerfil').reset();">Cancelar</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar imagen de perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formImagen" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="text-center mb-3">
                        <img src="<?= !empty($usuario['FOTO_URL']) ? base_url('uploads/perfiles/' . $usuario['FOTO_URL']) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#e9ecef"/><circle cx="60" cy="45" r="20" fill="#6c757d"/><path d="M30 95c0-16.569 13.431-30 30-30s30 13.431 30 30" fill="#6c757d"/></svg>') ?>" alt="Actual" id="imagen-actual" class="img-thumbnail" style="max-width: 120px; max-height: 120px;">
                    </div>
                    <div class="mb-3">
                        <label for="foto_perfil" class="form-label">Nueva imagen</label>
                        <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="preview-container" class="text-center" style="display:none;">
                        <small class="text-muted">Vista previa:</small>
                        <img id="preview-nueva" class="img-thumbnail mt-1" style="max-width: 100px; max-height: 100px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSubirImagen" onclick="subirImagen()"><i class="fas fa-upload me-1"></i>Subir</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfTokenName = <?= json_encode(config('Security')->tokenName ?? 'csrf_test_name') ?>;
    var csrfTokenValue = <?= json_encode(csrf_token()) ?>;
    var uploadUrl = <?= json_encode(base_url('docente/perfil/upload-image')) ?>;

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var r = new FileReader();
            r.onload = function(e) {
                document.getElementById('preview-nueva').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function subirImagen() {
        var fileInput = document.getElementById('foto_perfil');
        var file = fileInput.files[0];
        if (!file) {
            alert('Selecciona una imagen');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Máximo 2MB');
            return;
        }
        if (['image/jpeg', 'image/png', 'image/gif'].indexOf(file.type) === -1) {
            alert('Solo JPG, PNG o GIF');
            return;
        }
        var fd = new FormData();
        fd.append('foto_perfil', file);
        fd.append(csrfTokenName, csrfTokenValue);
        var btn = document.getElementById('btnSubirImagen');
        var orig = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Subiendo...';
            btn.disabled = true;
        }
        fetch(uploadUrl, {
                method: 'POST',
                body: fd
            })
            .then(function(r) {
                return r.json().catch(function() {
                    return {
                        success: false
                    };
                });
            })
            .then(function(data) {
                if (data.success) {
                    if (btn) {
                        btn.innerHTML = orig;
                        btn.disabled = false;
                    }
                    var av = document.getElementById('preview-avatar');
                    if (av && data.image_url) av.src = data.image_url;
                    var m = bootstrap.Modal.getInstance(document.getElementById('modalImagen'));
                    if (m) m.hide();
                    document.getElementById('formImagen').reset();
                    document.getElementById('preview-container').style.display = 'none';
                    alert('Imagen actualizada');
                    window.location.reload();
                } else {
                    if (btn) {
                        btn.innerHTML = orig;
                        btn.disabled = false;
                    }
                    alert(data.message || 'Error al subir');
                }
            })
            .catch(function() {
                if (btn) {
                    btn.innerHTML = orig;
                    btn.disabled = false;
                }
                alert('Error');
            });
    }
</script>
<?= $this->endSection() ?>