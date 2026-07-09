<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('admin/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/logo.png') ?>" alt="Logo" style="width: 30px; height: auto;" />
                <span class="ms-2 fw-bold" style="font-size: 1.3rem; color: #000;">Dep. Vinculación</span>
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
                    <a class="sidebar-link" href="<?= base_url('admin/dashboard') ?>" aria-expanded="false">
                        <span><i class="fa-solid fa-gauge-high fs-6"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-users nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PERSONAL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/estudiantes') ?>" aria-expanded="false">
                        <span><i class="fa-solid fa-users fs-6"></i></span>
                        <span class="hide-menu">Estudiantes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/docentes') ?>" aria-expanded="false">
                        <span><i class="fa-solid fa-user-tie fs-6"></i></span>
                        <span class="hide-menu">Docentes</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-cloud nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">BACKUP</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('admin/backup') ?>" aria-expanded="false">
                        <span><i class="fa-solid fa-cloud-arrow-up fs-6"></i></span>
                        <span class="hide-menu">Backup</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
