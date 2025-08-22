<?php

namespace App\Models;
use CodeIgniter\Model;

class DetallesConveniosModel extends Model
{
    protected $table = 'TAB_DETALLES_CONVENIOS';
    protected $primaryKey = 'ID_DETALLE_CONVENIO';
    protected $allowedFields = [
        'ID_TIPO_CONVENIO', 'ID_INSTITUCION_CONVENIO', 'FECHA_INICIO', 'FECHA_FIN', 'DURACION',
        'OBJETIVO', 'OBSERVACIONES', 'ARCHIVO_CONVENIO', 'RENOVABLE'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_CONVENIO' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'DURACION' => 'required|integer|greater_than[0]',
        'OBJETIVO' => 'required|min_length[10]',
        'RENOVABLE' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $beforeInsert = ['validarFechas'];
    protected $beforeUpdate = ['validarFechas'];
    
    protected function validarFechas(array $data)
    {
        if (isset($data['data']['FECHA_INICIO']) && isset($data['data']['FECHA_FIN'])) {
            $fechaInicio = new \DateTime($data['data']['FECHA_INICIO']);
            $fechaFin = new \DateTime($data['data']['FECHA_FIN']);
            
            if ($fechaFin < $fechaInicio) {
                $data['data']['FECHA_FIN'] = $data['data']['FECHA_INICIO'];
            }
        }
        return $data;
    }
    
    // Obtener convenios completos con institución y tipo
    public function getConveniosCompletos()
    {
        $builder = $this->db->table('TAB_DETALLES_CONVENIOS dc')
            ->select('dc.*, ic.NOMBRE, ic.RUC, ic.REPRESENTANTE_LEGAL, tc.CONVENIO as TIPO_CONVENIO')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = dc.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_CONVENIOS tc', 'tc.ID_TIPO_CONVENIO = dc.ID_TIPO_CONVENIO');
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener convenios por vencer (próximos 30 días)
    public function getConveniosPorVencer()
    {
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        $fechaActual = date('Y-m-d');
        
        return $this->where('FECHA_FIN >=', $fechaActual)
                    ->where('FECHA_FIN <=', $fechaLimite)
                    ->where('RENOVABLE', 1)
                    ->findAll();
    }
    
    // Obtener convenios por institución
    public function getConveniosPorInstitucion($idInstitucion)
    {
        return $this->where('ID_INSTITUCION_CONVENIO', $idInstitucion)->findAll();
    }
}