<?php

namespace App\Controllers;

use Config\Database;

/**
 * Controlador API para períodos académicos.
 * Solo usuarios con rol Coordinador (2) pueden cambiar el período de consulta.
 * Cualquier usuario autenticado puede listar períodos (para auto-sincronización).
 */
class PeriodoAcademicoController extends BaseController
{
    /**
     * GET /api/periodos
     * Devuelve la lista de todos los períodos académicos ordenados (más reciente primero)
     * y cuál es el período actual y el seleccionado en sesión.
     */
    public function listarPeriodos()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'No autenticado']);
        }

        try {
            $db = Database::connect();
            $periodos = $db->table('TAB_PERIODOS_ACADEMICOS')
                ->orderBy('AÑO_INICIO', 'DESC')
                ->orderBy('MES_INICIO', 'DESC')
                ->get()
                ->getResultArray();

            $actual = $periodos[0] ?? null;
            $idActual = $actual['ID_PERIODO_ACADEMICO'] ?? null;
            $idSeleccionado = session()->get('periodo_academico_id_seleccionado')
                ?? session()->get('periodo_academico_id')
                ?? $idActual;

            // Formatear etiquetas legibles
            helper('tiempo');
            $lista = [];
            foreach ($periodos as $p) {
                $etiqueta = formatear_periodo_academico_mes_anio($p);
                $lista[] = [
                    'id'        => (int) $p['ID_PERIODO_ACADEMICO'],
                    'nombre'    => $etiqueta ?? $p['NOMBRE_PERIODO'],
                    'es_actual' => ((int) $p['ID_PERIODO_ACADEMICO'] === (int) $idActual),
                ];
            }

            return $this->response->setJSON([
                'periodos'        => $lista,
                'id_actual'       => $idActual ? (int) $idActual : null,
                'id_seleccionado' => $idSeleccionado ? (int) $idSeleccionado : null,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'PeriodoAcademicoController::listarPeriodos: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error interno']);
        }
    }

    /**
     * POST /api/periodos/cambiar
     * Solo administradores. Guarda en sesión el período seleccionado para consulta.
     */
    public function cambiarPeriodo()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'No autenticado']);
        }

        if ((int) session()->get('rol') !== 2) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Solo coordinadores pueden cambiar el período de consulta']);
        }

        $idPeriodo = (int) $this->request->getPost('id_periodo');
        if ($idPeriodo <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID de período inválido']);
        }

        try {
            $db = Database::connect();
            $periodo = $db->table('TAB_PERIODOS_ACADEMICOS')
                ->where('ID_PERIODO_ACADEMICO', $idPeriodo)
                ->get(1)
                ->getRowArray();

            if (!$periodo) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Período no encontrado']);
            }

            helper('tiempo');
            $etiqueta = formatear_periodo_academico_mes_anio($periodo);

            session()->set([
                'periodo_academico_id_seleccionado' => $idPeriodo,
                'periodo_academico_id'              => $idPeriodo,
                'periodo_academico_nombre'          => $etiqueta ?? ($periodo['NOMBRE_PERIODO'] ?? ''),
                'periodo_academico_rango'           => '',
                'periodo_academico_anio'            => $periodo['AÑO_INICIO'] ?? null,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'periodo' => [
                    'id'     => $idPeriodo,
                    'nombre' => $etiqueta ?? ($periodo['NOMBRE_PERIODO'] ?? ''),
                ],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'PeriodoAcademicoController::cambiarPeriodo: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error interno']);
        }
    }

    /**
     * POST /api/periodos/restaurar
     * Solo administradores. Elimina la selección manual y vuelve al período actual.
     */
    public function restaurarPeriodoActual()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'No autenticado']);
        }

        if ((int) session()->get('rol') !== 2) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Solo coordinadores']);
        }

        session()->remove('periodo_academico_id_seleccionado');

        // Forzar re-sincronización
        session()->remove('periodo_academico_nombre');

        helper('tiempo');
        $p = obtener_periodo_academico_para_ui();

        return $this->response->setJSON([
            'success' => true,
            'periodo' => [
                'nombre' => $p['nombre'],
                'rango'  => $p['rango'],
            ],
        ]);
    }
}
