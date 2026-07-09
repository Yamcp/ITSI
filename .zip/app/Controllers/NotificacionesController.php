<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificacionesModel;
use App\Models\UsuariosModel;

class NotificacionesController extends BaseController
{
    protected $notificacionesModel;
    protected $usuariosModel;

    public function __construct()
    {
        $this->notificacionesModel = new NotificacionesModel();
        $this->usuariosModel = new UsuariosModel();
    }

    /**
     * Obtener notificaciones del usuario actual
     */
    public function index()
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $notificaciones = $this->notificacionesModel->obtenerNotificacionesUsuario($idUsuario, 20);
        $estadisticas = $this->notificacionesModel->obtenerEstadisticas($idUsuario);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'notificaciones' => $notificaciones,
                'estadisticas' => $estadisticas
            ]
        ]);
    }

    /**
     * Obtener notificaciones no leídas
     */
    public function noLeidas()
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $notificaciones = $this->notificacionesModel->obtenerNotificacionesNoLeidas($idUsuario);
        $contador = $this->notificacionesModel->contarNoLeidas($idUsuario);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'notificaciones' => $notificaciones,
                'contador' => $contador
            ]
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida($idNotificacion)
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $resultado = $this->notificacionesModel->marcarComoLeida($idNotificacion, $idUsuario);

        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al marcar la notificación'
            ]);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas()
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $resultado = $this->notificacionesModel->marcarTodasComoLeidas($idUsuario);

        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Todas las notificaciones han sido marcadas como leídas'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al marcar las notificaciones'
            ]);
        }
    }

    /**
     * Eliminar notificación
     */
    public function eliminar($idNotificacion)
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $resultado = $this->notificacionesModel->eliminarNotificacion($idNotificacion, $idUsuario);

        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notificación eliminada'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar la notificación'
            ]);
        }
    }

    /**
     * Obtener contador de notificaciones no leídas (para el header)
     */
    public function contador()
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'contador' => 0
            ]);
        }

        $contador = $this->notificacionesModel->contarNoLeidas($idUsuario);

        return $this->response->setJSON([
            'success' => true,
            'contador' => $contador
        ]);
    }

    /**
     * Vista de notificaciones para estudiantes
     */
    public function vistaEstudiante()
    {
        $idUsuario = session()->get('id_usuario');

        if (!$idUsuario) {
            return redirect()->to('/');
        }

        $notificaciones = $this->notificacionesModel->obtenerNotificacionesUsuario($idUsuario, 50);
        $estadisticas = $this->notificacionesModel->obtenerEstadisticas($idUsuario);

        $data = [
            'title' => 'Mis Notificaciones',
            'notificaciones' => $notificaciones,
            'estadisticas' => $estadisticas
        ];

        return view('estudiante/notificaciones/notificaciones', $data);
    }

    /**
     * Vista de notificaciones para docentes (solo asignaciones de tutoría).
     */
    public function vistaDocente()
    {
        $idUsuario = session()->get('id_usuario');

        if (!$idUsuario) {
            return redirect()->to('/');
        }

        // Rol 3 = Docente (1 admin, 2 coord, 3 docente, 4 estudiante)
        if ((int) (session()->get('rol') ?? 0) !== 3) {
            return redirect()->to('/');
        }

        $notificaciones = $this->notificacionesModel->obtenerNotificacionesTutoriaDocente((int) $idUsuario, 50);
        $estadisticas = $this->notificacionesModel->obtenerEstadisticasTutoria((int) $idUsuario);

        $data = [
            'title' => 'Asignaciones de tutoría - ITSI',
            'notificaciones' => $notificaciones,
            'estadisticas' => $estadisticas,
        ];

        return view('docente/notificaciones/notificaciones', $data);
    }

    /**
     * Obtener notificaciones por tipo
     */
    public function porTipo($tipo)
    {
        $idUsuario = session()->get('id_usuario');
        
        if (!$idUsuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
        }

        $notificaciones = $this->notificacionesModel->obtenerPorTipo($idUsuario, $tipo, 20);

        return $this->response->setJSON([
            'success' => true,
            'data' => $notificaciones
        ]);
    }
}
