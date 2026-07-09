<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionesModel extends Model
{
    protected $table = 'TAB_NOTIFICACIONES';
    protected $primaryKey = 'ID_NOTIFICACION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_USUARIO_DESTINATARIO',
        'ID_USUARIO_REMITENTE',
        'TITULO',
        'MENSAJE',
        'TIPO_NOTIFICACION',
        'ID_REFERENCIA',
        'TABLA_REFERENCIA',
        'LEIDA',
        'FECHA_LEIDA',
        'PRIORIDAD',
        'ACTIVA'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ID_USUARIO_DESTINATARIO' => 'required|integer',
        'TITULO' => 'required|max_length[200]',
        'MENSAJE' => 'required|max_length[1000]',
        'TIPO_NOTIFICACION' => 'required|in_list[asignacion_practica,tutoria_asignada,recordatorio,general]',
        'PRIORIDAD' => 'required|in_list[alta,media,baja]'
    ];

    protected $validationMessages = [
        'ID_USUARIO_DESTINATARIO' => [
            'required' => 'El destinatario es obligatorio',
            'integer' => 'El ID del destinatario debe ser un número entero'
        ],
        'TITULO' => [
            'required' => 'El título es obligatorio',
            'max_length' => 'El título no puede exceder 200 caracteres'
        ],
        'MENSAJE' => [
            'required' => 'El mensaje es obligatorio',
            'max_length' => 'El mensaje no puede exceder 1000 caracteres'
        ],
        'TIPO_NOTIFICACION' => [
            'required' => 'El tipo de notificación es obligatorio',
            'in_list' => 'Tipo de notificación no válido'
        ],
        'PRIORIDAD' => [
            'required' => 'La prioridad es obligatoria',
            'in_list' => 'Prioridad no válida'
        ]
    ];

    /**
     * Crear notificación de asignación de práctica
     */
    public function crearNotificacionAsignacionPractica($idEstudiante, $idDocente, $datosPractica)
    {
        // Notificación para el estudiante
        $notificacionEstudiante = [
            'ID_USUARIO_DESTINATARIO' => $idEstudiante,
            'ID_USUARIO_REMITENTE' => session()->get('id_usuario') ?? 1, // Coordinador que asigna
            'TITULO' => 'Nueva Práctica Asignada',
            'MENSAJE' => $this->generarMensajeEstudiante($datosPractica),
            'TIPO_NOTIFICACION' => 'asignacion_practica',
            'ID_REFERENCIA' => $datosPractica['id_practica'],
            'TABLA_REFERENCIA' => $datosPractica['tipo'] == 'preprofesional' ? 'TAB_PRACTICAS_PREPROFESIONALES' : 'TAB_SERVICIO_COMUNITARIO',
            'LEIDA' => 0,
            'PRIORIDAD' => 'alta',
            'ACTIVA' => 1
        ];

        // Notificación para el docente (tutor)
        $notificacionDocente = [
            'ID_USUARIO_DESTINATARIO' => $idDocente,
            'ID_USUARIO_REMITENTE' => session()->get('id_usuario') ?? 1, // Coordinador que asigna
            'TITULO' => 'Nueva tutoría asignada',
            'MENSAJE' => $this->generarMensajeDocente($datosPractica),
            'TIPO_NOTIFICACION' => 'tutoria_asignada',
            'ID_REFERENCIA' => $datosPractica['id_practica'],
            'TABLA_REFERENCIA' => $datosPractica['tipo'] == 'preprofesional' ? 'TAB_PRACTICAS_PREPROFESIONALES' : 'TAB_SERVICIO_COMUNITARIO',
            'LEIDA' => 0,
            'PRIORIDAD' => 'alta',
            'ACTIVA' => 1
        ];

        $this->db->transStart();
        
        try {
            // Insertar notificación para estudiante
            $this->insert($notificacionEstudiante);
            
            // Insertar notificación para docente
            $this->insert($notificacionDocente);
            
            $this->db->transComplete();
            
            return $this->db->transStatus() !== false;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error creando notificaciones: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generar mensaje para el estudiante
     */
    private function generarMensajeEstudiante($datosPractica)
    {
        $tipoPractica = $datosPractica['tipo'] == 'preprofesional' ? 'Práctica Preprofesional' : 'Servicio Comunitario';
        
        return "Se te ha asignado una nueva {$tipoPractica}:\n\n" .
               "📋 Institución: {$datosPractica['institucion']}\n" .
               "📅 Período: " . $this->formatoPeriodoPracticaNotificacion($datosPractica) . "\n" .
               "⏰ Horas: {$datosPractica['horas']} horas\n" .
               "👨‍🏫 Tutor: {$datosPractica['tutor']}\n" .
               "📝 Descripción: {$datosPractica['descripcion']}\n\n" .
               "Revisa los detalles en tu panel de prácticas.";
    }

    /**
     * Generar mensaje para el docente
     */
    private function generarMensajeDocente($datosPractica)
    {
        $tipoPractica = ($datosPractica['tipo'] ?? '') === 'preprofesional'
            ? 'prácticas preprofesionales'
            : 'servicio comunitario';
        $estudiante = $datosPractica['estudiante'] ?? 'un estudiante';

        return "Has sido asignado como tutor de {$estudiante} en {$tipoPractica}.";
    }

    /**
     * Obtener notificaciones de tutoría asignada para un docente,
     * con nombre, carrera y semestre del estudiante asignado.
     */
    public function obtenerNotificacionesTutoriaDocente($idUsuario, $limit = 50)
    {
        $db = \Config\Database::connect();
        $limit = max(1, (int) $limit);

        $sql = "
            SELECT
                n.*,
                COALESCE(
                    CONCAT(dp_pp.NOMBRE, ' ', dp_pp.APELLIDO),
                    CONCAT(dp_sc.NOMBRE, ' ', dp_sc.APELLIDO)
                ) AS ESTUDIANTE_NOMBRE,
                COALESCE(c_pp.NOMBRE, c_sc.NOMBRE) AS ESTUDIANTE_CARRERA,
                COALESCE(e_pp.SEMESTRE_ACTUAL, e_sc.SEMESTRE_ACTUAL) AS ESTUDIANTE_SEMESTRE,
                CASE
                    WHEN n.TABLA_REFERENCIA = 'TAB_SERVICIO_COMUNITARIO' THEN 'servicio'
                    ELSE 'preprofesional'
                END AS MODALIDAD
            FROM TAB_NOTIFICACIONES n
            LEFT JOIN TAB_PRACTICAS_PREPROFESIONALES pp
                ON n.TABLA_REFERENCIA = 'TAB_PRACTICAS_PREPROFESIONALES'
               AND n.ID_REFERENCIA = pp.ID_PRACTICA_PREPROFESIONAL
            LEFT JOIN TAB_ESTUDIANTES e_pp ON e_pp.ID_ESTUDIANTE = pp.ID_ESTUDIANTE
            LEFT JOIN TAB_DATOS_PERSONAS dp_pp ON dp_pp.ID_DATO_PERSONA = e_pp.ID_DATO_PERSONA
            LEFT JOIN TAB_CARRERAS c_pp ON c_pp.ID_CARRERA = e_pp.ID_CARRERA
            LEFT JOIN TAB_SERVICIO_COMUNITARIO sc
                ON n.TABLA_REFERENCIA = 'TAB_SERVICIO_COMUNITARIO'
               AND n.ID_REFERENCIA = sc.ID_SERVICIO_COMUNITARIO
            LEFT JOIN TAB_ESTUDIANTES e_sc ON e_sc.ID_ESTUDIANTE = sc.ID_ESTUDIANTE
            LEFT JOIN TAB_DATOS_PERSONAS dp_sc ON dp_sc.ID_DATO_PERSONA = e_sc.ID_DATO_PERSONA
            LEFT JOIN TAB_CARRERAS c_sc ON c_sc.ID_CARRERA = e_sc.ID_CARRERA
            WHERE n.ID_USUARIO_DESTINATARIO = ?
              AND n.TIPO_NOTIFICACION = 'tutoria_asignada'
              AND n.ACTIVA = 1
            ORDER BY n.FECHA_CREACION DESC
            LIMIT {$limit}
        ";

        return $db->query($sql, [(int) $idUsuario])->getResultArray();
    }

    /**
     * Estadísticas solo de notificaciones de tutoría.
     */
    public function obtenerEstadisticasTutoria($idUsuario)
    {
        $total = $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
            ->where('TIPO_NOTIFICACION', 'tutoria_asignada')
            ->where('ACTIVA', 1)
            ->countAllResults();

        $noLeidas = $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
            ->where('TIPO_NOTIFICACION', 'tutoria_asignada')
            ->where('ACTIVA', 1)
            ->where('LEIDA', 0)
            ->countAllResults();

        return [
            'total' => $total,
            'no_leidas' => $noLeidas,
            'leidas' => max(0, $total - $noLeidas),
        ];
    }

    /**
     * Texto de período para notificaciones (fecha fin opcional).
     */
    private function formatoPeriodoPracticaNotificacion(array $datosPractica): string
    {
        $ini = $datosPractica['fecha_inicio'] ?? '';
        $fin = $datosPractica['fecha_fin'] ?? '';
        if ($ini === '' || $ini === null) {
            return 'Por definir';
        }
        $iniFmt = date('d/m/Y', strtotime((string) $ini));
        if ($fin === '' || $fin === null) {
            return "desde {$iniFmt} (sin fecha fin registrada)";
        }
        return $iniFmt . ' - ' . date('d/m/Y', strtotime((string) $fin));
    }

    /**
     * Obtener notificaciones de un usuario
     */
    public function obtenerNotificacionesUsuario($idUsuario, $limit = 10, $offset = 0)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('ACTIVA', 1)
                   ->orderBy('FECHA_CREACION', 'DESC')
                   ->limit($limit, $offset)
                   ->findAll();
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function obtenerNotificacionesNoLeidas($idUsuario)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('LEIDA', 0)
                   ->where('ACTIVA', 1)
                   ->orderBy('FECHA_CREACION', 'DESC')
                   ->findAll();
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida($idNotificacion, $idUsuario)
    {
        return $this->where('ID_NOTIFICACION', $idNotificacion)
                   ->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->set([
                       'LEIDA' => 1,
                       'FECHA_LEIDA' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasComoLeidas($idUsuario)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('LEIDA', 0)
                   ->set([
                       'LEIDA' => 1,
                       'FECHA_LEIDA' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNoLeidas($idUsuario)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('LEIDA', 0)
                   ->where('ACTIVA', 1)
                   ->countAllResults();
    }

    /**
     * Contar notificaciones no leídas filtradas por tipo (p. ej. tutoria_asignada).
     */
    public function contarNoLeidasPorTipo($idUsuario, string $tipo)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('LEIDA', 0)
                   ->where('ACTIVA', 1)
                   ->where('TIPO_NOTIFICACION', $tipo)
                   ->countAllResults();
    }

    /**
     * Eliminar notificación (soft delete)
     */
    public function eliminarNotificacion($idNotificacion, $idUsuario)
    {
        return $this->where('ID_NOTIFICACION', $idNotificacion)
                   ->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->set(['ACTIVA' => 0])
                   ->update();
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public function obtenerEstadisticas($idUsuario)
    {
        $total = $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                     ->where('ACTIVA', 1)
                     ->countAllResults();
        
        $noLeidas = $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                        ->where('LEIDA', 0)
                        ->where('ACTIVA', 1)
                        ->countAllResults();
        
        $leidas = $total - $noLeidas;
        
        return [
            'total' => $total,
            'leidas' => $leidas,
            'no_leidas' => $noLeidas
        ];
    }

    /**
     * Obtener notificaciones por tipo
     */
    public function obtenerPorTipo($idUsuario, $tipo, $limit = 10)
    {
        return $this->where('ID_USUARIO_DESTINATARIO', $idUsuario)
                   ->where('TIPO_NOTIFICACION', $tipo)
                   ->where('ACTIVA', 1)
                   ->orderBy('FECHA_CREACION', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
