<?php

namespace App\Models;
use CodeIgniter\Model;

class InstructoresModel extends Model
{
    protected $table = 'TAB_INSTRUCTORES';
    protected $primaryKey = 'ID_INSTRUCTOR';
    protected $allowedFields = ['ID_TIPO_INSTRUCTOR', 'ID_DATO_PERSONA', 'ESPECIALIDAD', 'TITULO_PROFESIONAL'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_INSTRUCTOR' => 'required|integer',
        'ID_DATO_PERSONA' => 'required|integer|is_unique[TAB_INSTRUCTORES.ID_DATO_PERSONA,ID_INSTRUCTOR,{ID_INSTRUCTOR}]',
        'ESPECIALIDAD' => 'required|min_length[3]|max_length[255]',
        'TITULO_PROFESIONAL' => 'required|min_length[3]|max_length[255]'
    ];
    
    // Obtener instructores con datos personales
    public function getInstructoresConDatos()
    {
        $builder = $this->db->table('TAB_INSTRUCTORES i')
            ->select('i.*, dp.NOMBRE, dp.APELLIDO, dp.EMAIL, dp.CELULAR, ti.TIPO as TIPO_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->join('TAB_TIPO_INSTRUCTORES ti', 'ti.ID_TIPO_INSTRUCTOR = i.ID_TIPO_INSTRUCTOR');
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener instructor completo por ID
    public function getInstructorCompleto($id)
    {
        $builder = $this->db->table('TAB_INSTRUCTORES i')
            ->select('i.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR, ti.TIPO as TIPO_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->join('TAB_TIPO_INSTRUCTORES ti', 'ti.ID_TIPO_INSTRUCTOR = i.ID_TIPO_INSTRUCTOR')
            ->where('i.ID_INSTRUCTOR', $id);
            
        return $builder->get()->getRowArray();
    }
    
    // Verificar si un empleado es instructor
    public function esEmpleadoInstructor($idEmpleado)
    {
        $builder = $this->db->table('TAB_EMPLEADOS_INTRUCTORES')
            ->where('ID_EMPLEADO', $idEmpleado);
            
        return ($builder->countAllResults() > 0);
    }
}