<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposModalidadesModel extends Model
{
    protected $table = 'TAB_TIPOS_MODALIDADES';
    protected $primaryKey = 'ID_TIPO_MODALIDAD';
    protected $allowedFields = ['MODALIDAD'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'MODALIDAD' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPOS_MODALIDADES.MODALIDAD,ID_TIPO_MODALIDAD,{ID_TIPO_MODALIDAD}]'
    ];
}