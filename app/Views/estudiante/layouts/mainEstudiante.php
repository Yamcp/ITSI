<!-- app/Views/estudiante/layouts/mainEstudiante.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ITSI - Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />

    <!-- ESTILOS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Navbar -->
        <?= $this->include('estudiante/partials/navbarEstudiante'); ?>

        <!--Layout -->
        <div id="layoutSidenav">

            <!-- Sidebar -->
            <?= $this->include('estudiante/partials/sidebarEstudiante'); ?>

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
    <script src="<?= base_url('sistema/assets/js/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/app.min.js') ?>"></script>
    <script src="<?= base_url('sistema/assets/js/dashboard.js') ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <script>
        // Cierre automático de sesión al cerrar la pestaña/ventana (estudiante)
        (function() {
            let cerrandoSesionPorSistema = false;

            window.addEventListener('beforeunload', function () {
                if (cerrandoSesionPorSistema || window.__cerrandoSesionAuto) {
                    return;
                }

                cerrandoSesionPorSistema = true;
                window.__cerrandoSesionAuto = true;

                const url = '<?= base_url('auth/cerrar-sesion') ?>';

                // Intento principal con sendBeacon (POST)
                if (navigator.sendBeacon) {
                    try {
                        const data = new Blob([], { type: 'application/x-www-form-urlencoded' });
                        navigator.sendBeacon(url, data);
                    } catch (e) {
                        // Ignorar errores de beacon
                    }
                }

                // Disparo adicional por GET para evitar problemas de CSRF o métodos
                try {
                    const img = new Image();
                    img.src = url + '?auto=1&_ts=' + Date.now();
                } catch (e) {
                    // Ignorar errores
                }
            });
        })();
    </script>
</body>

</html>