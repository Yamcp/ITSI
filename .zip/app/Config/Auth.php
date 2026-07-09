<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    /**
     * Imagen de fondo de la pantalla de inicio de sesión y recuperar contraseña.
     * Ruta relativa a la carpeta public (sin / inicial). Sustituya el archivo
     * en disco o cambie este valor si usa otro nombre o formato (jpg, png, webp).
     */
    public string $loginBackgroundImage = 'login/assets/img/fondo_login.jpg';
}
