<?php

namespace App\Models;
use CodeIgniter\Model;

class ExportacionesModel extends Model
{
    protected $table = 'TAB_EXPORTACIONES';
    protected $primaryKey = 'ID_EXPORTACION';
    protected $allowedFields = ['ID_USUARIO', 'FECHA_EXPORTACION', 'DESCRIPCION_EXPORTACION'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'FECHA_EXPORTACION';
    protected $updatedField = '';
    
    protected $validationRules = [
        'ID_USUARIO' => 'required|integer',
        'DESCRIPCION_EXPORTACION' => 'required'
    ];
    
    // Registrar exportación automáticamente
    public function registrarExportacion($idUsuario, $descripcion)
    {
        return $this->insert([
            'ID_USUARIO' => $idUsuario,
            'DESCRIPCION_EXPORTACION' => $descripcion
        ]);
    }
    
    // Obtener exportaciones por usuario
    public function getExportacionesPorUsuario($idUsuario)
    {
        return $this->where('ID_USUARIO', $idUsuario)
                    ->orderBy('FECHA_EXPORTACION', 'DESC')
                    ->findAll();
    }
}