<?php

use Config\Database;

?>

<header class="app-header" style="padding: 0;">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #00367c;">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('coord/dashboard') ?>"></a>

        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2" style="color: #ffffff;"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item me-3 d-flex flex-column align-items-end">
                    <span class="text-white fw-medium">
                        <i class="ti ti-user-circle me-1"></i>
                        Bienvenido al sistema, <?= session('nombre') ?? 'Coordinador' ?>
                    </span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        $foto_perfil = session('foto_perfil');
                        if (!$foto_perfil && session()->get('id_usuario')) {
                            try {
                                $dbFoto = Database::connect();
                                $fotoRow = $dbFoto->query(
                                    "SELECT dp.FOTO_URL 
                                     FROM TAB_USUARIOS u 
                                     INNER JOIN TAB_DATOS_PERSONAS dp ON dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA 
                                     WHERE u.ID_USUARIO = ? LIMIT 1",
                                    [session()->get('id_usuario')]
                                )->getRowArray();
                                $foto_perfil = $fotoRow['FOTO_URL'] ?? null;
                                session()->set('foto_perfil', $foto_perfil);
                            } catch (\Throwable $e) {
                                log_message('error', 'Navbar coord - error obteniendo foto de perfil: ' . $e->getMessage());
                            }
                        }
                        $foto_url = ($foto_perfil && file_exists(FCPATH . 'uploads/perfiles/' . $foto_perfil))
                            ? base_url('uploads/perfiles/' . $foto_perfil)
                            : base_url('sistema/assets/images/profile/user-1.jpg');
                        $foto_url_cache = $foto_url . '?v=' . urlencode((string) (session()->get('id_usuario') ?? '0')) . '_' . urlencode((string) ($foto_perfil ?: 'default'));
                        ?>
                        <img src="<?= $foto_url_cache ?>" alt="Foto de perfil" width="35" height="35" class="rounded-circle" style="object-fit: cover; background-color: white; border: 2px solid #ffffff;">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="<?= base_url('coord/perfil') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">Mi Perfil</p>
                            </a>
                            <a href="<?= base_url('coord/cuenta') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-settings fs-6"></i>
                                <p class="mb-0 fs-3">Mi Cuenta</p>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="<?= base_url('auth/cerrar-sesion') ?>" class="btn btn-outline-danger mx-3 mt-2 d-block" id="btnCerrarSesion">
                                <i class="ti ti-logout me-1"></i>Cerrar sesión
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>