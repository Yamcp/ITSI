<?php

namespace App\Models;
use CodeIgniter\Model;

class EstadosPracticasModel extends Model
{
    protected $table = 'TAB_ESTADOS_PRACTICAS_PREPROFESIONALES';
    protected $primaryKey = 'ID_ESTADO_PREPROFESIONAL';
    protected $allowedFields = ['ESTADO', 'DESCRIPCION', 'COLOR'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ESTADO' => 'required|min_length[3]|max_length[50]|is_unique[TAB_ESTADOS_PRACTICAS_PREPROFESIONALES.ESTADO,ID_ESTADO_PREPROFESIONAL,{ID_ESTADO_PREPROFESIONAL}]'
    ];
}