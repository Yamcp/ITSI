<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoContratoModel extends Model
{
    protected $table = 'TAB_TIPOS_CONTRATOS';
    protected $primaryKey = 'ID_TIPO_CONTRATO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['CONTRATO'];
}