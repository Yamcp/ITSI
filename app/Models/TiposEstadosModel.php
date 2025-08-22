<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposEstadosModel extends Model
{
    protected $table = 'TAB_TIPOS_ESTADOS';
    protected $primaryKey = 'ID_TIPO_ESTADO';
    protected $allowedFields = ['ESTADO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ESTADO' => 'required|min_length[3]|max_length[50]|is_unique[TAB_TIPOS_ESTADOS.ESTADO,ID_TIPO_ESTADO,{ID_TIPO_ESTADO}]'
    ];
}