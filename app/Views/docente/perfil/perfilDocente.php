<!-- app/Views/docente/perfil/perfilDocente.php -->
<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('css/profile.css') ?>" rel="stylesheet" />
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border: 4px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        object-fit: cover;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .form-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-modern {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .badge-modern {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .progress-modern {
        height: 10px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar-modern {
        border-radius: 10px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    
    .info-item {
        padding: 1rem;
        border-radius: 10px;
        background: #f8f9fa;
        margin-bottom: 1rem;
        border-left: 4px solid #667eea;
    }
    
    .info-category-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 1.5rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 8px;
        border-left: 4px solid #667eea;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-label {
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    .form-control[readonly]:focus {
        border-color: #e9ecef;
        box-shadow: none;
    }
    
    .btn-camera {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
    .btn-camera:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .profile-avatar {
        transition: all 0.3s ease;
    }
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header del Perfil -->
        <div class="profile-header text-center">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="position-relative d-inline-block">
                        <img src="<?= !empty($usuario['FOTO_URL']) ? base_url('uploads/perfiles/' . $usuario['FOTO_URL']) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#e9ecef"/><circle cx="60" cy="45" r="20" fill="#6c757d"/><path d="M30 95c0-16.569 13.431-30 30-30s30 13.431 30 30" fill="#6c757d"/></svg>') ?>" 
                             alt="Avatar" class="profile-avatar mb-3" id="preview-avatar">
                        <div class="position-absolute top-0 end-0">
                            <button type="button" class="btn btn-sm btn-primary rounded-circle btn-camera" 
                                    data-bs-toggle="modal" data-bs-target="#modalImagen" 
                                    title="Cambiar imagen">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 text-md-start">
                    <h1 class="display-6 fw-bold mb-2"><?= $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'] ?></h1>
                    <p class="lead mb-3">
                        <span class="badge badge-modern bg-primary me-2"><?= $usuario['ROL'] ?></span>
                        <?php if ($usuario['ESTADO'] == 'A'): ?>
                            <span class="badge badge-modern bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge badge-modern bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Usuario desde: <?= date('d/m/Y', strtotime($usuario['FECHA_REGISTRO'] ?? 'now')) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-xl-4 mb-4">
                <div class="card form-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Información Básica -->
                        <div class="info-category">
                            <div class="info-category-title">
                                <i class="fas fa-id-card"></i>
                                <strong>Datos de Identificación:</strong>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><strong>Nombre Completo</strong></div>
                                <div class="info-value"><?= $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'] ?></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Cédula</strong></div>
                                <div class="info-value"><?= $usuario['CEDULA'] ?></div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="info-category">
                            <div class="info-category-title">
                                <i class="fas fa-address-book"></i>
                                <strong>Información de Contacto:</strong>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><strong>Celular</strong></div>
                                <div class="info-value <?= empty($usuario['CELULAR']) ? 'empty' : '' ?>">
                                    <?= $usuario['CELULAR'] ?: 'No registrado' ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Email</strong></div>
                                <div class="info-value <?= empty($usuario['EMAIL']) ? 'empty' : '' ?>">
                                    <?= $usuario['EMAIL'] ?: 'No registrado' ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Dirección</strong></div>
                                <div class="info-value <?= empty($usuario['DIRECCION']) ? 'empty' : '' ?>">
                                    <?= $usuario['DIRECCION'] ?: 'No registrada' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Información Demográfica -->
                        <div class="info-category">
                            <div class="info-category-title">
                                <i class="fas fa-user-friends"></i>
                                <strong>Información Demográfica:</strong>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><strong>Género</strong></div>
                                <div class="info-value <?= empty($usuario['GENERO']) ? 'empty' : '' ?>">
                                    <?= $usuario['GENERO'] ?: 'No especificado' ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Estado Civil</strong></div>
                                <div class="info-value <?= empty($usuario['ESTADO_CIVIL']) ? 'empty' : '' ?>">
                                    <?= $usuario['ESTADO_CIVIL'] ?: 'No especificado' ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Nacionalidad</strong></div>
                                <div class="info-value <?= empty($usuario['NACIONALIDAD']) ? 'empty' : '' ?>">
                                    <?= $usuario['NACIONALIDAD'] ?: 'No especificada' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Información Institucional -->
                        <div class="info-category">
                            <div class="info-category-title">
                                <i class="fas fa-university"></i>
                                <strong>Información Institucional:</strong>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><strong>Fecha de Ingreso</strong></div>
                                <div class="info-value <?= empty($usuario['FECHA_INGRESO']) ? 'empty' : '' ?>">
                                    <?= $usuario['FECHA_INGRESO'] ? date('d/m/Y', strtotime($usuario['FECHA_INGRESO'])) : 'No especificada' ?>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label"><strong>Estado de Cuenta</strong></div>
                                <div class="info-value">
                                    <?php if ($usuario['ESTADO'] == 'A'): ?>
                                        <span class="badge badge-modern bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-modern bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div class="col-xl-8">
                <div class="card form-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Editar Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Errores de validación:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($validation->getErrors() as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('docente/perfil/update') ?>" method="post" id="formPerfil">
                            <?= csrf_field() ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Nombre
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                        value="<?= $usuario['NOMBRE'] ?>" readonly>
                                    <small class="text-muted">El nombre no se puede modificar</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Apellido
                                    </label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" 
                                        value="<?= $usuario['APELLIDO'] ?>" readonly>
                                    <small class="text-muted">El apellido no se puede modificar</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-1"></i>Cédula
                                    </label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                        value="<?= $usuario['CEDULA'] ?>" readonly>
                                    <small class="text-muted">La cédula no se puede modificar</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="celular" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1"></i>Celular
                                    </label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('celular')) ? 'is-invalid' : '' ?>"
                                        id="celular" name="celular" value="<?= $usuario['CELULAR'] ?>">
                                    <div class="invalid-feedback">
                                        <?= (isset($validation) && $validation->hasError('celular')) ? $validation->getError('celular') : '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i>Dirección
                                </label>
                                <textarea class="form-control <?= (isset($validation) && $validation->hasError('direccion')) ? 'is-invalid' : '' ?>"
                                    id="direccion" name="direccion" rows="3"><?= $usuario['DIRECCION'] ?></textarea>
                                <div class="invalid-feedback">
                                    <?= (isset($validation) && $validation->hasError('direccion')) ? $validation->getError('direccion') : '' ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </label>
                                    <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                                        id="email" name="email" value="<?= $usuario['EMAIL'] ?>">
                                    <div class="invalid-feedback">
                                        <?= (isset($validation) && $validation->hasError('email')) ? $validation->getError('email') : '' ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="genero" class="form-label fw-semibold">
                                        <i class="fas fa-venus-mars me-1"></i>Género
                                    </label>
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
                                    <label for="estado_civil" class="form-label fw-semibold">
                                        <i class="fas fa-heart me-1"></i>Estado Civil
                                    </label>
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
                                    <label for="nacionalidad" class="form-label fw-semibold">
                                        <i class="fas fa-flag me-1"></i>Nacionalidad
                                    </label>
                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" 
                                        value="<?= $usuario['NACIONALIDAD'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="usuario" class="form-label fw-semibold">
                                        <i class="fas fa-user-tag me-1"></i>Nombre de Usuario
                                    </label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" 
                                        value="<?= $usuario['USUARIO'] ?>" readonly>
                                    <small class="text-muted">El nombre de usuario no se puede modificar</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_ingreso" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-plus me-1"></i>Fecha de Ingreso
                                    </label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                                        value="<?= $usuario['FECHA_INGRESO'] ?>" readonly>
                                    <small class="text-muted">La fecha de ingreso no se puede modificar</small>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-outline-secondary btn-modern" onclick="resetForm()">
                                            <i class="fas fa-undo me-2"></i>Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-success btn-modern">
                                            <i class="fas fa-save me-2"></i>Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImagenLabel">
                    <i class="fas fa-camera me-2"></i>Cambiar Imagen de Perfil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formImagen" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Vista previa de la imagen actual -->
                    <div class="text-center mb-3">
                        <img src="<?= !empty($usuario['FOTO_URL']) ? base_url('uploads/perfiles/' . $usuario['FOTO_URL']) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100" fill="#e9ecef"/><circle cx="100" cy="75" r="35" fill="#6c757d"/><path d="M50 160c0-27.614 22.386-50 50-50s50 22.386 50 50" fill="#6c757d"/></svg>') ?>" 
                             alt="Imagen actual" class="img-thumbnail" id="imagen-actual" style="max-width: 200px; max-height: 200px;">
                    </div>
                    
                    <!-- Campo de subida -->
                    <div class="mb-3">
                        <label for="foto_perfil" class="form-label fw-semibold">
                            <i class="fas fa-image me-1"></i>Seleccionar Nueva Imagen
                        </label>
                        <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" 
                               accept="image/*" onchange="previewImage(this)">
                        <div class="form-text">
                            Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                        </div>
                    </div>
                    
                    <!-- Vista previa de la nueva imagen -->
                    <div id="preview-container" class="text-center" style="display: none;">
                        <h6>Vista previa:</h6>
                        <img id="preview-nueva" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="-1">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="subirImagen()" tabindex="-1">
                    <i class="fas fa-upload me-1"></i>Subir Imagen
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Variables globales
var csrfToken = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';
var uploadUrl = '<?= base_url('docente/perfil/upload-image') ?>';

// Función para resetear formulario
function resetForm() {
    if (confirm('¿Está seguro de que desea restaurar todos los campos editables a sus valores originales?')) {
        document.getElementById('formPerfil').reset();
        alert('Formulario restaurado exitosamente');
    }
}

// Función para vista previa de imagen
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-nueva').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Función para subir imagen
function subirImagen() {
    var fileInput = document.getElementById('foto_perfil');
    var file = fileInput.files[0];
    
    if (!file) {
        alert('Por favor selecciona una imagen');
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen es demasiado grande. Máximo 2MB');
        return;
    }
    
    var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (allowedTypes.indexOf(file.type) === -1) {
        alert('Formato no válido. Solo se permiten JPG, PNG y GIF');
        return;
    }
    
    var formData = new FormData();
    formData.append('foto_perfil', file);
    formData.append(csrfToken, csrfHash);
    
    var submitBtn = document.querySelector('#modalImagen .btn-primary');
    var originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Subiendo...';
    submitBtn.disabled = true;
    
    fetch(uploadUrl, {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            alert('Imagen actualizada correctamente');
            document.getElementById('preview-avatar').src = data.image_url;
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalImagen'));
            modal.hide();
            document.getElementById('formImagen').reset();
            document.getElementById('preview-container').style.display = 'none';
        } else {
            alert(data.message || 'Error al subir la imagen');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Error al subir la imagen');
    })
    .finally(function() {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Manejo de accesibilidad del modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalImagen');
    const modalInstance = new bootstrap.Modal(modal);
    
    // Manejar el foco cuando el modal se muestra
    modal.addEventListener('shown.bs.modal', function() {
        // Remover tabindex="-1" de los botones cuando el modal está visible
        const buttons = modal.querySelectorAll('button[tabindex="-1"]');
        buttons.forEach(button => {
            button.removeAttribute('tabindex');
        });
        
        // Enfocar el primer elemento interactivo
        const firstFocusable = modal.querySelector('input, button, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    });
    
    // Manejar el foco cuando el modal se oculta
    modal.addEventListener('hidden.bs.modal', function() {
        // Restaurar tabindex="-1" a los botones cuando el modal está oculto
        const buttons = modal.querySelectorAll('button');
        buttons.forEach(button => {
            if (button.getAttribute('data-bs-dismiss') || button.onclick) {
                button.setAttribute('tabindex', '-1');
            }
        });
        
        // Enfocar el botón que abrió el modal
        const triggerButton = document.querySelector('[data-bs-target="#modalImagen"]');
        if (triggerButton) {
            triggerButton.focus();
        }
    });
    
    // Manejar el cierre del modal con Escape
    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modalInstance.hide();
        }
    });
});

// Verificar que las funciones estén disponibles
console.log('Funciones cargadas:', {
    resetForm: typeof resetForm,
    previewImage: typeof previewImage,
    subirImagen: typeof subirImagen
});
</script>
<?= $this->endSection() ?>