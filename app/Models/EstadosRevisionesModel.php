<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadosRevisionesModel extends Model
{
    protected $table = 'TAB_ESTADOS_REVISIONES';
    protected $primaryKey = 'ID_ESTADO_REVISION';
    protected $allowedFields = ['ESTADO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ESTADO' => 'required|max_length[20]'
    ];
    
    protected $validationMessages = [
        'ESTADO' => [
            'required' => 'El estado es requerido',
            'max_length' => 'El estado no puede exceder 20 caracteres'
        ]
    ];
    
    /**
     * Obtener todos los estados
     */
    public function getAllEstados()
    {
        return $this->orderBy('ID_ESTADO_REVISION', 'ASC')->findAll();
    }
    
    /**
     * Obtener estado por ID
     */
    public function getEstadoById($id)
    {
        return $this->find($id);
    }
    
    /**
     * Obtener estado por nombre
     */
    public function getEstadoByNombre($nombre)
    {
        return $this->where('ESTADO', $nombre)->first();
    }
}
