<?php

namespace App\Models;
use CodeIgniter\Model;

class InstitucionesConveniosModel extends Model
{
    protected $table = 'TAB_INSTITUCIONES_CONVENIOS';
    protected $primaryKey = 'ID_INSTITUCION_CONVENIO';
    protected $allowedFields = [
        'ID_TIPO_INSTITUCION', 'NOMBRE', 'RUC', 'DIRECCION', 'CIUDAD', 'TELEFONO', 'EMAIL',
        'REPRESENTANTE_LEGAL', 'CONTACTO', 'TELEFONO_CONTACTO', 'EMAIL_CONTACTO'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_INSTITUCION' => 'required|integer',
        'NOMBRE' => 'required|min_length[5]|max_length[255]',
        'RUC' => 'required|min_length[10]|max_length[13]|is_unique[TAB_INSTITUCIONES_CONVENIOS.RUC,ID_INSTITUCION_CONVENIO,{ID_INSTITUCION_CONVENIO}]',
        'EMAIL' => 'required|valid_email',
        'REPRESENTANTE_LEGAL' => 'required',
        'EMAIL_CONTACTO' => 'permit_empty|valid_email'
    ];
    
    // Obtener instituciones con tipo
    public function getInstitucionesConTipo()
    {
        $builder = $this->db->table('TAB_INSTITUCIONES_CONVENIOS ic')
            ->select('ic.*, ti.INSTITUCION as TIPO_INSTITUCION')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION');
            
        return $builder->get()->getResultArray();
    }
    
    // Obtener instituciones con convenios vigentes
    public function getInstitucionesConConveniosVigentes()
    {
        $builder = $this->db->table('TAB_INSTITUCIONES_CONVENIOS ic')
            ->select('ic.*')
            ->join('TAB_DETALLES_CONVENIOS dc', 'dc.ID_INSTITUCION_CONVENIO = ic.ID_INSTITUCION_CONVENIO')
            ->where('dc.FECHA_FIN >=', date('Y-m-d'))
            ->groupBy('ic.ID_INSTITUCION_CONVENIO');
            
        return $builder->get()->getResultArray();
    }
    
    // Buscar institución por nombre
    public function buscarPorNombre($termino)
    {
        return $this->like('NOMBRE', $termino)->findAll();
    }
}