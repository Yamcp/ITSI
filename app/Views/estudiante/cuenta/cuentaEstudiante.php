<?= $this->extend('estudiante/layouts/mainEstudiante') ?>
<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="page-title mb-3">
            <div class="row">
                <div class="col-6">
                    <h3>Mi Cuenta</h3>
                </div>
            </div>
        </div>
        <!-- Mensajes de alerta -->
        <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i>
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle me-2"></i>
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                <?= session('info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card" style="margin-top: 0;">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fa-solid fa-key me-2 text-primary"></i>
                            <span style="color: white;">Cambio de Contraseña</span>
                        </h4>
                        <p class="text-muted mb-0">Actualiza tu contraseña para mantener tu cuenta segura</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('estudiante/cuenta/cambiar-password') ?>" method="POST" id="formCambiarPassword">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Contraseña Actual -->
                                    <div class="mb-3">
                                        <label for="password_actual" class="form-label">
                                            <i class="fa-solid fa-lock me-2 text-warning"></i>
                                            Contraseña Actual
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control"
                                                id="password_actual"
                                                name="password_actual"
                                                placeholder="Ingresa tu contraseña actual"
                                                required>
                                            <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="togglePassword('password_actual')">
                                                <i class="fa-solid fa-eye" id="eye_actual"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fa-solid fa-info-circle me-1"></i>
                                            Ingresa tu contraseña actual para verificar tu identidad
                                        </div>
                                    </div>

                                    <!-- Nueva Contraseña -->
                                    <div class="mb-3">
                                        <label for="password_nuevo" class="form-label">
                                            <i class="fa-solid fa-lock me-2 text-success"></i>
                                            Nueva Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control"
                                                id="password_nuevo"
                                                name="password_nuevo"
                                                placeholder="Ingresa tu nueva contraseña"
                                                required>
                                            <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="togglePassword('password_nuevo')">
                                                <i class="fa-solid fa-eye" id="eye_nuevo"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fa-solid fa-shield-alt me-1"></i>
                                            La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y caracteres especiales
                                        </div>
                                    </div>

                                    <!-- Confirmar Nueva Contraseña -->
                                    <div class="mb-4">
                                        <label for="password_confirmar" class="form-label">
                                            <i class="fa-solid fa-lock me-2 text-info"></i>
                                            Confirmar Nueva Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control"
                                                id="password_confirmar"
                                                name="password_confirmar"
                                                placeholder="Confirma tu nueva contraseña"
                                                required>
                                            <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="togglePassword('password_confirmar')">
                                                <i class="fa-solid fa-eye" id="eye_confirmar"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            <i class="fa-solid fa-check-circle me-1"></i>
                                            Repite la nueva contraseña para confirmar
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-save me-2"></i>
                                            Cambiar Contraseña
                                        </button>
                                        <a href="<?= base_url('auth/cerrar-sesion') ?>" class="btn btn-secondary">
                                            <i class="fa-solid fa-times me-2"></i>
                                            Cancelar y salir
                                        </a>
                                    </div>
                                </div>

                                <!-- Panel de Requisitos -->
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fa-solid fa-list-check me-2" style="color: white;"></i>
                                                <span style="color: white;">Requisitos de Contraseña</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fa-solid fa-check text-success me-2" id="req_length"></i>
                                                    Mínimo 8 caracteres
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fa-solid fa-check text-success me-2" id="req_uppercase"></i>
                                                    Al menos una mayúscula
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fa-solid fa-check text-success me-2" id="req_lowercase"></i>
                                                    Al menos una minúscula
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fa-solid fa-check text-success me-2" id="req_number"></i>
                                                    Al menos un número
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fa-solid fa-check text-success me-2" id="req_special"></i>
                                                    Al menos un carácter especial
                                                </li>
                                            </ul>

                                            <hr>

                                            <div class="text-center">
                                                <div class="progress mb-2" style="height: 8px;">
                                                    <div class="progress-bar" id="password_strength" role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small class="text-muted" id="strength_text">Fuerza de la contraseña</small>
                                            </div>
                                        </div>
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

<!-- Estilos personalizados -->
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
    }

    .card-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border-radius: 0.5rem 0.5rem 0 0 !important;
        border: none;
    }

    .card-header h4 {
        margin: 0;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.15s ease-in-out;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .progress {
        border-radius: 0.375rem;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 0.375rem;
        transition: width 0.3s ease;
    }

    .alert {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Eliminar espacios innecesarios */
    .page-title {
        margin-bottom: 1rem !important;
    }

    .page-title h3 {
        margin-bottom: 0;
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e3a8a;
    }

    .container-fluid {
        padding-top: 0;
    }
</style>

<!-- JavaScript para validación y funcionalidad -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordNuevo = document.getElementById('password_nuevo');
        const passwordConfirmar = document.getElementById('password_confirmar');

        // Validar contraseña en tiempo real
        passwordNuevo.addEventListener('input', function() {
            validatePassword(this.value);
        });

        // Validar confirmación
        passwordConfirmar.addEventListener('input', function() {
            validatePasswordConfirmation();
        });

        // Validar formulario antes de enviar
        document.getElementById('formCambiarPassword').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById('eye_' + inputId.split('_')[1]);

        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }

    function validatePassword(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[@$!%*?&]/.test(password)
        };

        // Actualizar iconos de requisitos
        document.getElementById('req_length').className = requirements.length ? 'fa-solid fa-check text-success me-2' : 'fa-solid fa-times text-danger me-2';
        document.getElementById('req_uppercase').className = requirements.uppercase ? 'fa-solid fa-check text-success me-2' : 'fa-solid fa-times text-danger me-2';
        document.getElementById('req_lowercase').className = requirements.lowercase ? 'fa-solid fa-check text-success me-2' : 'fa-solid fa-times text-danger me-2';
        document.getElementById('req_number').className = requirements.number ? 'fa-solid fa-check text-success me-2' : 'fa-solid fa-times text-danger me-2';
        document.getElementById('req_special').className = requirements.special ? 'fa-solid fa-check text-success me-2' : 'fa-solid fa-times text-danger me-2';

        // Calcular fuerza de la contraseña
        const strength = Object.values(requirements).filter(Boolean).length;
        const strengthBar = document.getElementById('password_strength');
        const strengthText = document.getElementById('strength_text');

        let strengthPercentage = (strength / 5) * 100;
        let strengthClass = '';
        let strengthDescription = '';

        if (strengthPercentage <= 20) {
            strengthClass = 'bg-danger';
            strengthDescription = 'Muy débil';
        } else if (strengthPercentage <= 40) {
            strengthClass = 'bg-warning';
            strengthDescription = 'Débil';
        } else if (strengthPercentage <= 60) {
            strengthClass = 'bg-info';
            strengthDescription = 'Media';
        } else if (strengthPercentage <= 80) {
            strengthClass = 'bg-primary';
            strengthDescription = 'Fuerte';
        } else {
            strengthClass = 'bg-success';
            strengthDescription = 'Muy fuerte';
        }

        strengthBar.style.width = strengthPercentage + '%';
        strengthBar.className = 'progress-bar ' + strengthClass;
        strengthText.textContent = strengthDescription;

        return requirements;
    }

    function validatePasswordConfirmation() {
        const password = document.getElementById('password_nuevo').value;
        const confirmar = document.getElementById('password_confirmar').value;

        if (confirmar && password !== confirmar) {
            document.getElementById('password_confirmar').setCustomValidity('Las contraseñas no coinciden');
        } else {
            document.getElementById('password_confirmar').setCustomValidity('');
        }
    }

    function validateForm() {
        const password = document.getElementById('password_nuevo').value;
        const requirements = validatePassword(password);

        if (!Object.values(requirements).every(Boolean)) {
            alert('Por favor, asegúrate de que la nueva contraseña cumpla con todos los requisitos de seguridad.');
            return false;
        }

        return true;
    }
</script>

<?= $this->endSection() ?>