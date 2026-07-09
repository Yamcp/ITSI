<?php

namespace App\Models;

use CodeIgniter\Model;

class ExportacionesModel extends Model
{
    protected $table = 'TAB_EXPORTACIONES';
    protected $primaryKey = 'ID_EXPORTACION';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ID_USUARIO',
        'FECHA_EXPORTACION',
        'DESCRIPCION_EXPORTACION',
        'TIPO_EXPORTACION',
        'ESTADO_EXPORTACION',
        'ARCHIVO_EXPORTACION',
        'TAMANO_ARCHIVO'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_CREACION';
    protected $updatedField = 'FECHA_ACTUALIZACION';

    // Validation
    protected $validationRules = [
        'ID_USUARIO' => 'required|integer',
        'FECHA_EXPORTACION' => 'required|valid_date',
        'DESCRIPCION_EXPORTACION' => 'required|min_length[5]|max_length[255]',
        'TIPO_EXPORTACION' => 'required|in_list[backup,export,report]',
        'ESTADO_EXPORTACION' => 'required|in_list[pendiente,en_proceso,completado,error]'
    ];

    protected $validationMessages = [
        'ID_USUARIO' => [
            'required' => 'El ID de usuario es requerido',
            'integer' => 'El ID de usuario debe ser un número entero'
        ],
        'FECHA_EXPORTACION' => [
            'required' => 'La fecha de exportación es requerida',
            'valid_date' => 'La fecha de exportación debe ser válida'
        ],
        'DESCRIPCION_EXPORTACION' => [
            'required' => 'La descripción es requerida',
            'min_length' => 'La descripción debe tener al menos 5 caracteres',
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        // Asegurar que la fecha de exportación esté en el formato correcto
        if (isset($data['data']['FECHA_EXPORTACION'])) {
            $data['data']['FECHA_EXPORTACION'] = date('Y-m-d H:i:s');
        }
        
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        // Actualizar la fecha de actualización
        $data['data']['FECHA_ACTUALIZACION'] = date('Y-m-d H:i:s');
        
        return $data;
    }

    // Método para obtener backups con información del usuario
    public function getBackupsWithUser()
    {
        return $this->select('
                TAB_EXPORTACIONES.*,
                TAB_USUARIOS.USUARIO,
                TAB_DATOS_PERSONAS.NOMBRE,
                TAB_DATOS_PERSONAS.APELLIDO
            ')
            ->join('TAB_USUARIOS', 'TAB_USUARIOS.ID_USUARIO = TAB_EXPORTACIONES.ID_USUARIO', 'left')
            ->join('TAB_DATOS_PERSONAS', 'TAB_DATOS_PERSONAS.ID_DATO_PERSONA = TAB_USUARIOS.ID_DATO_PERSONA', 'left')
            ->where('TAB_EXPORTACIONES.TIPO_EXPORTACION', 'backup')
            ->orderBy('TAB_EXPORTACIONES.FECHA_EXPORTACION', 'DESC')
            ->findAll();
    }

    // Método para obtener un backup específico con información del usuario
    public function getBackupWithUser($id)
    {
        return $this->select('
                TAB_EXPORTACIONES.*,
                TAB_USUARIOS.USUARIO,
                TAB_DATOS_PERSONAS.NOMBRE,
                TAB_DATOS_PERSONAS.APELLIDO
            ')
            ->join('TAB_USUARIOS', 'TAB_USUARIOS.ID_USUARIO = TAB_EXPORTACIONES.ID_USUARIO', 'left')
            ->join('TAB_DATOS_PERSONAS', 'TAB_DATOS_PERSONAS.ID_DATO_PERSONA = TAB_USUARIOS.ID_DATO_PERSONA', 'left')
            ->where('TAB_EXPORTACIONES.ID_EXPORTACION', $id)
            ->first();
    }

    // Método para crear un nuevo backup
    public function crearBackup($data)
    {
        $backupData = [
            'ID_USUARIO' => $data['ID_USUARIO'] ?? session('id_usuario'),
            'FECHA_EXPORTACION' => date('Y-m-d H:i:s'),
            'DESCRIPCION_EXPORTACION' => $data['DESCRIPCION_EXPORTACION'] ?? 'Backup del sistema',
            'TIPO_EXPORTACION' => 'backup',
            'ESTADO_EXPORTACION' => 'completado',
            'ARCHIVO_EXPORTACION' => $data['ARCHIVO_EXPORTACION'] ?? null,
            'TAMANO_ARCHIVO' => $data['TAMANO_ARCHIVO'] ?? null
        ];

        return $this->insert($backupData);
    }

    // Método para actualizar el estado de un backup
    public function actualizarEstado($id, $estado)
    {
        return $this->update($id, ['ESTADO_EXPORTACION' => $estado]);
    }
}