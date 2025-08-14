<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposConveniosModel extends Model
{
    protected $table = 'TAB_TIPOS_CONVENIOS';
    protected $primaryKey = 'ID_TIPO_CONVENIO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['CONVENIO'];
}