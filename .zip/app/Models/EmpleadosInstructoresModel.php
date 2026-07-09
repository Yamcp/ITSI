<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadosInstructoresModel extends Model
{
    protected $table = 'TAB_EMPLEADOS_INSTRUCTORES';
    protected $primaryKey = 'ID_EMPLEADO_INSTRUCTOR';
    protected $allowedFields = ['ID_EMPLEADO', 'ID_INSTRUCTOR'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_EMPLEADO' => 'required|integer',
        'ID_INSTRUCTOR' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'ID_EMPLEADO' => [
            'required' => 'El ID del empleado es requerido',
            'integer' => 'El ID del empleado debe ser un número entero'
        ],
        'ID_INSTRUCTOR' => [
            'required' => 'El ID del instructor es requerido',
            'integer' => 'El ID del instructor debe ser un número entero'
        ]
    ];
    
    // Obtener relación empleado-instructor con datos completos
    public function getRelacionesCompletas()
    {
        $builder = $this->db->table('TAB_EMPLEADOS_INSTRUCTORES ei')
            ->select('ei.*, 
                     e.CARGO, e.FECHA_INGRESO as FECHA_INGRESO_EMPLEADO,
                     dp_emp.NOMBRE as NOMBRE_EMPLEADO, dp_emp.APELLIDO as APELLIDO_EMPLEADO, 
                     dp_emp.CEDULA as CEDULA_EMPLEADO, dp_emp.EMAIL as EMAIL_EMPLEADO,
                     i.ESPECIALIDAD, i.TITULO_PROFESIONAL,
                     dp_inst.NOMBRE as NOMBRE_INSTRUCTOR, dp_inst.APELLIDO as APELLIDO_INSTRUCTOR,
                     dp_inst.CEDULA as CEDULA_INSTRUCTOR, dp_inst.EMAIL as EMAIL_INSTRUCTOR,
                     ti.TIPO as TIPO_INSTRUCTOR')
            ->join('TAB_EMPLEADOS e', 'e.ID_EMPLEADO = ei.ID_EMPLEADO')
            ->join('TAB_DATOS_PERSONAS dp_emp', 'dp_emp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ei.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp_inst', 'dp_inst.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->join('TAB_TIPOS_INSTRUCTORES ti', 'ti.ID_TIPO_INSTRUCTOR = i.ID_TIPO_INSTRUCTOR')
            ->orderBy('dp_emp.NOMBRE', 'ASC');
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener instructores de un empleado específico
    public function getInstructoresPorEmpleado($idEmpleado)
    {
        $builder = $this->db->table('TAB_EMPLEADOS_INSTRUCTORES ei')
            ->select('ei.*, i.ESPECIALIDAD, i.TITULO_PROFESIONAL,
                     dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL,
                     ti.TIPO as TIPO_INSTRUCTOR')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ei.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->join('TAB_TIPOS_INSTRUCTORES ti', 'ti.ID_TIPO_INSTRUCTOR = i.ID_TIPO_INSTRUCTOR')
            ->where('ei.ID_EMPLEADO', $idEmpleado);
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener empleados de un instructor específico
    public function getEmpleadosPorInstructor($idInstructor)
    {
        $builder = $this->db->table('TAB_EMPLEADOS_INSTRUCTORES ei')
            ->select('ei.*, e.CARGO, e.FECHA_INGRESO,
                     dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL,
                     d.NOMBRE as DEPARTAMENTO')
            ->join('TAB_EMPLEADOS e', 'e.ID_EMPLEADO = ei.ID_EMPLEADO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_DEPARTAMENTOS d', 'd.ID_DEPARTAMENTO = e.ID_DEPARTAMENTO')
            ->where('ei.ID_INSTRUCTOR', $idInstructor);
            
        return $builder->get()->getResultArray();
    }
    
    // Verificar si existe una relación empleado-instructor
    public function existeRelacion($idEmpleado, $idInstructor)
    {
        return $this->where('ID_EMPLEADO', $idEmpleado)
                   ->where('ID_INSTRUCTOR', $idInstructor)
                   ->countAllResults() > 0;
    }
    
    // Crear relación empleado-instructor
    public function crearRelacion($idEmpleado, $idInstructor)
    {
        if (!$this->existeRelacion($idEmpleado, $idInstructor)) {
            return $this->insert([
                'ID_EMPLEADO' => $idEmpleado,
                'ID_INSTRUCTOR' => $idInstructor
            ]);
        }
        return false; // La relación ya existe
    }
    
    // Eliminar relación empleado-instructor
    public function eliminarRelacion($idEmpleado, $idInstructor)
    {
        return $this->where('ID_EMPLEADO', $idEmpleado)
                   ->where('ID_INSTRUCTOR', $idInstructor)
                   ->delete();
    }
    
    // Eliminar todas las relaciones de un empleado
    public function eliminarRelacionesEmpleado($idEmpleado)
    {
        return $this->where('ID_EMPLEADO', $idEmpleado)->delete();
    }
    
    // Eliminar todas las relaciones de un instructor
    public function eliminarRelacionesInstructor($idInstructor)
    {
        return $this->where('ID_INSTRUCTOR', $idInstructor)->delete();
    }
}
