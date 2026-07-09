<?php

namespace App\Models;
use CodeIgniter\Model;

class DatosPersonasModel extends Model
{
    protected $table = 'TAB_DATOS_PERSONAS';
    protected $primaryKey = 'ID_DATO_PERSONA';
    protected $allowedFields = [
        'NOMBRE', 'APELLIDO', 'CEDULA', 'CELULAR', 'DIRECCION', 'EMAIL', 
        'GENERO', 'ESTADO_CIVIL', 'NACIONALIDAD', 'FECHA_INGRESO', 'ACTIVO', 'FOTO_URL'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE' => 'required|min_length[2]|max_length[100]',
        'APELLIDO' => 'required|min_length[2]|max_length[100]',
        'CEDULA' => 'required|min_length[10]|max_length[13]|is_unique[TAB_DATOS_PERSONAS.CEDULA,ID_DATO_PERSONA,{ID_DATO_PERSONA}]',
        'EMAIL' => 'required|valid_email|is_unique[TAB_DATOS_PERSONAS.EMAIL,ID_DATO_PERSONA,{ID_DATO_PERSONA}]',
        'CELULAR' => 'permit_empty|min_length[10]|max_length[15]',
        'GENERO' => 'permit_empty|in_list[M,F,O]',
        'ESTADO_CIVIL' => 'permit_empty|in_list[SOLTERO,CASADO,DIVORCIADO,VIUDO,UNION LIBRE]',
        'ACTIVO' => 'permit_empty|in_list[0,1]'
    ];
    
    // Asegura que la foto URL sea válida (si existe)
    protected $beforeInsert = ['validarFoto'];
    protected $beforeUpdate = ['validarFoto'];
    
    protected function validarFoto(array $data)
    {
        if (!empty($data['data']['FOTO_URL'])) {
            // Sanitizar la URL o ruta de la foto
            $data['data']['FOTO_URL'] = filter_var($data['data']['FOTO_URL'], FILTER_SANITIZE_URL);
        }
        
        // Asegurar formato de fecha correcto
        if (isset($data['data']['FECHA_INGRESO']) && $data['data']['FECHA_INGRESO'] == '') {
            $data['data']['FECHA_INGRESO'] = date('Y-m-d');
        }
        
        return $data;
    }
    
    // Búsqueda por cédula o nombre
    public function buscarPorCedulaONombre($termino)
    {
        return $this->like('CEDULA', $termino)
                    ->orLike('NOMBRE', $termino)
                    ->orLike('APELLIDO', $termino)
                    ->findAll();
    }
    
    // Obtener nombre completo formateado
    public function getNombreCompleto($idPersona)
    {
        $persona = $this->find($idPersona);
        if ($persona) {
            return $persona['NOMBRE'] . ' ' . $persona['APELLIDO'];
        }
        return '';
    }
}