<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;

class DocumentosPracticasAdminController extends BaseController
{
    protected $documentosModel;
    protected $usuariosModel;
    protected $estadosRevisionesModel;
    protected $tiposDocumentosModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentosPracticasModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
        $this->tiposDocumentosModel = new TiposDocumentosPracticasModel();
    }

    /**
     * Mostrar la vista de gestión de documentos de prácticas
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Documentos de Prácticas',
            'documentos' => $this->getDocumentosCompletos(),
            'estadisticas' => $this->getEstadisticas(),
            'tipos_documentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'estudiantes' => $this->getEstudiantes()
        ];

        return view('admin/documentos/documentos_practicas', $data);
    }

    /**
     * Mostrar formulario de subida de documentos
     */
    public function upload()
    {
        $data = [
            'title' => 'Subir Documento de Práctica',
            'estudiantes' => $this->getEstudiantes(),
            'tipos_documentos' => $this->tiposDocumentosModel->findAll()
        ];

        return view('admin/documentos/DocumentosPracticas', $data);
    }

    /**
     * Procesar subida de documento
     */
    public function procesarSubida()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'id_practica' => 'required|integer',
            'id_tipo_documento' => 'required|integer',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf,doc,docx]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }

            $archivo = $this->request->getFile('archivo');

            if ($archivo->isValid() && !$archivo->hasMoved()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move(WRITEPATH . 'uploads/documentos-practicas/', $nombreArchivo);

            $data = [
                'ID_PRACTICA_PREPROFESIONAL' => $this->request->getPost('id_practica'),
                'ID_TIPO_DOCUMENTO' => $this->request->getPost('id_tipo_documento'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                'TIPO_ARCHIVO' => $archivo->getClientMimeType(),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                'ESTADO_REVISION' => 'Pendiente',
                'OBSERVACIONES' => $this->request->getPost('observaciones')
                ];

            if ($this->documentosModel->insert($data)) {
                    return $this->response->setJSON([
                        'success' => true,
                    'message' => 'Documento subido exitosamente'
                ]);
            } else {
            return $this->response->setJSON([
                'success' => false,
                    'message' => 'Error al guardar el documento'
            ]);
        }
    }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al subir el archivo'
        ]);
    }

    /**
     * Listar documentos por estudiante
     */
    public function listarPorEstudiante($idPractica)
    {
        $documentos = $this->documentosModel
            ->select('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.*, TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.NOMBRE as TIPO_DOCUMENTO_NOMBRE')
            ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES', 'TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES.ID_TIPO_DOCUMENTO_PREPROFESIONAL')
            ->where('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.ID_PRACTICA_PREPROFESIONAL', $idPractica)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES.FECHA_SUBIDA', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Cambiar estado de revisión del documento
     */
    public function cambiarEstado($id)
    {
        $nuevoEstado = $this->request->getPost('estado');
        $observaciones = $this->request->getPost('observaciones');

        $data = [
            'ESTADO_REVISION' => $nuevoEstado,
            'OBSERVACIONES' => $observaciones
        ];

        if ($this->documentosModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ]);
        }
    }

    /**
     * Descargar documento
     */
    public function descargar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setStatusCode(404, 'Documento no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($rutaArchivo)) {
            return $this->response->setStatusCode(404, 'Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }

    /**
     * Eliminar documento
     */
    public function eliminar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        // Eliminar archivo físico
        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        // Eliminar registro de la base de datos
        if ($this->documentosModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el documento'
            ]);
        }
    }

    /**
     * Obtener documentos completos con información relacionada
     */
    private function getDocumentosCompletos()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_DOCUMENTOS_PRACTICAS_PREPROFESIONALES dp')
            ->select('
                dp.*,
                tdp.NOMBRE as TIPO_DOCUMENTO_NOMBRE,
                pp.ID_ESTUDIANTE,
                CONCAT(persona.NOMBRE, " ", persona.APELLIDO) as ESTUDIANTE_NOMBRE
            ')
            ->join('TAB_TIPOS_DOCUMENTOS_PREPROFESIONALES tdp', 'dp.ID_TIPO_DOCUMENTO = tdp.ID_TIPO_DOCUMENTO_PREPROFESIONAL', 'left')
            ->join('TAB_PRACTICAS_PREPROFESIONALES pp', 'dp.ID_PRACTICA_PREPROFESIONAL = pp.ID_PRACTICA_PREPROFESIONAL', 'left')
            ->join('TAB_ESTUDIANTES e', 'pp.ID_ESTUDIANTE = e.ID_ESTUDIANTE', 'left')
            ->join('TAB_DATOS_PERSONAS persona', 'e.ID_DATO_PERSONA = persona.ID_DATO_PERSONA', 'left')
            ->orderBy('dp.FECHA_SUBIDA', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes()
    {
        return $this->documentosModel
            ->orderBy('FECHA_SUBIDA', 'DESC')
            ->limit(10)
            ->findAll();
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticas()
    {
        $total = $this->documentosModel->countAllResults();
        $aprobados = $this->documentosModel->where('ESTADO_REVISION', 'Aprobado')->countAllResults();
        $pendientes = $this->documentosModel->where('ESTADO_REVISION', 'Pendiente')->countAllResults();
        $rechazados = $this->documentosModel->where('ESTADO_REVISION', 'Rechazado')->countAllResults();

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados
        ];
    }

    /**
     * Obtener estudiantes
     */
    private function getEstudiantes()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('TAB_ESTUDIANTES e')
            ->select('
                e.ID_ESTUDIANTE,
                CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO,
                dp.CEDULA,
                c.NOMBRE as CARRERA
            ')
            ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA')
            ->join('TAB_CARRERAS c', 'c.ID_CARRERA = e.ID_CARRERA')
            ->where('e.ID_TIPO_ESTADO', 1) // Solo estudiantes activos
            ->orderBy('dp.NOMBRE', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Buscar documentos con filtros
     */
    public function buscarDocumentos()
    {
        $filtros = $this->request->getGet();
        
        $builder = $this->documentosModel;
        
        if (!empty($filtros['tipo_documento'])) {
            $builder->where('ID_TIPO_DOCUMENTO', $filtros['tipo_documento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('ESTADO_REVISION', $filtros['estado']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('DATE(FECHA_SUBIDA) >=', $filtros['fecha_desde']);
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('DATE(FECHA_SUBIDA) <=', $filtros['fecha_hasta']);
        }

        $documentos = $builder->orderBy('FECHA_SUBIDA', 'DESC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Exportar documentos
     */
    public function exportar($formato = 'excel')
    {
            $documentos = $this->getDocumentosCompletos();
            
        switch (strtolower($formato)) {
            case 'excel':
                return $this->exportarExcel($documentos);
            case 'pdf':
                return $this->exportarPDF($documentos);
            default:
            return $this->response->setJSON([
                'success' => true,
                    'data' => $documentos
            ]);
        }
    }

    /**
     * Exportar a Excel
     */
    private function exportarExcel($documentos)
    {
        try {
            helper('ExcelHelper');
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setTitle('Documentos de Prácticas');
            
            // Crear encabezado estándar
            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet, 
                'REPORTE DE DOCUMENTOS DE PRÁCTICAS', 
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'F1'
            );
            
            // Encabezados de columnas
            $headers = [
                'ID',
                'Estudiante',
                'Tipo Documento',
                'Archivo',
                'Fecha Subida',
                'Estado'
            ];
            
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');
            
            // Llenar datos
            $row = 6;
            foreach ($documentos as $doc) {
                $sheet->setCellValue('A' . $row, $doc['ID_DOCUMENTO_PREPROFESIONAL']);
                $sheet->setCellValue('B' . $row, $doc['ESTUDIANTE_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('C' . $row, $doc['TIPO_DOCUMENTO_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $doc['NOMBRE_ARCHIVO']);
                $sheet->setCellValue('E' . $row, date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])));
                $sheet->setCellValue('F' . $row, $doc['ESTADO_REVISION']);
                $row++;
            }
            
            // Aplicar estilos
            \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:F' . ($row - 1));
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'F');
            
            // Configurar descarga
            $filename = 'documentos_practicas_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar Excel: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar a PDF
     */
    private function exportarPDF($documentos)
    {
        // Implementar exportación a PDF si es necesario
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Exportación a PDF no implementada aún'
        ]);
    }

    /**
     * API: Obtener documentos para el grid
     */
    public function obtenerDocumentos()
    {
        try {
            $documentos = $this->getDocumentosCompletos();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $documentos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cargar documentos: ' . $e->getMessage()
            ]);
        }
    }
}