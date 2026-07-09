<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Departamento de Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />
    <link href="<?= base_url('login/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
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
            background-image: url('<?= base_url(config('Auth')->loginBackgroundImage) ?>');
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

        .brand-logo {
            max-width: 80px;
            margin-bottom: 1rem;
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

        .toggle-password {
            color: #6c757d;
            transition: color 0.2s ease;
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

        .btn-login {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-login:hover:not(:disabled) {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-login:disabled {
            opacity: 0.85;
        }

        .login-options {
            gap: 0.75rem;
        }

        .forgot-password-link {
            color: #2563eb;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-password-link:hover {
            color: #1d4ed8;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        @media (max-width: 576px) {
            .login-card {
                max-width: 95vw;
                padding: 0.5rem;
            }

            .card-body {
                padding: 1.25rem;
            }

            .login-options {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
</head>

<body>
    <!-- Toast solo para mensajes de éxito (ej. sesión cerrada) -->
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
                    <img src="<?= base_url('login/assets/img/logo_instituto.png') ?>" alt="Logo Instituto" class="instituto-logo mb-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Instituto Superior">
                    <h1 class="login-title">Sistema de Vinculación</h1>
                    <div class="login-subtitle">Por favor, inicie sesión para continuar</div>
                </div>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger d-flex align-items-center mb-3 py-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
                        <div class="flex-grow-1"><?= esc(session()->getFlashdata('error')) ?></div>
                        <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>
                <form action="<?= site_url('auth/autenticar') ?>" method="post" id="loginForm" autocomplete="off" novalidate>
                    <?= csrf_field() ?>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Cédula o Correo" required autofocus
                            value="<?= esc(old('usuario')) ?>"
                            autocomplete="username">
                        <label for="usuario"><i class="bi bi-person-circle me-2"></i>Usuario o Cédula</label>
                        <div class="invalid-feedback">
                            Este campo es obligatorio.
                        </div>
                    </div>
                    <div class="form-floating mb-4 position-relative">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required
                            autocomplete="current-password">
                        <label for="password"><i class="bi bi-lock-fill me-2"></i>Contraseña</label>
                        <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                            id="togglePassword" aria-label="Mostrar u ocultar contraseña"
                            style="z-index: 10; border: none; background: none; padding: 0;">
                            <i class="bi bi-eye toggle-password-icon" id="togglePasswordIcon"></i>
                        </button>
                        <div class="invalid-feedback">
                            La contraseña es obligatoria.
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 login-options flex-wrap">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Recordarme
                            </label>
                        </div>
                        <a href="<?= site_url('auth/recuperar-contrasena') ?>" class="text-decoration-none small forgot-password-link">
                            <i class="bi bi-key me-1"></i>¿Olvidaste tu contraseña?
                        </a>
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
                &copy; <?= date('Y') ?> Departamento de Vinculación
            </footer>
        </div>
    </div>
    <script src="<?= base_url('login/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Función para guardar datos en localStorage
        function saveToLocalStorage(key, value) {
            try {
                localStorage.setItem(key, value);
            } catch (e) {
                console.log('No se pudo guardar en localStorage:', e);
            }
        }

        // Función para obtener datos de localStorage
        function getFromLocalStorage(key) {
            try {
                return localStorage.getItem(key);
            } catch (e) {
                console.log('No se pudo obtener de localStorage:', e);
                return null;
            }
        }

        // Función para eliminar datos de localStorage
        function removeFromLocalStorage(key) {
            try {
                localStorage.removeItem(key);
            } catch (e) {
                console.log('No se pudo eliminar de localStorage:', e);
            }
        }

        // Cargar datos guardados al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const usuarioInput = document.getElementById('usuario');
            const rememberCheckbox = document.getElementById('rememberMe');

            // Cargar usuario guardado solo si el campo viene vacío (evita pisar old() tras error)
            const savedUsuario = getFromLocalStorage('remembered_usuario');
            if (savedUsuario && !usuarioInput.value.trim()) {
                usuarioInput.value = savedUsuario;
            }

            // Cargar estado del checkbox
            const savedRemember = getFromLocalStorage('remember_me');
            if (savedRemember === 'true') {
                rememberCheckbox.checked = true;
            }
        });

        // Funcionalidad para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });

        // Mostrar spinner al enviar
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usuarioInput = document.getElementById('usuario');
            const rememberCheckbox = document.getElementById('rememberMe');

            // Validación visual Bootstrap 5
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            } else {
                // Guardar datos si el checkbox está marcado
                if (rememberCheckbox.checked) {
                    saveToLocalStorage('remembered_usuario', usuarioInput.value);
                    saveToLocalStorage('remember_me', 'true');
                } else {
                    // Limpiar datos si no está marcado
                    removeFromLocalStorage('remembered_usuario');
                    removeFromLocalStorage('remember_me');
                }

                document.getElementById('btnLogin').disabled = true;
                document.getElementById('btnText').classList.add('d-none');
                document.getElementById('btnSpinner').classList.remove('d-none');
            }
            this.classList.add('was-validated');
        });

        // Manejar cambios en el checkbox
        document.getElementById('rememberMe').addEventListener('change', function() {
            const usuarioInput = document.getElementById('usuario');

            if (this.checked) {
                // Si se marca, guardar el usuario actual
                if (usuarioInput.value.trim()) {
                    saveToLocalStorage('remembered_usuario', usuarioInput.value);
                }
                saveToLocalStorage('remember_me', 'true');
            } else {
                // Si se desmarca, limpiar los datos
                removeFromLocalStorage('remembered_usuario');
                removeFromLocalStorage('remember_me');
            }
        });

        // Cerrar toast de éxito automáticamente
        var successToast = document.querySelector('.toast.text-bg-success');
        if (successToast) {
            setTimeout(function() {
                var toast = bootstrap.Toast.getOrCreateInstance(successToast, { delay: 5000 });
                toast.hide();
            }, 5000);
        }
    </script>
</body>

</html>