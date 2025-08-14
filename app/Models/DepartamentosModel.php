<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentosModel extends Model
{
    protected $table = 'TAB_DEPARTAMENTOS';
    protected $primaryKey = 'ID_DEPARTAMENTO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'NOMBRE', 'RESPONSABLE'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'NOMBRE' => 'required|max_length[100]',
        'RESPONSABLE' => 'required|max_length[100]'
    ];
}