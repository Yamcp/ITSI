<?php

namespace App\Models;
use CodeIgniter\Model;

class DocumentosInvestigacionModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_INVESTIGACION';
    protected $primaryKey = 'ID_DOCUMENTO';
    protected $allowedFields = ['ID_LINEA_INVESTIGACION', 'TITULO', 'AUTORES', 'RESUMEN', 'VIABLE', 'ARCHIVO'];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_LINEA_INVESTIGACION' => 'required|integer',
        'TITULO' => 'required|min_length[10]|max_length[255]',
        'AUTORES' => 'required',
        'RESUMEN' => 'required|min_length[50]',
        'VIABLE' => 'permit_empty|in_list[0,1]'
    ];
    
    // Obtener documentos viables
    public function getDocumentosViables()
    {
        return $this->where('VIABLE', 1)->findAll();
    }
    
    // Obtener documentos por línea de investigación
    public function getDocumentosPorLinea($idLinea)
    {
        return $this->where('ID_LINEA_INVESTIGACION', $idLinea)->findAll();
    }
    
    // Obtener documentos con información de línea
    public function getDocumentosCompletos()
    {
        $builder = $this->db->table('TAB_DOCUMENTOS_INVESTIGACION d')
            ->select('d.*, li.NOMBRE as LINEA_INVESTIGACION')
            ->join('TAB_LINEAS_INVESTIGACION li', 'li.ID_LINEA_INVESTIGACION = d.ID_LINEA_INVESTIGACION');
            
        return $builder->get()->getResultArray();
    }
}