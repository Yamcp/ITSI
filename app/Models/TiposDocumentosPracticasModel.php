<?php

namespace App\Models;

use CodeIgniter\Model;

class TiposDocumentosPracticasModel extends Model
{
    protected $table = 'TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES';
    protected $primaryKey = 'ID_TIPO_DOCUMENTO_PREPROFESIONAL';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'CODIGO',
        'NOMBRE',
        'DESCRIPCION',
        'ORDEN',
        'OBLIGATORIO'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'CODIGO' => 'required|max_length[50]|is_unique[TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.CODIGO,ID_TIPO_DOCUMENTO_PREPROFESIONAL,{ID_TIPO_DOCUMENTO_PREPROFESIONAL}]',
        'NOMBRE' => 'required|max_length[150]',
        'DESCRIPCION' => 'permit_empty',
        'ORDEN' => 'permit_empty|integer|is_natural',
        'OBLIGATORIO' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'CODIGO' => [
            'required' => 'El código es requerido',
            'max_length' => 'El código no puede exceder 50 caracteres',
            'is_unique' => 'El código ya existe'
        ],
        'NOMBRE' => [
            'required' => 'El nombre es requerido',
            'max_length' => 'El nombre no puede exceder 150 caracteres'
        ],
        'DESCRIPCION' => [
            'max_length' => 'La descripción no puede exceder el límite permitido'
        ],
        'ORDEN' => [
            'integer' => 'El orden debe ser un número entero',
            'is_natural' => 'El orden debe ser un número natural'
        ],
        'OBLIGATORIO' => [
            'in_list' => 'El campo obligatorio debe ser 0 o 1'
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
     * Obtener todos los tipos de documentos activos
     */
    public function getAllTipos()
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
        
        $builder->orderBy('CODIGO', 'ASC');

        $rows = $builder->get()->getResultArray();
        foreach ($rows as &$row) {
            if (!array_key_exists('REQUERIDO', $row) && array_key_exists('OBLIGATORIO', $row)) {
                $row['REQUERIDO'] = $row['OBLIGATORIO'];
            }
        }
        unset($row);

        return $rows;
    }

    /**
     * Obtener tipos de documentos ordenados por código
     */
    public function getTiposOrdenados()
    {
        return $this->orderBy('CODIGO', 'ASC')
                    ->orderBy('ORDEN', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener tipos de documentos requeridos
     */
    public function getTiposRequeridos()
    {
        return $this->where('OBLIGATORIO', 1)
                    ->where('ACTIVO', 1)
                    ->orderBy('ORDEN', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener tipos de documentos opcionales
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
    public function tipoExiste($codigo)
    {
        return $this->where('CODIGO', $codigo)->first() !== null;
    }

    /**
     * Obtener tipo de documento por código
     */
    public function getTipoPorCodigo($codigo)
    {
        return $this->where('CODIGO', $codigo)->first();
    }

    /**
     * Obtener tipos de documentos con estadísticas
     */
    public function getTiposConEstadisticas()
    {
        $builder = $this->db->table($this->table . ' tdp');
        $builder->select('
            tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL,
            tdp.CODIGO,
            tdp.NOMBRE,
            tdp.DESCRIPCION,
            tdp.OBLIGATORIO as REQUERIDO,
            tdp.ORDEN,
            tdp.ACTIVO,
            COUNT(dp.ID_DOCUMENTO_PREPROFESIONAL) as total_documentos,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 2 THEN 1 ELSE 0 END) as en_revision,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) as aprobados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) as rechazados,
            SUM(CASE WHEN dp.ID_ESTADO_REVISION = 5 THEN 1 ELSE 0 END) as requiere_correccion
        ');
        $builder->join('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp', 'tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL = dp.ID_TIPO_DOCUMENTO', 'left');
        $builder->groupBy('tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL, tdp.CODIGO, tdp.NOMBRE, tdp.DESCRIPCION, tdp.OBLIGATORIO, tdp.ORDEN, tdp.ACTIVO');
        $builder->orderBy('tdp.ORDEN', 'ASC');
        $builder->orderBy('tdp.CODIGO', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Crear los 12 tipos de documentos de prácticas predefinidos
     */
    public function crearTiposPredefinidos()
    {
        $tipos = [
            [
                'CODIGO' => '1.1',
                'NOMBRE' => 'Oficio de Asignación de Tutor Docente',
                'DESCRIPCION' => 'Documento oficial que asigna un tutor docente para el seguimiento de las prácticas preprofesionales del estudiante.',
                'REQUERIDO' => 1,
                'ORDEN' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.2',
                'NOMBRE' => 'Oficio Personal a Entidad Receptora',
                'DESCRIPCION' => 'Oficio dirigido a la entidad receptora solicitando la aceptación del estudiante para realizar sus prácticas.',
                'REQUERIDO' => 1,
                'ORDEN' => 2,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.3',
                'NOMBRE' => 'Carta de Aceptación de Entidad Receptora',
                'DESCRIPCION' => 'Carta oficial de la entidad receptora confirmando la aceptación del estudiante para realizar sus prácticas.',
                'REQUERIDO' => 1,
                'ORDEN' => 3,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.4',
                'NOMBRE' => 'Solicitud Institucional Valorada',
                'DESCRIPCION' => 'Solicitud institucional que valora y aprueba la realización de prácticas preprofesionales del estudiante.',
                'REQUERIDO' => 1,
                'ORDEN' => 4,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.5',
                'NOMBRE' => 'Certificado de Culminación (60 horas)',
                'DESCRIPCION' => 'Certificado que acredita la culminación exitosa de las 60 horas de prácticas preprofesionales.',
                'REQUERIDO' => 1,
                'ORDEN' => 5,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.6',
                'NOMBRE' => 'Rúbrica de Evaluación Entidad Receptora',
                'DESCRIPCION' => 'Rúbrica de evaluación completada por la entidad receptora sobre el desempeño del estudiante.',
                'REQUERIDO' => 1,
                'ORDEN' => 6,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.7',
                'NOMBRE' => 'Hojas de Asistencia de Estudiantes',
                'DESCRIPCION' => 'Registro de asistencia del estudiante durante el período de prácticas preprofesionales.',
                'REQUERIDO' => 1,
                'ORDEN' => 7,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.8',
                'NOMBRE' => 'Ficha de Registro de Actividades Realizadas',
                'DESCRIPCION' => 'Ficha detallada de todas las actividades realizadas por el estudiante durante sus prácticas.',
                'REQUERIDO' => 1,
                'ORDEN' => 8,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.9',
                'NOMBRE' => 'Ficha de Control y Seguimiento Docente',
                'DESCRIPCION' => 'Ficha de control y seguimiento completada por el docente tutor durante las prácticas.',
                'REQUERIDO' => 1,
                'ORDEN' => 9,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.10',
                'NOMBRE' => 'Rúbrica de Evaluación de Control y Seguimiento Docente',
                'DESCRIPCION' => 'Rúbrica de evaluación del control y seguimiento realizado por el docente tutor.',
                'REQUERIDO' => 1,
                'ORDEN' => 10,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.11',
                'NOMBRE' => 'Rúbrica de Evaluación de Resultados',
                'DESCRIPCION' => 'Rúbrica de evaluación final de los resultados obtenidos durante las prácticas preprofesionales.',
                'REQUERIDO' => 1,
                'ORDEN' => 11,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => '1.12',
                'NOMBRE' => 'Respaldo en Fotos, Videos y Evidencias',
                'DESCRIPCION' => 'Archivos multimedia y evidencias fotográficas del trabajo realizado durante las prácticas.',
                'REQUERIDO' => 0,
                'ORDEN' => 12,
                'ACTIVO' => 1
            ]
        ];

        $insertados = 0;
        foreach ($tipos as $tipo) {
            if (!$this->tipoExiste($tipo['CODIGO'])) {
                if ($this->insert($tipo)) {
                    $insertados++;
                }
            }
        }

        return $insertados;
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
     * Reordenar tipos de documentos
     */
    public function reordenarTipos($ordenes)
    {
        $this->db->transStart();
        
        foreach ($ordenes as $id => $orden) {
            $this->update($id, ['ORDEN' => $orden]);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Activar/desactivar tipo de documento
     */
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['ACTIVO' => $activo]);
    }

    /**
     * Obtener tipos de documentos para select
     */
    public function getTiposParaSelect()
    {
        $tipos = $this->getAllTipos();
        $opciones = [];
        
        foreach ($tipos as $tipo) {
            $opciones[$tipo['ID_TIPO_DOCUMENTO']] = $tipo['CODIGO'] . '. ' . $tipo['NOMBRE'];
        }
        
        return $opciones;
    }
}