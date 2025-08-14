<header class="app-header w-100">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #00367c;">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('estudiante/inicio') ?>"></a>
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2" style="color: #ffffff;"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item me-3">
                    <span class="text-white fw-medium">
                        <i class="ti ti-user-circle me-1"></i>
                        Bienvenido al sistema, <?= session('nombres') ?? 'Estudiante' ?>
                    </span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        $foto_perfil = session('foto_perfil');
                        $foto_url = ($foto_perfil && file_exists(FCPATH . 'sistema/assets/images/profile/' . $foto_perfil))
                            ? base_url('sistema/assets/images/profile/' . $foto_perfil)
                            : base_url('sistema/assets/images/profile/user-1.jpg');
                        ?>
                        <img src="<?= $foto_url ?>" alt="Foto de perfil" width="35" height="35" class="rounded-circle" style="object-fit: cover; background-color: white; border: 2px solid #ffffff;">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="<?= base_url('estudiante/perfil') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">Mi Perfil</p>
                            </a>
                            <a href="<?= base_url('estudiante/cuenta') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-settings fs-6"></i>
                                <p class="mb-0 fs-3">Mi Cuenta</p>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="<?= base_url('login/cerrar-sesion') ?>" class="btn btn-outline-danger mx-3 mt-2 d-block">
                                <i class="ti ti-logout me-1"></i>Cerrar sesión
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
