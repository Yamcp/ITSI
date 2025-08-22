<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposInstructoresModel extends Model
{
    protected $table = 'TAB_TIPO_INSTRUCTORES';
    protected $primaryKey = 'ID_TIPO_INSTRUCTOR';
    protected $allowedFields = ['TIPO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'TIPO' => 'required|min_length[3]|max_length[50]|is_unique[TAB_TIPO_INSTRUCTORES.TIPO,ID_TIPO_INSTRUCTOR,{ID_TIPO_INSTRUCTOR}]'
    ];
}