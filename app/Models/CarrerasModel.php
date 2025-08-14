<?php

namespace App\Models;

use CodeIgniter\Model;

class CarrerasModel extends Model
{
    protected $table = 'TAB_CARRERAS';
    protected $primaryKey = 'ID_CARRERA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'NOMBRE', 'DURACION_SEMESTRES'
    ];

    protected $useTimestamps = false;
    protected $validationRules = [
        'NOMBRE' => 'required|max_length[100]',
        'DURACION_SEMESTRES' => 'required|integer'
    ];
}