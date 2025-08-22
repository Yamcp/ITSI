<?php

namespace App\Models;
use CodeIgniter\Model;

class AsistenciasPracticasModel extends Model
{
    protected $table = 'TAB_ASISTENCIAS_PRACTICAS';
    protected $primaryKey = 'ID_ASISTENCIA';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA', 'FECHA_ASISTENCIA', 'HORA_ENTRADA', 'HORA_SALIDA',
        'ACTIVIDADES_DIA', 'FECHA_REGISTRO', 'OBSERVACIONES'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'ID_ASIGNACION_PRACTICA' => 'required|integer',
        'FECHA_ASISTENCIA' => 'required|valid_date',
        'HORA_ENTRADA' => 'required',
        'HORA_SALIDA' => 'required',
        'ACTIVIDADES_DIA' => 'required|min_length[10]'
    ];
    
    protected $beforeInsert = ['setFechaRegistro', 'validarHoras'];
    protected $beforeUpdate = ['validarHoras'];
    
    protected function setFechaRegistro(array $data)
    {
        $data['data']['FECHA_REGISTRO'] = date('Y-m-d H:i:s');
        return $data;
    }
    
    protected function validarHoras(array $data)
    {
        if (isset($data['data']['HORA_ENTRADA']) && isset($data['data']['HORA_SALIDA'])) {
            $entrada = strtotime($data['data']['HORA_ENTRADA']);
            $salida = strtotime($data['data']['HORA_SALIDA']);
            
            if ($salida <= $entrada) {
                // La hora de salida debe ser posterior a la de entrada
                $data['data']['HORA_SALIDA'] = date('H:i:s', strtotime('+1 hour', $entrada));
            }
        }
        return $data;
    }
    
    // Obtener horas totales de asistencia por asignación
    public function getTotalHorasAsistencia($idAsignacion)
    {
        $builder = $this->db->table('TAB_ASISTENCIAS_PRACTICAS')
            ->selectSum('TIMESTAMPDIFF(HOUR, HORA_ENTRADA, HORA_SALIDA)', 'total_horas')
            ->where('ID_ASIGNACION_PRACTICA', $idAsignacion);
            
        $resultado = $builder->get()->getRowArray();
        return isset($resultado['total_horas']) ? $resultado['total_horas'] : 0;
    }
    
    // Obtener asistencias por rango de fechas
    public function getAsistenciasPorRango($idAsignacion, $fechaInicio, $fechaFin)
    {
        return $this->where('ID_ASIGNACION_PRACTICA', $idAsignacion)
                    ->where('FECHA_ASISTENCIA >=', $fechaInicio)
                    ->where('FECHA_ASISTENCIA <=', $fechaFin)
                    ->orderBy('FECHA_ASISTENCIA', 'DESC')
                    ->findAll();
    }
}