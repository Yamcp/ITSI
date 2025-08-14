<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposModalidadesModel extends Model
{
    protected $table = 'TAB_TIPOS_MODALIDADES';
    protected $primaryKey = 'ID_TIPO_MODALIDAD';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['MODALIDAD'];
}