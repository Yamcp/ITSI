<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Departamento de Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />

    <!-- ESTILOS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/modals.css') ?>" />
    <?= $this->renderSection('styles') ?>
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Navbar -->
        <?= $this->include('admin/partials/navbarAdmin'); ?>

        <div id="layoutSidenav">
            <!-- Sidebar -->
            <?= $this->include('admin/partials/sidebarAdmin'); ?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <?= $this->renderSection('content') ?>
                    </div>
                </main>

                <?= $this->include('partials/footer') ?>
            </div>
        </div>
    </div>

    <?= $this->renderSection('modal') ?>

    <script src="<?= base_url('sistema/assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/simplebar/dist/simplebar.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/app.min.js') ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script>
        function cerrarSesion() {
            const btnCerrar = document.getElementById('btnCerrarSesion') || document.querySelector('a[href*="cerrar-sesion"]');
            if (btnCerrar) {
                btnCerrar.innerHTML = '<i class="ti ti-loader me-1"></i>Cerrando...';
                btnCerrar.style.pointerEvents = 'none';
                btnCerrar.style.opacity = '0.7';
            }
            window.location.href = '<?= base_url('auth/cerrar-sesion') ?>';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const btnCerrarSesion = document.getElementById('btnCerrarSesion');
            if (btnCerrarSesion) {
                btnCerrarSesion.addEventListener('click', function (e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            }

            const btnCerrarSesionSidebar = document.getElementById('btnCerrarSesionSidebar');
            if (btnCerrarSesionSidebar) {
                btnCerrarSesionSidebar.addEventListener('click', function (e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            }

            const enlacesCerrarSesion = document.querySelectorAll('a[href*="cerrar-sesion"]:not(#btnCerrarSesion):not(#btnCerrarSesionSidebar)');
            enlacesCerrarSesion.forEach(function (enlace) {
                enlace.addEventListener('click', function (e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            });
        });

        window.cerrarSesion = cerrarSesion;

        (function () {
            const INACTIVIDAD_MS = 10 * 60 * 1000;
            const urlCerrarSesion = '<?= base_url('auth/cerrar-sesion') ?>';
            let timerInactividad;

            function redirigirALogin() {
                window.location.href = urlCerrarSesion;
            }

            function reiniciarTimer() {
                clearTimeout(timerInactividad);
                timerInactividad = setTimeout(redirigirALogin, INACTIVIDAD_MS);
            }

            const eventos = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart', 'click'];
            eventos.forEach(function (ev) {
                document.addEventListener(ev, reiniciarTimer);
            });

            reiniciarTimer();
        })();
    </script>
    <?= $this->include('partials/modal_confirmar') ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>