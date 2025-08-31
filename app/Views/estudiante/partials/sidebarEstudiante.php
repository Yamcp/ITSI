<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/logo.png') ?>" alt="Logo" style="width: 30px; height: auto;" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Dep. Vinculación </span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="fa-solid fa-xmark fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="fa-solid fa-house nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">INICIO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
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
                    <a class="sidebar-link" href="<?= base_url('admin/educacion') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-layer-group fs-6"></i>
                        </span>
                        <span class="hide-menu">Mis cursos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('vinculacion/convenios') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-clipboard-check fs-6"></i>
                        </span>
                        <span class="hide-menu">Mis evaluaciones</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-briefcase nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PRÁCTICAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/practicas') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-user-graduate fs-6"></i>
                        </span>
                        <span class="hide-menu">Mis prácticas</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>