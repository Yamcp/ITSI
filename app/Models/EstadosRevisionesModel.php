<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadosRevisionesModel extends Model
{
    // Como no hay tabla de estados, usamos valores predefinidos
    protected $table = 'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES'; // Tabla temporal para queries
    protected $primaryKey = 'ID_DOCUMENTO_PREPROFESIONAL';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = false; // No usamos campos de esta tabla

    // Estados predefinidos
    private $estadosPredefinidos = [
        [
            'ID_ESTADO_REVISION' => 1,
            'ESTADO' => 'Pendiente',
            'DESCRIPCION' => 'Documento pendiente de revisión',
            'COLOR' => '#ffc107',
            'ICONO' => 'clock',
            'ORDEN' => 1,
            'ACTIVO' => 1
        ],
        [
            'ID_ESTADO_REVISION' => 2,
            'ESTADO' => 'Aprobado',
            'DESCRIPCION' => 'Documento aprobado',
            'COLOR' => '#28a745',
            'ICONO' => 'check-circle',
            'ORDEN' => 2,
            'ACTIVO' => 1
        ],
        [
            'ID_ESTADO_REVISION' => 3,
            'ESTADO' => 'Rechazado',
            'DESCRIPCION' => 'Documento rechazado',
            'COLOR' => '#dc3545',
            'ICONO' => 'times-circle',
            'ORDEN' => 3,
            'ACTIVO' => 1
        ],
        [
            'ID_ESTADO_REVISION' => 4,
            'ESTADO' => 'En Revisión',
            'DESCRIPCION' => 'Documento en proceso de revisión',
            'COLOR' => '#17a2b8',
            'ICONO' => 'eye',
            'ORDEN' => 4,
            'ACTIVO' => 1
        ]
    ];

    /**
     * Obtener todos los estados activos
     */
    public function getAllEstados()
    {
        return array_filter($this->estadosPredefinidos, function($estado) {
            return $estado['ACTIVO'] == 1;
        });
    }

    /**
     * Obtener estado por ID
     */
    public function find($id = null)
    {
        if ($id === null) {
            return $this->getAllEstados();
        }

        foreach ($this->estadosPredefinidos as $estado) {
            if ($estado['ID_ESTADO_REVISION'] == $id) {
                return $estado;
            }
        }

        return null;
    }

    /**
     * Obtener estado por nombre
     */
    public function getEstadoPorNombre($estado)
    {
        foreach ($this->estadosPredefinidos as $estadoData) {
            if ($estadoData['ESTADO'] == $estado) {
                return $estadoData;
            }
        }

        return null;
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
     * Obtener estados con colores para frontend
     */
    public function getEstadosConColores()
    {
        return $this->getAllEstados();
    }

    /**
     * Obtener estadísticas por estado
     */
    public function getEstadisticasPorEstado()
    {
        $db = \Config\Database::connect();
        $estados = $this->getAllEstados();
        $estadisticas = [];

        foreach ($estados as $estado) {
            $count = $db->table('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES')
                ->where('ESTADO_REVISION', $estado['ESTADO'])
                ->countAllResults();

            $estadisticas[] = [
                'ID_ESTADO_REVISION' => $estado['ID_ESTADO_REVISION'],
                'ESTADO' => $estado['ESTADO'],
                'COLOR' => $estado['COLOR'],
                'ICONO' => $estado['ICONO'],
                'total_documentos' => $count
            ];
        }

        return $estadisticas;
    }

    /**
     * Verificar si un estado existe
     */
    public function estadoExiste($estado)
    {
        return $this->getEstadoPorNombre($estado) !== null;
    }

    /**
     * Obtener estado completo por ID
     */
    public function getEstadoCompleto($id)
    {
        return $this->find($id);
    }

    /**
     * Obtener estados ordenados
     */
    public function getEstadosOrdenados()
    {
        $estados = $this->getAllEstados();
        usort($estados, function($a, $b) {
            return $a['ORDEN'] - $b['ORDEN'];
        });
        
        return $estados;
    }

    /**
     * Obtener siguiente orden disponible
     */
    public function getSiguienteOrden()
    {
        $estados = $this->getAllEstados();
        $maxOrden = 0;
        
        foreach ($estados as $estado) {
            if ($estado['ORDEN'] > $maxOrden) {
                $maxOrden = $estado['ORDEN'];
            }
        }
        
        return $maxOrden + 1;
    }

    /**
     * Crear estados predefinidos (método de compatibilidad)
     */
    public function crearEstadosPredefinidos()
    {
        // Los estados ya están predefinidos, no hay nada que crear
        return count($this->estadosPredefinidos);
    }

    /**
     * Métodos de compatibilidad que no hacen nada ya que no hay tabla
     */
    public function insert($data = null, $returnID = true)
    {
        // No se pueden insertar estados ya que están predefinidos
        return false;
    }

    public function update($id = null, $data = null): bool
    {
        // No se pueden actualizar estados ya que están predefinidos
        return false;
    }

    public function delete($id = null, $purge = false)
    {
        // No se pueden eliminar estados ya que están predefinidos
        return false;
    }

    public function save($row): bool
    {
        // No se pueden guardar estados ya que están predefinidos
        return false;
    }
}