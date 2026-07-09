<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosHabilitantesInstitucionModel extends Model
{
    protected $table = 'TAB_DOCUMENTOS_HABILITANTES_INSTITUCION';
    protected $primaryKey = 'ID_DOCUMENTO_HABILITANTE';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'ID_INSTITUCION_CONVENIO',
        'NOMBRE_ARCHIVO',
        'NOMBRE_ORIGINAL',
        'TIPO_ARCHIVO',
        'TAMANO_BYTES',
        'FECHA_SUBIDA',
    ];

    protected $validationRules = [
        'ID_INSTITUCION_CONVENIO' => 'required|integer|is_natural_no_zero',
        'NOMBRE_ARCHIVO' => 'required|max_length[255]',
        'NOMBRE_ORIGINAL' => 'required|max_length[255]',
        'TIPO_ARCHIVO' => 'permit_empty|max_length[100]',
        'FECHA_SUBIDA' => 'required|valid_date',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function getPorInstitucion(int $idInstitucion): array
    {
        return $this->where('ID_INSTITUCION_CONVENIO', $idInstitucion)
            ->orderBy('FECHA_SUBIDA', 'ASC')
            ->findAll();
    }
}
