<?php

namespace App\Models;
use CodeIgniter\Model;

class DepartamentosModel extends Model
{
    protected $table = 'TAB_DEPARTAMENTOS';
    protected $primaryKey = 'ID_DEPARTAMENTO';
    protected $allowedFields = ['NOMBRE', 'RESPONSABLE'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE' => 'required|min_length[3]|max_length[255]|is_unique[TAB_DEPARTAMENTOS.NOMBRE,ID_DEPARTAMENTO,{ID_DEPARTAMENTO}]'
    ];
}