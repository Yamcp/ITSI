<?php

namespace App\Models;
use CodeIgniter\Model;

class LineasInvestigacionModel extends Model
{
    protected $table = 'TAB_LINEAS_INVESTIGACION';
    protected $primaryKey = 'ID_LINEA_INVESTIGACION';
    protected $allowedFields = ['ID_ASIGNACION_PRACTICA', 'NOMBRE'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE' => 'required|min_length[5]|max_length[255]'
    ];
    
    // Las líneas pueden estar o no vinculadas a una práctica
    // Obtener líneas con o sin asignación
    public function getLineasConAsignacion()
    {
        $builder = $this->db->table('TAB_LINEAS_INVESTIGACION li')
            ->select('li.*, ap.DESCRIPCION as DESCRIPCION_PRACTICA')
            ->join('TAB_ASIGNACIONES_PRACTICAS ap', 'ap.ID_ASIGNACION_PRACTICA = li.ID_ASIGNACION_PRACTICA', 'left');
            
        return $builder->get()->getResultArray();
    }
}