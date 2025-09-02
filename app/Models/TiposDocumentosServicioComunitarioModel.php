<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposDocumentosServicioComunitarioModel extends Model
{
    protected $table = 'TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_TIPO_DOCUMENTO_SERVICIO';
    protected $allowedFields = [
        'CODIGO',
        'NOMBRE',
        'DESCRIPCION',
        'ORDEN',
        'OBLIGATORIO'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'CODIGO' => 'required|max_length[50]|is_unique[TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.CODIGO,ID_TIPO_DOCUMENTO_SERVICIO,{id}]',
        'NOMBRE' => 'required|max_length[150]',
        'DESCRIPCION' => 'permit_empty',
        'ORDEN' => 'required|integer',
        'OBLIGATORIO' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'CODIGO' => [
            'required' => 'El código es requerido',
            'max_length' => 'El código no puede exceder 50 caracteres',
            'is_unique' => 'El código ya existe'
        ],
        'NOMBRE' => [
            'required' => 'El nombre es requerido',
            'max_length' => 'El nombre no puede exceder 150 caracteres'
        ],
        'ORDEN' => [
            'required' => 'El orden es requerido',
            'integer' => 'El orden debe ser un número entero'
        ],
        'OBLIGATORIO' => [
            'required' => 'El campo obligatorio es requerido',
            'in_list' => 'El campo obligatorio debe ser 0 o 1'
        ]
    ];

    /**
     * Obtener tipos de documentos ordenados
     */
    public function getTiposOrdenados()
    {
        return $this->orderBy('ORDEN', 'ASC')->findAll();
    }

    /**
     * Obtener tipos obligatorios
     */
    public function getTiposObligatorios()
    {
        return $this->where('OBLIGATORIO', 1)
            ->orderBy('ORDEN', 'ASC')
            ->findAll();
    }

    /**
     * Obtener tipos opcionales
     */
    public function getTiposOpcionales()
    {
        return $this->where('OBLIGATORIO', 0)
            ->orderBy('ORDEN', 'ASC')
            ->findAll();
    }

    /**
     * Verificar si un código existe
     */
    public function codigoExiste($codigo, $excluirId = null)
    {
        $builder = $this->where('CODIGO', $codigo);
        if ($excluirId) {
            $builder->where('ID_TIPO_DOCUMENTO_SERVICIO !=', $excluirId);
        }
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener siguiente orden disponible
     */
    public function getSiguienteOrden()
    {
        $ultimo = $this->selectMax('ORDEN')->first();
        return ($ultimo['ORDEN'] ?? 0) + 1;
    }

    /**
     * Reordenar tipos de documentos
     */
    public function reordenar($ids)
    {
        $this->db->transStart();
        
        foreach ($ids as $orden => $id) {
            $this->update($id, ['ORDEN' => $orden + 1]);
        }
        
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
