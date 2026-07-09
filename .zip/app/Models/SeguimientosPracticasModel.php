<?php

namespace App\Models;
use CodeIgniter\Model;

class SeguimientosPracticasModel extends Model
{
    protected $table = 'TAB_SEGUIMIENTO_PRACTICAS';
    protected $primaryKey = 'ID_SEGUIMIENTO';
    protected $allowedFields = [
        'ID_ASIGNACION_PRACTICA', 'HORAS_CUMPLIDAS', 'ACTIVIDADES_REALIZADAS',
        'OBSERVACIONES', 'ARCHIVO_REPORTE'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_ASIGNACION_PRACTICA' => 'required|integer',
        'HORAS_CUMPLIDAS' => 'required|integer|greater_than_equal_to[0]',
        'ACTIVIDADES_REALIZADAS' => 'required|min_length[10]'
    ];
    
    // Obtener último seguimiento de una práctica
    public function getUltimoSeguimiento($idAsignacion)
    {
        return $this->where('ID_ASIGNACION_PRACTICA', $idAsignacion)
                    ->orderBy('ID_SEGUIMIENTO', 'DESC')
                    ->first();
    }
    
    // Obtener todos los seguimientos de una práctica
    public function getSeguimientosPractica($idAsignacion)
    {
        return $this->where('ID_ASIGNACION_PRACTICA', $idAsignacion)
                    ->orderBy('ID_SEGUIMIENTO', 'DESC')
                    ->findAll();
    }
}