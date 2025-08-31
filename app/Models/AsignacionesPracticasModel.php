<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionesPracticasModel extends Model
{
    protected $table            = 'TAB_ASIGNACIONES_PRACTICAS';
    protected $primaryKey       = 'ID_ASIGNACION_PRACTICA';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'ID_TIPO_PRACTICA',
        'ID_USUARIO',
        'ID_ESTADO_PRACTICAS',
        'ID_INSTITUCION_CONVENIO',
        'FECHA_INICIO',
        'FECHA_FIN',
        'HORA_TOTAL',
        'DESCRIPCION',
        'CRONOGRAMA',
    ];

    // Si tus columnas usan timestamps automáticos, ajústalo; de lo contrario, déjalo en false
    protected $useTimestamps = false;

    // Reglas de validación (opcional, ajústalas a tu necesidad)
    protected $validationRules = [
        'ID_TIPO_PRACTICA'       => 'required|is_natural_no_zero',
        'ID_USUARIO'             => 'required|is_natural_no_zero',
        'ID_ESTADO_PRACTICAS'    => 'required|is_natural_no_zero',
        'ID_INSTITUCION_CONVENIO'=> 'required|is_natural_no_zero',
        'FECHA_INICIO'           => 'required|valid_date',
        'FECHA_FIN'              => 'required|valid_date',
        'HORA_TOTAL'             => 'required|is_natural',
        'DESCRIPCION'            => 'required',
        'CRONOGRAMA'             => 'required',
    ];

    /**
     * Retorna asignaciones con información relacionada:
     * - Tipo de práctica
     * - Estado de práctica
     * - Institución de convenio
     * - Usuario y sus datos personales (nombre y apellido)
     *
     * @param int|null $id Si se envía, retorna un solo registro (array); si no, retorna lista (array[])
     */
    public function getAsignacionCompleta(int $id = null)
    {
        $builder = $this->builder()
            ->select("
                {$this->table}.*,
                TP.PRACTICA            AS TIPO_PRACTICA_NOMBRE,
                EP.ESTADO              AS ESTADO_PRACTICA_NOMBRE,
                IC.NOMBRE              AS INSTITUCION_NOMBRE,
                U.USUARIO              AS USUARIO_LOGIN,
                DP.NOMBRE              AS USUARIO_NOMBRE,
                DP.APELLIDO            AS USUARIO_APELLIDO
            ")
            ->join('TAB_TIPOS_PRACTICAS TP', 'TP.ID_TIPO_PRACTICA = ' . $this->table . '.ID_TIPO_PRACTICA', 'left')
            ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = ' . $this->table . '.ID_ESTADO_PRACTICAS', 'left')
            ->join('TAB_INSTITUCIONES_CONVENIOS IC', 'IC.ID_INSTITUCION_CONVENIO = ' . $this->table . '.ID_INSTITUCION_CONVENIO', 'left')
            ->join('TAB_USUARIOS U', 'U.ID_USUARIO = ' . $this->table . '.ID_USUARIO', 'left')
            ->join('TAB_DATOS_PERSONAS DP', 'DP.ID_DATO_PERSONA = U.ID_DATO_PERSONA', 'left')
            ->orderBy($this->table . '.ID_ASIGNACION_PRACTICA', 'DESC');

        if ($id !== null) {
            return $builder->where($this->table . '.ID_ASIGNACION_PRACTICA', $id)
                           ->get()
                           ->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Búsqueda simple por institución, usuario, tipo de práctica o estado.
     */
    public function buscar(string $term = '')
    {
        $builder = $this->builder()
            ->select("
                {$this->table}.*,
                TP.PRACTICA AS TIPO_PRACTICA_NOMBRE,
                EP.ESTADO   AS ESTADO_PRACTICA_NOMBRE,
                IC.NOMBRE   AS INSTITUCION_NOMBRE,
                CONCAT(DP.NOMBRE, ' ', DP.APELLIDO) AS USUARIO_COMPLETO
            ")
            ->join('TAB_TIPOS_PRACTICAS TP', 'TP.ID_TIPO_PRACTICA = ' . $this->table . '.ID_TIPO_PRACTICA', 'left')
            ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = ' . $this->table . '.ID_ESTADO_PRACTICAS', 'left')
            ->join('TAB_INSTITUCIONES_CONVENIOS IC', 'IC.ID_INSTITUCION_CONVENIO = ' . $this->table . '.ID_INSTITUCION_CONVENIO', 'left')
            ->join('TAB_USUARIOS U', 'U.ID_USUARIO = ' . $this->table . '.ID_USUARIO', 'left')
            ->join('TAB_DATOS_PERSONAS DP', 'DP.ID_DATO_PERSONA = U.ID_DATO_PERSONA', 'left');

        if ($term !== '') {
            $builder->groupStart()
                ->like('IC.NOMBRE', $term)
                ->orLike('TP.PRACTICA', $term)
                ->orLike('EP.ESTADO', $term)
                ->orLike('DP.NOMBRE', $term)
                ->orLike('DP.APELLIDO', $term)
                ->groupEnd();
        }

        return $builder->orderBy($this->table . '.ID_ASIGNACION_PRACTICA', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Paginación básica con filtros por estado y tipo de práctica.
     */
    public function listarPaginado(int $perPage = 10, int $page = 1, ?int $idEstado = null, ?int $idTipoPractica = null)
    {
        $builder = $this->builder()
            ->select("
                {$this->table}.*,
                TP.PRACTICA AS TIPO_PRACTICA_NOMBRE,
                EP.ESTADO   AS ESTADO_PRACTICA_NOMBRE,
                IC.NOMBRE   AS INSTITUCION_NOMBRE
            ")
            ->join('TAB_TIPOS_PRACTICAS TP', 'TP.ID_TIPO_PRACTICA = ' . $this->table . '.ID_TIPO_PRACTICA', 'left')
            ->join('TAB_ESTADO_PRACTICAS EP', 'EP.ID_ESTADO_PRACTICAS = ' . $this->table . '.ID_ESTADO_PRACTICAS', 'left')
            ->join('TAB_INSTITUCIONES_CONVENIOS IC', 'IC.ID_INSTITUCION_CONVENIO = ' . $this->table . '.ID_INSTITUCION_CONVENIO', 'left');

        if ($idEstado) {
            $builder->where($this->table . '.ID_ESTADO_PRACTICAS', $idEstado);
        }
        if ($idTipoPractica) {
            $builder->where($this->table . '.ID_TIPO_PRACTICA', $idTipoPractica);
        }

        $offset = ($page - 1) * $perPage;

        $data = $builder->orderBy($this->table . '.ID_ASIGNACION_PRACTICA', 'DESC')
                        ->get($perPage, $offset)
                        ->getResultArray();

        // Total para construir paginación
        $countBuilder = $this->builder();
        if ($idEstado) $countBuilder->where('ID_ESTADO_PRACTICAS', $idEstado);
        if ($idTipoPractica) $countBuilder->where('ID_TIPO_PRACTICA', $idTipoPractica);
        $total = $countBuilder->countAllResults();

        return ['data' => $data, 'total' => $total, 'perPage' => $perPage, 'page' => $page];
    }

    /**
     * Utilidad: si necesitas calcular horas totales a partir de hora_entrada/salida (para coherencia con tu controlador).
     */
    public function calcularHorasTrabajadas(string $horaEntrada, string $horaSalida): float
    {
        $entrada = strtotime($horaEntrada);
        $salida  = strtotime($horaSalida);
        if ($entrada === false || $salida === false || $salida < $entrada) {
            return 0.0;
        }
        $segundos = $salida - $entrada;
        return round($segundos / 3600, 2);
    }
}