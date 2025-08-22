<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposActividadesModel extends Model
{
    protected $table = 'TAB_TIPOS_ACTIVIDADES';
    protected $primaryKey = 'ID_TIPO_ACTIVIDAD';
    protected $allowedFields = ['ACTIVIDAD'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ACTIVIDAD' => 'required|min_length[3]|max_length[100]|is_unique[TAB_TIPOS_ACTIVIDADES.ACTIVIDAD,ID_TIPO_ACTIVIDAD,{ID_TIPO_ACTIVIDAD}]'
    ];
}