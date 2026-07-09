<?php

namespace App\Filters;

use App\Models\UsuariosModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Bloquea el acceso si el usuario no tiene un rol válido en TAB_ROLES.
 */
class AuthRolFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Debe iniciar sesión para continuar.');
        }

        $idUsuario = (int) $session->get('id_usuario');
        if ($idUsuario <= 0) {
            return $this->cerrarPorRolInvalido();
        }

        $usuarioModel = new UsuariosModel();
        $rolData = $usuarioModel->obtenerRolActivoPorUsuarioId($idUsuario);

        if (!$rolData) {
            return $this->cerrarPorRolInvalido();
        }

        if ((int) $session->get('rol') !== (int) $rolData['rol']) {
            $session->set('rol', (int) $rolData['rol']);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function cerrarPorRolInvalido()
    {
        session()->setFlashdata(
            'error',
            'Su cuenta no tiene un rol asignado. Contacte al administrador o coordinador para solicitar acceso al sistema.'
        );

        return redirect()->to('/auth/cerrar-sesion');
    }
}
