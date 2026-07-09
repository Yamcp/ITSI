<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposConveniosModel extends Model
{
    protected $table = 'TAB_TIPOS_CONVENIOS';
    protected $primaryKey = 'ID_TIPO_CONVENIO';
    protected $allowedFields = ['CONVENIO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'CONVENIO' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPOS_CONVENIOS.CONVENIO,ID_TIPO_CONVENIO,{ID_TIPO_CONVENIO}]'
    ];
}