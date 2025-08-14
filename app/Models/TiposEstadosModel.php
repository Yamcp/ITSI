<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposEstadosModel extends Model
{
    protected $table = 'TAB_TIPOS_ESTADOS';
    protected $primaryKey = 'ID_TIPO_ESTADO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['ESTADO'];
}