<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;
use App\Models\TiposDocumentosServicioComunitarioModel;

class DocumentosUnificadosController extends BaseController
{
    protected $documentosPracticasModel;
    protected $documentosServicioModel;
    protected $usuariosModel;
    protected $estadosRevisionesModel;
    protected $tiposDocumentosPracticasModel;
    protected $tiposDocumentosServicioModel;

    public function __construct()
    {
        $this->documentosPracticasModel = new DocumentosPracticasModel();
        $this->documentosServicioModel = new DocumentosServicioComunitarioModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
        $this->tiposDocumentosPracticasModel = new TiposDocumentosPracticasModel();
        $this->tiposDocumentosServicioModel = new TiposDocumentosServicioComunitarioModel();
    }

    /**
     * Mostrar la vista unificada de gestión de documentos
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión Unificada de Documentos',
            'estadisticas' => $this->getEstadisticasUnificadas(),
            'tipos_documentos_practicas' => $this->tiposDocumentosPracticasModel->getAllTipos(),
            'tipos_documentos_servicio' => $this->getTiposDocumentosServicio(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'estudiantes' => $this->getEstudiantes()
        ];

        return view('admin/documentos/vista_unificada_documentos', $data);
    }

    /**
     * Obtener estadísticas unificadas de todos los documentos
     */
    public function getEstadisticasUnificadas()
    {
        // Estadísticas de documentos de prácticas
        $totalPracticas = $this->documentosPracticasModel->countAllResults();
        $aprobadosPracticas = $this->documentosPracticasModel->where('ID_ESTADO_REVISION', 3)->countAllResults();
        $pendientesPracticas = $this->documentosPracticasModel->where('ID_ESTADO_REVISION', 1)->countAllResults();
        $rechazadosPracticas = $this->documentosPracticasModel->where('ID_ESTADO_REVISION', 4)->countAllResults();

        // Estadísticas de documentos de servicio comunitario
        $totalServicio = $this->documentosServicioModel->countAllResults();
        $aprobadosServicio = $this->documentosServicioModel->where('ESTADO_REVISION', 'Aprobado')->countAllResults();
        $pendientesServicio = $this->documentosServicioModel->where('ESTADO_REVISION', 'Pendiente')->countAllResults();
        $rechazadosServicio = $this->documentosServicioModel->where('ESTADO_REVISION', 'Rechazado')->countAllResults();

        // Totales unificados
        $total = $totalPracticas + $totalServicio;
        $aprobados = $aprobadosPracticas + $aprobadosServicio;
        $pendientes = $pendientesPracticas + $pendientesServicio;
        $rechazados = $rechazadosPracticas + $rechazadosServicio;

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados,
            'practicas' => [
                'total' => $totalPracticas,
                'aprobados' => $aprobadosPracticas,
                'pendientes' => $pendientesPracticas,
                'rechazados' => $rechazadosPracticas
            ],
            'servicio' => [
                'total' => $totalServicio,
                'aprobados' => $aprobadosServicio,
                'pendientes' => $pendientesServicio,
                'rechazados' => $rechazadosServicio
            ]
        ];
    }

    /**
     * Obtener documentos por tipo con estadísticas
     */
    public function getDocumentosPorTipo($tipo = 'todos')
    {
        $resultados = [];

        if ($tipo === 'todos' || $tipo === 'practicas') {
            // Obtener tipos de documentos de prácticas con estadísticas
            $tiposPracticas = $this->tiposDocumentosPracticasModel->getAllTipos();
            
            foreach ($tiposPracticas as $tipoDoc) {
                $estadisticas = $this->getEstadisticasPorTipoPractica($tipoDoc['ID_TIPO_DOCUMENTO']);
                
                $resultados[] = [
                    'id' => $tipoDoc['ID_TIPO_DOCUMENTO'],
                    'tipo' => 'practicas',
                    'codigo' => $tipoDoc['CODIGO'],
                    'nombre' => $tipoDoc['NOMBRE'],
                    'descripcion' => $tipoDoc['DESCRIPCION'],
                    'requerido' => $tipoDoc['REQUERIDO'],
                    'orden' => $tipoDoc['ORDEN'],
                    'estadisticas' => $estadisticas,
                    'icono' => $this->getIconoTipoDocumento($tipoDoc['CODIGO']),
                    'color' => 'practicas'
                ];
            }
        }

        if ($tipo === 'todos' || $tipo === 'servicio') {
            // Obtener tipos de documentos de servicio comunitario con estadísticas
            $tiposServicio = $this->getTiposDocumentosServicio();
            
            foreach ($tiposServicio as $tipoDoc) {
                $estadisticas = $this->getEstadisticasPorTipoServicio($tipoDoc['ID_TIPO_DOCUMENTO_SERVICIO']);
                
                $resultados[] = [
                    'id' => $tipoDoc['ID_TIPO_DOCUMENTO_SERVICIO'],
                    'tipo' => 'servicio',
                    'codigo' => $tipoDoc['CODIGO'] ?? 'SC.' . $tipoDoc['ID_TIPO_DOCUMENTO_SERVICIO'],
                    'nombre' => $tipoDoc['NOMBRE'],
                    'descripcion' => $tipoDoc['DESCRIPCION'] ?? '',
                    'requerido' => $tipoDoc['OBLIGATORIO'] ?? true,
                    'orden' => $tipoDoc['ORDEN'] ?? $tipoDoc['ID_TIPO_DOCUMENTO_SERVICIO'],
                    'estadisticas' => $estadisticas,
                    'icono' => $this->getIconoTipoDocumentoServicio($tipoDoc['NOMBRE']),
                    'color' => 'servicio'
                ];
            }
        }

        // Ordenar por tipo y orden
        usort($resultados, function($a, $b) {
            if ($a['tipo'] === $b['tipo']) {
                return $a['orden'] - $b['orden'];
            }
            return $a['tipo'] === 'practicas' ? -1 : 1;
        });

        return $resultados;
    }

    /**
     * Obtener estadísticas por tipo de documento de prácticas
     */
    private function getEstadisticasPorTipoPractica($idTipoDocumento)
    {
        $total = $this->documentosPracticasModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->countAllResults();

        $aprobados = $this->documentosPracticasModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ID_ESTADO_REVISION', 3)
            ->countAllResults();

        $pendientes = $this->documentosPracticasModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ID_ESTADO_REVISION', 1)
            ->countAllResults();

        $rechazados = $this->documentosPracticasModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ID_ESTADO_REVISION', 4)
            ->countAllResults();

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados
        ];
    }

    /**
     * Obtener estadísticas por tipo de documento de servicio comunitario
     */
    private function getEstadisticasPorTipoServicio($idTipoDocumento)
    {
        $total = $this->documentosServicioModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->countAllResults();

        $aprobados = $this->documentosServicioModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ESTADO_REVISION', 'Aprobado')
            ->countAllResults();

        $pendientes = $this->documentosServicioModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ESTADO_REVISION', 'Pendiente')
            ->countAllResults();

        $rechazados = $this->documentosServicioModel
            ->where('ID_TIPO_DOCUMENTO', $idTipoDocumento)
            ->where('ESTADO_REVISION', 'Rechazado')
            ->countAllResults();

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'pendientes' => $pendientes,
            'rechazados' => $rechazados
        ];
    }

    /**
     * Obtener tipos de documentos de servicio comunitario
     */
    private function getTiposDocumentosServicio()
    {
        // Si no existe la tabla, retornar tipos predefinidos
        try {
            return $this->tiposDocumentosServicioModel->findAll();
        } catch (\Exception $e) {
            // Tipos predefinidos de servicio comunitario
            return [
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 1,
                    'CODIGO' => 'SC.1',
                    'NOMBRE' => 'Plan de Trabajo de Servicio Comunitario',
                    'DESCRIPCION' => 'Plan detallado de las actividades a realizar durante el servicio comunitario.',
                    'ORDEN' => 1,
                    'OBLIGATORIO' => true
                ],
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 2,
                    'CODIGO' => 'SC.2',
                    'NOMBRE' => 'Cronograma de Actividades',
                    'DESCRIPCION' => 'Cronograma detallado con fechas y horarios de las actividades de servicio comunitario.',
                    'ORDEN' => 2,
                    'OBLIGATORIO' => true
                ],
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 3,
                    'CODIGO' => 'SC.3',
                    'NOMBRE' => 'Informe de Actividades Realizadas',
                    'DESCRIPCION' => 'Informe detallado de todas las actividades realizadas durante el servicio comunitario.',
                    'ORDEN' => 3,
                    'OBLIGATORIO' => true
                ],
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 4,
                    'CODIGO' => 'SC.4',
                    'NOMBRE' => 'Evidencias Fotográficas',
                    'DESCRIPCION' => 'Fotografías que evidencian la realización de las actividades de servicio comunitario.',
                    'ORDEN' => 4,
                    'OBLIGATORIO' => true
                ],
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 5,
                    'CODIGO' => 'SC.5',
                    'NOMBRE' => 'Evaluación de la Comunidad',
                    'DESCRIPCION' => 'Evaluación realizada por la comunidad sobre el impacto del servicio comunitario.',
                    'ORDEN' => 5,
                    'OBLIGATORIO' => true
                ],
                [
                    'ID_TIPO_DOCUMENTO_SERVICIO' => 6,
                    'CODIGO' => 'SC.6',
                    'NOMBRE' => 'Informe Final de Servicio Comunitario',
                    'DESCRIPCION' => 'Informe final que resume todo el trabajo realizado durante el servicio comunitario.',
                    'ORDEN' => 6,
                    'OBLIGATORIO' => true
                ]
            ];
        }
    }

    /**
     * Obtener icono para tipo de documento de prácticas
     */
    private function getIconoTipoDocumento($codigo)
    {
        $iconos = [
            '1.1' => 'fas fa-file-alt',
            '1.2' => 'fas fa-file-alt',
            '1.3' => 'fas fa-file-alt',
            '1.4' => 'fas fa-file-alt',
            '1.5' => 'fas fa-certificate',
            '1.6' => 'fas fa-clipboard-check',
            '1.7' => 'fas fa-calendar-check',
            '1.8' => 'fas fa-clipboard-list',
            '1.9' => 'fas fa-user-tie',
            '1.10' => 'fas fa-clipboard-check',
            '1.11' => 'fas fa-chart-line',
            '1.12' => 'fas fa-images'
        ];

        return $iconos[$codigo] ?? 'fas fa-file-alt';
    }

    /**
     * Obtener icono para tipo de documento de servicio comunitario
     */
    private function getIconoTipoDocumentoServicio($nombre)
    {
        $iconos = [
            'Plan de Trabajo' => 'fas fa-tasks',
            'Cronograma' => 'fas fa-calendar-alt',
            'Informe de Actividades' => 'fas fa-file-alt',
            'Evidencias Fotográficas' => 'fas fa-camera',
            'Evaluación de la Comunidad' => 'fas fa-star',
            'Informe Final' => 'fas fa-file-signature'
        ];

        foreach ($iconos as $palabra => $icono) {
            if (strpos($nombre, $palabra) !== false) {
                return $icono;
            }
        }

        return 'fas fa-file-alt';
    }

    /**
     * Obtener lista de estudiantes
     */
    private function getEstudiantes()
    {
        return $this->usuariosModel
            ->select('TAB_USUARIOS.ID_USUARIO, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO, TAB_DATOS_PERSONAS.CEDULA')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->join('TAB_ROLES', 'TAB_USUARIOS.ID_USUARIO = TAB_ROLES.ID_USUARIO')
            ->join('TAB_TIPOS_ROLES', 'TAB_ROLES.ID_TIPOS_ROLES = TAB_TIPOS_ROLES.ID_TIPOS_ROLES')
            ->where('TAB_TIPOS_ROLES.ROL', 'Estudiante')
            ->where('TAB_USUARIOS.ESTADO', '1')
            ->orderBy('TAB_DATOS_PERSONAS.NOMBRE', 'ASC')
            ->findAll();
    }

    /**
     * API endpoint para obtener documentos por tipo
     */
    public function apiDocumentosPorTipo($tipo = 'todos')
    {
        $documentos = $this->getDocumentosPorTipo($tipo);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * API endpoint para obtener estadísticas unificadas
     */
    public function apiEstadisticas()
    {
        $estadisticas = $this->getEstadisticasUnificadas();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

    /**
     * Aplicar filtros a los documentos
     */
    public function aplicarFiltros()
    {
        $filtros = $this->request->getPost();
        $tipo = $filtros['tipo'] ?? 'todos';
        $busqueda = $filtros['busqueda'] ?? '';
        $estado = $filtros['estado'] ?? '';

        $documentos = $this->getDocumentosPorTipo($tipo);

        // Aplicar filtro de búsqueda
        if (!empty($busqueda)) {
            $documentos = array_filter($documentos, function($doc) use ($busqueda) {
                return stripos($doc['nombre'], $busqueda) !== false ||
                       stripos($doc['descripcion'], $busqueda) !== false ||
                       stripos($doc['codigo'], $busqueda) !== false;
            });
        }

        // Aplicar filtro de estado (si se implementa en el futuro)
        // Por ahora solo filtramos por tipo y búsqueda

        return $this->response->setJSON([
            'success' => true,
            'data' => array_values($documentos)
        ]);
    }

    /**
     * Generar reporte unificado
     */
    public function generarReporte()
    {
        $filtros = $this->request->getGet();
        $tipo = $filtros['tipo'] ?? 'todos';
        
        $documentos = $this->getDocumentosPorTipo($tipo);
        $estadisticas = $this->getEstadisticasUnificadas();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'documentos' => $documentos,
                'estadisticas' => $estadisticas,
                'filtros' => $filtros
            ]
        ]);
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF()
    {
        try {
            $filtros = $this->request->getGet();
            $tipo = $filtros['tipo'] ?? 'todos';
            
            $documentos = $this->getDocumentosPorTipo($tipo);
            $estadisticas = $this->getEstadisticasUnificadas();
            
            // Configurar PDF
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('Sistema ITSI');
            $pdf->SetAuthor('Administrador');
            $pdf->SetTitle('Reporte Unificado de Documentos');
            $pdf->SetSubject('Reporte de Documentos del Sistema ITSI');
            
            // Configurar márgenes
            $pdf->SetMargins(15, 20, 15);
            $pdf->SetHeaderMargin(10);
            $pdf->SetFooterMargin(10);
            
            // Configurar fuente
            $pdf->SetFont('helvetica', '', 10);
            
            // Agregar página
            $pdf->AddPage();
            
            // Encabezado
            $this->generarEncabezadoPDF($pdf);
            
            // Estadísticas
            $this->generarEstadisticasPDF($pdf, $estadisticas);
            
            // Tabla de documentos
            $this->generarTablaDocumentosPDF($pdf, $documentos);
            
            // Pie de página
            $this->generarPiePaginaPDF($pdf);
            
            // Generar nombre del archivo
            $nombreArchivo = 'reporte_documentos_unificado_' . date('Y-m-d_H-i-s') . '.pdf';
            
            // Descargar PDF
            $pdf->Output($nombreArchivo, 'D');
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generar encabezado del PDF
     */
    private function generarEncabezadoPDF($pdf)
    {
        // Título principal
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'REPORTE UNIFICADO DE DOCUMENTOS', 0, 1, 'C');
        $pdf->Ln(5);
        
        // Información de la institución
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'INSTITUTO TECNOLÓGICO SUPERIOR IBARRA', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Sistema de Gestión de Documentos', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Fecha de generación
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
        $pdf->Ln(5);
    }

    /**
     * Generar estadísticas en el PDF
     */
    private function generarEstadisticasPDF($pdf, $estadisticas)
    {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'ESTADÍSTICAS GENERALES', 0, 1, 'L');
        $pdf->Ln(3);
        
        // Crear tabla de estadísticas
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetFillColor(240, 240, 240);
        
        // Encabezados
        $pdf->Cell(45, 8, 'Total Documentos', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Aprobados', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Pendientes', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Rechazados', 1, 1, 'C', true);
        
        // Datos
        $pdf->Cell(45, 8, $estadisticas['total'] ?? 0, 1, 0, 'C');
        $pdf->Cell(45, 8, $estadisticas['aprobados'] ?? 0, 1, 0, 'C');
        $pdf->Cell(45, 8, $estadisticas['pendientes'] ?? 0, 1, 0, 'C');
        $pdf->Cell(45, 8, $estadisticas['rechazados'] ?? 0, 1, 1, 'C');
        
        $pdf->Ln(10);
    }

    /**
     * Generar tabla de documentos en el PDF
     */
    private function generarTablaDocumentosPDF($pdf, $documentos)
    {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'TIPOS DE DOCUMENTOS', 0, 1, 'L');
        $pdf->Ln(3);
        
        if (empty($documentos)) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 8, 'No se encontraron documentos con los filtros aplicados.', 0, 1, 'C');
            return;
        }
        
        // Configurar tabla
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetFillColor(240, 240, 240);
        
        // Encabezados de la tabla
        $pdf->Cell(20, 8, 'Código', 1, 0, 'C', true);
        $pdf->Cell(60, 8, 'Tipo de Documento', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Categoría', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Total', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Aprobados', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Pendientes', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Rechazados', 1, 1, 'C', true);
        
        // Datos de los documentos
        foreach ($documentos as $doc) {
            // Verificar si necesitamos una nueva página
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                // Repetir encabezados
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetFillColor(240, 240, 240);
                $pdf->Cell(20, 8, 'Código', 1, 0, 'C', true);
                $pdf->Cell(60, 8, 'Tipo de Documento', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Categoría', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Total', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Aprobados', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Pendientes', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Rechazados', 1, 1, 'C', true);
            }
            
            $pdf->SetFont('helvetica', '', 8);
            $pdf->Cell(20, 8, $doc['codigo'], 1, 0, 'C');
            $pdf->Cell(60, 8, substr($doc['nombre'], 0, 35), 1, 0, 'L');
            $pdf->Cell(20, 8, $doc['tipo'] === 'practicas' ? 'Prácticas' : 'Servicio', 1, 0, 'C');
            $pdf->Cell(20, 8, $doc['estadisticas']['total'], 1, 0, 'C');
            $pdf->Cell(20, 8, $doc['estadisticas']['aprobados'], 1, 0, 'C');
            $pdf->Cell(20, 8, $doc['estadisticas']['pendientes'], 1, 0, 'C');
            $pdf->Cell(20, 8, $doc['estadisticas']['rechazados'], 1, 1, 'C');
        }
        
        $pdf->Ln(10);
    }

    /**
     * Generar pie de página del PDF
     */
    private function generarPiePaginaPDF($pdf)
    {
        $pdf->SetY(-30);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 6, 'Generado por: Sistema ITSI - ' . date('d/m/Y H:i:s'), 0, 1, 'C');
        $pdf->Cell(0, 6, 'Instituto Tecnológico Superior Ibarra', 0, 1, 'C');
    }
}
