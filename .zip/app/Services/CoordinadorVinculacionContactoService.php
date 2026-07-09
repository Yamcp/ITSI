<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

/**
 * Datos de contacto del coordinador de vinculación para enlaces WhatsApp (estudiante).
 */
class CoordinadorVinculacionContactoService
{
    /**
     * Primer usuario activo con rol de coordinación (ID_TIPOS_ROLES = 1) que tenga celular.
     *
     * @return array{numero: string, nombre: string}|null
     */
    public static function obtenerDatosWhatsapp(BaseConnection $db): ?array
    {
        try {
            $row = $db->table('TAB_USUARIOS u')
                ->select('dp.CELULAR, dp.NOMBRE, dp.APELLIDO')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
                ->join('TAB_ROLES r', 'r.ID_USUARIO = u.ID_USUARIO')
                ->join('TAB_TIPOS_ROLES tr', 'tr.ID_TIPOS_ROLES = r.ID_TIPOS_ROLES')
                ->where('tr.ID_TIPOS_ROLES', 1)
                ->where('u.ESTADO', '1')
                ->orderBy('u.ID_USUARIO', 'ASC')
                ->limit(1)
                ->get()
                ->getRowArray();

            if ($row === null || empty(trim((string) ($row['CELULAR'] ?? '')))) {
                return null;
            }

            $digits = preg_replace('/\D/', '', trim((string) $row['CELULAR']));
            if (strlen($digits) < 9) {
                return null;
            }

            $nombre = trim((string) ($row['NOMBRE'] ?? '') . ' ' . (string) ($row['APELLIDO'] ?? ''));
            if ($nombre === '') {
                $nombre = 'Coordinación de vinculación';
            }

            return [
                'numero' => $digits,
                'nombre' => $nombre,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'CoordinadorVinculacionContactoService: ' . $e->getMessage());

            return null;
        }
    }

    public static function urlWhatsapp(string $numeroDigits, string $mensaje = ''): string
    {
        $base = 'https://wa.me/' . $numeroDigits;

        return $mensaje !== '' ? ($base . '?text=' . rawurlencode($mensaje)) : $base;
    }

    /**
     * Variables listas para la vista del estudiante (dashboard / perfil).
     *
     * @return array{coordinador_whatsapp_url: string, coordinador_whatsapp_nombre: string}
     */
    public static function datosParaVistaEstudiante(BaseConnection $db): array
    {
        $wa = self::obtenerDatosWhatsapp($db);
        if ($wa === null) {
            return [
                'coordinador_whatsapp_url' => '',
                'coordinador_whatsapp_nombre' => '',
            ];
        }

        $nombreEst = trim((string) (session('nombre') ?? '') . ' ' . (string) (session('apellido') ?? ''));
        $msg = 'Hola, soy ' . ($nombreEst !== '' ? $nombreEst : 'un estudiante') . ' del ITSI y escribo desde el portal del Departamento de Vinculación. ';

        return [
            'coordinador_whatsapp_url' => self::urlWhatsapp($wa['numero'], $msg),
            'coordinador_whatsapp_nombre' => $wa['nombre'],
        ];
    }
}
