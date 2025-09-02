<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentosAdminController extends BaseController
{
    public function __construct()
    {
        // Verificar autenticación y rol de administrador
        if (!session()->get('logged_in') || session()->get('rol') != 1) {
            return redirect()->to('/');
        }
    }

    public function index()
    {
        // Cargar la vista principal de documentos
        return view('admin/documentos/documentos_views');
    }

    public function documentosPracticas()
    {
        // Cargar la vista de documentos de prácticas
        return view('admin/documentos/documentos_practicas');
    }

    public function documentosServicioComunitario()
    {
        // Cargar la vista de documentos de servicio comunitario
        return view('admin/documentos/documentos_servicio_comunitario');
    }

    public function subirDocumento()
    {
        // Lógica para subir documentos
        if ($this->request->getMethod() === 'post') {
            $archivo = $this->request->getFile('archivo');
            
            if ($archivo->isValid() && !$archivo->hasMoved()) {
                // Validar tipo y tamaño del archivo
                $tipoPermitido = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
                $extension = $archivo->getExtension();
                
                if (!in_array(strtolower($extension), $tipoPermitido)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Tipo de archivo no permitido'
                    ]);
                }
                
                // Validar tamaño (máximo 50 MB)
                if ($archivo->getSize() > 50 * 1024 * 1024) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'El archivo excede el tamaño máximo permitido (50 MB)'
                    ]);
                }
                
                // Generar nombre único para el archivo
                $nuevoNombre = uniqid() . '_' . $archivo->getName();
                
                // Mover archivo a la carpeta de uploads
                $archivo->move(WRITEPATH . 'uploads/documentos', $nuevoNombre);
                
                // Obtener datos del formulario
                $tipoDocumento = $this->request->getPost('tipo_documento');
                $estudiante = $this->request->getPost('estudiante');
                $entidadReceptora = $this->request->getPost('entidad_receptora');
                $docenteTutor = $this->request->getPost('docente_tutor');
                $estadoRevision = $this->request->getPost('estado_revision');
                $prioridad = $this->request->getPost('prioridad');
                $observaciones = $this->request->getPost('observaciones');
                
                // Aquí puedes guardar la información en la base de datos
                // Por ahora solo retornamos éxito con los datos recibidos
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Documento subido exitosamente',
                    'filename' => $nuevoNombre,
                    'data' => [
                        'tipo_documento' => $tipoDocumento,
                        'estudiante' => $estudiante,
                        'entidad_receptora' => $entidadReceptora,
                        'docente_tutor' => $docenteTutor,
                        'estado_revision' => $estadoRevision,
                        'prioridad' => $prioridad,
                        'observaciones' => $observaciones
                    ]
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el archivo'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }

    public function obtenerDocumentos()
    {
        // Lógica para obtener lista de documentos
        // Aquí puedes implementar la consulta a la base de datos
        
        $documentos = [
            [
                'id' => 1,
                'nombre' => 'Informe_Final_Practica.pdf',
                'categoria' => 'practicas',
                'tipo' => 'informe',
                'tamaño' => '2.5 MB',
                'estado' => 'aprobado',
                'fecha_subida' => '2025-08-30',
                'usuario' => 'Yamilex Campues'
            ],
            [
                'id' => 2,
                'nombre' => 'Plan_Trabajo.docx',
                'categoria' => 'academicos',
                'tipo' => 'plan_trabajo',
                'tamaño' => '1.8 MB',
                'estado' => 'pendiente',
                'fecha_subida' => '2025-08-29',
                'usuario' => 'Ana Yandun'
            ]
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    public function eliminarDocumento($id = null)
    {
        // Lógica para eliminar documentos
        if ($id) {
            // Aquí puedes implementar la eliminación del archivo y registro de la BD
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de documento requerido'
        ]);
    }

    public function descargarDocumento($id = null)
    {
        // Lógica para descargar documentos
        if ($id) {
            // Aquí puedes implementar la lógica de descarga
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Descarga iniciada'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de documento requerido'
        ]);
    }

    public function crearCarpeta()
    {
        // Lógica para crear carpetas
        if ($this->request->getMethod() === 'post') {
            $nombre = $this->request->getPost('nombre_carpeta');
            $descripcion = $this->request->getPost('descripcion_carpeta');
            $categoria = $this->request->getPost('categoria_carpeta');
            
            if ($nombre) {
                // Aquí puedes implementar la creación de la carpeta en la BD
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Carpeta creada exitosamente'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nombre de carpeta requerido'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }
}
