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

    /**
     * Items de prácticas preprofesionales en progreso (para registrar asistencia en fechas faltantes).
     *
     * @return list<array{tipo: string, id: int, etiqueta: string}>
     */
    public static function itemsPreprofesionalesEnProgreso(int $idUsuario): array
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return [];
        }

        $db = Database::connect();

        $pps = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_PRACTICA', self::ESTADO_PP_ACTIVO)
            ->get()
            ->getResultArray();

        $items = [];
        foreach ($pps as $pp) {
            $idP = (int) ($pp['ID_PRACTICA_PREPROFESIONAL'] ?? 0);
            if ($idP <= 0) {
                continue;
            }

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

        return $items;
    }

    /**
     * Items de servicio comunitario en progreso (para registrar asistencia en fechas faltantes).
     *
     * @return list<array{tipo: string, id: int, etiqueta: string}>
     */
    public static function itemsServiciosComunitariosEnProgreso(int $idUsuario): array
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return [];
        }

        $db = Database::connect();

        $scs = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_SERVICIO', self::ESTADO_SC_ACTIVO)
            ->get()
            ->getResultArray();

        $items = [];
        foreach ($scs as $sc) {
            $idS = (int) ($sc['ID_SERVICIO_COMUNITARIO'] ?? 0);
            if ($idS <= 0) {
                continue;
            }

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

        return $items;
    }

    /**
     * Resumen solo prácticas preprofesionales en progreso vs asistencias registradas en una fecha.
     *
     * @return array{en_progreso: int, pendientes_hoy: int, registradas_hoy: int}
     */
    public static function resumenPreprofesionalDia(int $idUsuario, ?string $fecha = null): array
    {
        $fechaDia = $fecha ?? date('Y-m-d');
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return ['en_progreso' => 0, 'pendientes_hoy' => 0, 'registradas_hoy' => 0];
        }

        $db = Database::connect();
        $pps = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_PRACTICA', self::ESTADO_PP_ACTIVO)
            ->get()
            ->getResultArray();

        $enProgreso = count($pps);
        $pendientes = 0;

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
                $pendientes++;
            }
        }

        return [
            'en_progreso' => $enProgreso,
            'pendientes_hoy' => $pendientes,
            'registradas_hoy' => max(0, $enProgreso - $pendientes),
        ];
    }

    /**
     * Horas requeridas vs cumplidas para prácticas preprofesionales en estado «En Progreso».
     * Cumplidas = suma de TIMESTAMPDIFF en asistencias + HORAS_CUMPLIDAS en seguimiento (mismo criterio que PracticasEstudianteController).
     *
     * @return array{requeridas: int, cumplidas: int, restantes: int, porcentaje: int}|null null si no hay práctica activa
     */
    public static function horasPreprofesionalesEnProgreso(int $idUsuario): ?array
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return null;
        }

        $db = Database::connect();
        $pps = $db->table('TAB_PRACTICAS_PREPROFESIONALES')
            ->select('ID_PRACTICA_PREPROFESIONAL, HORAS_PRACTICAS')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_PRACTICA', self::ESTADO_PP_ACTIVO)
            ->get()
            ->getResultArray();

        if ($pps === []) {
            return null;
        }

        $requeridas = 0;
        $cumplidas = 0;
        foreach ($pps as $pp) {
            $idP = (int) ($pp['ID_PRACTICA_PREPROFESIONAL'] ?? 0);
            if ($idP <= 0) {
                continue;
            }
            $requeridas += (int) ($pp['HORAS_PRACTICAS'] ?? 0);
            $cumplidas += self::horasCumplidasPorPracticaPreprofesional($db, $idP);
        }

        if ($requeridas <= 0) {
            return null;
        }

        $restantes = max(0, $requeridas - $cumplidas);
        $basePct = min($cumplidas, $requeridas) / $requeridas;

        return [
            'requeridas' => $requeridas,
            'cumplidas' => $cumplidas,
            'restantes' => $restantes,
            'porcentaje' => (int) min(100, max(0, round(100 * $basePct))),
        ];
    }

    private static function horasCumplidasPorPracticaPreprofesional($db, int $idPractica): int
    {
        // No usar selectSum(TIMESTAMPDIFF(...)): CI4 prohíbe comas en el argumento de selectSum.
        $asist = $db->table('TAB_ASISTENCIAS_PRACTICAS_PREPROFESIONALES')
            ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
            ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
            ->get()
            ->getRow();
        $seg = $db->table('TAB_SEGUIMIENTO_PRACTICAS_PREPROFESIONALES')
            ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
            ->where('ID_PRACTICA_PREPROFESIONAL', $idPractica)
            ->get()
            ->getRow();

        return (int) ($asist->total_horas ?? 0) + (int) ($seg->total_horas ?? 0);
    }

    /**
     * Horas requeridas vs cumplidas para servicio comunitario en estado «En Progreso».
     * Cumplidas = suma de TIMESTAMPDIFF en asistencias + HORAS_CUMPLIDAS en seguimiento.
     *
     * @return array{requeridas: int, cumplidas: int, restantes: int, porcentaje: int}|null
     */
    public static function horasServicioComunitarioEnProgreso(int $idUsuario): ?array
    {
        $idEst = self::obtenerIdEstudiantePorUsuario($idUsuario);
        if ($idEst === null) {
            return null;
        }

        $db = Database::connect();
        $scs = $db->table('TAB_SERVICIO_COMUNITARIO')
            ->select('ID_SERVICIO_COMUNITARIO, HORAS_SERVICIO')
            ->where('ID_ESTUDIANTE', $idEst)
            ->where('ESTADO_SERVICIO', self::ESTADO_SC_ACTIVO)
            ->get()
            ->getResultArray();

        if ($scs === []) {
            return null;
        }

        $requeridas = 0;
        $cumplidas = 0;
        foreach ($scs as $sc) {
            $idS = (int) ($sc['ID_SERVICIO_COMUNITARIO'] ?? 0);
            if ($idS <= 0) {
                continue;
            }
            $requeridas += (int) ($sc['HORAS_SERVICIO'] ?? 0);
            $cumplidas += self::horasCumplidasPorServicioComunitario($db, $idS);
        }

        if ($requeridas <= 0) {
            return null;
        }

        $restantes = max(0, $requeridas - $cumplidas);
        $basePct = min($cumplidas, $requeridas) / $requeridas;

        return [
            'requeridas' => $requeridas,
            'cumplidas' => $cumplidas,
            'restantes' => $restantes,
            'porcentaje' => (int) min(100, max(0, round(100 * $basePct))),
        ];
    }

    private static function horasCumplidasPorServicioComunitario($db, int $idServicio): int
    {
        // No usar selectSum(TIMESTAMPDIFF(...)): CI4 prohíbe comas en el argumento de selectSum.
        $asist = $db->table('TAB_ASISTENCIAS_SERVICIO_COMUNITARIO')
            ->select('SUM(TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)) AS total_horas', false)
            ->where('ID_SERVICIO_COMUNITARIO', $idServicio)
            ->get()
            ->getRow();
        $seg = $db->table('TAB_SEGUIMIENTO_SERVICIO_COMUNITARIO')
            ->selectSum('HORAS_CUMPLIDAS', 'total_horas')
            ->where('ID_SERVICIO_COMUNITARIO', $idServicio)
            ->get()
            ->getRow();

        return (int) ($asist->total_horas ?? 0) + (int) ($seg->total_horas ?? 0);
    }
}
