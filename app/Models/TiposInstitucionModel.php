<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposInstitucionModel extends Model
{
    protected $table = 'TAB_TIPOS_INSTITUCION';
    protected $primaryKey = 'ID_TIPO_INSTITUCION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['INSTITUCION'];
}