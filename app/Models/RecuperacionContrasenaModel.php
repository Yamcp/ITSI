<?php

namespace App\Models;

use CodeIgniter\Model;

class RecuperacionContrasenaModel extends Model
{
    protected $table            = 'TAB_RECUPERACION_CONTRASENA';
    protected $primaryKey       = 'ID_RECUPERACION';
    protected $allowedFields   = ['ID_USUARIO', 'TOKEN', 'EXPIRA_EN', 'USADO', 'CREADO_EN'];
    protected $useAutoIncrement = true;
    protected $useTimestamps   = false;
    protected $dateFormat      = 'datetime';
    protected $createdField   = 'CREADO_EN';

    /**
     * Crea un token de recuperación para el usuario (válido 1 hora)
     */
    public function crearToken(int $idUsuario): ?string
    {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Invalidar tokens anteriores del mismo usuario
        $this->where('ID_USUARIO', $idUsuario)->set(['USADO' => 1])->update();

        $this->insert([
            'ID_USUARIO' => $idUsuario,
            'TOKEN'      => $token,
            'EXPIRA_EN'  => $expira,
            'USADO'      => 0,
            'CREADO_EN'  => date('Y-m-d H:i:s'),
        ]);

        return $this->getInsertID() ? $token : null;
    }

    /**
     * Valida el token y devuelve el ID_USUARIO si es válido, null si no
     */
    public function validarToken(string $token): ?int
    {
        $row = $this->where('TOKEN', $token)
            ->where('USADO', 0)
            ->where('EXPIRA_EN >', date('Y-m-d H:i:s'))
            ->first();

        return $row ? (int) $row['ID_USUARIO'] : null;
    }

    /**
     * Marca el token como usado
     */
    public function marcarUsado(string $token): bool
    {
        return $this->where('TOKEN', $token)->set(['USADO' => 1])->update() !== false;
    }
}
