<?php

namespace App\Models;

use CodeIgniter\Model;

class DocentesTutoresModel extends Model
{
    protected $table = 'TAB_DOCENTES_TUTORES';
    protected $primaryKey = 'ID_DOCENTE_TUTOR';
    protected $allowedFields = [
        'ID_USUARIO',
        'ID_DATO_PERSONA',
        'ESPECIALIDAD',
        'TITULO_PROFESIONAL',
        'AREA_ESPECIALIZACION',
        'AÑOS_EXPERIENCIA',
        'ACTIVO',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';

    /**
     * Obtener todos los docentes activos con datos personales.
     */
    public function getDocentesConDatos(): array
    {
        return $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL,
                      u.USUARIO, u.ESTADO as ESTADO_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = dt.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_USUARIO = dt.ID_USUARIO', 'left')
            ->where('dt.ACTIVO', 1)
            ->orderBy('dp.APELLIDO', 'ASC')
            ->orderBy('dp.NOMBRE', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener un docente completo por su ID.
     */
    public function getDocenteCompleto(int $id): ?array
    {
        $row = $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL,
                      u.USUARIO, u.ESTADO as ESTADO_USUARIO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = dt.ID_DATO_PERSONA')
            ->join('TAB_USUARIOS u', 'u.ID_USUARIO = dt.ID_USUARIO', 'left')
            ->where('dt.ID_DOCENTE_TUTOR', $id)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Obtener docente por ID de usuario (para el perfil docente logueado).
     */
    public function getDocentePorUsuario(int $idUsuario): ?array
    {
        $row = $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = dt.ID_DATO_PERSONA')
            ->where('dt.ID_USUARIO', $idUsuario)
            ->where('dt.ACTIVO', 1)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Lista simplificada para selects (modal de prácticas).
     */
    public function getDocentesParaSelect(): array
    {
        return $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->select('dt.ID_DOCENTE_TUTOR, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = dt.ID_DATO_PERSONA')
            ->where('dt.ACTIVO', 1)
            ->orderBy('dp.APELLIDO', 'ASC')
            ->orderBy('dp.NOMBRE', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Verificar si una cédula ya está registrada como docente.
     */
    public function existeCedula(string $cedula, ?int $excluirId = null): bool
    {
        $builder = $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = dt.ID_DATO_PERSONA')
            ->where('dp.CEDULA', $cedula);

        if ($excluirId !== null) {
            $builder->where('dt.ID_DOCENTE_TUTOR !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
