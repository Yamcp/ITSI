<?php

namespace App\Models;
use CodeIgniter\Model;

class ActividadesEducacionModel extends Model
{
    protected $table = 'TAB_ACTIVIDADES_EDUCACION';
    protected $primaryKey = 'ID_ACTIVIDAD_EDUCACION';
    protected $allowedFields = [
        'ID_INSTRUCTOR', 'ID_TIPO_MODALIDAD', 'ID_TIPO_ACTIVIDAD', 'ID_USUARIO',
        'NOMBRE_ACTIVIDAD', 'DESCRIPCION', 'OBJETIVOS', 'DURACION_HORAS',
        'FECHA_INICIO', 'FECHA_FIN', 'LUGAR', 'ENLACE', 'HORARIO', 'INCLUYE_CERTIFICADO',
        'PROGRAMA_DETALLADO'
    ];
    protected $returnType = 'array';
    
    protected $validationRules = [
        'NOMBRE_ACTIVIDAD' => 'required|min_length[5]|max_length[255]',
        'ID_INSTRUCTOR' => 'required|integer',
        'ID_TIPO_MODALIDAD' => 'required|integer',
        'ID_TIPO_ACTIVIDAD' => 'required|integer',
        'ID_USUARIO' => 'required|integer',
        'DURACION_HORAS' => 'required|integer',
        'FECHA_INICIO' => 'required|valid_date',
        'FECHA_FIN' => 'required|valid_date',
        'DESCRIPCION' => 'required|min_length[10]',
        'OBJETIVOS' => 'required|min_length[10]',
        'LUGAR' => 'permit_empty|max_length[150]',
        'ENLACE' => 'permit_empty|max_length[500]',
        'HORARIO' => 'required|max_length[100]',
        'PROGRAMA_DETALLADO' => 'required',
        'INCLUYE_CERTIFICADO' => 'required|in_list[0,1]'
    ];
    
    // Validar que fecha fin >= fecha inicio
    protected $beforeInsert = ['validarFechas'];
    protected $beforeUpdate = ['validarFechas'];
    
    protected function validarFechas(array $data)
    {
        if (isset($data['data']['FECHA_INICIO']) && isset($data['data']['FECHA_FIN'])) {
            $fechaInicio = new \DateTime($data['data']['FECHA_INICIO']);
            $fechaFin = new \DateTime($data['data']['FECHA_FIN']);
            
            if ($fechaFin < $fechaInicio) {
                // La fecha de fin no puede ser anterior a la de inicio
                // En un entorno real, deberías manejar este error adecuadamente
                $data['data']['FECHA_FIN'] = $data['data']['FECHA_INICIO']; 
            }
        }
        return $data;
    }

    /**
     * Clasifica modalidad según el texto en TAB_TIPOS_MODALIDADES (presencial | virtual | hibrida).
     */
    public static function slugModalidadDesdeNombre(?string $nombreModalidad): string
    {
        $n = mb_strtolower(trim((string) $nombreModalidad));
        if ($n === '') {
            return '';
        }
        if (preg_match('/híbr|hibri|semi[\s\-]?presencial/u', $n)) {
            return 'hibrida';
        }
        if (preg_match('/virtual|en\s+l[ií]nea|l[ií]nea|remoto|online|distancia/u', $n)) {
            return 'virtual';
        }
        if (str_contains($n, 'presencial')) {
            return 'presencial';
        }

        return 'presencial';
    }

    /**
     * Reglas de validación request para lugar y enlace según slug de modalidad.
     *
     * @return array{lugar: string, enlace: string}
     */
    public static function reglasLugarEnlacePorSlug(string $slug): array
    {
        $lugar = 'permit_empty|max_length[150]';
        $enlace = 'permit_empty|max_length[500]';
        if ($slug === 'presencial') {
            $lugar = 'required|max_length[150]';
        } elseif ($slug === 'virtual') {
            $enlace = 'required|max_length[500]';
        } elseif ($slug === 'hibrida') {
            $lugar = 'required|max_length[150]';
            $enlace = 'required|max_length[500]';
        }

        return ['lugar' => $lugar, 'enlace' => $enlace];
    }

    /** @var bool|null */
    protected $cacheTieneColumnaEnlace = null;

    /**
     * True si en BD existe la columna ENLACE (evita fallar en consultas en servidores sin migrar).
     */
    public function tablaTieneColumnaEnlace(): bool
    {
        if ($this->cacheTieneColumnaEnlace !== null) {
            return $this->cacheTieneColumnaEnlace;
        }
        try {
            $q = $this->db->query(
                'SELECT COUNT(*) AS c FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                [$this->table, 'ENLACE']
            );
            $row = $q->getRowArray();
            $this->cacheTieneColumnaEnlace = ((int) ($row['c'] ?? 0)) > 0;
        } catch (\Throwable $e) {
            $this->cacheTieneColumnaEnlace = false;
        }

        return $this->cacheTieneColumnaEnlace;
    }
    
    // Obtener actividad con información relacionada
    public function getActividadCompleta($id)
    {
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.*, ta.ACTIVIDAD as TIPO_ACTIVIDAD, tm.MODALIDAD, i.ESPECIALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA')
            ->where('ae.ID_ACTIVIDAD_EDUCACION', $id);
            
        return $builder->get()->getRowArray();
    }
    
    // Obtener actividades activas (no finalizadas)
    public function getActividadesActivas()
    {
        return $this->where('FECHA_FIN >=', date('Y-m-d'))
                    ->orderBy('FECHA_INICIO', 'ASC')
                    ->findAll();
    }

    /**
     * Actividades vigentes con datos relacionados (para catálogo del docente/estudiante).
     */
    public function getActividadesVigentesConDatos()
    {
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.*, ta.ACTIVIDAD as ACTIVIDAD, tm.MODALIDAD, i.ESPECIALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD', 'left')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD', 'left')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
            ->where('ae.FECHA_FIN >=', date('Y-m-d'))
            ->orderBy('ae.FECHA_INICIO', 'ASC');

        return $builder->get()->getResultArray();
    }
    
    // Buscar actividades por tipo
    public function buscarPorTipo($idTipo)
    {
        return $this->where('ID_TIPO_ACTIVIDAD', $idTipo)->findAll();
    }
    
    // Obtener todas las actividades con información relacionada (sin duplicados)
    public function getActividadesConDatos()
    {
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.*, ta.ACTIVIDAD as ACTIVIDAD, tm.MODALIDAD, i.ESPECIALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD', 'left')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD', 'left')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
            ->orderBy('ae.FECHA_INICIO', 'DESC');
            
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener solo las actividades del instructor dado (para "Mis Actividades" del docente).
     */
    public function getActividadesConDatosPorInstructor($idInstructor)
    {
        if (empty($idInstructor) || $idInstructor <= 0) {
            return [];
        }
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.*, ta.ACTIVIDAD as ACTIVIDAD, tm.MODALIDAD, i.ESPECIALIDAD, dp.NOMBRE, dp.APELLIDO')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD', 'left')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD', 'left')
            ->join('TAB_INSTRUCTORES i', 'i.ID_INSTRUCTOR = ae.ID_INSTRUCTOR', 'left')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = i.ID_DATO_PERSONA', 'left')
            ->where('ae.ID_INSTRUCTOR', $idInstructor)
            ->orderBy('ae.FECHA_INICIO', 'DESC');
            
        return $builder->get()->getResultArray();
    }

    /**
     * Actividades en las que el estudiante está inscrito (para perfil: enlace registrado por coordinador).
     *
     * @return list<array<string, mixed>>
     */
    public function getActividadesInscritasParaPerfilEstudiante(int $idEstudiante): array
    {
        if ($idEstudiante < 1) {
            return [];
        }
        $builder = $this->db->table('TAB_INSCRIPCIONES_ACTIVIDADES ia')
            ->select('ae.ID_ACTIVIDAD_EDUCACION, ae.NOMBRE_ACTIVIDAD, ae.FECHA_INICIO, ae.FECHA_FIN, ae.HORARIO, tm.MODALIDAD, ta.ACTIVIDAD as TIPO_ACTIVIDAD')
            ->join('TAB_ACTIVIDADES_EDUCACION ae', 'ae.ID_ACTIVIDAD_EDUCACION = ia.ID_ACTIVIDAD_EDUCACION')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD', 'left')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD', 'left')
            ->where('ia.ID_ESTUDIANTE', $idEstudiante)
            ->orderBy('ae.FECHA_INICIO', 'DESC');
        if ($this->tablaTieneColumnaEnlace()) {
            $builder->select('ae.ENLACE', false);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Actividades donde el usuario es instructor (para perfil docente: enlace del coordinador).
     *
     * @return list<array<string, mixed>>
     */
    public function getActividadesInstructorParaPerfil(int $idInstructor): array
    {
        if ($idInstructor < 1) {
            return [];
        }
        $builder = $this->db->table('TAB_ACTIVIDADES_EDUCACION ae')
            ->select('ae.ID_ACTIVIDAD_EDUCACION, ae.NOMBRE_ACTIVIDAD, ae.FECHA_INICIO, ae.FECHA_FIN, ae.HORARIO, tm.MODALIDAD, ta.ACTIVIDAD as TIPO_ACTIVIDAD')
            ->join('TAB_TIPOS_MODALIDADES tm', 'tm.ID_TIPO_MODALIDAD = ae.ID_TIPO_MODALIDAD', 'left')
            ->join('TAB_TIPOS_ACTIVIDADES ta', 'ta.ID_TIPO_ACTIVIDAD = ae.ID_TIPO_ACTIVIDAD', 'left')
            ->where('ae.ID_INSTRUCTOR', $idInstructor)
            ->orderBy('ae.FECHA_INICIO', 'DESC');
        if ($this->tablaTieneColumnaEnlace()) {
            $builder->select('ae.ENLACE', false);
        }

        return $builder->get()->getResultArray();
    }
}