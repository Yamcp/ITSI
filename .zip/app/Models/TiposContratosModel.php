<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposContratosModel extends Model
{
    protected $table = 'TAB_TIPO_CONTRATO';
    protected $primaryKey = 'ID_TIPO_CONTRATO';
    protected $allowedFields = ['TIPO_CONTRATO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'TIPO_CONTRATO' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPO_CONTRATO.TIPO_CONTRATO,ID_TIPO_CONTRATO,{ID_TIPO_CONTRATO}]'
    ];
}