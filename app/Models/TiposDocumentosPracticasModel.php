<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposDocumentosPracticasModel extends Model
{
    protected $table = 'TAB_TIPOS_DOCUMENTOS_PRACTICAS';
    protected $primaryKey = 'ID_TIPO_DOCUMENTO';
    protected $allowedFields = ['CODIGO', 'NOMBRE', 'DESCRIPCION', 'ORDEN'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'CODIGO' => 'required|max_length[50]',
        'NOMBRE' => 'required|max_length[150]',
        'DESCRIPCION' => 'required',
        'ORDEN' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'CODIGO' => [
            'required' => 'El código es requerido',
            'max_length' => 'El código no puede exceder 50 caracteres'
        ],
        'NOMBRE' => [
            'required' => 'El nombre es requerido',
            'max_length' => 'El nombre no puede exceder 150 caracteres'
        ],
        'DESCRIPCION' => [
            'required' => 'La descripción es requerida'
        ],
        'ORDEN' => [
            'required' => 'El orden es requerido',
            'integer' => 'El orden debe ser un número entero'
        ]
    ];
    
    /**
     * Obtener todos los tipos de documentos ordenados
     */
    public function getAllTipos()
    {
        return $this->orderBy('ORDEN', 'ASC')->findAll();
    }
    
    /**
     * Obtener tipo por ID
     */
    public function getTipoById($id)
    {
        return $this->find($id);
    }
    
    /**
     * Obtener tipo por código
     */
    public function getTipoByCodigo($codigo)
    {
        return $this->where('CODIGO', $codigo)->first();
    }
    
    /**
     * Obtener tipos activos
     */
    public function getTiposActivos()
    {
        return $this->orderBy('ORDEN', 'ASC')->findAll();
    }
}
