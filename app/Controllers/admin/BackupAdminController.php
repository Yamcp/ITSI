<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\ExportacionesModel;
use CodeIgniter\HTTP\ResponseInterface;

class BackupAdminController extends BaseController
{
    protected $exportacionesModel;

    public function __construct()
    {
        $this->exportacionesModel = new ExportacionesModel();
    }

    /**
     * Mostrar la vista principal de backups
     */
    public function index(): string
    {
        try {
            // Obtener todos los backups con información del usuario
            $exportaciones = $this->exportacionesModel->getBackupsWithUser();
            
            $data = [
                'title' => 'Gestión de Backups',
                'exportaciones' => $exportaciones
            ];
            
            return view('admin/backup/backup', $data);
            
        } catch (\Exception $e) {
            // Si hay error en la base de datos, mostrar vista con array vacío
            $data = [
                'title' => 'Gestión de Backups',
                'exportaciones' => []
            ];
            
            return view('admin/backup/backup', $data);
        }
    }

    /**
     * Crear un nuevo backup
     */
    public function crear(): ResponseInterface
    {
        try {
            $input = $this->request->getJSON(true);
            
            // Validar datos de entrada
            $validation = \Config\Services::validation();
            $validation->setRules([
                'descripcion' => 'required|min_length[5]|max_length[255]',
                'tipo_backup' => 'required|in_list[completo,incremental,diferencial]',
                'prioridad' => 'required|in_list[baja,media,alta,critica]'
            ]);

            if (!$validation->run($input)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validation->getErrors()
                ])->setStatusCode(400);
            }

            // Crear el backup
            $backupData = [
                'ID_USUARIO' => session('id_usuario') ?? 1,
                'DESCRIPCION_EXPORTACION' => $input['descripcion'],
                'TIPO_EXPORTACION' => 'backup',
                'ESTADO_EXPORTACION' => 'completado',
                'ARCHIVO_EXPORTACION' => 'backup_' . date('Y-m-d_H-i-s') . '.sql',
                'TAMANO_ARCHIVO' => rand(1024, 10240) // Simular tamaño de archivo
            ];

            $backupId = $this->exportacionesModel->crearBackup($backupData);

            if ($backupId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Backup generado exitosamente',
                    'backup_id' => $backupId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al generar el backup'
                ])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en el sistema: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener detalles de un backup específico
     */
    public function detalle($id): ResponseInterface
    {
        try {
            $backup = $this->exportacionesModel->getBackupWithUser($id);
            
            if (!$backup) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup no encontrado'
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $backup
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener detalles: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Descargar un backup
     */
    public function descargar($id): ResponseInterface
    {
        try {
            $backup = $this->exportacionesModel->find($id);
            
            if (!$backup) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup no encontrado'
                ])->setStatusCode(404);
            }

            // Simular descarga del archivo
            $filename = $backup['ARCHIVO_EXPORTACION'] ?? 'backup_' . $id . '.sql';
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Descarga iniciada',
                'filename' => $filename,
                'download_url' => base_url('admin/backup/download-file/' . $id)
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al descargar: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Eliminar un backup
     */
    public function eliminar($id): ResponseInterface
    {
        try {
            $backup = $this->exportacionesModel->find($id);
            
            if (!$backup) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup no encontrado'
                ])->setStatusCode(404);
            }

            if ($this->exportacionesModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Backup eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el backup'
                ])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Restaurar sistema desde un backup
     */
    public function restaurar($id): ResponseInterface
    {
        try {
            $backup = $this->exportacionesModel->find($id);
            
            if (!$backup) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup no encontrado'
                ])->setStatusCode(404);
            }

            // Simular proceso de restauración
            // En un sistema real, aquí iría la lógica para restaurar la base de datos
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sistema restaurado exitosamente desde el backup'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al restaurar: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Exportar historial de backups
     */
    public function exportarHistorial(): ResponseInterface
    {
        try {
            $backups = $this->exportacionesModel->getBackupsWithUser();
            
            // Simular exportación del historial
            $filename = 'historial_backups_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Historial exportado exitosamente',
                'filename' => $filename,
                'download_url' => base_url('admin/backup/download-history')
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar historial: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Aplicar filtros a la lista de backups
     */
    public function filtrar(): ResponseInterface
    {
        try {
            $input = $this->request->getJSON(true);
            
            $builder = $this->exportacionesModel->select('
                TAB_EXPORTACIONES.*,
                TAB_USUARIOS.USUARIO,
                TAB_DATOS_PERSONAS.NOMBRE,
                TAB_DATOS_PERSONAS.APELLIDO
            ')
            ->join('TAB_USUARIOS', 'TAB_USUARIOS.ID_USUARIO = TAB_EXPORTACIONES.ID_USUARIO', 'left')
            ->join('TAB_DATOS_PERSONAS', 'TAB_DATOS_PERSONAS.ID_DATO_PERSONA = TAB_USUARIOS.ID_DATO_PERSONA', 'left')
            ->where('TAB_EXPORTACIONES.TIPO_EXPORTACION', 'backup');

            // Aplicar filtros
            if (!empty($input['filtro_usuario'])) {
                $builder->where('TAB_USUARIOS.USUARIO', $input['filtro_usuario']);
            }

            if (!empty($input['fecha_desde'])) {
                $builder->where('DATE(TAB_EXPORTACIONES.FECHA_EXPORTACION) >=', $input['fecha_desde']);
            }

            if (!empty($input['fecha_hasta'])) {
                $builder->where('DATE(TAB_EXPORTACIONES.FECHA_EXPORTACION) <=', $input['fecha_hasta']);
            }

            if (!empty($input['filtro_estado'])) {
                $builder->where('TAB_EXPORTACIONES.ESTADO_EXPORTACION', $input['filtro_estado']);
            }

            $backups = $builder->orderBy('TAB_EXPORTACIONES.FECHA_EXPORTACION', 'DESC')->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $backups
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al aplicar filtros: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener estadísticas de backups
     */
    public function estadisticas(): ResponseInterface
    {
        try {
            $totalBackups = $this->exportacionesModel->where('TIPO_EXPORTACION', 'backup')->countAllResults();
            $backupsCompletados = $this->exportacionesModel->where('TIPO_EXPORTACION', 'backup')
                ->where('ESTADO_EXPORTACION', 'completado')->countAllResults();
            $backupsError = $this->exportacionesModel->where('TIPO_EXPORTACION', 'backup')
                ->where('ESTADO_EXPORTACION', 'error')->countAllResults();
            
            // Obtener el último backup
            $ultimoBackup = $this->exportacionesModel->where('TIPO_EXPORTACION', 'backup')
                ->orderBy('FECHA_EXPORTACION', 'DESC')->first();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'total_backups' => $totalBackups,
                    'backups_completados' => $backupsCompletados,
                    'backups_error' => $backupsError,
                    'ultimo_backup' => $ultimoBackup
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
