<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= base_url('coord/dashboard') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('sistema/assets/images/logos/logo.png') ?>" alt="Logo"
                    style="width: 30px; height: auto;" />
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
                    <a class="sidebar-link" href="<?= base_url('coord/dashboard') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-gauge-high fs-6"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-users nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PERSONAL</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/estudiantes') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-users fs-6"></i>
                        </span>
                        <span class="hide-menu">Estudiantes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/docentes') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-user-tie fs-6"></i>
                        </span>
                        <span class="hide-menu">Docentes</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-book nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">EDUCACIÓN CONTINUA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/educacion') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-layer-group fs-6"></i>
                        </span>
                        <span class="hide-menu">Actividades educativas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/instructores') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-person-chalkboard fs-6"></i>
                        </span>
                        <span class="hide-menu">Instructores</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-briefcase nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">PRÁCTICAS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/practicas') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-user-graduate fs-6"></i>
                        </span>
                        <span class="hide-menu">Prácticas asignadas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/documentos/practicas') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-folder-open fs-6"></i>
                        </span>
                        <span class="hide-menu">Documentos - Preprofesionales</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/documentos/servicio') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-hands-helping fs-6"></i>
                        </span>
                        <span class="hide-menu">Documentos - Servicio Comunitario</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="fa-solid fa-briefcase nav-small-cap-icon fs-6"></i>
                    <span class="hide-menu">CONVENIOS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('coord/convenios') ?>" aria-expanded="false">
                        <span>
                            <i class="fa-solid fa-handshake fs-6"></i>
                        </span>
                        <span class="hide-menu">Convenios</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>