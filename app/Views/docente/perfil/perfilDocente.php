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
    
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        color: #6c757d;
        font-size: 0.95rem;
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
                    <img src="<?= base_url('assets/img/avatar.png') ?>" alt="Avatar" class="profile-avatar mb-3">
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
                        <div class="info-item">
                            <div class="info-label">Nombre Completo</div>
                            <div class="info-value"><?= $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'] ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Cédula</div>
                            <div class="info-value"><?= $usuario['CEDULA'] ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Celular</div>
                            <div class="info-value"><?= $usuario['CELULAR'] ?: 'No registrado' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Dirección</div>
                            <div class="info-value"><?= $usuario['DIRECCION'] ?: 'No registrada' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= $usuario['EMAIL'] ?: 'No registrado' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Género</div>
                            <div class="info-value"><?= $usuario['GENERO'] ?: 'No especificado' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Estado Civil</div>
                            <div class="info-value"><?= $usuario['ESTADO_CIVIL'] ?: 'No especificado' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Nacionalidad</div>
                            <div class="info-value"><?= $usuario['NACIONALIDAD'] ?: 'No especificada' ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Fecha de Ingreso</div>
                            <div class="info-value"><?= $usuario['FECHA_INGRESO'] ? date('d/m/Y', strtotime($usuario['FECHA_INGRESO'])) : 'No especificada' ?></div>
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

                        <form action="<?= base_url('admin/perfil/update') ?>" method="post" id="formPerfil">
                            <?= csrf_field() ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Nombre
                                    </label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('nombre')) ? 'is-invalid' : '' ?>"
                                        id="nombre" name="nombre" value="<?= $usuario['NOMBRE'] ?>" required>
                                    <div class="invalid-feedback">
                                        <?= (isset($validation) && $validation->hasError('nombre')) ? $validation->getError('nombre') : '' ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Apellido
                                    </label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('apellido')) ? 'is-invalid' : '' ?>"
                                        id="apellido" name="apellido" value="<?= $usuario['APELLIDO'] ?>" required>
                                    <div class="invalid-feedback">
                                        <?= (isset($validation) && $validation->hasError('apellido')) ? $validation->getError('apellido') : '' ?>
                                    </div>
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
                                        value="<?= $usuario['FECHA_INGRESO'] ?>">
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <!-- Cambio de Contraseña -->
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-lock me-2"></i>
                                        Cambiar Contraseña
                                    </h6>
                                    <small class="text-muted mb-3 d-block">
                                        Deja en blanco si no deseas cambiar la contraseña
                                    </small>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password_actual" class="form-label fw-semibold">
                                                <i class="fas fa-key me-1"></i>Contraseña Actual
                                            </label>
                                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_actual')) ? 'is-invalid' : '' ?>"
                                                id="password_actual" name="password_actual">
                                            <div class="invalid-feedback">
                                                <?= (isset($validation) && $validation->hasError('password_actual')) ? $validation->getError('password_actual') : '' ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password_nuevo" class="form-label fw-semibold">
                                                <i class="fas fa-lock me-1"></i>Nueva Contraseña
                                            </label>
                                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_nuevo')) ? 'is-invalid' : '' ?>"
                                                id="password_nuevo" name="password_nuevo">
                                            <div class="invalid-feedback">
                                                <?= (isset($validation) && $validation->hasError('password_nuevo')) ? $validation->getError('password_nuevo') : '' ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirm" class="form-label fw-semibold">
                                                <i class="fas fa-lock me-1"></i>Confirmar Nueva Contraseña
                                            </label>
                                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>"
                                                id="password_confirm" name="password_confirm">
                                            <div class="invalid-feedback">
                                                <?= (isset($validation) && $validation->hasError('password_confirm')) ? $validation->getError('password_confirm') : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-secondary btn-modern me-2" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>Restaurar
                                </button>
                                <button type="submit" class="btn btn-primary btn-modern">
                                    <i class="fas fa-save me-1"></i>Guardar Cambios
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

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación del formulario
        const form = document.getElementById('formPerfil');
        const passwordNuevo = document.getElementById('password_nuevo');
        const passwordConfirm = document.getElementById('password_confirm');
        const passwordActual = document.getElementById('password_actual');

        // Validar que si se ingresa nueva contraseña, también se ingrese la actual
        passwordNuevo.addEventListener('input', function() {
            if (this.value && !passwordActual.value) {
                passwordActual.setCustomValidity('Debe ingresar la contraseña actual para cambiar la contraseña');
            } else {
                passwordActual.setCustomValidity('');
            }
        });

        // Validar que las contraseñas coincidan
        passwordConfirm.addEventListener('input', function() {
            if (this.value !== passwordNuevo.value) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });

        // Validar que si se ingresa confirmación, también se ingrese nueva contraseña
        passwordConfirm.addEventListener('input', function() {
            if (this.value && !passwordNuevo.value) {
                passwordNuevo.setCustomValidity('Debe ingresar la nueva contraseña');
            } else {
                passwordNuevo.setCustomValidity('');
            }
        });

        // Limpiar validaciones cuando se borren los campos
        passwordNuevo.addEventListener('input', function() {
            if (!this.value) {
                passwordConfirm.setCustomValidity('');
                passwordActual.setCustomValidity('');
            }
        });

        passwordConfirm.addEventListener('input', function() {
            if (!this.value) {
                passwordNuevo.setCustomValidity('');
            }
        });

        passwordActual.addEventListener('input', function() {
            if (!this.value) {
                passwordNuevo.setCustomValidity('');
                passwordConfirm.setCustomValidity('');
            }
        });

        // Manejo del envío del formulario
        form.addEventListener('submit', function(e) {
            // Validar que si se ingresa nueva contraseña, se complete todo el proceso
            if (passwordNuevo.value || passwordConfirm.value) {
                if (!passwordActual.value) {
                    e.preventDefault();
                    showNotification('Debe ingresar la contraseña actual para cambiar la contraseña', 'warning');
                    return;
                }
                if (!passwordNuevo.value) {
                    e.preventDefault();
                    showNotification('Debe ingresar la nueva contraseña', 'warning');
                    return;
                }
                if (!passwordConfirm.value) {
                    e.preventDefault();
                    showNotification('Debe confirmar la nueva contraseña', 'warning');
                    return;
                }
                if (passwordNuevo.value !== passwordConfirm.value) {
                    e.preventDefault();
                    showNotification('Las contraseñas no coinciden', 'warning');
                    return;
                }
            }
        });
    });

    function resetForm() {
        if (confirm('¿Está seguro de que desea restaurar todos los campos a sus valores originales?')) {
            document.getElementById('formPerfil').reset();
            // Restaurar valores originales
            document.getElementById('nombre').value = '<?= $usuario['NOMBRE'] ?>';
            document.getElementById('apellido').value = '<?= $usuario['APELLIDO'] ?>';
            document.getElementById('celular').value = '<?= $usuario['CELULAR'] ?>';
            document.getElementById('direccion').value = '<?= $usuario['DIRECCION'] ?>';
            document.getElementById('email').value = '<?= $usuario['EMAIL'] ?>';
            document.getElementById('genero').value = '<?= $usuario['GENERO'] ?>';
            document.getElementById('estado_civil').value = '<?= $usuario['ESTADO_CIVIL'] ?>';
            document.getElementById('nacionalidad').value = '<?= $usuario['NACIONALIDAD'] ?>';
            document.getElementById('fecha_ingreso').value = '<?= $usuario['FECHA_INGRESO'] ?>';
            
            showNotification('Formulario restaurado exitosamente', 'info');
        }
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };

        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
</script>
<?= $this->endSection() ?>