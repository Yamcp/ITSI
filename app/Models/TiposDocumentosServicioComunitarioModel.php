<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposDocumentosServicioComunitarioModel extends Model
{
    /** Código del documento final en formato PSC-XXX (referencia para `crearTiposPredefinidos()`; el registro vive en BD). */
    public const CODIGO_DOCUMENTO_FINAL = 'PSC-013';

    protected $table = 'TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO';
    protected $primaryKey = 'ID_TIPO_DOCUMENTO_SERVICIO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'CODIGO',
        'NOMBRE',
        'DESCRIPCION',
        'ORDEN',
        'OBLIGATORIO',
        'ACTIVO',
        'FECHA_CREACION',
        'FECHA_ACTUALIZACION'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'CODIGO' => 'required|max_length[10]|is_unique[TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO.CODIGO,ID_TIPO_DOCUMENTO_SERVICIO,{ID_TIPO_DOCUMENTO_SERVICIO}]',
        'NOMBRE' => 'required|max_length[255]',
        'DESCRIPCION' => 'permit_empty|max_length[1000]',
        'ORDEN' => 'required|integer|is_natural_no_zero',
        'OBLIGATORIO' => 'required|in_list[0,1]',
        'ACTIVO' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'CODIGO' => [
            'required' => 'El código es obligatorio',
            'max_length' => 'El código no puede exceder 10 caracteres',
            'is_unique' => 'El código ya existe'
        ],
        'NOMBRE' => [
            'required' => 'El nombre es obligatorio',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'DESCRIPCION' => [
            'max_length' => 'La descripción no puede exceder 1000 caracteres'
        ],
        'ORDEN' => [
            'required' => 'El orden es obligatorio',
            'integer' => 'El orden debe ser un número entero',
            'is_natural_no_zero' => 'El orden debe ser un número positivo'
        ],
        'OBLIGATORIO' => [
            'required' => 'El campo obligatorio es requerido',
            'in_list' => 'El valor debe ser 0 o 1'
        ],
        'ACTIVO' => [
            'required' => 'El campo activo es requerido',
            'in_list' => 'El valor debe ser 0 o 1'
        ]
    ];

    /**
     * Obtener todos los tipos de documentos activos
     */
    public function getAllTipos()
    {
        try {
            $query = $this->db->table($this->table)
                ->where('ACTIVO', 1)
                ->orderBy('ORDEN', 'ASC')
                ->orderBy('CODIGO', 'ASC')
                ->get();

            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'TiposDocumentosServicioComunitarioModel::getAllTipos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener tipos de documentos por estado
     */
    public function getTiposPorEstado($activo = 1)
    {
        try {
            $query = $this->db->table($this->table)
                ->where('ACTIVO', $activo)
                ->orderBy('ORDEN', 'ASC')
                ->orderBy('CODIGO', 'ASC')
                ->get();
            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Obtener tipo de documento por código
     */
    public function getTipoPorCodigo($codigo)
    {
        try {
            $query = $this->db->table($this->table)
                ->where('CODIGO', $codigo)
                ->where('ACTIVO', 1)
                ->get();
            if ($query === false) {
                return null;
            }
            return $query->getRowArray();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Obtener tipos obligatorios
     */
    public function getTiposObligatorios()
    {
        try {
            $query = $this->db->table($this->table)
                ->where('OBLIGATORIO', 1)
                ->where('ACTIVO', 1)
                ->orderBy('ORDEN', 'ASC')
                ->get();
            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Obtener tipos opcionales
     */
    public function getTiposOpcionales()
    {
        return $this->where('OBLIGATORIO', 0)
                   ->where('ACTIVO', 1)
                   ->orderBy('ORDEN', 'ASC')
                   ->findAll();
    }

    /**
     * Verificar si un tipo de documento existe
     */
    public function existeTipo($codigo, $excluirId = null)
    {
        $query = $this->where('CODIGO', $codigo);
        
        if ($excluirId) {
            $query->where('ID_TIPO_DOCUMENTO_SERVICIO !=', $excluirId);
        }
        
        return $query->countAllResults() > 0;
    }

    /**
     * Obtener el siguiente orden disponible
     */
    public function getSiguienteOrden()
    {
        $ultimoOrden = $this->selectMax('ORDEN')
                           ->where('ACTIVO', 1)
                           ->first();
        
        return ($ultimoOrden['ORDEN'] ?? 0) + 1;
    }

    /**
     * Activar/desactivar tipo de documento
     */
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['ACTIVO' => $activo]);
    }

    /**
     * Obtener estadísticas de tipos de documentos
     */
    public function getEstadisticas()
    {
        $total = $this->countAllResults();
        $activos = $this->where('ACTIVO', 1)->countAllResults();
        $inactivos = $this->where('ACTIVO', 0)->countAllResults();
        $obligatorios = $this->where('OBLIGATORIO', 1)->where('ACTIVO', 1)->countAllResults();
        $opcionales = $this->where('OBLIGATORIO', 0)->where('ACTIVO', 1)->countAllResults();

        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'obligatorios' => $obligatorios,
            'opcionales' => $opcionales
        ];
    }

    /**
     * Buscar tipos de documentos
     */
    public function buscarTipos($termino)
    {
        return $this->groupStart()
                   ->like('CODIGO', $termino)
                   ->orLike('NOMBRE', $termino)
                   ->orLike('DESCRIPCION', $termino)
                   ->groupEnd()
                   ->where('ACTIVO', 1)
                   ->orderBy('ORDEN', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener tipos de documentos con paginación
     */
    public function getTiposPaginados($porPagina = 10, $pagina = 1)
    {
        return $this->orderBy('ORDEN', 'ASC')
                   ->orderBy('CODIGO', 'ASC')
                   ->paginate($porPagina, 'default', $pagina);
    }

    /**
     * Reordenar tipos de documentos
     */
    public function reordenarTipos($ordenes)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            foreach ($ordenes as $id => $orden) {
                $this->update($id, ['ORDEN' => $orden]);
            }
            
            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Crear tipos de documentos predefinidos
     */
    public function crearTiposPredefinidos()
    {
        $tiposPredefinidos = [
            [
                'CODIGO' => 'SC.1',
                'NOMBRE' => 'Plan de Trabajo de Servicio Comunitario',
                'DESCRIPCION' => 'Plan detallado de las actividades a realizar durante el servicio comunitario.',
                'ORDEN' => 1,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'SC.2',
                'NOMBRE' => 'Cronograma de Actividades',
                'DESCRIPCION' => 'Cronograma detallado con fechas y horarios de las actividades de servicio comunitario.',
                'ORDEN' => 2,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'SC.3',
                'NOMBRE' => 'Informe de Actividades Realizadas',
                'DESCRIPCION' => 'Informe detallado de todas las actividades realizadas durante el servicio comunitario.',
                'ORDEN' => 3,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'SC.4',
                'NOMBRE' => 'Evidencias Fotográficas',
                'DESCRIPCION' => 'Fotografías que evidencian la realización de las actividades de servicio comunitario.',
                'ORDEN' => 4,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'SC.5',
                'NOMBRE' => 'Evaluación de la Comunidad',
                'DESCRIPCION' => 'Evaluación realizada por la comunidad sobre el impacto del servicio comunitario.',
                'ORDEN' => 5,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'SC.6',
                'NOMBRE' => 'Informe Final de Servicio Comunitario',
                'DESCRIPCION' => 'Informe final que resume todo el trabajo realizado durante el servicio comunitario.',
                'ORDEN' => 6,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => self::CODIGO_DOCUMENTO_FINAL,
                'NOMBRE' => 'Documento final',
                'DESCRIPCION' => 'Documento de cierre o carpeta final del servicio comunitario.',
                'ORDEN' => 7,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            foreach ($tiposPredefinidos as $tipo) {
                // Verificar si ya existe
                if (!$this->existeTipo($tipo['CODIGO'])) {
                    $this->insert($tipo);
                }
            }
            
            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }
}