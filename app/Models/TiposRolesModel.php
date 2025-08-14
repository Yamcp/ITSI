<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposRolesModel extends Model
{
    protected $table = 'TAB_TIPOS_ROLES';
    protected $primaryKey = 'ID_TIPOS_ROLES';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['ROL'];
}