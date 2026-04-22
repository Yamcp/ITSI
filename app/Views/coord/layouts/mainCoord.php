<?php
// Alerta: convenios por caducar (próximos 3 meses) - solo en área coordinación
$conveniosPorCaducarAlerta = 0;
if (session()->get('logged_in') && (int) session()->get('rol') === 1) {
    try {
        $db = \Config\Database::connect();
        $hoy = date('Y-m-d');
        $en3Meses = date('Y-m-d', strtotime('+3 months'));
        $builder = $db->table('TAB_DETALLES_CONVENIOS');
        $conveniosPorCaducarAlerta = $builder->where('FECHA_FIN >=', $hoy)->where('FECHA_FIN <=', $en3Meses)->countAllResults();
    } catch (\Throwable $e) {
        log_message('error', 'Layout coord - alerta convenios por caducar: ' . $e->getMessage());
    }
}
?>
<!-- app/Views/coord/layouts/mainCoord.php -->
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
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Navbar -->
        <?= $this->include('coord/partials/navbarCoord'); ?>

        <!--Layout -->
        <div id="layoutSidenav">

            <!-- Sidebar -->
            <?= $this->include('coord/partials/sidebarCoord'); ?>

            <!-- Main Content -->
            <div id="layoutSidenav_content">
                <main>
                    <?php if ($conveniosPorCaducarAlerta > 0): ?>
                        <div class="container-fluid px-3 px-md-4 pt-2">
                            <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0 border-0 shadow-sm" role="alert" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong>Convenios por caducar:</strong> <?= $conveniosPorCaducarAlerta === 1 ? '1 convenio vence' : $conveniosPorCaducarAlerta . ' convenios vencen' ?> en los próximos 3 meses.
                                        <a href="<?= base_url('coord/convenios') ?>" class="alert-link text-white text-decoration-underline fw-semibold ms-1">Ver convenios</a>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="container-fluid">
                        <?= $this->renderSection('content') ?>
                    </div>
                </main>

                <!-- Footer -->
                <?= $this->include('partials/footer') ?>
            </div>
        </div>
    </div>

    <!-- Modales fuera del layout principal: evitan conflictos de z-index con la cabecera fija y el scroll -->
    <?= $this->renderSection('modal') ?>

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