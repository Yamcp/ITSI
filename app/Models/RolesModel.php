<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table = 'tab_roles';
    protected $primaryKey = 'ID_ROL';
    protected $allowedFields = ['ID_USUARIO', 'ID_TIPOS_ROLES'];
    protected $useTimestamps = false;
}