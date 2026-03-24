<?php

namespace App\Services;

use Config\Database;

/**
 * Reglas de asistencia diaria para estudiantes con práctica o servicio en progreso.
 */
class EstudianteAsistenciaService
{
    private const ESTADO_PP_ACTIVO = 'En Progreso';

    private const ESTADO_SC_ACTIVO = 'En Progreso';

    public static function obtenerIdEstudiantePorUsuario(int $idUsuario): ?int
    {
        $db = Database::connect();
        $row = $db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario)
            ->get()
            ->getRowArray();

        return $row ? (int) $row['ID_ESTUDIANTE'] : null;
    }

    public static function tienePracticaPreprofesionalEnProgreso(int $idUsuario): bool
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return false;
        }
        $db = Database::connect();

        return $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_PRACTICA', self::ESTADO_PP_ACTIVO)
            ->countAllResults() > 0;
    }

    public static function tieneServicioComunitarioEnProgreso(int $idUsuario): bool
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return false;
        }
        $db = Database::connect();

        return $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_SERVICIO', self::ESTADO_SC_ACTIVO)
            ->countAllResults() > 0;
    }

    /**
     * @return array{debe_registrar: bool, items: list<array{tipo: string, id: int, etiqueta: string}>, fecha: string}
     */
    public static function pendientesAsistenciaHoy(int $idUsuario, ?string $fecha = null): array
    {
        $fechaDia = $fecha ?? date('Y-m-d');
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return ['debe_registrar' => false, 'items' => [], 'fecha' => $fechaDia];
        }

        $db = Database::connect();
        $items = [];

        $pps = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_PRACTICA', self::ESTADO_PP_ACTIVO)
            ->get()
            ->getResultArray();

        foreach ($pps as $pp) {
            $idP = (int) ($pp['ID_PRACTICA_PREPROFESIONAL'] ?? 0);
            if ($idP <= 0) {
                continue;
            }
            $n = $db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
                ->where('ID_PRACTICA_PREPROFESIONAL', $idP)
                ->where('FECHA_ASISTENCIA', $fechaDia)
                ->countAllResults();
            if ($n === 0) {
                $label = 'Práctica preprofesional';
                if (!empty($pp['PROYECTO_ESPECIFICO'])) {
                    $label .= ': ' . $pp['PROYECTO_ESPECIFICO'];
                } elseif (!empty($pp['AREA_ESPECIALIZACION'])) {
                    $label .= ': ' . $pp['AREA_ESPECIALIZACION'];
                }
                $items[] = [
                    'tipo' => 'preprofesional',
                    'id' => $idP,
                    'etiqueta' => $label,
                ];
            }
        }

        $scs = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_SERVICIO', self::ESTADO_SC_ACTIVO)
            ->get()
            ->getResultArray();

        foreach ($scs as $sc) {
            $idS = (int) ($sc['ID_SERVICIO_COMUNITARIO'] ?? 0);
            if ($idS <= 0) {
                continue;
            }
            $n = $db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
                ->where('ID_SERVICIO_COMUNITARIO', $idS)
                ->where('FECHA_ASISTENCIA', $fechaDia)
                ->countAllResults();
            if ($n === 0) {
                $label = 'Servicio comunitario';
                if (!empty($sc['PROYECTO_SOCIAL'])) {
                    $label .= ': ' . $sc['PROYECTO_SOCIAL'];
                }
                $items[] = [
                    'tipo' => 'servicio',
                    'id' => $idS,
                    'etiqueta' => $label,
                ];
            }
        }

        return [
            'debe_registrar' => $items !== [],
            'items' => $items,
            'fecha' => $fechaDia,
        ];
    }
}
