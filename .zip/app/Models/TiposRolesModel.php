<?php

namespace App\Models;
use CodeIgniter\Model;

class TiposRolesModel extends Model
{
    protected $table = 'TAB_TIPOS_ROLES';
    protected $primaryKey = 'ID_TIPOS_ROLES';
    protected $allowedFields = ['ROL'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ROL' => 'required|min_length[3]|max_length[50]|is_unique[TAB_TIPOS_ROLES.ROL,ID_TIPOS_ROLES,{ID_TIPOS_ROLES}]'
    ];
}