<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('estudiante/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/logo.png') ?>" alt="Logo" style="width: 30px; height: auto;" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Dep. Vinculación </span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="fa-solid fa-xmark fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="" style="margin-top: -29px;">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="fa-solid fa-house nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INICIO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/dashboard') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-gauge-high fs-6"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-book nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EDUCACIÓN CONTINUA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/educacion') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-layer-group fs-6"></i>
                        </span>
                        <span class="hide-menu">Mis cursos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/evaluaciones') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-clipboard-check fs-6"></i>
                        </span>
                        <span class="hide-menu">Evaluaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/convenios') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-handshake fs-6"></i>
                        </span>
                        <span class="hide-menu">Convenios</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-briefcase nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PRÁCTICAS PROFESIONALES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/practicas') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-user-graduate fs-6"></i>
                        </span>
                        <span class="hide-menu">Documentación</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/practicas/formatos') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-file-lines fs-6"></i>
                        </span>
                        <span class="hide-menu">Formatos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-briefcase nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PRÁCTICAS DE SERVICIO COMUNITARIO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/practicas/servicio-comunitario') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-hands-helping fs-6"></i>
                        </span>
                        <span class="hide-menu">Documentación</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('estudiante/practicas/servicio-comunitario/formatos') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-file-lines fs-6"></i>
                        </span>
                        <span class="hide-menu">Formatos</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>