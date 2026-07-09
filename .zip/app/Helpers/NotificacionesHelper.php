<?php

namespace App\Helpers;

use App\Models\NotificacionesModel;
use App\Models\UsuariosModel;

class NotificacionesHelper
{
    protected static $notificacionesModel;
    protected static $usuariosModel;

    /**
     * Obtener instancia del modelo de notificaciones
     */
    protected static function getNotificacionesModel()
    {
        if (!self::$notificacionesModel) {
            self::$notificacionesModel = new NotificacionesModel();
        }
        return self::$notificacionesModel;
    }

    /**
     * Obtener instancia del modelo de usuarios
     */
    protected static function getUsuariosModel()
    {
        if (!self::$usuariosModel) {
            self::$usuariosModel = new UsuariosModel();
        }
        return self::$usuariosModel;
    }

    /**
     * Crear notificación de asignación de práctica
     */
    public static function crearNotificacionAsignacionPractica($idEstudiante, $idDocente, $datosPractica)
    {
        $model = self::getNotificacionesModel();
        return $model->crearNotificacionAsignacionPractica($idEstudiante, $idDocente, $datosPractica);
    }

    /**
     * Crear notificación personalizada
     */
    public static function crearNotificacion($idDestinatario, $titulo, $mensaje, $tipo = 'general', $prioridad = 'media', $idReferencia = null, $tablaReferencia = null)
    {
        $model = self::getNotificacionesModel();
        
        $datos = [
            'ID_USUARIO_DESTINATARIO' => $idDestinatario,
            'ID_USUARIO_REMITENTE' => session()->get('id_usuario') ?? 1,
            'TITULO' => $titulo,
            'MENSAJE' => $mensaje,
            'TIPO_NOTIFICACION' => $tipo,
            'ID_REFERENCIA' => $idReferencia,
            'TABLA_REFERENCIA' => $tablaReferencia,
            'LEIDA' => 0,
            'PRIORIDAD' => $prioridad,
            'ACTIVA' => 1
        ];

        return $model->insert($datos);
    }

    /**
     * Crear notificación de recordatorio
     */
    public static function crearRecordatorio($idDestinatario, $titulo, $mensaje, $prioridad = 'media')
    {
        return self::crearNotificacion($idDestinatario, $titulo, $mensaje, 'recordatorio', $prioridad);
    }

    /**
     * Crear notificación general del sistema
     */
    public static function crearNotificacionSistema($idDestinatario, $titulo, $mensaje, $prioridad = 'media')
    {
        return self::crearNotificacion($idDestinatario, $titulo, $mensaje, 'general', $prioridad);
    }

    /**
     * Obtener notificaciones de un usuario
     */
    public static function obtenerNotificacionesUsuario($idUsuario, $limit = 10, $offset = 0)
    {
        $model = self::getNotificacionesModel();
        return $model->obtenerNotificacionesUsuario($idUsuario, $limit, $offset);
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public static function obtenerNotificacionesNoLeidas($idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->obtenerNotificacionesNoLeidas($idUsuario);
    }

    /**
     * Contar notificaciones no leídas de un usuario
     */
    public static function contarNoLeidas($idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->contarNoLeidas($idUsuario);
    }

    /**
     * Marcar notificación como leída
     */
    public static function marcarComoLeida($idNotificacion, $idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->marcarComoLeida($idNotificacion, $idUsuario);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public static function marcarTodasComoLeidas($idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->marcarTodasComoLeidas($idUsuario);
    }

    /**
     * Eliminar notificación
     */
    public static function eliminarNotificacion($idNotificacion, $idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->eliminarNotificacion($idNotificacion, $idUsuario);
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public static function obtenerEstadisticas($idUsuario)
    {
        $model = self::getNotificacionesModel();
        return $model->obtenerEstadisticas($idUsuario);
    }

    /**
     * Enviar notificación a múltiples usuarios
     */
    public static function enviarNotificacionMasiva($idsUsuarios, $titulo, $mensaje, $tipo = 'general', $prioridad = 'media')
    {
        $resultados = [];
        
        foreach ($idsUsuarios as $idUsuario) {
            $resultado = self::crearNotificacion($idUsuario, $titulo, $mensaje, $tipo, $prioridad);
            $resultados[] = [
                'id_usuario' => $idUsuario,
                'exitoso' => $resultado !== false,
                'id_notificacion' => $resultado
            ];
        }
        
        return $resultados;
    }

    /**
     * Enviar notificación a todos los usuarios de un rol
     */
    public static function enviarNotificacionPorRol($rol, $titulo, $mensaje, $tipo = 'general', $prioridad = 'media')
    {
        $usuariosModel = self::getUsuariosModel();
        
        // Obtener usuarios del rol específico
        $db = \Config\Database::connect();
        $builder = $db->table('TAB_USUARIOS u')
            ->select('u.ID_USUARIO')
            ->join('TAB_ROLES r', 'r.ID_USUARIO = u.ID_USUARIO')
            ->join('TAB_TIPOS_ROLES tr', 'tr.ID_TIPOS_ROLES = r.ID_TIPOS_ROLES')
            ->where('tr.ROL', $rol)
            ->where('u.ESTADO', '1');

        $usuarios = $builder->get()->getResultArray();
        $idsUsuarios = array_column($usuarios, 'ID_USUARIO');
        
        return self::enviarNotificacionMasiva($idsUsuarios, $titulo, $mensaje, $tipo, $prioridad);
    }

    /**
     * Crear notificación de bienvenida para nuevo usuario
     */
    public static function crearNotificacionBienvenida($idUsuario, $nombreUsuario)
    {
        $titulo = "¡Bienvenido al Sistema ITSI!";
        $mensaje = "Hola {$nombreUsuario},\n\n" .
                  "¡Bienvenido al Sistema de Gestión de Prácticas del Instituto Tecnológico Superior de Ibarra!\n\n" .
                  "Aquí podrás:\n" .
                  "• Gestionar tus prácticas preprofesionales\n" .
                  "• Realizar tu servicio comunitario\n" .
                  "• Subir y revisar documentos\n" .
                  "• Comunicarte con tutores y coordinadores\n\n" .
                  "¡Esperamos que tengas una excelente experiencia!";
        
        return self::crearNotificacionSistema($idUsuario, $titulo, $mensaje, 'media');
    }

    /**
     * Crear notificación de recordatorio de vencimiento
     */
    public static function crearRecordatorioVencimiento($idUsuario, $tipoDocumento, $fechaVencimiento)
    {
        $titulo = "Recordatorio: Documento próximo a vencer";
        $mensaje = "Tu documento '{$tipoDocumento}' vence el " . date('d/m/Y', strtotime($fechaVencimiento)) . ".\n\n" .
                  "Por favor, asegúrate de completarlo y subirlo antes de la fecha límite.";
        
        return self::crearRecordatorio($idUsuario, $titulo, $mensaje, 'alta');
    }

    /**
     * Crear notificación de documento aprobado
     */
    public static function crearNotificacionDocumentoAprobado($idUsuario, $tipoDocumento)
    {
        $titulo = "Documento Aprobado";
        $mensaje = "¡Excelente! Tu documento '{$tipoDocumento}' ha sido aprobado.\n\n" .
                  "Puedes continuar con el siguiente paso de tu práctica.";
        
        return self::crearNotificacionSistema($idUsuario, $titulo, $mensaje, 'media');
    }

    /**
     * Crear notificación de documento rechazado
     */
    public static function crearNotificacionDocumentoRechazado($idUsuario, $tipoDocumento, $observaciones = '')
    {
        $titulo = "Documento Requiere Correcciones";
        $mensaje = "Tu documento '{$tipoDocumento}' requiere algunas correcciones antes de ser aprobado.\n\n";
        
        if ($observaciones) {
            $mensaje .= "Observaciones:\n{$observaciones}\n\n";
        }
        
        $mensaje .= "Por favor, revisa los comentarios y vuelve a subir el documento corregido.";
        
        return self::crearNotificacionSistema($idUsuario, $titulo, $mensaje, 'alta');
    }

    /**
     * Limpiar notificaciones antiguas (más de 30 días)
     */
    public static function limpiarNotificacionesAntiguas()
    {
        $model = self::getNotificacionesModel();
        $fechaLimite = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        return $model->where('FECHA_CREACION <', $fechaLimite)
                    ->where('LEIDA', 1)
                    ->set(['ACTIVA' => 0])
                    ->update();
    }

    /**
     * Obtener resumen de notificaciones para dashboard
     */
    public static function obtenerResumenDashboard($idUsuario)
    {
        $model = self::getNotificacionesModel();
        
        $total = $model->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                      ->where('ACTIVA', 1)
                      ->countAllResults();
        
        $noLeidas = $model->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                         ->where('LEIDA', 0)
                         ->where('ACTIVA', 1)
                         ->countAllResults();
        
        $recientes = $model->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                          ->where('ACTIVA', 1)
                          ->orderBy('FECHA_CREACION', 'DESC')
                          ->limit(5)
                          ->findAll();
        
        return [
            'total' => $total,
            'no_leidas' => $noLeidas,
            'leidas' => $total - $noLeidas,
            'recientes' => $recientes
        ];
    }
}
