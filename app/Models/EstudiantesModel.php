<?php

namespace App\Models;
use CodeIgniter\Model;

class EstudiantesModel extends Model
{
    protected $table = 'TAB_ESTUDIANTES';
    protected $primaryKey = 'ID_ESTUDIANTE';
    protected $allowedFields = ['ID_TIPO_ESTADO', 'ID_DATO_PERSONA', 'ID_CARRERA', 'SEMESTRE_ACTUAL'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_ESTADO' => 'required|integer',
        'ID_DATO_PERSONA' => 'required|integer|is_unique[TAB_ESTUDIANTES.ID_DATO_PERSONA,ID_ESTUDIANTE,{ID_ESTUDIANTE}]',
        'ID_CARRERA' => 'required|integer',
        'SEMESTRE_ACTUAL' => 'required|integer|greater_than[0]|less_than[11]'
    ];
    
    /**
     * Estudiante(s) con datos personales, carrera y estado.
     * Sin ID: lista (opcionalmente filtrada). Con ID: una fila o null.
     *
     * @param array<string, mixed> $filtros carrera, estado, semestre (solo si $id es null)
     * @return list<array<string, mixed>>|array<string, mixed>|null
     */
    public function getEstudianteCompleto(?int $id = null, array $filtros = [])
    {
        $builder = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR, dp.FOTO_URL,
                     c.NOMBRE as CARRERA, te.ESTADO as ESTADO_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_TIPOS_ESTADOS te', 'te.ID_TIPO_ESTADO = e.ID_TIPO_ESTADO');

        if ($id !== null) {
            $builder->where('e.ID_ESTUDIANTE', $id);

            return $builder->get()->getRowArray();
        }

        if (! empty($filtros['carrera'])) {
            $builder->where('c.ID_CARRERA', $filtros['carrera']);
        }
        if (! empty($filtros['estado'])) {
            $builder->where('e.ID_TIPO_ESTADO', $filtros['estado']);
        }
        if (! empty($filtros['semestre'])) {
            $builder->where('e.SEMESTRE_ACTUAL', $filtros['semestre']);
        }

        return $builder->orderBy('dp.APELLIDO', 'ASC')
            ->orderBy('dp.NOMBRE', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Obtener estudiante con datos personales
    public function getEstudianteConDatos($idEstudiante)
    {
        return $this->getEstudianteCompleto((int) $idEstudiante);
    }
    
    // Obtener estudiantes por carrera
    public function getEstudiantesPorCarrera($idCarrera)
    {
        return $this->where('ID_CARRERA', $idCarrera)->findAll();
    }
    
    // Obtener estudiante por usuario
    public function getEstudiantePorUsuario($idUsuario)
    {
        $builder = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.*')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario);
            
        return $builder->get()->getRowArray();
    }

    /**
     * Lista de estudiantes para selector (inscripciones a actividades, etc.)
     */
    public function getEstudiantesParaInscripcion()
    {
        return $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.ID_ESTUDIANTE, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, c.NOMBRE as CARRERA')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left')
            ->orderBy('dp.APELLIDO', 'ASC')
            ->get()
            ->getResultArray();
    }
}