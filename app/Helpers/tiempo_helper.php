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
