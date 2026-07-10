<?php

namespace App\Controllers\coord;

use App\Controllers\BaseController;
use App\Models\DocumentosServicioComunitarioModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosServicioComunitarioModel;

class DocumentosServicioComunitarioCoordController extends BaseController
{
    protected $documentosModel;
    protected $usuariosModel;
    protected $estadosRevisionesModel;
    protected $tiposDocumentosModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentosServicioComunitarioModel();
        $this->usuariosModel = new UsuariosModel();
        $this->estadosRevisionesModel = new EstadosRevisionesModel();
        $this->tiposDocumentosModel = new TiposDocumentosServicioComunitarioModel();
    }

    /**
     * Mostrar la vista de gestión de documentos de servicio comunitario
     */
    public function index()
    {
        try {
            $idEstudiante = (int) $this->request->getGet('estudiante');
            // Verificar si hay tipos de documentos, si no, crear los predefinidos
            $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
            
            if (empty($tiposDocumentos)) {
                // Intentar crear los tipos predefinidos
                $resultado = $this->tiposDocumentosModel->crearTiposPredefinidos();
                if ($resultado) {
                    $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
                } else {
                    // Si falla, crear manualmente los tipos básicos
                    $this->crearTiposBasicos();
                    $tiposDocumentos = $this->tiposDocumentosModel->getAllTipos();
                }
            }

            $idEstFiltro = $idEstudiante > 0 ? $idEstudiante : null;
            // La grilla y obtenerDocumentos leen siempre de la BD; no se insertan datos de demostración automáticamente.

            $data = [
                'title' => 'Gestión de Documentos de Servicio Comunitario',
                'documentos' => $this->getDocumentosCompletos($idEstFiltro),
                'estadisticas' => $this->getEstadisticas(),
                'tiposDocumentos' => $tiposDocumentos,
                'tipos_documentos' => $tiposDocumentos,
                'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
                'estudiantes' => $this->getEstudiantes(),
                'documentos_formatos_servicio' => $this->getListaFormatosServicio(),
                'estudiante_filtro' => $idEstFiltro,
            ];

            return view('coord/documentos/documentos_servicio_comunitario', $data);
            
        } catch (\Exception $e) {
            // En caso de error, mostrar vista con datos mínimos
            $data = [
                'title' => 'Gestión de Documentos de Servicio Comunitario',
                'documentos' => [],
                'estadisticas' => ['Aprobados' => 0, 'pendientes' => 0, 'requiere_correccion' => 0, 'rechazados' => 0],
                'tiposDocumentos' => [],
                'tipos_documentos' => [],
                'estados_revision' => [],
                'estudiantes' => [],
                'documentos_formatos_servicio' => $this->getListaFormatosServicio(),
            ];
            
            return view('coord/documentos/documentos_servicio_comunitario', $data);
        }
    }

    private function getDirFormatosServicio()
    {
        $dir = WRITEPATH . 'uploads/formatos_servicio/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    private function getListaFormatosServicioPath()
    {
        return $this->getDirFormatosServicio() . 'lista.json';
    }

    public function getListaFormatosServicio()
    {
        $path = $this->getListaFormatosServicioPath();
        if (!file_exists($path) || !is_readable($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $lista = json_decode($json, true);
        return is_array($lista) ? $lista : [];
    }

    public function subirDocumentoFormato()
    {
        $nombre = trim((string) $this->request->getPost('nombre'));
        $file = $this->request->getFile('documento');
        if (!$nombre) {
            return $this->response->setJSON(['success' => false, 'message' => 'Indique el nombre del documento.']);
        }
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Seleccione un archivo válido.']);
        }
        $ext = strtolower($file->getClientExtension());
        $permitidos = ['pdf', 'doc', 'docx'];
        if (!in_array($ext, $permitidos, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solo se permiten archivos PDF, DOC o DOCX.']);
        }
        $dir = $this->getDirFormatosServicio();
        $nombreArchivo = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $file->getClientName());
        $nombreArchivo = time() . '_' . $nombreArchivo;
        $file->move($dir, $nombreArchivo);
        $lista = $this->getListaFormatosServicio();
        $lista[] = ['nombre' => $nombre, 'archivo' => $nombreArchivo];
        $path = $this->getListaFormatosServicioPath();
        if (file_put_contents($path, json_encode($lista, JSON_UNESCAPED_UNICODE)) === false) {
            @unlink($dir . $nombreArchivo);
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la lista.']);
        }
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documento subido. Los estudiantes podrán descargarlo en Formatos de Servicio Comunitario.',
            'lista' => $this->getListaFormatosServicio(),
        ]);
    }

    public function eliminarDocumentoFormato($archivo)
    {
        $archivo = basename($archivo);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.']);
        }
        $dir = $this->getDirFormatosServicio();
        $rutaArchivo = $dir . $archivo;
        $lista = $this->getListaFormatosServicio();
        $nuevaLista = array_values(array_filter($lista, function ($item) use ($archivo) {
            return ($item['archivo'] ?? '') !== $archivo;
        }));
        if (count($nuevaLista) === count($lista)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado.']);
        }
        if (file_exists($rutaArchivo) && is_file($rutaArchivo)) {
            @unlink($rutaArchivo);
        }
        $path = $this->getListaFormatosServicioPath();
        file_put_contents($path, json_encode($nuevaLista, JSON_UNESCAPED_UNICODE));
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Documento eliminado.',
            'lista' => $this->getListaFormatosServicio(),
        ]);
    }

    /**
     * Actualizar solo el nombre mostrado de un documento de formato (no renombra el archivo en disco).
     */
    public function actualizarNombreDocumentoFormato()
    {
        $archivo = basename((string) $this->request->getPost('archivo'));
        $nombre = trim((string) $this->request->getPost('nombre'));
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.']);
        }
        if ($nombre === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Indique el nombre del documento.']);
        }
        if (mb_strlen($nombre) > 500) {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre no puede superar 500 caracteres.']);
        }
        $lista = $this->getListaFormatosServicio();
        $found = false;
        foreach ($lista as $k => $item) {
            if (($item['archivo'] ?? '') === $archivo) {
                $lista[$k]['nombre'] = $nombre;
                $found = true;
                break;
            }
        }
        if (!$found) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado.']);
        }
        $path = $this->getListaFormatosServicioPath();
        if (file_put_contents($path, json_encode($lista, JSON_UNESCAPED_UNICODE)) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar la lista.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Nombre actualizado correctamente.',
            'lista' => $this->getListaFormatosServicio(),
        ]);
    }

    /**
     * Obtener documentos para AJAX
     */
    public function obtenerDocumentos()
    {
        try {
            $idEstudiante = (int) $this->request->getGet('estudiante');
            $documentos = $this->getDocumentosCompletos($idEstudiante > 0 ? $idEstudiante : null);
            
            return $this->response->setJSON([
                'success' => true,
                'documentos' => $documentos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener documentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Procesar subida de documento
     */
    public function store()
    {
        $rules = [
            'id_tipo_documento' => 'required|integer|is_natural_no_zero',
            'id_servicio' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,51200]|ext_in[archivo,pdf,doc,docx,jpg,jpeg,png,zip,rar]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Manejar subida de archivo
            $archivo = $this->request->getFile('archivo');
            $uploadPath = WRITEPATH . 'uploads/documentos-servicio/';

            // Asegurar que el directorio exista
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $this->generarNombreArchivo($archivo);
                $archivo->move($uploadPath, $nombreArchivo);

                $datos = [
                    'ID_ESTADO_REVISION' => 1, // Estado inicial: Pendiente
                    'ID_TIPO_DOCUMENTO_SERVICIO' => $this->request->getPost('id_tipo_documento'),
                    'ID_USUARIO' => $this->request->getPost('id_servicio'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                    'TIPO' => $archivo->getClientMimeType(),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                    'OBSERVACIONES' => $this->request->getPost('observaciones') ?? ''
                ];

                if ($this->documentosModel->insert($datos)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al guardar el documento en la base de datos'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al subir el archivo'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver documento
     */
    public function ver($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Descargar documento
     */
    public function descargar($id)
    {
        $documento = $this->documentosModel->find($id);
        
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
        
        if (!file_exists($filePath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no encontrado'
            ]);
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Eliminar documento
     */
    public function eliminar($id)
    {
        try {
            $documento = $this->documentosModel->find($id);
            
            if (!$documento) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Documento no encontrado'
                ]);
            }

            // Eliminar archivo físico
            $filePath = WRITEPATH . 'uploads/documentos-servicio/' . $documento['NOMBRE_ARCHIVO'];
            if (file_exists($filePath)) {
                unlink($filePath);
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
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cambiar estado del documento
     */
    public function cambiarEstado($id)
    {
        $rules = [
            'estado' => 'required|in_list[Pendiente,En Revisión,Aprobado,Rechazado,Requiere Corrección]',
            'observaciones' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Obtener ID del estado
            $estadoNombre = $this->request->getPost('estado');
            $estado = $this->estadosRevisionesModel->getEstadoPorNombre($estadoNombre);
            
            if (!$estado) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido'
                ]);
            }

            $datos = [
                'ID_ESTADO_REVISION' => $estado['ID_ESTADO_REVISION'],
                'OBSERVACIONES_REVISOR' => $this->request->getPost('observaciones') ?? ''
            ];

            if ($this->documentosModel->update($id, $datos)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el estado'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener documentos completos con toda la información.
     * @param int|null $idEstudiante Si se indica, solo documentos de ese estudiante.
     */
    public function getDocumentosCompletos($idEstudiante = null)
    {
        try {
            return $this->documentosModel->getDocumentosCompletos($idEstudiante);
        } catch (\Exception $e) {
            // Si hay error, devolver array vacío para evitar errores en la vista
            return [];
        }
    }

    /**
     * Obtener estadísticas de documentos
     */
    public function getEstadisticas()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query('
                SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN ID_ESTADO_REVISION = 1 THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN ID_ESTADO_REVISION = 3 THEN 1 ELSE 0 END) AS aprobados,
                    SUM(CASE WHEN ID_ESTADO_REVISION = 4 THEN 1 ELSE 0 END) AS rechazados,
                    SUM(CASE WHEN ID_ESTADO_REVISION = 5 THEN 1 ELSE 0 END) AS requiere_correccion
                FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO
            ');

            $row = ($query === false) ? [] : ($query->getRowArray() ?: []);
            $aprobados = (int) ($row['aprobados'] ?? 0);
            $pendientes = (int) ($row['pendientes'] ?? 0);
            $rechazados = (int) ($row['rechazados'] ?? 0);
            $requiereCorreccion = (int) ($row['requiere_correccion'] ?? 0);

            return [
                'total' => (int) ($row['total'] ?? 0),
                'Aprobados' => $aprobados,
                'aprobados' => $aprobados,
                'pendientes' => $pendientes,
                'rechazados' => $rechazados,
                'requiere_correccion' => $requiereCorreccion,
            ];
        } catch (\Throwable $e) {
            return [
                'total' => 0,
                'Aprobados' => 0,
                'aprobados' => 0,
                'pendientes' => 0,
                'rechazados' => 0,
                'requiere_correccion' => 0,
            ];
        }
    }

    /**
     * Obtener lista de estudiantes
     */
    public function getEstudiantes()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->table('TAB_ESTUDIANTES e')
                ->select('
                    e.ID_ESTUDIANTE,
                    u.ID_USUARIO,
                    dp.NOMBRE,
                    dp.APELLIDO,
                    dp.CEDULA,
                    CONCAT(dp.NOMBRE, " ", dp.APELLIDO) as NOMBRE_COMPLETO
                ')
                ->join('TAB_DATOS_PERSONAS dp', 'dp.ID_DATO_PERSONA = e.ID_DATO_PERSONA', 'left')
                ->join('TAB_USUARIOS u', 'u.ID_DATO_PERSONA = e.ID_DATO_PERSONA', 'left')
                ->orderBy('dp.NOMBRE', 'ASC')
                ->get();

            return $query === false ? [] : $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'getEstudiantes servicio docs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generar nombre único para archivo
     */
    private function generarNombreArchivo($archivo)
    {
        $extension = $archivo->getClientExtension();
        $nombreBase = pathinfo($archivo->getClientName(), PATHINFO_FILENAME);
        $nombreBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreBase);
        
        return $nombreBase . '_' . time() . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Mostrar vista de reportes
     */
    public function reportes()
    {
        $filtros = $this->request->getGet();
        
        $data = [
            'title' => 'Reportes de Documentos de Servicio Comunitario',
            'documentos' => $this->getDocumentosCompletos(),
            'estadisticas' => $this->getEstadisticas(),
            'tiposDocumentos' => $this->tiposDocumentosModel->getAllTipos(),
            'estados_revision' => $this->estadosRevisionesModel->getAllEstados(),
            'filtros' => $filtros
        ];

        return view('coord/documentos/reportes_servicio_comunitario', $data);
    }

    /**
     * Crear tipos básicos de documentos de servicio comunitario
     */
    private function crearTiposBasicos()
    {
        $tiposBasicos = [
            [
                'CODIGO' => 'PSC-001',
                'NOMBRE' => 'Oficio de Asignación de Tutor',
                'DESCRIPCION' => 'Documento oficial que designa al docente tutor responsable del servicio comunitario',
                'ORDEN' => 1,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-002',
                'NOMBRE' => 'Oficio a Entidad Receptora',
                'DESCRIPCION' => 'Carta formal a la institución solicitando oportunidad de servicio comunitario',
                'ORDEN' => 2,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-003',
                'NOMBRE' => 'Carta de Aceptación',
                'DESCRIPCION' => 'Carta oficial de aceptación de la entidad receptora',
                'ORDEN' => 3,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-004',
                'NOMBRE' => 'Solicitud Institucional',
                'DESCRIPCION' => 'Solicitud al Rector para aprobación institucional',
                'ORDEN' => 4,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ],
            [
                'CODIGO' => 'PSC-005',
                'NOMBRE' => 'Certificado de Culminación',
                'DESCRIPCION' => 'Certificado de horas completadas de servicio comunitario',
                'ORDEN' => 5,
                'OBLIGATORIO' => 1,
                'ACTIVO' => 1
            ]
        ];

        // Insertar tipos básicos
        foreach ($tiposBasicos as $tipo) {
            try {
                $this->tiposDocumentosModel->insert($tipo);
            } catch (\Exception $e) {
                // Continuar con el siguiente si hay error
                continue;
            }
        }
    }

    /**
     * Crear nuevo tipo de documento PSC
     */
    public function crearTipo()
    {
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'codigo' => 'required|max_length[10]|regex_match[/^PSC-\d{3}$/]',
                'nombre' => 'required|max_length[255]',
                'descripcion' => 'permit_empty|max_length[5000]',
                'orden' => 'required|integer|greater_than[0]|less_than[100]',
                'obligatorio' => 'required|in_list[0,1]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validation->getErrors(),
                ]);
            }

            $codigo = $this->request->getPost('codigo');
            $nombre = $this->request->getPost('nombre');
            $descripcion = $this->request->getPost('descripcion') ?? '';
            $orden = (int) $this->request->getPost('orden');
            $obligatorio = (int) $this->request->getPost('obligatorio');

            if ($this->tiposDocumentosModel->existeTipo($codigo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El código PSC ya existe. Use otro código.',
                ]);
            }

            $tipoConOrden = $this->tiposDocumentosModel->where('ORDEN', $orden)->where('ACTIVO', 1)->first();
            if ($tipoConOrden) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El orden ya está en uso. Elija otro número.',
                ]);
            }

            $data = [
                'CODIGO' => $codigo,
                'NOMBRE' => $nombre,
                'DESCRIPCION' => $descripcion,
                'ORDEN' => $orden,
                'OBLIGATORIO' => $obligatorio,
                'ACTIVO' => 1,
            ];

            if ($this->tiposDocumentosModel->skipValidation(true)->insert($data)) {
                $nuevo = $this->tiposDocumentosModel->where('CODIGO', $codigo)->first();

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de documento PSC creado correctamente',
                    'tipo' => $nuevo,
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el tipo de documento',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'crearTipo servicio: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Actualizar tipo de documento PSC (descripción, nombre, código, orden, obligatorio)
     */
    public function actualizarTipo($id = null)
    {
        try {
            $id = (int) $id;
            if ($id <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Identificador de tipo no válido',
                ]);
            }

            $actual = $this->tiposDocumentosModel->find($id);
            if (!$actual) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de documento no encontrado',
                ]);
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'codigo' => 'required|max_length[10]|regex_match[/^PSC-\d{3}$/]',
                'nombre' => 'required|max_length[255]',
                'descripcion' => 'permit_empty|max_length[5000]',
                'orden' => 'required|integer|greater_than[0]|less_than[100]',
                'obligatorio' => 'required|in_list[0,1]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validation->getErrors(),
                ]);
            }

            $codigo = $this->request->getPost('codigo');
            $nombre = $this->request->getPost('nombre');
            $descripcion = $this->request->getPost('descripcion') ?? '';
            $orden = (int) $this->request->getPost('orden');
            $obligatorio = (int) $this->request->getPost('obligatorio');

            if ($this->tiposDocumentosModel->existeTipo($codigo, $id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El código PSC ya está en uso por otro tipo.',
                ]);
            }

            $duplicadoOrden = $this->tiposDocumentosModel
                ->where('ORDEN', $orden)
                ->where('ACTIVO', 1)
                ->where('ID_TIPO_DOCUMENTO_SERVICIO !=', $id)
                ->first();
            if ($duplicadoOrden) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El orden ya está en uso. Elija otro número.',
                ]);
            }

            $data = [
                'CODIGO' => $codigo,
                'NOMBRE' => $nombre,
                'DESCRIPCION' => $descripcion,
                'ORDEN' => $orden,
                'OBLIGATORIO' => $obligatorio,
            ];

            if ($this->tiposDocumentosModel->skipValidation(true)->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de documento actualizado correctamente',
                    'tipo' => $this->tiposDocumentosModel->find($id),
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo guardar el tipo de documento',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'actualizarTipo servicio: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Exportar documentos de servicio comunitario (excel|pdf)
     */
    public function exportar($formato = 'excel')
    {
        try {
            $documentos = $this->obtenerDocumentosParaExportar();

            switch (strtolower((string) $formato)) {
                case 'excel':
                    return $this->exportarExcel($documentos);
                case 'pdf':
                    return $this->exportarPDF($documentos);
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Formato no soportado. Use excel o pdf.',
                    ])->setStatusCode(400);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error exportar documentos servicio: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Consulta directa para exportación (evita Model::findAll).
     */
    private function obtenerDocumentosParaExportar(): array
    {
        $db = \Config\Database::connect();
        $sql = '
            SELECT
                dsc.ID_DOCUMENTO_SERVICIO,
                dsc.NOMBRE_ARCHIVO,
                dsc.FECHA_SUBIDA,
                dsc.ID_ESTADO_REVISION,
                tds.NOMBRE AS TIPO_DOCUMENTO_NOMBRE,
                er.ESTADO AS ESTADO_REVISION,
                dp.NOMBRE AS NOMBRE_ESTUDIANTE,
                dp.APELLIDO AS APELLIDO_ESTUDIANTE
            FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO dsc
            LEFT JOIN TAB_TIPOS_DOCUMENTOS_SERVICIO_COMUNITARIO tds
                ON dsc.ID_TIPO_DOCUMENTO = tds.ID_TIPO_DOCUMENTO_SERVICIO
            LEFT JOIN TAB_ESTADOS_REVISIONES er
                ON dsc.ID_ESTADO_REVISION = er.ID_ESTADO_REVISION
            LEFT JOIN TAB_SERVICIO_COMUNITARIO sc
                ON dsc.ID_SERVICIO_COMUNITARIO = sc.ID_SERVICIO_COMUNITARIO
            LEFT JOIN TAB_ESTUDIANTES e
                ON sc.ID_ESTUDIANTE = e.ID_ESTUDIANTE
            LEFT JOIN TAB_DATOS_PERSONAS dp
                ON e.ID_DATO_PERSONA = dp.ID_DATO_PERSONA
            ORDER BY dsc.FECHA_SUBIDA DESC
        ';

        try {
            $query = $db->query($sql);
            if ($query === false) {
                $fallback = $db->query('SELECT ID_DOCUMENTO_SERVICIO, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_ESTADO_REVISION FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ORDER BY FECHA_SUBIDA DESC');
                return $fallback === false ? [] : $fallback->getResultArray();
            }
            return $query->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'obtenerDocumentosParaExportar servicio: ' . $e->getMessage());
            try {
                $fallback = $db->query('SELECT ID_DOCUMENTO_SERVICIO, NOMBRE_ARCHIVO, FECHA_SUBIDA, ID_ESTADO_REVISION FROM TAB_DOCUMENTOS_SERVICIO_COMUNITARIO ORDER BY FECHA_SUBIDA DESC');
                return $fallback === false ? [] : $fallback->getResultArray();
            } catch (\Throwable $e2) {
                return [];
            }
        }
    }

    private function exportarExcel($documentos)
    {
        try {
            helper('ExcelHelper');

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Documentos Servicio');

            \App\Helpers\ExcelHelper::createStandardHeader(
                $sheet,
                'REPORTE DE DOCUMENTOS DE SERVICIO COMUNITARIO',
                'Sistema de Gestión Académica ITSI',
                'Logo PDF.jpg',
                'A1',
                'F1'
            );

            $headers = ['ID', 'Estudiante', 'Tipo Documento', 'Archivo', 'Fecha Subida', 'Estado'];
            \App\Helpers\ExcelHelper::createColumnHeaders($sheet, $headers, 5, 'A');

            $row = 6;
            foreach ($documentos as $doc) {
                $estudiante = trim(($doc['NOMBRE_ESTUDIANTE'] ?? '') . ' ' . ($doc['APELLIDO_ESTUDIANTE'] ?? ''));
                $fecha = !empty($doc['FECHA_SUBIDA']) ? date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])) : '';
                $sheet->setCellValue('A' . $row, $doc['ID_DOCUMENTO_SERVICIO'] ?? '');
                $sheet->setCellValue('B' . $row, $estudiante !== '' ? $estudiante : 'N/A');
                $sheet->setCellValue('C' . $row, $doc['TIPO_DOCUMENTO_NOMBRE'] ?? 'N/A');
                $sheet->setCellValue('D' . $row, $doc['NOMBRE_ARCHIVO'] ?? '');
                $sheet->setCellValue('E' . $row, $fecha);
                $sheet->setCellValue('F' . $row, $doc['ESTADO_REVISION'] ?? '');
                $row++;
            }

            if ($row > 6) {
                \App\Helpers\ExcelHelper::applyDataStyle($sheet, 'A6:F' . ($row - 1));
            }
            \App\Helpers\ExcelHelper::autoSizeColumns($sheet, 'A', 'F');

            $filename = 'documentos_servicio_' . date('Y-m-d') . '.xlsx';
            \App\Helpers\ExcelHelper::setDownloadHeaders($filename);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar Excel: ' . $e->getMessage(),
            ]);
        }
    }

    private function exportarPDF($documentos)
    {
        try {
            $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('Sistema ITSI');
            $pdf->SetAuthor('Coordinador');
            $pdf->SetTitle('Documentos de Servicio Comunitario');
            $pdf->SetSubject('Reporte de documentos de servicio comunitario');
            $pdf->SetMargins(12, 18, 12);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->AddPage();

            helper('PdfHelper');
            \App\Helpers\PdfHelper::addLogoToPdf($pdf, 'Logo PDF.jpg', 12, 10, 28);

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 8, 'REPORTE DE DOCUMENTOS DE SERVICIO COMUNITARIO', 0, 1, 'C');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, 'Instituto Tecnológico Superior Ibarra', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Generado el: ' . \App\Helpers\PdfHelper::getCurrentDateTime(), 0, 1, 'C');
            $pdf->Ln(4);

            $headers = [
                ['w' => 18, 'label' => 'ID'],
                ['w' => 55, 'label' => 'Estudiante'],
                ['w' => 50, 'label' => 'Tipo'],
                ['w' => 70, 'label' => 'Archivo'],
                ['w' => 35, 'label' => 'Fecha'],
                ['w' => 35, 'label' => 'Estado'],
            ];

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(52, 58, 64);
            $pdf->SetTextColor(255, 255, 255);
            foreach ($headers as $header) {
                $pdf->Cell($header['w'], 7, $header['label'], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(0, 0, 0);
            $fill = false;

            if (empty($documentos)) {
                $pdf->Cell(array_sum(array_column($headers, 'w')), 8, 'No hay documentos registrados', 1, 1, 'C');
            } else {
                foreach ($documentos as $doc) {
                    if ($pdf->GetY() > 185) {
                        $pdf->AddPage();
                        $pdf->SetFont('helvetica', 'B', 8);
                        $pdf->SetFillColor(52, 58, 64);
                        $pdf->SetTextColor(255, 255, 255);
                        foreach ($headers as $header) {
                            $pdf->Cell($header['w'], 7, $header['label'], 1, 0, 'C', true);
                        }
                        $pdf->Ln();
                        $pdf->SetFont('helvetica', '', 7);
                        $pdf->SetTextColor(0, 0, 0);
                    }

                    $pdf->SetFillColor(245, 245, 245);
                    $estudiante = trim(($doc['NOMBRE_ESTUDIANTE'] ?? '') . ' ' . ($doc['APELLIDO_ESTUDIANTE'] ?? ''));
                    $fecha = !empty($doc['FECHA_SUBIDA']) ? date('d/m/Y H:i', strtotime($doc['FECHA_SUBIDA'])) : '';
                    $row = [
                        (string) ($doc['ID_DOCUMENTO_SERVICIO'] ?? ''),
                        $estudiante,
                        (string) ($doc['TIPO_DOCUMENTO_NOMBRE'] ?? ''),
                        (string) ($doc['NOMBRE_ARCHIVO'] ?? ''),
                        $fecha,
                        (string) ($doc['ESTADO_REVISION'] ?? ''),
                    ];

                    foreach ($headers as $i => $header) {
                        $pdf->Cell($header['w'], 6, mb_substr($row[$i], 0, 40), 1, 0, $i === 0 || $i === 4 ? 'C' : 'L', $fill);
                    }
                    $pdf->Ln();
                    $fill = !$fill;
                }
            }

            $filename = 'documentos_servicio_' . date('Y-m-d_H-i-s') . '.pdf';
            $pdf->Output($filename, 'D');
            exit;
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al exportar PDF: ' . $e->getMessage(),
            ]);
        }
    }
}
