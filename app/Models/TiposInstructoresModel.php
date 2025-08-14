<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposInstructoresModel extends Model
{
    protected $table = 'TAB_TIPOS_INSTRUCTORES';
    protected $primaryKey = 'ID_TIPO_INSTRUCTOR';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['INSTRUCTOR'];
}