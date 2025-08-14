<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosInvestigacionModel extends Model
{
    protected $table      = 'TAB_DOCUMENTOS_INVESTIGACION';
    protected $primaryKey = 'ID_DOCUMENTO';
    protected $allowedFields = [
        'ID_AREA_TEMATICA', 'TITULO', 'AUTORES', 'RESUMEN', 'VIABLE', 'ARCHIVO'
    ];
}