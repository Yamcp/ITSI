<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposPracticasModel extends Model
{
    protected $table = 'TAB_TIPOS_PRACTICAS';
    protected $primaryKey = 'ID_TIPO_PRACTICA';
    protected $allowedFields = ['PRACTICA'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'PRACTICA' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPOS_PRACTICAS.PRACTICA,ID_TIPO_PRACTICA,{ID_TIPO_PRACTICA}]'
    ];
}