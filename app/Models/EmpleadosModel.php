<?php

namespace App\Models;
use CodeIgniter\Model;

class EmpleadosModel extends Model
{
    protected $table = 'TAB_EMPLEADOS';
    protected $primaryKey = 'ID_EMPLEADO';
    protected $allowedFields = ['ID_DEPARTAMENTO', 'ID_DATO_PERSONA', 'ID_TIPO_CONTRATO', 'CARGO', 'FECHA_INGRESO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_DEPARTAMENTO' => 'required|integer',
        'ID_DATO_PERSONA' => 'required|integer|is_unique[TAB_EMPLEADOS.ID_DATO_PERSONA,ID_EMPLEADO,{ID_EMPLEADO}]',
        'ID_TIPO_CONTRATO' => 'required|integer',
        'CARGO' => 'required|min_length[3]|max_length[100]',
        'FECHA_INGRESO' => 'required|valid_date'
    ];
    
    // Obtener empleado con datos personales
    public function getEmpleadoConDatos($idEmpleado)
    {
        $builder = $this->db->table('TAB_EMPLEADOS e')
            ->select('e.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR, 
                     d.NOMBRE as DEPARTAMENTO, tc.TIPO_CONTRATO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_DEPARTAMENTOS d', 'd.ID_DEPARTAMENTO = e.ID_DEPARTAMENTO')
            ->join('TAB_TIPO_CONTRATO tc', 'tc.ID_TIPO_CONTRATO = e.ID_TIPO_CONTRATO')
            ->where('e.ID_EMPLEADO', $idEmpleado);
            
        return $builder->get()->getRowArray();
    }
    
    // Obtener empleados por departamento
    public function getEmpleadosPorDepartamento($idDepartamento)
    {
        return $this->where('ID_DEPARTAMENTO', $idDepartamento)->findAll();
    }
    
    // Obtener empleado por usuario
    public function getEmpleadoPorUsuario($idUsuario)
    {
        $builder = $this->db->table('TAB_EMPLEADOS e')
            ->select('e.*')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
            ->where('u.ID_USUARIO', $idUsuario);
            
        return $builder->get()->getRowArray();
    }
}