<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        $session = session();
        if (!$session->get('logged_in') || !$session->get('requiere_cambio_password')) {
            return;
        }

        $path = trim(service('uri')->getPath(), '/');
        if ($path === 'index.php') {
            $path = '';
        }

        $posIndex = strpos($path, 'index.php/');
        if ($posIndex !== false) {
            $path = substr($path, $posIndex + strlen('index.php/'));
        }

        foreach (['auth/', 'coord/', 'docente/', 'estudiante/', 'admin/'] as $prefijoRuta) {
            $posPrefijo = strpos($path, $prefijoRuta);
            if ($posPrefijo !== false) {
                $path = substr($path, $posPrefijo);
                break;
            }
        }

        $path = trim($path, '/');
        $rol = (int) $session->get('rol');

        if (str_starts_with($path, 'admin/') && $rol !== 4) {
            redirect()->to('/')->with('error', 'Acceso no autorizado')->send();
            exit;
        }

        if ($rol === 4 && str_starts_with($path, 'coord/')) {
            if (!str_starts_with($path, 'coord/estudiantes')
                && !str_starts_with($path, 'coord/docentes')
                && !str_starts_with($path, 'coord/backup')
                && $path !== 'coord/dashboard'
                && $path !== 'coord/cuenta'
                && $path !== 'coord/cuenta/cambiar-password') {
                redirect()->to('/admin/dashboard')->with('error', 'Acceso no autorizado')->send();
                exit;
            }
        }

        $rutasPermitidas = ['auth/cerrar-sesion'];

        switch ($rol) {
            case 1:
                $rutasPermitidas[] = 'coord/cuenta';
                $rutasPermitidas[] = 'coord/cuenta/cambiar-password';
                $rutaCuenta = 'coord/cuenta';
                break;
            case 2:
                $rutasPermitidas[] = 'docente/cuenta';
                $rutasPermitidas[] = 'docente/cuenta/cambiar-password';
                $rutaCuenta = 'docente/cuenta';
                break;
            case 3:
                $rutasPermitidas[] = 'estudiante/cuenta';
                $rutasPermitidas[] = 'estudiante/cuenta/cambiar-password';
                $rutaCuenta = 'estudiante/cuenta';
                break;
            case 4:
                $rutasPermitidas[] = 'admin/cuenta';
                $rutasPermitidas[] = 'admin/cuenta/cambiar-password';
                $rutaCuenta = 'admin/cuenta';
                break;
            default:
                $session->destroy();
                redirect()->to('/')->send();
                exit;
        }

        if (!in_array($path, $rutasPermitidas, true)) {
            redirect()->to($rutaCuenta)
                ->with('info', 'Debe cambiar su contraseña inicial antes de continuar o cerrar sesión.')
                ->send();
            exit;
        }
    }

    protected function getLayoutForRole(string $default = 'coord/layouts/mainCoord'): string
    {
        return (int) session()->get('rol') === 4 ? 'admin/layouts/mainAdmin' : $default;
    }
}
