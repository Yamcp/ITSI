<?php

namespace App\Models;
use CodeIgniter\Model;

class AsignacionesPracticasModel extends Model
{
    protected $table = 'TAB_ASIGNACIONES_PRACTICAS';
    protected $primaryKey = 'ID_ASIGNACION_PRACTICA';
    protected $allowedFields = [
        'ID_TIPO_PRACTICA', 'ID_USUARIO', 'ID_ESTADO_PRACTICAS', 'ID_INSTITUCION_CONVENIO',
        'FECHA_INICIO', 'FECHA_FIN', 'HORA_TOTAL', 'DESCRIPCION', 'CRONOGRAMA'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_PRACTICA' => 'required|integer',
        'ID_USUARIO' => 'required|integer',
        'ID_ESTADO_PRACTICAS' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'HORA_TOTAL' => 'required|integer|greater_than[0]'
    ];
    
    protected $beforeInsert = ['validarFechas'];
    protected $beforeUpdate = ['validarFechas'];
    
    protected function validarFechas(array $data)
    {
        if (isset($data['data']['FECHA_INICIO']) && isset($data['data']['FECHA_FIN'])) {
            $fechaInicio = new \DateTime($data['data']['FECHA_INICIO']);
            $fechaFin = new \DateTime($data['data']['FECHA_FIN']);
            
            if ($fechaFin < $fechaInicio) {
                // La fecha de fin no puede ser anterior a la de inicio
                $data['data']['FECHA_FIN'] = $data['data']['FECHA_INICIO'];
            }
        }
        return $data;
    }
    
    // Obtener prácticas con información completa
    public function getPracticasCompletas()
    {
        $builder = $this->db->table('TAB_ASIGNACIONES_PRACTICAS ap')
            ->select('ap.*, tp.PRACTICA, ep.ESTADO, ic.NOMBRE as NOMBRE_INSTITUCION,
                     u.USUARIO, dp.NOMBRE, dp.APELLIDO, e.ID_CARRERA, c.NOMBRE as CARRERA')
            ->join('TAB_TIPOS_PRACTICAS tp', 'tp.ID_TIPO_PRACTICA = ap.ID_TIPO_PRACTICA')
            ->join('TAB_ESTADO_PRACTICAS ep', 'ep.ID_ESTADO_PRACTICAS = ap.ID_ESTADO_PRACTICAS')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = ap.ID_INSTITUCION_CONVENIO')
            ->join('TAB_USUARIOS u', 'u.ID_USUARIO = ap.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
            ->join('TAB_ESTUDIANTES e', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA', 'left')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA', 'left');
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener prácticas por estudiante
    public function getPracticasPorEstudiante($idUsuario)
    {
        return $this->where('ID_USUARIO', $idUsuario)->findAll();
    }
    
    // Obtener prácticas por carrera
    public function getPracticasPorCarrera($idCarrera)
    {
        $builder = $this->db->table('TAB_ASIGNACIONES_PRACTICAS ap')
            ->select('ap.*')
            ->join('TAB_USUARIOS u', 'u.ID_USUARIO = ap.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = u.ID_DATO_PERSONA')
            ->join('TAB_ESTUDIANTES e', 'e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA')
            ->where('e.ID_CARRERA', $idCarrera);
            
        return $builder->get()->getResultArray();
    }
}