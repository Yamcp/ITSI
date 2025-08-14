<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposPracticasModel extends Model
{
    protected $table = 'TAB_TIPOS_PRACTICAS';
    protected $primaryKey = 'ID_TIPO_PRACTICA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['PRACTICA'];
}