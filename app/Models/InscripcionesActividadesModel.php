<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionesActividadesModel extends Model
{
    protected $table = 'TAB_INSCRIPCIONES_ACTIVIDADES';
    protected $primaryKey = 'ID_INSCRIPCION';
    protected $allowedFields = [
        'ID_ACTIVIDAD_EDUCACION',
        'ID_ESTUDIANTE',
        'FECHA_INSCRIPCION',
        'ESTADO'
    ];
    protected $returnType = 'array';

    /**
     * Obtener participantes de una actividad con datos del estudiante
     */
    public function getParticipantesPorActividad($idActividad)
    {
        $builder = $this->db->table('TAB_INSCRIPCIONES_ACTIVIDADES ia')
            ->select('ia.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR, c.NOMBRE as CARRERA, e.SEMESTRE_ACTUAL')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = ia.ID_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left')
            ->where('ia.ID_ACTIVIDAD_EDUCACION', $idActividad)
            ->orderBy('dp.APELLIDO', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Contar participantes por actividad
     */
    public function contarPorActividad($idActividad)
    {
        return $this->where('ID_ACTIVIDAD_EDUCACION', $idActividad)->countAllResults();
    }

    /**
     * Verificar si un estudiante ya está inscrito en la actividad
     */
    public function estaInscrito($idActividad, $idEstudiante)
    {
        return $this->where('ID_ACTIVIDAD_EDUCACION', $idActividad)
            ->where('ID_ESTUDIANTE', $idEstudiante)
            ->countAllResults() > 0;
    }

    /**
     * Inscribir estudiante (si no está ya inscrito)
     */
    public function inscribir($idActividad, $idEstudiante)
    {
        if ($this->estaInscrito($idActividad, $idEstudiante)) {
            return false;
        }
        return $this->insert([
            'ID_ACTIVIDAD_EDUCACION' => $idActividad,
            'ID_ESTUDIANTE' => $idEstudiante,
            'FECHA_INSCRIPCION' => date('Y-m-d'),
            'ESTADO' => 'Inscrito'
        ]);
    }

    /**
     * Dar de baja inscripción
     */
    public function quitarInscripcion($idActividad, $idEstudiante)
    {
        return $this->where('ID_ACTIVIDAD_EDUCACION', $idActividad)
            ->where('ID_ESTUDIANTE', $idEstudiante)
            ->delete();
    }
}
