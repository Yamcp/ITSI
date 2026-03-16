<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Departamento de Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />

    <!-- ESTILOS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/theme-toggle.css') ?>" />
    <?= $this->renderSection('styles') ?>
    <script>
        (function(){var t=document.documentElement,k='itsi-theme';try{var s=localStorage.getItem(k)||'light';t.setAttribute('data-bs-theme',s);}catch(e){t.setAttribute('data-bs-theme','light');}})();
    </script>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Navbar -->
        <?= $this->include('docente/partials/navbarDocente'); ?>

        <!--Layout -->
        <div id="layoutSidenav">

            <!-- Sidebar -->
            <?= $this->include('docente/partials/sidebarDocente'); ?>

            <!-- Main Content -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <?= $this->renderSection('content') ?>
                    </div>
                    <div class="container-fluid">
                        <?= $this->renderSection('modal') ?>
                    </div>
                </main>

                <!-- Footer -->
                <?= $this->include('partials/footer') ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('sistema/assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/libs/simplebar/dist/simplebar.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/theme-toggle.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/dashboard.js') ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script>
        // Cierre de sesión automático por inactividad (10 minutos)
        (function() {
            var INACTIVIDAD_MS = 10 * 60 * 1000; // 10 minutos
            var urlCerrarSesion = '<?= base_url('auth/cerrar-sesion') ?>';
            var timerInactividad;

            function redirigirALogin() {
                window.location.href = urlCerrarSesion;
            }

            function reiniciarTimer() {
                clearTimeout(timerInactividad);
                timerInactividad = setTimeout(redirigirALogin, INACTIVIDAD_MS);
            }

            var eventos = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart', 'click'];
            eventos.forEach(function(ev) {
                document.addEventListener(ev, reiniciarTimer);
            });

            reiniciarTimer();
        })();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>