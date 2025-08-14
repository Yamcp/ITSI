<?php

namespace App\Models;

use CodeIgniter\Model;

class AreasTematicasModel extends Model
{
    protected $table      = 'TAB_AREAS_TEMATICAS';
    protected $primaryKey = 'ID_AREA_TEMATICA';
    protected $allowedFields = ['ID_ASIGNACION_PRACTICA', 'NOMBRE'];
}