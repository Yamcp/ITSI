<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'TAB_USUARIOS';
    protected $primaryKey = 'ID_USUARIO';
    protected $allowedFields = ['ID_DATO_PERSONA', 'USUARIO', 'CONTRASENA', 'ESTADO'];

    /**
     * Obtiene el rol habilitado del usuario directamente desde TAB_ROLES.
     */
    public function obtenerRolActivoPorUsuarioId(int $idUsuario): ?array
    {
        $query = $this->db->query("
            SELECT tr.ID_TIPOS_ROLES as rol, tr.ROL as nombre_rol
            FROM TAB_ROLES r
            INNER JOIN TAB_TIPOS_ROLES tr ON r.ID_TIPOS_ROLES = tr.ID_TIPOS_ROLES
            WHERE r.ID_USUARIO = ?
              AND tr.ID_TIPOS_ROLES IN (1, 2, 3, 4)
            ORDER BY r.ID_ROL ASC
            LIMIT 1
        ", [$idUsuario]);

        $row = $query->getRowArray();
        if (!$row) {
            return null;
        }

        $rol = (int) ($row['rol'] ?? $row['ROL'] ?? 0);
        if (!in_array($rol, [1, 2, 3, 4], true)) {
            return null;
        }

        return [
            'rol' => $rol,
            'nombre_rol' => $row['nombre_rol'] ?? $row['NOMBRE_ROL'] ?? null,
        ];
    }

    public function verificarUsuario($usuario, $contrasena)
    {
        try {
            $query = $this->db->query("
                SELECT 
                    u.ID_USUARIO as id,
                    u.USUARIO as username,
                    u.CONTRASENA as password_hash,
                    u.ESTADO as estado,
                    dp.NOMBRE as nombre,
                    dp.APELLIDO as apellido,
                    dp.EMAIL as email,
                    dp.FOTO_URL as foto_perfil,
                    dp.CEDULA as cedula
                FROM TAB_USUARIOS u
                INNER JOIN TAB_DATOS_PERSONAS dp ON u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA
                WHERE (u.USUARIO = ? OR dp.CEDULA = ?) AND u.ESTADO = '1'
                LIMIT 1
            ", [$usuario, $usuario]);

            $user = $query->getRow();

            if (!$user) {
                return [
                    'status' => false,
                    'mensaje' => 'Usuario o contraseña incorrectos',
                    'codigo' => 'credenciales',
                ];
            }

            $passwordValid = false;
            $requiereCambioPassword = false;

            if (password_verify($contrasena, $user->password_hash)) {
                $passwordValid = true;
            } elseif ($contrasena === $user->password_hash) {
                $passwordValid = true;
                $requiereCambioPassword = true;
            }

            if (!$passwordValid) {
                return [
                    'status' => false,
                    'mensaje' => 'Usuario o contraseña incorrectos',
                    'codigo' => 'credenciales',
                ];
            }

            $rolData = $this->obtenerRolActivoPorUsuarioId((int) $user->id);
            if (!$rolData) {
                return [
                    'status' => false,
                    'mensaje' => 'Su cuenta no tiene un rol asignado. Contacte al administrador o coordinador para solicitar acceso al sistema.',
                    'codigo' => 'sin_rol',
                ];
            }

            return [
                'status' => true,
                'usuario' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido,
                    'email' => $user->email,
                    'foto_perfil' => $user->foto_perfil ?? null,
                    'rol' => $rolData['rol'],
                    'nombre_rol' => $rolData['nombre_rol'],
                    'estado' => $user->estado,
                    'requiere_cambio_password' => $requiereCambioPassword,
                ],
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error en verificarUsuario: ' . $e->getMessage());
            return [
                'status' => false,
                'mensaje' => 'Error interno del sistema',
                'codigo' => 'error',
            ];
        }
    }

    /**
     * Crear un nuevo usuario con contraseña hasheada
     */
    public function crearUsuario($datosPersona, $usuario, $contrasena, $rol = 3)
    {
        $this->db->transStart();

        try {
            // Insertar datos personales
            $this->db->table('TAB_DATOS_PERSONAS')->insert($datosPersona);
            $idDatoPersona = $this->db->insertID();

            // Insertar usuario
            $datosUsuario = [
                'ID_DATO_PERSONA' => $idDatoPersona,
                'USUARIO' => $usuario,
                'CONTRASENA' => password_hash($contrasena, PASSWORD_DEFAULT),
                'ESTADO' => '1'
            ];
            $this->db->table('TAB_USUARIOS')->insert($datosUsuario);
            $idUsuario = $this->db->insertID();

            // Asignar rol
            $datosRol = [
                'ID_USUARIO' => $idUsuario,
                'ID_TIPOS_ROLES' => $rol
            ];
            $this->db->table('TAB_ROLES')->insert($datosRol);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return false;
            }

            return $idUsuario;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error creando usuario: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener información completa del usuario por ID
     */
    public function obtenerUsuarioPorId($idUsuario)
    {
        $query = $this->db->query("
            SELECT 
                u.ID_USUARIO as id,
                u.USUARIO as username,
                u.ESTADO as estado,
                dp.NOMBRE as nombre,
                dp.APELLIDO as apellido,
                dp.EMAIL as email,
                dp.CELULAR as celular,
                tr.ID_TIPOS_ROLES as rol,
                tr.ROL as nombre_rol
            FROM TAB_USUARIOS u
            INNER JOIN TAB_DATOS_PERSONAS dp ON u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA
            INNER JOIN TAB_ROLES r ON u.ID_USUARIO = r.ID_USUARIO
            INNER JOIN TAB_TIPOS_ROLES tr ON r.ID_TIPOS_ROLES = tr.ID_TIPOS_ROLES
            WHERE u.ID_USUARIO = ?
        ", [$idUsuario]);

        return $query->getRow();
    }

    /**
     * Verificar si un nombre de usuario ya existe
     */
    public function usuarioExiste($usuario, $excludeId = null)
    {
        $builder = $this->where('USUARIO', $usuario);
        
        if ($excludeId) {
            $builder->where('ID_USUARIO !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Buscar usuario por correo o nombre de usuario (recuperación de contraseña).
     * Aplica a todos los roles: coordinador, docente, estudiante.
     */
    public function buscarPorEmailOUsuario($emailOUsuario)
    {
        if (empty(trim($emailOUsuario))) {
            return null;
        }
        $valor = trim($emailOUsuario);
        $query = $this->db->query("
            SELECT u.ID_USUARIO, u.USUARIO, dp.EMAIL, dp.NOMBRE, dp.APELLIDO
            FROM TAB_USUARIOS u
            INNER JOIN TAB_DATOS_PERSONAS dp ON u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA
            WHERE (u.USUARIO = ? OR dp.EMAIL = ?) AND u.ESTADO = '1'
            LIMIT 1
        ", [$valor, $valor]);
        return $query->getRowArray();
    }

    /**
     * Actualizar contraseña a hash
     */
    public function actualizarPasswordHash($idUsuario, $contrasena)
    {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        return $this->update($idUsuario, ['CONTRASENA' => $hash]);
    }

    /**
     * Obtener perfil completo del usuario para la vista de perfil
     */
    public function getUserProfile($idUsuario)
    {
        try {
            $query = $this->db->query("
                SELECT 
                    u.ID_USUARIO,
                    u.USUARIO,
                    u.ESTADO,
                    u.CONTRASENA,
                    dp.ID_DATO_PERSONA,
                    dp.NOMBRE,
                    dp.APELLIDO,
                    dp.CEDULA,
                    dp.CELULAR,
                    dp.DIRECCION,
                    dp.EMAIL,
                    dp.GENERO,
                    dp.ESTADO_CIVIL,
                    dp.NACIONALIDAD,
                    dp.FECHA_INGRESO,
                    dp.ACTIVO,
                    dp.FOTO_URL,
                    tr.ID_TIPOS_ROLES,
                    tr.ROL,
                    r.ID_ROL,
                    CASE 
                        WHEN u.ESTADO = '1' THEN 'A'
                        ELSE 'I'
                    END as ESTADO_LETRA,
                    CASE 
                        WHEN dp.FECHA_INGRESO IS NOT NULL THEN dp.FECHA_INGRESO
                        ELSE u.ID_USUARIO
                    END as FECHA_REGISTRO
                FROM TAB_USUARIOS u
                INNER JOIN TAB_DATOS_PERSONAS dp ON u.ID_DATO_PERSONA = dp.ID_DATO_PERSONA
                INNER JOIN TAB_ROLES r ON u.ID_USUARIO = r.ID_USUARIO
                INNER JOIN TAB_TIPOS_ROLES tr ON r.ID_TIPOS_ROLES = tr.ID_TIPOS_ROLES
                WHERE u.ID_USUARIO = ?
                LIMIT 1
            ", [$idUsuario]);

            $result = $query->getRowArray();
            
            if ($result) {
                // Formatear campos para la vista
                $result['ESTADO'] = $result['ESTADO_LETRA'];
                $result['ROL'] = $result['ROL'];
                
                // Asegurar que los campos opcionales tengan valores por defecto
                $result['GENERO'] = $result['GENERO'] ?: '';
                $result['ESTADO_CIVIL'] = $result['ESTADO_CIVIL'] ?: '';
                $result['NACIONALIDAD'] = $result['NACIONALIDAD'] ?: '';
                $result['FECHA_INGRESO'] = $result['FECHA_INGRESO'] ?: '';
                $result['CELULAR'] = $result['CELULAR'] ?: '';
                $result['DIRECCION'] = $result['DIRECCION'] ?: '';
                $result['EMAIL'] = $result['EMAIL'] ?: '';
                
                return $result;
            }
            
            return null;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getUserProfile: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualizar perfil del usuario
     */
    public function actualizarPerfil($idUsuario, $datosPersona, $datosUsuario = [])
    {
        $this->db->transStart();
        
        try {
            // Obtener el ID_DATO_PERSONA del usuario
            $usuario = $this->find($idUsuario);
            if (!$usuario) {
                return false;
            }
            
            // Actualizar datos personales
            $this->db->table('TAB_DATOS_PERSONAS')
                     ->where('ID_DATO_PERSONA', $usuario['ID_DATO_PERSONA'])
                     ->update($datosPersona);
            
            // Actualizar datos de usuario si se proporcionan
            if (!empty($datosUsuario)) {
                $this->update($idUsuario, $datosUsuario);
            }
            
            $this->db->transComplete();
            
            return $this->db->transStatus() !== false;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error actualizando perfil: ' . $e->getMessage());
            return false;
        }
    }
}