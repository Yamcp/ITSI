<?php

namespace App\Models;
use CodeIgniter\Model;

class ActividadesEducacionModel extends Model
{
    protected $table = 'TAB_ACTIVIDADES_EDUCACION';
    protected $primaryKey = 'ID_ACTIVIDAD_EDUCACION';
    protected $allowedFields = [
        'ID_INSTRUCTOR', 'ID_TIPO_MODALIDAD', 'ID_TIPO_ACTIVIDAD', 'ID_USUARIO',
        'NOMBRE_ACTIVIDAD', 'DESCRIPCION', 'OBJETIVOS', 'DURACION_HORAS',
        'FECHA_INICIO', 'FECHA_FIN', 'LUGAR', 'HORARIO', 'INCLUYE_CERTIFICADO',
        'PROGRAMA_DETALLADO'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE_ACTIVIDAD' => 'required|min_length[5]|max_length[255]',
        'ID_INSTRUCTOR' => 'required|integer',
        'ID_TIPO_MODALIDAD' => 'required|integer',
        'ID_TIPO_ACTIVIDAD' => 'required|integer',
        'ID_USUARIO' => 'required|integer',
        'DURACION_HORAS' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date'
    ];
    
    // Validar que fecha fin >= fecha inicio
    protected $beforeInsert = ['validarFechas'];
    protected $beforeUpdate = ['validarFechas'];
    
    protected function validarFechas(array $data)
    {
        if (isset($data['data']['FECHA_INICIO']) && isset($data['data']['FECHA_FIN'])) {
            $fechaInicio = new \DateTime($data['data']['FECHA_INICIO']);
            $fechaFin = new \DateTime($data['data']['FECHA_FIN']);
            
            if ($fechaFin < $fechaInicio) {
                // La fecha de fin no puede ser anterior a la de inicio
                // En un entorno real, deberías manejar este error adecuadamente
                $data['data']['FECHA_FIN'] = $data['data']['FECHA_INICIO']; 
            }
        }
        return $data;
    }
    
    // Obtener actividad con información relacionada
    public function getActividadCompleta($id)
    {
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.*, ta.ACTIVIDAD as TIPO_ACTIVIDAD, tm.MODALIDAD, 
                     i.ESPECIALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->where('ae.ID_ACTIVIDAD_EDUCACION', $id);
            
        return $builder->get()->getRowArray();
    }
    
    // Obtener actividades activas (no finalizadas)
    public function getActividadesActivas()
    {
        return $this->where('FECHA_FIN >=', date('Y-m-d'))
                    ->orderBy('FECHA_INICIO', 'ASC')
                    ->findAll();
    }
    
    // Buscar actividades por tipo
    public function buscarPorTipo($idTipo)
    {
        return $this->where('ID_TIPO_ACTIVIDAD', $idTipo)->findAll();
    }
}