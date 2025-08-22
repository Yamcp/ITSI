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
    
    // Obtener estudiante con datos personales
    public function getEstudianteConDatos($idEstudiante)
    {
        $builder = $this->db->table('TAB_ESTUDIANTES e')
            ->select('e.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR, 
                     c.NOMBRE as CARRERA, te.ESTADO as ESTADO_ESTUDIANTE')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->join('TAB_TIPOS_ESTADOS te', 'te.ID_TIPO_ESTADO = e.ID_TIPO_ESTADO')
            ->where('e.ID_ESTUDIANTE', $idEstudiante);
            
        return $builder->get()->getRowArray();
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
}