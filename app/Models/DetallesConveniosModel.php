<?php

namespace App\Models;
use CodeIgniter\Model;

class DetallesConveniosModel extends Model
{
    protected $table = 'TAB_DETALLES_CONVENIOS';
    protected $primaryKey = 'ID_DETALLE_CONVENIO';
    protected $allowedFields = [
        'ID_TIPO_CONVENIO', 'ID_INSTITUCION_CONVENIO', 'ID_CARRERA', 'FECHA_INICIO', 'FECHA_FIN', 'DURACION',
        'OBJETIVO', 'OBSERVACIONES', 'ARCHIVO_CONVENIO', 'RENOVABLE', 'PLAZAS_DISPONIBLES'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'ID_TIPO_CONVENIO' => 'required|integer',
        'ID_INSTITUCION_CONVENIO' => 'required|integer',
        'ID_CARRERA' => 'permit_empty|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'DURACION' => 'required|integer|greater_than[0]',
        'OBJETIVO' => 'required|min_length[10]',
        'RENOVABLE' => 'permit_empty|in_list[0,1]',
        'PLAZAS_DISPONIBLES' => 'permit_empty|integer|greater_than_equal_to[0]'
    ];
    
    protected $beforeInsert = ['validarFechas'];
    protected $beforeUpdate = ['validarFechas'];
    
    protected function validarFechas(array $data)
    {
        if (isset($data['data']['FECHA_INICIO']) && isset($data['data']['FECHA_FIN'])) {
            $fechaInicio = new \DateTime($data['data']['FECHA_INICIO']);
            $fechaFin = new \DateTime($data['data']['FECHA_FIN']);
            
            if ($fechaFin < $fechaInicio) {
                $data['data']['FECHA_FIN'] = $data['data']['FECHA_INICIO'];
            }
        }
        return $data;
    }
    
    /** Comprueba si la tabla tiene la columna ID_CARRERA (migración ejecutada). */
    protected function tieneColumnaCarrera()
    {
        $fields = $this->db->getFieldNames('TAB_DETALLES_CONVENIOS');
        return $fields && in_array('ID_CARRERA', $fields);
    }

    /** Comprueba si la tabla tiene la columna PLAZAS_DISPONIBLES. */
    protected function tieneColumnaPlazas()
    {
        $fields = $this->db->getFieldNames('TAB_DETALLES_CONVENIOS');
        return $fields && in_array('PLAZAS_DISPONIBLES', $fields);
    }

    /** Indica si la tabla tiene columnas de migración (carrera y plazas). Útil para controladores. */
    public function tieneColumnasCarreraYPlazas()
    {
        return $this->tieneColumnaCarrera() && $this->tieneColumnaPlazas();
    }

    // Obtener convenios completos con institución, tipo, carrera y plazas (si existen columnas)
    public function getConveniosCompletos()
    {
        $builder = $this->db->table('TAB_DETALLES_CONVENIOS dc')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = dc.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_CONVENIOS tc', 'tc.ID_TIPO_CONVENIO = dc.ID_TIPO_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION');

        if ($this->tieneColumnaCarrera()) {
            $builder->select('dc.*, ic.NOMBRE, ic.RUC, ic.REPRESENTANTE_LEGAL, ic.LOGO, tc.CONVENIO as TIPO_CONVENIO, ti.INSTITUCION as TIPO_INSTITUCION, c.NOMBRE as CARRERA_NOMBRE')
                ->join('TAB_CARRERAS c', 'c.ID_CARRERA = dc.ID_CARRERA', 'left');
        } else {
            $builder->select('dc.*, ic.NOMBRE, ic.RUC, ic.REPRESENTANTE_LEGAL, ic.LOGO, tc.CONVENIO as TIPO_CONVENIO, ti.INSTITUCION as TIPO_INSTITUCION, NULL as CARRERA_NOMBRE');
        }

        return $builder->get()->getResultArray();
    }
    
    // Obtener convenios por vencer (próximos 30 días)
    public function getConveniosPorVencer()
    {
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        $fechaActual = date('Y-m-d');
        
        return $this->where('FECHA_FIN >=', $fechaActual)
                    ->where('FECHA_FIN <=', $fechaLimite)
                    ->where('RENOVABLE', 1)
                    ->findAll();
    }
    
    // Obtener convenios por institución
    public function getConveniosPorInstitucion($idInstitucion)
    {
        return $this->where('ID_INSTITUCION_CONVENIO', $idInstitucion)->findAll();
    }

    /**
     * Convenios vigentes (FECHA_FIN >= hoy) destinados a una carrera.
     * Si la tabla no tiene ID_CARRERA, devuelve [] (ejecutar migración).
     */
    public function getConveniosVigentesPorCarrera($idCarrera)
    {
        if (empty($idCarrera) || !$this->tieneColumnaCarrera()) {
            return [];
        }
        $hoy = date('Y-m-d');
        $builder = $this->db->table('TAB_DETALLES_CONVENIOS dc')
            ->select('dc.*, ic.NOMBRE, ic.NOMBRE as NOMBRE_INSTITUCION, ic.RUC, ic.REPRESENTANTE_LEGAL, ic.LOGO, tc.CONVENIO as TIPO_CONVENIO, ti.INSTITUCION as TIPO_INSTITUCION, c.NOMBRE as CARRERA_NOMBRE')
            ->join('TAB_INSTITUCIONES_CONVENIOS ic', 'ic.ID_INSTITUCION_CONVENIO = dc.ID_INSTITUCION_CONVENIO')
            ->join('TAB_TIPOS_CONVENIOS tc', 'tc.ID_TIPO_CONVENIO = dc.ID_TIPO_CONVENIO')
            ->join('TAB_TIPOS_INSTITUCION ti', 'ti.ID_TIPO_INSTITUCION = ic.ID_TIPO_INSTITUCION')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = dc.ID_CARRERA', 'left')
            ->where('dc.FECHA_FIN >=', $hoy)
            ->where('dc.ID_CARRERA', (int) $idCarrera);
        return $builder->get()->getResultArray();
    }

    /**
     * Plazas ya ocupadas (estudiantes con práctica asignada) para una institución y carrera.
     * Cuenta prácticas preprofesionales + servicio comunitario en esa institución de estudiantes de esa carrera.
     */
    public function getPlazasOcupadas($idInstitucion, $idCarrera)
    {
        $db = $this->db;
        $pp = $db->table('TAB_PRACTICAS_PREPROFESIONALES pp')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = pp.ID_ESTUDIANTE')
            ->where('pp.ID_INSTITUCION_CONVENIO', (int) $idInstitucion)
            ->where('e.ID_CARRERA', (int) $idCarrera)
            ->countAllResults();
        $sc = $db->table('TAB_SERVICIO_COMUNITARIO sc')
            ->join('TAB_ESTUDIANTES e', 'e.ID_ESTUDIANTE = sc.ID_ESTUDIANTE')
            ->where('sc.ID_INSTITUCION_CONVENIO', (int) $idInstitucion)
            ->where('e.ID_CARRERA', (int) $idCarrera)
            ->countAllResults();
        return $pp + $sc;
    }
}