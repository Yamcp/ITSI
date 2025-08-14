<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosPersonasModel extends Model
{
    protected $table = 'TAB_DATOS_PERSONAS';
    protected $primaryKey = 'ID_DATO_PERSONA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'NOMBRE', 'APELLIDO', 'CEDULA', 'CELULAR', 'DIRECCION', 'EMAIL',
        'FECHA_NACIMIENTO', 'GENERO', 'ESTADO_CIVIL', 'NACIONALIDAD',
        'FECHA_INGRESO', 'ACTIVO', 'FOTO_URL'
    ];
    
    protected $useTimestamps = false;
    protected $validationRules = [
        'NOMBRE' => 'required|max_length[100]',
        'APELLIDO' => 'required|max_length[100]',
        'CEDULA' => 'required|max_length[10]|is_unique[TAB_DATOS_PERSONAS.CEDULA]',
        'EMAIL' => 'required|valid_email|max_length[100]',
        'CELULAR' => 'required|max_length[10]',
        'FECHA_NACIMIENTO' => 'required|valid_date',
        'GENERO' => 'required|max_length[15]',
        'ESTADO_CIVIL' => 'required|max_length[20]',
        'FECHA_INGRESO' => 'required|valid_date'
    ];
    
    protected $validationMessages = [
        'CEDULA' => [
            'is_unique' => 'Esta cédula ya está registrada en el sistema.'
        ]
    ];
}