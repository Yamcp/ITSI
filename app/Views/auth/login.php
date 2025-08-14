<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Bienestar Estudiantil</title>
    <link href="<?= base_url('login/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            /* Se aplica el gradiente con transparencia para teñir la imagen de fondo */
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.8), rgba(187, 222, 251, 0.85));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* Evita barras de scroll por el desenfoque */
        }

        /* Pseudo-elemento para el fondo con la imagen desenfocada */
        body::before {
            content: '';
            position: fixed;
            /* Fijo para que cubra toda la pantalla */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Lo enviamos detrás de todo el contenido */

            /* Usamos tu imagen de fondo */
            /* La ruta asume que 'assets' está en la raíz de tu 'public' folder */
            background-image: url('<?= base_url('login/assets/img/fondo_login.jpg') ?>');
            background-size: cover;
            /* Cubre todo el espacio sin deformar */
            background-position: center;
            /* Centra la imagen */

            /* El efecto de desenfoque (puedes ajustar el valor en píxeles) */
            filter: blur(8px);
            /* Opcional: para que el borde del blur no se vea extraño */
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

        .brand-logo {
            max-width: 80px;
            margin-bottom: 1rem;
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
    <!-- Toasts -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
            </div>
        <?php endif; ?>
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
                    <img src="<?= base_url('login/assets/img/logo_instituto.png') ?>" alt="Logo Instituto" class="instituto-logo mb-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Instituto Superior">
                    <h1 class="login-title">Sistema de Vinculación</h1>
                    <div class="login-subtitle">Por favor, inicie sesión para continuar</div>
                </div>
                <form action="<?= site_url('admin/dashboard') ?>" method="post" id="loginForm" autocomplete="off" novalidate>
                    <?= csrf_field() ?>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="identificador" name="identificador" placeholder="Cédula o Correo" required autofocus
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Ingrese su usuario o cédula">
                        <label for="identificador"><i class="bi bi-person-circle me-2"></i>Usuario o Cédula</label>
                        <div class="invalid-feedback">
                            Este campo es obligatorio.
                        </div>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Ingrese su contraseña">
                        <label for="password"><i class="bi bi-lock-fill me-2"></i>Contraseña</label>
                        <div class="invalid-feedback">
                            La contraseña es obligatoria.
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Recordarme
                            </label>
                        </div>
                        <a href="<?= site_url('forgot-password') ?>" class="text-decoration-none">¿Olvidó su contraseña?</a>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login text-uppercase fw-bold" id="btnLogin">
                            <span id="btnText">INGRESAR</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
            <footer class="text-center mt-3 text-muted small">
                &copy; <?= date('Y') ?> Bienestar Estudiantil
            </footer>
        </div>
    </div>
    <script src="<?= base_url('login/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Mostrar spinner al enviar
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Validación visual Bootstrap 5
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            } else {
                document.getElementById('btnLogin').disabled = true;
                document.getElementById('btnText').classList.add('d-none');
                document.getElementById('btnSpinner').classList.remove('d-none');
            }
            this.classList.add('was-validated');
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>