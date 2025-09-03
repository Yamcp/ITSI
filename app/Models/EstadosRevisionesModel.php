<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadosRevisionesModel extends Model
{
    protected $table = 'TAB_ESTADOS_REVISIONES';
    protected $primaryKey = 'ID_ESTADO_REVISION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ESTADO',
        'DESCRIPCION',
        'COLOR',
        'ICONO',
        'ORDEN',
        'ACTIVO'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'ESTADO' => 'required|max_length[50]|is_unique[TAB_ESTADOS_REVISIONES.ESTADO,ID_ESTADO_REVISION,{ID_ESTADO_REVISION}]',
        'DESCRIPCION' => 'permit_empty|max_length[255]',
        'COLOR' => 'permit_empty|max_length[20]',
        'ICONO' => 'permit_empty|max_length[50]',
        'ORDEN' => 'permit_empty|integer|is_natural',
        'ACTIVO' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'ESTADO' => [
            'required' => 'El estado es requerido',
            'max_length' => 'El estado no puede exceder 50 caracteres',
            'is_unique' => 'El estado ya existe'
        ],
        'DESCRIPCION' => [
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ],
        'COLOR' => [
            'max_length' => 'El color no puede exceder 20 caracteres'
        ],
        'ICONO' => [
            'max_length' => 'El icono no puede exceder 50 caracteres'
        ],
        'ORDEN' => [
            'integer' => 'El orden debe ser un número entero',
            'is_natural' => 'El orden debe ser un número natural'
        ],
        'ACTIVO' => [
            'in_list' => 'El campo activo debe ser 0 o 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Verificar si una columna existe en la tabla
     */
    private function columnExists($columnName)
    {
        $query = $this->db->query("
            SELECT COUNT(*) as count 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = '{$this->table}' 
              AND COLUMN_NAME = '{$columnName}'
        ");
        $result = $query->getRow();
        return $result->count > 0;
    }

    /**
     * Obtener todos los estados activos
     */
    public function getAllEstados()
    {
        $builder = $this->builder();
        
        // Si la columna ACTIVO existe, filtrar por ella
        if ($this->columnExists('ACTIVO')) {
            $builder->where('ACTIVO', 1);
        }
        
        // Si la columna ORDEN existe, ordenar por ella
        if ($this->columnExists('ORDEN')) {
            $builder->orderBy('ORDEN', 'ASC');
        }
        
        $builder->orderBy('ID_ESTADO_REVISION', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estados ordenados
     */
    public function getEstadosOrdenados()
    {
        return $this->orderBy('ORDEN', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener estado por nombre
     */
    public function getEstadoPorNombre($estado)
    {
        return $this->where('ESTADO', $estado)->first();
    }

    /**
     * Crear estados predefinidos para revisiones
     */
    public function crearEstadosPredefinidos()
    {
        $estados = [
            [
                'ESTADO' => 'Pendiente',
                'DESCRIPCION' => 'Documento subido y esperando revisión',
                'COLOR' => 'warning',
                'ICONO' => 'fas fa-clock',
                'ORDEN' => 1,
                'ACTIVO' => 1
            ],
            [
                'ESTADO' => 'En Revisión',
                'DESCRIPCION' => 'Documento siendo revisado por el administrador',
                'COLOR' => 'info',
                'ICONO' => 'fas fa-eye',
                'ORDEN' => 2,
                'ACTIVO' => 1
            ],
            [
                'ESTADO' => 'Aprobado',
                'DESCRIPCION' => 'Documento aprobado y validado',
                'COLOR' => 'success',
                'ICONO' => 'fas fa-check-circle',
                'ORDEN' => 3,
                'ACTIVO' => 1
            ],
            [
                'ESTADO' => 'Rechazado',
                'DESCRIPCION' => 'Documento rechazado, requiere correcciones',
                'COLOR' => 'danger',
                'ICONO' => 'fas fa-times-circle',
                'ORDEN' => 4,
                'ACTIVO' => 1
            ],
            [
                'ESTADO' => 'Requiere Corrección',
                'DESCRIPCION' => 'Documento requiere correcciones antes de aprobación',
                'COLOR' => 'warning',
                'ICONO' => 'fas fa-exclamation-triangle',
                'ORDEN' => 5,
                'ACTIVO' => 1
            ]
        ];

        $insertados = 0;
        foreach ($estados as $estado) {
            if (!$this->getEstadoPorNombre($estado['ESTADO'])) {
                if ($this->insert($estado)) {
                    $insertados++;
                }
            }
        }

        return $insertados;
    }

    /**
     * Obtener estados para select
     */
    public function getEstadosParaSelect()
    {
        $estados = $this->getAllEstados();
        $opciones = [];
        
        foreach ($estados as $estado) {
            $opciones[$estado['ID_ESTADO_REVISION']] = $estado['ESTADO'];
        }
        
        return $opciones;
    }

    /**
     * Obtener siguiente orden disponible
     */
    public function getSiguienteOrden()
    {
        $ultimo = $this->selectMax('ORDEN')->first();
        return ($ultimo['ORDEN'] ?? 0) + 1;
    }

    /**
     * Reordenar estados
     */
    public function reordenarEstados($ordenes)
    {
        $this->db->transStart();
        
        foreach ($ordenes as $id => $orden) {
            $this->update($id, ['ORDEN' => $orden]);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Activar/desactivar estado
     */
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['ACTIVO' => $activo]);
    }

    /**
     * Obtener estadísticas por estado
     */
    public function getEstadisticasPorEstado()
    {
        $builder = $this->db->table($this->table . ' er');
        $builder->select('
            er.ID_ESTADO_REVISION,
            er.ESTADO,
            er.COLOR,
            er.ICONO,
            COUNT(dp.ID_DOCUMENTO_PRACTICA) as total_documentos
        ');
        $builder->join('TAB_DOCUMENTOS_PRACTICAS dp', 'er.ID_ESTADO_REVISION = dp.ID_ESTADO_REVISION', 'left');
        $builder->where('er.ACTIVO', 1);
        $builder->groupBy('er.ID_ESTADO_REVISION, er.ESTADO, er.COLOR, er.ICONO');
        $builder->orderBy('er.ORDEN', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Verificar si un estado existe
     */
    public function estadoExiste($estado)
    {
        return $this->where('ESTADO', $estado)->first() !== null;
    }

    /**
     * Obtener estado por ID con información completa
     */
    public function getEstadoCompleto($id)
    {
        return $this->where('ID_ESTADO_REVISION', $id)->first();
    }

    /**
     * Obtener estados con colores para frontend
     */
    public function getEstadosConColores()
    {
        $estados = $this->getAllEstados();
        $estadosConColores = [];
        
        foreach ($estados as $estado) {
            $estadosConColores[] = [
                'id' => $estado['ID_ESTADO_REVISION'],
                'estado' => $estado['ESTADO'],
                'descripcion' => $estado['DESCRIPCION'],
                'color' => $estado['COLOR'],
                'icono' => $estado['ICONO'],
                'orden' => $estado['ORDEN']
            ];
        }
        
        return $estadosConColores;
    }
}