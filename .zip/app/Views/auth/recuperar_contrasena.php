<?php
$esRestablecer = !empty($token ?? '');
$tituloPagina = $esRestablecer ? 'Nueva contraseña' : 'Recuperar contraseña';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esRestablecer ? 'Nueva contraseña' : 'Recuperar contraseña' ?> | Departamento de Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />
    <link href="<?= base_url('login/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('login/authstyles.css') ?>" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.8), rgba(187, 222, 251, 0.85));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: url('<?= base_url(config('Auth')->loginBackgroundImage) ?>');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.1);
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 2.5rem rgba(15, 23, 42, 0.12);
            animation: fadeInDown 0.7s;
            max-width: 420px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.98);
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6, #60a5fa);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .instituto-logo {
            max-width: 140px;
            margin-bottom: 1rem;
        }

        .form-floating>.form-control {
            border-radius: 0.5rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-floating>.form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.2);
        }

        .form-floating>.form-control:focus~label {
            color: #2563eb;
        }

        .card-body {
            padding: 2rem 2rem 1.5rem 2rem;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .login-card--restablecer {
            max-width: 720px;
        }

        .restablecer-form-col {
            padding-right: 0.5rem;
        }

        .restablecer-requisitos-col {
            padding-left: 0.5rem;
        }

        .password-requirements {
            border: none;
            box-shadow: none;
            height: 100%;
        }

        .password-requirements .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: none;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .password-requirements .progress {
            border-radius: 0.375rem;
            background-color: #e9ecef;
        }

        .password-requirements .progress-bar {
            border-radius: 0.375rem;
            transition: width 0.3s ease;
        }

        .toggle-password {
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #2563eb;
        }

        .toggle-password:focus {
            outline: none;
            box-shadow: none;
        }

        .toggle-password-icon {
            font-size: 1.1rem;
        }

        .form-floating.position-relative .form-control {
            padding-right: 3rem;
        }

        .btn-auth {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-auth:hover:not(:disabled) {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-auth:disabled {
            opacity: 0.85;
        }

        .back-link {
            color: #2563eb;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #1d4ed8;
        }

        .auth-hint {
            font-size: 0.875rem;
            color: #6c757d;
            line-height: 1.5;
        }

        @media (max-width: 767px) {
            .login-card--restablecer {
                max-width: 95vw;
            }

            .restablecer-form-col,
            .restablecer-requisitos-col {
                padding-left: 0;
                padding-right: 0;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                max-width: 95vw;
                padding: 0.5rem;
            }

            .card-body {
                padding: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-card card w-100<?= $esRestablecer ? ' login-card--restablecer' : '' ?>">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?= base_url('login/assets/img/logo_instituto.png') ?>" alt="Logo Instituto" class="instituto-logo mb-2">
                    <h1 class="login-title"><?= $tituloPagina ?></h1>
                    <div class="login-subtitle">
                        <?php if ($esRestablecer) : ?>
                            Ingresa tu nueva contraseña cumpliendo los requisitos de seguridad
                        <?php else : ?>
                            Ingresa tu correo electrónico o usuario para recibir las instrucciones
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger d-flex align-items-center mb-3 py-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
                        <div class="flex-grow-1"><?= esc(session()->getFlashdata('error')) ?></div>
                        <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <?php if ($esRestablecer) : ?>
                    <!-- Formulario: Nueva contraseña -->
                    <form action="<?= site_url('auth/restablecer-contrasena') ?>" method="post" id="formRestablecer" autocomplete="off" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= esc($token) ?>">
                        <div class="row g-3 align-items-stretch">
                            <div class="col-12 col-md-7 restablecer-form-col">
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required minlength="8">
                                    <label for="password"><i class="bi bi-lock-fill me-2"></i>Nueva contraseña</label>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                                        data-target="password" aria-label="Mostrar u ocultar contraseña"
                                        style="z-index: 10; border: none; background: none; padding: 0;">
                                        <i class="bi bi-eye toggle-password-icon"></i>
                                    </button>
                                    <div class="invalid-feedback">La contraseña no cumple los requisitos de seguridad.</div>
                                </div>
                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" placeholder="Confirmar contraseña" required minlength="8">
                                    <label for="password_confirmar"><i class="bi bi-lock-fill me-2"></i>Confirmar contraseña</label>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                                        data-target="password_confirmar" aria-label="Mostrar u ocultar confirmación de contraseña"
                                        style="z-index: 10; border: none; background: none; padding: 0;">
                                        <i class="bi bi-eye toggle-password-icon"></i>
                                    </button>
                                    <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-auth text-uppercase fw-bold" id="btnRestablecer">
                                        <span id="btnRestablecerText"><i class="bi bi-check-lg me-2"></i>Guardar contraseña</span>
                                        <span id="btnRestablecerSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="text-center text-md-start">
                                    <a href="<?= site_url('/') ?>" class="text-decoration-none small back-link"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 restablecer-requisitos-col">
                                <div class="password-requirements card bg-light h-100">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-white">
                                            <i class="bi bi-list-check me-2"></i>Requisitos de Contraseña
                                        </h6>
                                    </div>
                                    <div class="card-body py-3">
                                        <ul class="list-unstyled mb-0 small">
                                            <li class="mb-2">
                                                <i class="bi bi-check-lg text-success me-2" id="req_length"></i>Mínimo 8 caracteres
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-lg text-success me-2" id="req_uppercase"></i>Al menos una mayúscula
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-lg text-success me-2" id="req_lowercase"></i>Al menos una minúscula
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-lg text-success me-2" id="req_number"></i>Al menos un número
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-lg text-success me-2" id="req_special"></i>Al menos un carácter especial (@$!%*?&_-)
                                            </li>
                                        </ul>
                                        <hr class="my-2">
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
                <?php else : ?>
                    <!-- Formulario: Solicitar recuperación -->
                    <form action="<?= site_url('auth/solicitar-recuperacion') ?>" method="post" id="formRecuperar" autocomplete="off" novalidate>
                        <?= csrf_field() ?>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="email_o_usuario" name="email_o_usuario"
                                placeholder="Correo o usuario" required
                                value="<?= esc(old('email_o_usuario')) ?>"
                                autocomplete="username"
                                autofocus>
                            <label for="email_o_usuario"><i class="bi bi-person-badge me-2"></i>Correo o usuario</label>
                            <div class="invalid-feedback">Este campo es obligatorio.</div>
                        </div>
                        <p class="auth-hint mb-4">
                            <i class="bi bi-info-circle me-1"></i>
                            Si tu cuenta está registrada, recibirás un correo con el enlace para restablecer tu contraseña.
                        </p>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-auth text-uppercase fw-bold" id="btnRecuperar">
                                <span id="btnRecuperarText"><i class="bi bi-send me-2"></i>Enviar instrucciones</span>
                                <span id="btnRecuperarSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="text-center">
                            <a href="<?= site_url('/') ?>" class="text-decoration-none small back-link">
                                <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <footer class="text-center mt-3 text-muted small">
                &copy; <?= date('Y') ?> Departamento de Vinculación
            </footer>
        </div>
    </div>
    <script src="<?= base_url('login/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        (function() {
            var successToast = document.querySelector('.toast.text-bg-success');
            if (successToast) {
                setTimeout(function() {
                    var toast = bootstrap.Toast.getOrCreateInstance(successToast, { delay: 5000 });
                    toast.hide();
                }, 5000);
            }
        })();

        function mostrarSpinnerEnvio(btnId, textId, spinnerId) {
            var btn = document.getElementById(btnId);
            var text = document.getElementById(textId);
            var spinner = document.getElementById(spinnerId);
            if (!btn || !text || !spinner) return;
            btn.disabled = true;
            text.classList.add('d-none');
            spinner.classList.remove('d-none');
        }
    </script>
    <?php if ($esRestablecer) : ?>
        <script>
            (function() {
                var passwordInput = document.getElementById('password');
                var confirmInput = document.getElementById('password_confirmar');
                var form = document.getElementById('formRestablecer');

                document.querySelectorAll('.toggle-password').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var input = document.getElementById(this.dataset.target);
                        var icon = this.querySelector('.toggle-password-icon');
                        if (!input || !icon) return;

                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.replace('bi-eye', 'bi-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.replace('bi-eye-slash', 'bi-eye');
                        }
                    });
                });

                function validatePassword(password) {
                    var requirements = {
                        length: password.length >= 8,
                        uppercase: /[A-Z]/.test(password),
                        lowercase: /[a-z]/.test(password),
                        number: /\d/.test(password),
                        special: /[@$!%*?&_-]/.test(password)
                    };

                    document.getElementById('req_length').className = requirements.length
                        ? 'bi bi-check-lg text-success me-2' : 'bi bi-x-lg text-danger me-2';
                    document.getElementById('req_uppercase').className = requirements.uppercase
                        ? 'bi bi-check-lg text-success me-2' : 'bi bi-x-lg text-danger me-2';
                    document.getElementById('req_lowercase').className = requirements.lowercase
                        ? 'bi bi-check-lg text-success me-2' : 'bi bi-x-lg text-danger me-2';
                    document.getElementById('req_number').className = requirements.number
                        ? 'bi bi-check-lg text-success me-2' : 'bi bi-x-lg text-danger me-2';
                    document.getElementById('req_special').className = requirements.special
                        ? 'bi bi-check-lg text-success me-2' : 'bi bi-x-lg text-danger me-2';

                    var strength = Object.values(requirements).filter(Boolean).length;
                    var strengthBar = document.getElementById('password_strength');
                    var strengthText = document.getElementById('strength_text');
                    var strengthPercentage = (strength / 5) * 100;
                    var strengthClass = '';
                    var strengthDescription = 'Fuerza de la contraseña';

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

                function passwordIsValid(password) {
                    var requirements = validatePassword(password);
                    return Object.values(requirements).every(Boolean);
                }

                function validatePasswordConfirmation() {
                    var password = passwordInput.value;
                    var confirmar = confirmInput.value;

                    if (confirmar && password !== confirmar) {
                        confirmInput.setCustomValidity('Las contraseñas no coinciden');
                    } else {
                        confirmInput.setCustomValidity('');
                    }
                }

                passwordInput.addEventListener('input', function() {
                    var valid = passwordIsValid(this.value);
                    if (!valid && this.value.length > 0) {
                        this.setCustomValidity('La contraseña no cumple los requisitos de seguridad.');
                    } else {
                        this.setCustomValidity('');
                    }
                    validatePasswordConfirmation();
                });

                confirmInput.addEventListener('input', validatePasswordConfirmation);

                form.addEventListener('submit', function(e) {
                    var p = passwordInput.value;
                    var c = confirmInput.value;

                    if (!passwordIsValid(p)) {
                        passwordInput.setCustomValidity('La contraseña no cumple los requisitos de seguridad.');
                    } else {
                        passwordInput.setCustomValidity('');
                    }

                    if (c && p !== c) {
                        confirmInput.setCustomValidity('Las contraseñas no coinciden');
                    } else {
                        confirmInput.setCustomValidity('');
                    }

                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    } else {
                        mostrarSpinnerEnvio('btnRestablecer', 'btnRestablecerText', 'btnRestablecerSpinner');
                    }
                    this.classList.add('was-validated');
                });
            })();
        </script>
    <?php else : ?>
        <script>
            document.getElementById('formRecuperar').addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    mostrarSpinnerEnvio('btnRecuperar', 'btnRecuperarText', 'btnRecuperarSpinner');
                }
                this.classList.add('was-validated');
            });
        </script>
    <?php endif; ?>
</body>

</html>