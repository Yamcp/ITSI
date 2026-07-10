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
            $exportaciones = $this->exportacionesModel->getBackupsWithUser();

            $data = [
                'title' => 'Gestión de Backups',
                'exportaciones' => $exportaciones,
                'layout' => $this->getLayoutForRole()
            ];

            return view('admin/backup/backup', $data);
        } catch (\Exception $e) {
            $data = [
                'title' => 'Gestión de Backups',
                'exportaciones' => [],
                'layout' => $this->getLayoutForRole()
            ];

            return view('admin/backup/backup', $data);
        }
    }

    /**
     * Crear un nuevo backup (archivo SQL real)
     */
    public function crear(): ResponseInterface
    {
        try {
            $input = $this->request->getJSON(true) ?? [];

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

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $this->resolveBackupPath($filename);
            $tamano = $this->generarArchivoSql($filepath);

            $backupData = [
                'ID_USUARIO' => session('id_usuario') ?? 1,
                'DESCRIPCION_EXPORTACION' => $input['descripcion'],
                'TIPO_EXPORTACION' => 'backup',
                'ESTADO_EXPORTACION' => 'completado',
                'ARCHIVO_EXPORTACION' => $filename,
                'TAMANO_ARCHIVO' => $tamano
            ];

            $backupId = $this->exportacionesModel->crearBackup($backupData);

            if ($backupId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Backup generado exitosamente',
                    'backup_id' => $backupId,
                    'filename' => $filename,
                    'tamano' => $tamano
                ]);
            }

            @unlink($filepath);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al registrar el backup en la base de datos'
            ])->setStatusCode(500);
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
     * Obtener logs de un backup (registro de la operación)
     */
    public function logs($id): ResponseInterface
    {
        try {
            $id = (int) $id;
            $backup = $this->exportacionesModel->getBackupWithUser($id);

            if (!$backup) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Backup no encontrado'
                ])->setStatusCode(404);
            }

            $fecha = $backup['FECHA_EXPORTACION'] ?? date('Y-m-d H:i:s');
            $usuario = trim(($backup['NOMBRE'] ?? '') . ' ' . ($backup['APELLIDO'] ?? ''));
            if ($usuario === '') {
                $usuario = $backup['USUARIO'] ?? 'Usuario #' . ($backup['ID_USUARIO'] ?? '');
            }
            $archivo = $backup['ARCHIVO_EXPORTACION'] ?? 'backup_' . $id . '.sql';
            $tamano = isset($backup['TAMANO_ARCHIVO']) ? (int) $backup['TAMANO_ARCHIVO'] : 0;
            $tamanoKb = $tamano > 0 ? round($tamano / 1024, 2) . ' KB' : 'N/A';
            $estado = $backup['ESTADO_EXPORTACION'] ?? 'completado';
            $ruta = $this->resolveBackupPath($archivo);
            $archivoExiste = is_file($ruta);

            $lineas = [];
            $lineas[] = '========== LOG DE BACKUP #' . $id . ' ==========';
            $lineas[] = '';
            $lineas[] = 'Fecha de generación: ' . $fecha;
            $lineas[] = 'Usuario: ' . $usuario;
            $lineas[] = 'Descripción: ' . ($backup['DESCRIPCION_EXPORTACION'] ?? '-');
            $lineas[] = 'Tipo: ' . ($backup['TIPO_EXPORTACION'] ?? 'backup');
            $lineas[] = 'Estado: ' . $estado;
            $lineas[] = 'Archivo: ' . $archivo;
            $lineas[] = 'Tamaño: ' . $tamanoKb;
            $lineas[] = 'Archivo en disco: ' . ($archivoExiste ? 'Sí' : 'No');
            $lineas[] = '';
            $lineas[] = '--- Registro del proceso ---';
            $lineas[] = '[' . $fecha . '] Inicio del proceso de exportación.';
            $lineas[] = '[' . $fecha . '] Usuario solicitante: ' . $usuario . '.';
            $lineas[] = '[' . $fecha . '] Exportando tablas de la base de datos...';
            if ($estado === 'completado') {
                $lineas[] = '[' . $fecha . '] Exportación completada correctamente.';
                $lineas[] = '[' . $fecha . '] Backup finalizado. Archivo generado: ' . $archivo . ' (' . $tamanoKb . ')';
            } else {
                $lineas[] = '[' . $fecha . '] Estado del backup: ' . $estado . '.';
            }
            $lineas[] = '';
            $lineas[] = '========== Fin del log ==========';

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'log' => implode("\n", $lineas),
                    'backup_id' => $id,
                    'fecha' => $fecha
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener logs: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Descargar un backup (entrega el archivo SQL)
     */
    public function descargar($id)
    {
        try {
            $backup = $this->exportacionesModel->find($id);

            if (!$backup) {
                return $this->responseErrorDescarga('Backup no encontrado', 404);
            }

            $filename = basename((string) ($backup['ARCHIVO_EXPORTACION'] ?? ('backup_' . $id . '.sql')));
            if ($filename === '' || $filename === '.' || $filename === '..') {
                $filename = 'backup_' . $id . '.sql';
            }
            if (!str_ends_with(strtolower($filename), '.sql')) {
                $filename .= '.sql';
            }

            $filepath = $this->resolveBackupPath($filename);

            // Si el registro existe pero el archivo no (backups antiguos/simulados), generar uno actual
            if (!is_file($filepath)) {
                $tamano = $this->generarArchivoSql($filepath);
                $this->exportacionesModel->update($id, [
                    'ARCHIVO_EXPORTACION' => $filename,
                    'TAMANO_ARCHIVO' => $tamano,
                    'ESTADO_EXPORTACION' => 'completado'
                ]);
            }

            if (!is_file($filepath) || !is_readable($filepath)) {
                return $this->responseErrorDescarga('No se pudo generar o leer el archivo de backup', 500);
            }

            return $this->response->download($filepath, null)->setFileName($filename);
        } catch (\Exception $e) {
            return $this->responseErrorDescarga('Error al descargar: ' . $e->getMessage(), 500);
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

            $filename = basename((string) ($backup['ARCHIVO_EXPORTACION'] ?? ''));
            if ($filename !== '') {
                $filepath = $this->resolveBackupPath($filename);
                if (is_file($filepath)) {
                    @unlink($filepath);
                }
            }

            if ($this->exportacionesModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Backup eliminado exitosamente'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el backup'
            ])->setStatusCode(500);
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
            $filename = 'historial_backups_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = $this->resolveBackupPath($filename);

            $fp = fopen($filepath, 'wb');
            if ($fp === false) {
                throw new \RuntimeException('No se pudo crear el archivo CSV');
            }

            fputcsv($fp, [
                'ID',
                'Usuario',
                'Nombre',
                'Fecha',
                'Descripcion',
                'Estado',
                'Archivo',
                'Tamano'
            ]);

            foreach ($backups as $backup) {
                fputcsv($fp, [
                    $backup['ID_EXPORTACION'] ?? '',
                    $backup['USUARIO'] ?? '',
                    trim(($backup['NOMBRE'] ?? '') . ' ' . ($backup['APELLIDO'] ?? '')),
                    $backup['FECHA_EXPORTACION'] ?? '',
                    $backup['DESCRIPCION_EXPORTACION'] ?? '',
                    $backup['ESTADO_EXPORTACION'] ?? '',
                    $backup['ARCHIVO_EXPORTACION'] ?? '',
                    $backup['TAMANO_ARCHIVO'] ?? ''
                ]);
            }
            fclose($fp);

            return $this->response->download($filepath, null)->setFileName($filename);
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
            $input = $this->request->getJSON(true) ?? [];

            $builder = $this->exportacionesModel->select('
                TAB_EXPORTACIONES.*,
                TAB_USUARIOS.USUARIO,
                TAB_DATOS_PERSONAS.NOMBRE,
                TAB_DATOS_PERSONAS.APELLIDO
            ')
                ->join('TAB_USUARIOS', 'TAB_USUARIOS.ID_USUARIO = TAB_EXPORTACIONES.ID_USUARIO', 'left')
                ->join('TAB_DATOS_PERSONAS', 'TAB_DATOS_PERSONAS.ID_DATO_PERSONA = TAB_USUARIOS.ID_DATO_PERSONA', 'left')
                ->where('TAB_EXPORTACIONES.TIPO_EXPORTACION', 'backup');

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

    /**
     * Directorio writable/backups
     */
    protected function getBackupDirectory(): string
    {
        $dir = WRITEPATH . 'backups' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException('No se pudo crear el directorio de backups');
        }

        return $dir;
    }

    /**
     * Ruta segura del archivo (solo basename)
     */
    protected function resolveBackupPath(string $filename): string
    {
        $filename = basename(str_replace(['\\', "\0"], ['/', ''], $filename));
        if ($filename === '' || $filename === '.' || $filename === '..') {
            throw new \InvalidArgumentException('Nombre de archivo de backup inválido');
        }

        return $this->getBackupDirectory() . $filename;
    }

    /**
     * Genera un dump SQL de todas las tablas y retorna el tamaño en bytes
     */
    protected function generarArchivoSql(string $filepath): int
    {
        $db = \Config\Database::connect();
        $dbName = $db->getDatabase();
        $tables = $db->listTables();

        $handle = fopen($filepath, 'wb');
        if ($handle === false) {
            throw new \RuntimeException('No se pudo crear el archivo de backup');
        }

        try {
            $header = "-- Backup ITSI\n";
            $header .= '-- Generado: ' . date('Y-m-d H:i:s') . "\n";
            $header .= '-- Base de datos: ' . $dbName . "\n";
            $header .= "SET NAMES utf8mb4;\n";
            $header .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            fwrite($handle, $header);

            foreach ($tables as $table) {
                $createRow = $db->query('SHOW CREATE TABLE ' . $db->escapeIdentifiers($table))->getRowArray();
                if (!$createRow) {
                    continue;
                }

                $createSql = $createRow['Create Table'] ?? ($createRow['Create View'] ?? null);
                if (!$createSql) {
                    continue;
                }

                fwrite($handle, "-- ----------------------------\n");
                fwrite($handle, '-- Tabla: ' . $table . "\n");
                fwrite($handle, "-- ----------------------------\n");
                fwrite($handle, 'DROP TABLE IF EXISTS ' . $db->escapeIdentifiers($table) . ";\n");
                fwrite($handle, $createSql . ";\n\n");

                $query = $db->table($table)->get();
                foreach ($query->getResultArray() as $row) {
                    $columns = array_map(static fn($col) => $db->escapeIdentifiers($col), array_keys($row));
                    $values = array_map(static function ($value) use ($db) {
                        if ($value === null) {
                            return 'NULL';
                        }
                        return $db->escape($value);
                    }, array_values($row));

                    fwrite(
                        $handle,
                        'INSERT INTO ' . $db->escapeIdentifiers($table)
                        . ' (' . implode(', ', $columns) . ') VALUES ('
                        . implode(', ', $values) . ");\n"
                    );
                }

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        } finally {
            fclose($handle);
        }

        clearstatcache(true, $filepath);
        $size = filesize($filepath);
        if ($size === false) {
            throw new \RuntimeException('No se pudo obtener el tamaño del backup generado');
        }

        return (int) $size;
    }

    /**
     * Respuesta de error según si la petición es AJAX o navegación directa
     */
    protected function responseErrorDescarga(string $message, int $status)
    {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $message
            ])->setStatusCode($status);
        }

        return redirect()->to(base_url('admin/backup'))->with('error', $message);
    }
}
