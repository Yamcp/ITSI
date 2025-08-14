<?php

namespace App\Models;

use CodeIgniter\Model;

class ExportacionesModel extends Model
{
    protected $table      = 'TAB_EXPORTACIONES';
    protected $primaryKey = 'ID_EXPORTACION';
    protected $allowedFields = [
        'ID_USUARIO', 'FECHA_EXPORTACION', 'DESCRIPCION_EXPORTACION'
    ];
}