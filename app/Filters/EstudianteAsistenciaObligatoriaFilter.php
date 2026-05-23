<?php

namespace App\Filters;

use App\Services\EstudianteAsistenciaService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Si el estudiante tiene práctica/servicio en progreso sin asistencia del día,
 * solo puede usar dashboard, prácticas y documentos vinculados hasta registrarla.
 */
class EstudianteAsistenciaObligatoriaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in') || (int) $session->get('rol') !== 4) {
            return null;
        }

        $segments = $request->getUri()->getSegments();
        if (($segments[0] ?? '') !== 'estudiante') {
            return null;
        }

        if ($this->rutaPermitidaConAsistenciaPendiente($segments)) {
            return null;
        }

        $pend = EstudianteAsistenciaService::pendientesAsistenciaHoy((int) $session->get('id_usuario'));
        if (!$pend['debe_registrar']) {
            return null;
        }

        return redirect()->to(site_url('estudiante/dashboard'))
            ->with('warning', 'Debes registrar tu asistencia del día (prácticas preprofesionales y/o servicio comunitario) antes de usar el resto del sistema.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    /**
     * @param list<string> $segments
     */
    private function rutaPermitidaConAsistenciaPendiente(array $segments): bool
    {
        $s1 = $segments[1] ?? '';

        if ($s1 === 'dashboard') {
            return true;
        }

        if ($s1 === 'practicas') {
            return true;
        }

        if ($s1 === 'documentos-practicas') {
            return true;
        }

        if ($s1 === 'documentos-servicio-comunitario') {
            return true;
        }

        return false;
    }
}
