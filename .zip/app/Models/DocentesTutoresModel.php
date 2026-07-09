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
     * Datos personales del tutor: prioriza la persona del usuario (perfil),
     * para que coincida con lo editado en Mi Perfil.
     */
    private function builderConDatosPersonales()
    {
        return $this->db->table('TAB_DOCENTES_TUTORES dt')
            ->join('TAB_USUARIOS u', 'u.ID_USUARIO = dt.ID_USUARIO', 'left')
            ->join(
                'TAB_DATOS_PERSONAS dp',
                'dp.ID_DATO_PERSONA = COALESCE(u.ID_DATO_PERSONA, dt.ID_DATO_PERSONA)'
            );
    }

    /**
     * Obtener todos los docentes activos con datos personales.
     */
    public function getDocentesConDatos(): array
    {
        return $this->builderConDatosPersonales()
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL,
                      u.USUARIO, u.ESTADO as ESTADO_USUARIO')
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
        $row = $this->builderConDatosPersonales()
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL,
                      u.USUARIO, u.ESTADO as ESTADO_USUARIO')
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
        $row = $this->builderConDatosPersonales()
            ->select('dt.*, dp.NOMBRE, dp.APELLIDO, dp.CEDULA, dp.EMAIL, dp.CELULAR,
                      dp.DIRECCION, dp.GENERO, dp.ESTADO_CIVIL, dp.NACIONALIDAD, dp.FOTO_URL')
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
        return $this->builderConDatosPersonales()
            ->select('dt.ID_DOCENTE_TUTOR, CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO')
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
        $builder = $this->builderConDatosPersonales()
            ->where('dp.CEDULA', $cedula);

        if ($excluirId !== null) {
            $builder->where('dt.ID_DOCENTE_TUTOR !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
