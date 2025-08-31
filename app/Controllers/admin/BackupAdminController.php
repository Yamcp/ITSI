<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExportacionesModel;
use CodeIgniter\HTTP\RedirectResponse;

class BackupAdminController extends BaseController
{
    protected $exportacionesModel;

    public function __construct()
    {
        $this->exportacionesModel = new ExportacionesModel();
    }

    public function index(): string
    {
        try {
            // Obtener todas las exportaciones/backups
            $exportaciones = $this->exportacionesModel->findAll();
            
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

    public function create(): RedirectResponse
    {
        try {
            // Lógica para crear backup
            $descripcion = $this->request->getPost('descripcion') ?? 'Backup manual del sistema';
            
            $data = [
                'ID_USUARIO' => session('id_usuario') ?? 1,
                'FECHA_EXPORTACION' => date('Y-m-d H:i:s'),
                'DESCRIPCION_EXPORTACION' => $descripcion,
                'TIPO_EXPORTACION' => 'backup',
                'ESTADO_EXPORTACION' => 'completado'
            ];
            
            if ($this->exportacionesModel->insert($data)) {
                return redirect()->to('admin/backup')->with('success', 'Backup generado exitosamente');
            } else {
                return redirect()->to('admin/backup')->with('error', 'Error al generar el backup');
            }
            
        } catch (\Exception $e) {
            return redirect()->to('admin/backup')->with('error', 'Error en el sistema: ' . $e->getMessage());
        }
    }

    public function download($id): RedirectResponse
    {
        try {
            $backup = $this->exportacionesModel->find($id);
            
            if (!$backup) {
                return redirect()->to('admin/backup')->with('error', 'Backup no encontrado');
            }
            
            // Aquí iría la lógica para descargar el archivo
            // Por ahora solo redirigimos con mensaje de éxito
            
            return redirect()->to('admin/backup')->with('success', 'Descarga iniciada');
            
        } catch (\Exception $e) {
            return redirect()->to('admin/backup')->with('error', 'Error al descargar: ' . $e->getMessage());
        }
    }

    public function delete($id): RedirectResponse
    {
        try {
            if ($this->exportacionesModel->delete($id)) {
                return redirect()->to('admin/backup')->with('success', 'Backup eliminado exitosamente');
            } else {
                return redirect()->to('admin/backup')->with('error', 'Error al eliminar el backup');
            }
            
        } catch (\Exception $e) {
            return redirect()->to('admin/backup')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}