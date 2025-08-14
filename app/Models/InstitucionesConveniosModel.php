<?php

namespace App\Models;

use CodeIgniter\Model;

class InstitucionesConveniosModel extends Model
{
    protected $table      = 'TAB_INSTITUCIONES_CONVENIOS';
    protected $primaryKey = 'ID_INSTITUCION_CONVENIO';
    protected $allowedFields = [
        'ID_TIPO_INSTITUCION', 'NOMBRE', 'RUC', 'DIRECCION', 'TELEFONO', 'EMAIL',
        'REPRESENTANTE_LEGAL', 'CONTACTO', 'TELEFONO_CONTACTO', 'EMAIL_CONTACTO', 'AREA_INTERES'
    ];
}