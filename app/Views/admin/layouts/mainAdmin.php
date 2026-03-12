<!-- app/Views/admin/layouts/mainAdmin.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Departamento de Vinculación</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('sistema/assets/images/logos/logo.png') ?>" />

    <!-- ESTILOS -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/styles.min.css') ?>" />
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Navbar -->
        <?= $this->include('admin/partials/navbarAdmin'); ?>

        <!--Layout -->
        <div id="layoutSidenav">

            <!-- Sidebar -->
            <?= $this->include('admin/partials/sidebarAdmin'); ?>

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
    <?php if (!str_contains(current_url(), 'backup')): ?>
    <script src="<?= base_url('sistema/assets/js/dashboard.js') ?>"></script>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    <script>
        // Función para manejar el cierre de sesión
        function cerrarSesion() {
            // Mostrar indicador de carga
            const btnCerrar = document.getElementById('btnCerrarSesion') || document.querySelector('a[href*="cerrar-sesion"]');
            if (btnCerrar) {
                btnCerrar.innerHTML = '<i class="ti ti-loader me-1"></i>Cerrando...';
                btnCerrar.style.pointerEvents = 'none';
                btnCerrar.style.opacity = '0.7';
            }
            
            // Redirigir al cierre de sesión
            window.location.href = '<?= base_url('auth/cerrar-sesion') ?>';
        }
        
        // Agregar event listener cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Buscar el botón del navbar
            const btnCerrarSesion = document.getElementById('btnCerrarSesion');
            if (btnCerrarSesion) {
                btnCerrarSesion.addEventListener('click', function(e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            }
            
            // Buscar el botón del sidebar
            const btnCerrarSesionSidebar = document.getElementById('btnCerrarSesionSidebar');
            if (btnCerrarSesionSidebar) {
                btnCerrarSesionSidebar.addEventListener('click', function(e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            }
            
            // También buscar otros enlaces de cierre de sesión como respaldo
            const enlacesCerrarSesion = document.querySelectorAll('a[href*="cerrar-sesion"]:not(#btnCerrarSesion):not(#btnCerrarSesionSidebar)');
            enlacesCerrarSesion.forEach(function(enlace) {
                enlace.addEventListener('click', function(e) {
                    e.preventDefault();
                    cerrarSesion();
                });
            });
        });
        
        // Función global para cierre de sesión (por si se llama desde otros lugares)
        window.cerrarSesion = cerrarSesion;

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