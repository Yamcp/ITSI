<?php

namespace App\Models;
use CodeIgniter\Model;

class EstadosPracticasModel extends Model
{
    protected $table = 'TAB_ESTADO_PRACTICAS';
    protected $primaryKey = 'ID_ESTADO_PRACTICAS';
    protected $allowedFields = ['ESTADO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ESTADO' => 'required|min_length[3]|max_length[50]|is_unique[TAB_ESTADO_PRACTICAS.ESTADO,ID_ESTADO_PRACTICAS,{ID_ESTADO_PRACTICAS}]'
    ];
}