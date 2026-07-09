<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposInstitucionesModel extends Model
{
    protected $table = 'TAB_TIPOS_INSTITUCION';
    protected $primaryKey = 'ID_TIPO_INSTITUCION';
    protected $allowedFields = ['INSTITUCION'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'INSTITUCION' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPOS_INSTITUCION.INSTITUCION,ID_TIPO_INSTITUCION,{ID_TIPO_INSTITUCION}]'
    ];
}