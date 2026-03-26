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
br    <link href="<?= base_url('login/authstyles.css') ?>" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.8), rgba(187, 222, 251, 0.85));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: url('<?= base_url('login/assets/img/fondo_login.jpg') ?>');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.1);
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            animation: fadeInDown 0.7s;
            max-width: 400px;
            margin: 2rem auto;
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

        .form-floating>.form-control:focus~label {
            color: #0d6efd;
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

        @media (max-width: 576px) {
            .login-card {
                max-width: 95vw;
                padding: 0.5rem;
            }

            .card-body {
                padding: 1rem;
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
        <div class="login-card card w-100">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?= base_url('login/assets/img/logo_instituto.png') ?>" alt="Logo Instituto" class="instituto-logo mb-2">
                    <h1 class="login-title"><?= $tituloPagina ?></h1>
                    <div class="login-subtitle">
                        <?php if ($esRestablecer) : ?>
                            Ingresa tu nueva contraseña (mínimo 8 caracteres)
                        <?php else : ?>
                            Ingresa tu correo electrónico, cédula o usuario. Disponible para coordinadores, docentes y estudiantes.
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
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required minlength="8">
                            <label for="password"><i class="bi bi-lock-fill me-2"></i>Nueva contraseña</label>
                            <div class="invalid-feedback">Mínimo 8 caracteres.</div>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" placeholder="Confirmar contraseña" required minlength="8">
                            <label for="password_confirmar"><i class="bi bi-lock-fill me-2"></i>Confirmar contraseña</label>
                            <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary text-uppercase fw-bold">
                                <i class="bi bi-check-lg me-2"></i>Guardar contraseña
                            </button>
                        </div>
                        <div class="text-center">
                            <a href="<?= site_url('/') ?>" class="text-decoration-none small"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
                        </div>
                    </form>
                <?php else : ?>
                    <!-- Formulario: Solicitar recuperación -->
                    <form action="<?= site_url('auth/solicitar-recuperacion') ?>" method="post" id="formRecuperar" autocomplete="off" novalidate>
                        <?= csrf_field() ?>
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="email_o_usuario" name="email_o_usuario"
                                placeholder="Correo o usuario" required
                                value="<?= esc(old('email_o_usuario')) ?>"
                                autofocus>
                            <label for="email_o_usuario"><i class="bi bi-person-badge me-2"></i>Correo, cédula o usuario</label>
                            <div class="invalid-feedback">Este campo es obligatorio.</div>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary text-uppercase fw-bold py-2">
                                <i class="bi bi-send me-2"></i>Enviar instrucciones
                            </button>
                        </div>
                        <div class="text-center">
                            <a href="<?= site_url('/') ?>" class="text-decoration-none small">
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
    <?php if ($esRestablecer) : ?>
        <script>
            document.getElementById('formRestablecer').addEventListener('submit', function(e) {
                var p = document.getElementById('password').value;
                var c = document.getElementById('password_confirmar').value;
                if (c && p !== c) {
                    e.preventDefault();
                    document.getElementById('password_confirmar').setCustomValidity('Las contraseñas no coinciden');
                } else {
                    document.getElementById('password_confirmar').setCustomValidity('');
                }
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });
            document.getElementById('password_confirmar').addEventListener('input', function() {
                if (this.value !== document.getElementById('password').value) this.setCustomValidity('Las contraseñas no coinciden');
                else this.setCustomValidity('');
            });
        </script>
    <?php else : ?>
        <script>
            document.getElementById('formRecuperar').addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });
        </script>
    <?php endif; ?>
</body>

</html>