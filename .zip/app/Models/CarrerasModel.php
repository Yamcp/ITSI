<?php

namespace App\Models;
use CodeIgniter\Model;

class CarrerasModel extends Model
{
    protected $table = 'TAB_CARRERAS';
    protected $primaryKey = 'ID_CARRERA';
    protected $allowedFields = ['NOMBRE'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE' => 'required|min_length[5]|max_length[255]|is_unique[TAB_CARRERAS.NOMBRE,ID_CARRERA,{ID_CARRERA}]'
    ];
}