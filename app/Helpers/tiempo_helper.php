<?php

if (!function_exists('tiempo_transcurrido')) {
    /**
     * Calcula el tiempo transcurrido desde una fecha dada
     * 
     * @param string $fecha Fecha en formato Y-m-d H:i:s
     * @return string Tiempo transcurrido formateado
     */
    function tiempo_transcurrido($fecha)
    {
        if (empty($fecha)) {
            return 'Fecha no disponible';
        }

        try {
            $ahora = new \DateTime();
            $fechaReporte = new \DateTime($fecha);
            $diferencia = $ahora->diff($fechaReporte);

            if ($diferencia->days > 0) {
                return "Hace {$diferencia->days} día" . ($diferencia->days > 1 ? 's' : '');
            } elseif ($diferencia->h > 0) {
                return "Hace {$diferencia->h} hora" . ($diferencia->h > 1 ? 's' : '');
            } elseif ($diferencia->i > 0) {
                return "Hace {$diferencia->i} minuto" . ($diferencia->i > 1 ? 's' : '');
            } else {
                return "Hace unos segundos";
            }
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('formatear_fecha_relativa')) {
    /**
     * Formatea una fecha de manera relativa (hace X tiempo)
     * 
     * @param string $fecha Fecha en formato Y-m-d H:i:s
     * @param bool $mostrar_fecha_completa Si mostrar la fecha completa cuando es muy antigua
     * @return string Fecha formateada
     */
    function formatear_fecha_relativa($fecha, $mostrar_fecha_completa = true)
    {
        if (empty($fecha)) {
            return 'Fecha no disponible';
        }

        try {
            $ahora = new \DateTime();
            $fechaReporte = new \DateTime($fecha);
            $diferencia = $ahora->diff($fechaReporte);

            // Si es muy antigua (más de 30 días), mostrar fecha completa
            if ($diferencia->days > 30 && $mostrar_fecha_completa) {
                return $fechaReporte->format('d/m/Y H:i');
            }

            if ($diferencia->days > 0) {
                return "Hace {$diferencia->days} día" . ($diferencia->days > 1 ? 's' : '');
            } elseif ($diferencia->h > 0) {
                return "Hace {$diferencia->h} hora" . ($diferencia->h > 1 ? 's' : '');
            } elseif ($diferencia->i > 0) {
                return "Hace {$diferencia->i} minuto" . ($diferencia->i > 1 ? 's' : '');
            } else {
                return "Hace unos segundos";
            }
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('calcular_dias_restantes')) {
    /**
     * Calcula los días restantes hasta una fecha
     * 
     * @param string $fechaFin Fecha de fin en formato Y-m-d
     * @return string Días restantes formateados
     */
    function calcular_dias_restantes($fechaFin)
    {
        if (empty($fechaFin)) {
            return 'Fecha no disponible';
        }

        try {
            $ahora = new \DateTime();
            $fechaFin = new \DateTime($fechaFin);
            
            if ($fechaFin < $ahora) {
                $diferencia = $ahora->diff($fechaFin);
                return "Vencido hace {$diferencia->days} día" . ($diferencia->days > 1 ? 's' : '');
            } else {
                $diferencia = $ahora->diff($fechaFin);
                return "{$diferencia->days} día" . ($diferencia->days > 1 ? 's' : '') . " restantes";
            }
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }
}

if (!function_exists('formatear_periodo_academico_mes_anio')) {
    /**
     * Etiqueta legible del período: "Junio 2026 - Julio 2026".
     *
     * @param array<string, mixed>|null $periodo Fila de V_PERIODO_ACADEMICO_ACTUAL o TAB_PERIODOS_ACADEMICOS
     */
    function formatear_periodo_academico_mes_anio(?array $periodo): ?string
    {
        if ($periodo === null) {
            return null;
        }

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        $mi = (int) ($periodo['MES_INICIO'] ?? $periodo['mes_inicio'] ?? 0);
        $mf = (int) ($periodo['MES_FIN'] ?? $periodo['mes_fin'] ?? 0);
        $ai = $periodo['AÑO_INICIO'] ?? $periodo['año_inicio'] ?? $periodo['ano_inicio'] ?? null;
        $af = $periodo['AÑO_FIN'] ?? $periodo['año_fin'] ?? $periodo['ano_fin'] ?? null;

        if ($mi >= 1 && $mi <= 12 && $mf >= 1 && $mf <= 12
            && $ai !== null && $ai !== '' && $af !== null && $af !== '') {
            return $meses[$mi] . ' ' . $ai . ' - ' . $meses[$mf] . ' ' . $af;
        }

        $fechaIni = $periodo['FECHA_INICIO'] ?? $periodo['fecha_inicio'] ?? null;
        $fechaFin = $periodo['FECHA_FIN'] ?? $periodo['fecha_fin'] ?? null;

        if (!empty($fechaIni) && !empty($fechaFin)) {
            try {
                $di = new \DateTime((string) $fechaIni);
                $df = new \DateTime((string) $fechaFin);
                $mi = (int) $di->format('n');
                $mf = (int) $df->format('n');
                $ai = $di->format('Y');
                $af = $df->format('Y');

                if ($mi >= 1 && $mi <= 12 && $mf >= 1 && $mf <= 12) {
                    return $meses[$mi] . ' ' . $ai . ' - ' . $meses[$mf] . ' ' . $af;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }
}

if (!function_exists('obtener_periodo_academico_para_ui')) {
    /**
     * Etiqueta del período para navbar/dashboard.
     *
     * En cada invocación verifica contra la BD si el período actual cambió
     * y auto-sincroniza la sesión cuando corresponde.
     *
     * Si el coordinador seleccionó manualmente un período anterior
     * (clave de sesión `periodo_academico_id_seleccionado`), se respeta esa elección.
     *
     * @return array{nombre: ?string, rango: string, es_historico: bool, id: ?int}
     */
    function obtener_periodo_academico_para_ui(): array
    {
        try {
            $db  = \Config\Database::connect();
            $row = $db->query('SELECT * FROM V_PERIODO_ACADEMICO_ACTUAL LIMIT 1')->getRowArray();
        } catch (\Throwable $e) {
            log_message('error', 'obtener_periodo_academico_para_ui: ' . $e->getMessage());
            return [
                'nombre'       => session('periodo_academico_nombre'),
                'rango'        => (string) (session('periodo_academico_rango') ?? ''),
                'es_historico' => false,
                'id'           => session('periodo_academico_id') ? (int) session('periodo_academico_id') : null,
            ];
        }

        // ── Auto-sincronización: si el período actual en BD difiere del de sesión ──
        $idActualBD    = $row ? (int) ($row['ID_PERIODO_ACADEMICO'] ?? 0) : 0;
        $idEnSesion    = session('periodo_academico_id') ? (int) session('periodo_academico_id') : 0;
        $idSeleccionado = session('periodo_academico_id_seleccionado')
            ? (int) session('periodo_academico_id_seleccionado')
            : 0;

        // Si no hay selección manual o la selección manual era el período anterior
        // que ahora fue reemplazado, actualizamos al nuevo período actual.
        if ($idActualBD > 0 && $idSeleccionado === 0 && $idActualBD !== $idEnSesion) {
            // Período nuevo detectado → actualizar sesión
            $fmt = formatear_periodo_academico_mes_anio($row);
            session()->set([
                'periodo_academico_id'     => $idActualBD,
                'periodo_academico_nombre' => $fmt ?? ($row['NOMBRE_PERIODO'] ?? null),
                'periodo_academico_rango'  => '',
                'periodo_academico_anio'   => $row['AÑO_ACADEMICO'] ?? $row['AÑO_INICIO'] ?? null,
            ]);
            log_message('info', "Período académico auto-actualizado en sesión: {$idEnSesion} → {$idActualBD}");
        }

        // ── Determinar qué mostrar ──
        $esHistorico = false;

        if ($idSeleccionado > 0 && $idSeleccionado !== $idActualBD) {
            // Coordinador está consultando un período anterior
            $esHistorico = true;
        }

        // Refrescar nombre si es necesario (formato antiguo)
        $periodoNombre = session('periodo_academico_nombre');
        $periodoRango  = (string) (session('periodo_academico_rango') ?? '');

        $necesitaRefresh = !$periodoNombre || (
            preg_match('/^\d{2}\/\d{4}\s*-\s*\d{2}\/\d{4}$/', trim((string) $periodoNombre))
            || str_contains(strtolower((string) $periodoNombre), ' hasta ')
        );

        if ($necesitaRefresh && $row) {
            $fmt = formatear_periodo_academico_mes_anio($row);
            if ($fmt !== null) {
                session()->set([
                    'periodo_academico_nombre' => $fmt,
                    'periodo_academico_rango'  => '',
                ]);
                $periodoNombre = $fmt;
                $periodoRango  = '';
            } else {
                $periodoNombre = $row['NOMBRE_PERIODO'] ?? $row['nombre_periodo'] ?? null;
                $periodoRango  = '';
                $fi = $row['FECHA_INICIO'] ?? $row['fecha_inicio'] ?? null;
                $ff = $row['FECHA_FIN'] ?? $row['fecha_fin'] ?? null;
                if ($fi && $ff) {
                    $periodoRango = $fi . ' - ' . $ff;
                }
                session()->set([
                    'periodo_academico_nombre' => $periodoNombre,
                    'periodo_academico_rango'  => $periodoRango,
                ]);
            }
        }

        return [
            'nombre'       => $periodoNombre,
            'rango'        => $periodoRango,
            'es_historico'  => $esHistorico,
            'id'           => session('periodo_academico_id') ? (int) session('periodo_academico_id') : null,
        ];
    }
}

if (!function_exists('obtener_todos_los_periodos')) {
    /**
     * Devuelve todos los períodos académicos ordenados (más reciente primero)
     * con etiquetas legibles. Usado por el selector del coordinador.
     *
     * @return array<int, array{id: int, nombre: string, es_actual: bool}>
     */
    function obtener_todos_los_periodos(): array
    {
        try {
            $db = \Config\Database::connect();
            $periodos = $db->query('SELECT * FROM V_PERIODOS_ACADEMICOS_ORDENADOS')->getResultArray();
            $actual   = $db->query('SELECT ID_PERIODO_ACADEMICO FROM V_PERIODO_ACADEMICO_ACTUAL LIMIT 1')->getRowArray();
            $idActual = $actual ? (int) $actual['ID_PERIODO_ACADEMICO'] : 0;

            $lista = [];
            foreach ($periodos as $p) {
                $etiqueta = formatear_periodo_academico_mes_anio($p);
                $lista[] = [
                    'id'        => (int) $p['ID_PERIODO_ACADEMICO'],
                    'nombre'    => $etiqueta ?? ($p['NOMBRE_PERIODO'] ?? ''),
                    'es_actual' => ((int) $p['ID_PERIODO_ACADEMICO'] === $idActual),
                ];
            }
            return $lista;
        } catch (\Throwable $e) {
            log_message('error', 'obtener_todos_los_periodos: ' . $e->getMessage());
            return [];
        }
    }
}
