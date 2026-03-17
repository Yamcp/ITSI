<?php

namespace App\Controllers\estudiante;

use App\Models\InstitucionesConveniosModel;
use App\Models\DetallesConveniosModel;
use App\Models\TiposInstitucionesModel;
use App\Controllers\BaseController;

class InstitucionesConveniosController extends BaseController
{
    protected $institucionesModel;
    protected $conveniosModel;
    protected $tiposInstitucionesModel;

    public function __construct()
    {
        $this->institucionesModel = new \App\Models\InstitucionesConveniosModel();
        $this->conveniosModel = new \App\Models\DetallesConveniosModel();
        $this->tiposInstitucionesModel = new \App\Models\TiposInstitucionesModel();
    }

    public function index()
    {
        $convenios = [];
        $instituciones = [];

        // Obtener carrera del estudiante logueado (solo ve convenios e instituciones de su carrera y vigentes)
        $idCarrera = $this->obtenerIdCarreraEstudiante();
        if ($idCarrera !== null) {
            // Convenios vigentes (FECHA_FIN >= hoy) destinados a la carrera del estudiante
            $convenios = $this->conveniosModel->getConveniosVigentesPorCarrera($idCarrera);
            // Solo los que tienen plaza disponible (plazas totales - ocupadas > 0)
            $convenios = array_filter($convenios, function ($c) {
                $plazas = (int) ($c['PLAZAS_DISPONIBLES'] ?? 0);
                $ocupadas = $this->conveniosModel->getPlazasOcupadas($c['ID_INSTITUCION_CONVENIO'], $c['ID_CARRERA']);
                return ($plazas - $ocupadas) > 0;
            });
            $convenios = array_values($convenios);
            // Instituciones que tienen al menos un convenio vigente con plaza para su carrera (sin duplicados)
            $idsInstituciones = array_values(array_unique(array_column($convenios, 'ID_INSTITUCION_CONVENIO')));
            if (!empty($idsInstituciones)) {
                $instituciones = $this->institucionesModel
                    ->select('TAB_INSTITUCIONES_CONVENIOS.*, TAB_TIPOS_INSTITUCION.INSTITUCION as TIPO')
                    ->join('TAB_TIPOS_INSTITUCION', 'TAB_INSTITUCIONES_CONVENIOS.ID_TIPO_INSTITUCION = TAB_TIPOS_INSTITUCION.ID_TIPO_INSTITUCION')
                    ->whereIn('TAB_INSTITUCIONES_CONVENIOS.ID_INSTITUCION_CONVENIO', $idsInstituciones)
                    ->findAll();
            }
        }

        $data = [
            'title' => 'Instituciones y Convenios',
            'instituciones' => $instituciones,
            'convenios' => $convenios
        ];

        return view('estudiante/convenios/index', $data);
    }

    /**
     * Obtiene ID_CARRERA del estudiante logueado (session id_usuario -> TAB_ESTUDIANTES.ID_CARRERA).
     * Devuelve null si no hay sesión o no es estudiante con carrera.
     */
    private function obtenerIdCarreraEstudiante()
    {
        if (!session()->get('logged_in') || (int) session()->get('rol') !== 3) {
            return null;
        }
        $idUsuario = session()->get('id_usuario');
        if (!$idUsuario) {
            return null;
        }
        $db = \Config\Database::connect();
        $usuario = $db->table('TAB_USUARIOS')->where('ID_USUARIO', $idUsuario)->get()->getRowArray();
        if (!$usuario || empty($usuario['ID_DATO_PERSONA'])) {
            return null;
        }
        $estudiante = $db->table('TAB_ESTUDIANTES')
            ->select('ID_CARRERA')
            ->where('ID_DATO_PERSONA', $usuario['ID_DATO_PERSONA'])
            ->get()->getRowArray();
        return isset($estudiante['ID_CARRERA']) ? (int) $estudiante['ID_CARRERA'] : null;
    }
}
