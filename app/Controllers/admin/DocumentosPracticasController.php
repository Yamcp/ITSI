<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\DocumentosPracticasModel;
use App\Models\UsuariosModel;
use App\Models\EstadosRevisionesModel;
use App\Models\TiposDocumentosPracticasModel;

class DocumentosPracticasController extends BaseController
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
     * Mostrar la vista de subida de documentos
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Documentos de Prácticas',
            'documentos' => $this->getDocumentosRecientes(),
            'estudiantes' => $this->getEstudiantes()
        ];

        return view('admin/documentos/DocumentosPracticas', $data);
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
    public function store()
    {
        $rules = [
            'document_type' => 'required|in_list[oficio-asignacion-tutor,oficio-personal-entidad,carta-aceptacion,solicitud-institucional,certificado-culminacion,rubrica-evaluacion-entidad,hojas-asistencia,ficha-registro-actividades,ficha-control-seguimiento,rubrica-evaluacion-docente,rubrica-evaluacion-resultados,respaldo-fotos]',
            'id_usuario' => 'required|integer|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf,doc,docx,jpg,jpeg,png,mp4,avi]',
            'observaciones' => 'permit_empty|max_length[500]'
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
            $uploadPath = WRITEPATH . 'uploads/documentos-practicas/';

            // Asegurar que el directorio exista
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $this->generarNombreArchivo($archivo);
                $archivo->move($uploadPath, $nombreArchivo);

                // Obtener ID del tipo de documento
                $tipoDocumentoId = $this->getTipoDocumentoId($this->request->getPost('document_type'));

                $datos = [
                    'ID_ESTADO_REVISION' => 1, // Estado inicial: Pendiente
                    'ID_TIPO_DOCUMENTO' => $tipoDocumentoId,
                    'ID_USUARIO' => $this->request->getPost('id_usuario'),
                    'NOMBRE_ARCHIVO' => $nombreArchivo,
                    'TIPO' => $this->request->getPost('document_type'),
                    'FECHA_SUBIDA' => date('Y-m-d H:i:s'),
                    'OBSERVACIONES' => $this->request->getPost('observaciones') ?? ''
                ];

                if ($this->documentosModel->insert($datos)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente',
                        'data' => [
                            'id' => $this->documentosModel->getInsertID(),
                            'nombre' => $archivo->getClientName(),
                            'fecha' => date('d/m/Y H:i')
                        ]
                    ]);
                } else {
                    // Si falla la inserción, eliminar el archivo subido
                    unlink($uploadPath . $nombreArchivo);
                    throw new \Exception('Error al guardar en la base de datos');
                }
            } else {
                throw new \Exception('Archivo no válido o error al subir el archivo');
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Descargar documento
     */
    public function download($id)
    {
        $documento = $this->documentosModel->find($id);

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos-practicas/' . $documento['NOMBRE_ARCHIVO'];

        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }

    /**
     * Listar documentos por estudiante
     */
    public function listarPorEstudiante($idUsuario)
    {
        $documentos = $this->documentosModel
            ->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_TIPOS_DOCUMENTOS_PRACTICAS.NOMBRE as TIPO_DOCUMENTO_NOMBRE')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_TIPOS_DOCUMENTOS_PRACTICAS', 'TAB_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO = TAB_TIPOS_DOCUMENTOS_PRACTICAS.ID_TIPO_DOCUMENTO')
            ->where('TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO', $idUsuario)
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
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
        $rules = [
            'estado' => 'required|in_list[1,2,3]', // 1: Pendiente, 2: Aprobado, 3: Rechazado
            'observaciones_revisor' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $documento = $this->documentosModel->find($id);
        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        $datos = [
            'ID_ESTADO_REVISION' => $this->request->getPost('estado'),
            'OBSERVACIONES_REVISOR' => $this->request->getPost('observaciones_revisor') ?? ''
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
    }

    /**
     * Obtener documentos recientes
     */
    public function getDocumentosRecientes()
    {
        return $this->documentosModel
            ->select('TAB_DOCUMENTOS_PRACTICAS.*, TAB_ESTADOS_REVISIONES.ESTADO as ESTADO_REVISION, TAB_DATOS_PERSONAS.NOMBRE, TAB_DATOS_PERSONAS.APELLIDO')
            ->join('TAB_ESTADOS_REVISIONES', 'TAB_DOCUMENTOS_PRACTICAS.ID_ESTADO_REVISION = TAB_ESTADOS_REVISIONES.ID_ESTADO_REVISION')
            ->join('TAB_USUARIOS', 'TAB_DOCUMENTOS_PRACTICAS.ID_USUARIO = TAB_USUARIOS.ID_USUARIO')
            ->join('TAB_DATOS_PERSONAS', 'TAB_USUARIOS.ID_DATO_PERSONA = TAB_DATOS_PERSONAS.ID_DATO_PERSONA')
            ->orderBy('TAB_DOCUMENTOS_PRACTICAS.FECHA_SUBIDA', 'DESC')
            ->limit(10)
            ->findAll();
    }

    /**
     * Obtener lista de estudiantes
     */
    public function getEstudiantes()
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
     * Generar nombre único para el archivo
     */
    private function generarNombreArchivo($archivo)
    {
        $extension = $archivo->getClientExtension();
        $timestamp = date('YmdHis');
        $random = bin2hex(random_bytes(4));
        return $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Obtener ID del tipo de documento basado en el tipo
     */
    private function getTipoDocumentoId($tipo)
    {
        $tiposMap = [
            'oficio-asignacion-tutor' => 1,
            'oficio-personal-entidad' => 2,
            'carta-aceptacion' => 3,
            'solicitud-institucional' => 4,
            'certificado-culminacion' => 5,
            'rubrica-evaluacion-entidad' => 6,
            'hojas-asistencia' => 7,
            'ficha-registro-actividades' => 8,
            'ficha-control-seguimiento' => 9,
            'rubrica-evaluacion-docente' => 10,
            'rubrica-evaluacion-resultados' => 11,
            'respaldo-fotos' => 12
        ];

        return $tiposMap[$tipo] ?? 1;
    }

    /**
     * API endpoint para obtener estudiantes (AJAX)
     */
    public function apiEstudiantes()
    {
        $estudiantes = $this->getEstudiantes();
        return $this->response->setJSON([
            'success' => true,
            'data' => $estudiantes
        ]);
    }

    /**
     * API endpoint para obtener documentos recientes (AJAX)
     */
    public function apiDocumentosRecientes()
    {
        $documentos = $this->getDocumentosRecientes();
        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }
}
