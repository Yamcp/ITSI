<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadoPracticasModel extends Model
{
    protected $table = 'TAB_ESTADOS_PRACTICAS';
    protected $primaryKey = 'ID_ESTADO_PRACTICA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['ESTADO'];
}