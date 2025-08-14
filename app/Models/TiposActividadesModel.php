<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposActividadesModel extends Model
{
    protected $table = 'TAB_TIPOS_ACTIVIDADES';
    protected $primaryKey = 'ID_TIPO_ACTIVIDAD';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['ACTIVIDAD'];
}