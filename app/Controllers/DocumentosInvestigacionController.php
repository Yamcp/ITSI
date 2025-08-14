<?php

namespace App\Controllers;

use App\Models\DocumentosInvestigacionModel;
use App\Models\AreasTematicasModel;

class DocumentosInvestigacionController extends BaseController
{
    protected $documentosModel;
    protected $areasTematicasModel;

    public function __construct()
    {
        $this->documentosModel = new DocumentosInvestigacionModel();
        $this->areasTematicasModel = new AreasTematicasModel();
    }

    public function index()
    {
        $documentos = $this->documentosModel
            ->select('TAB_DOCUMENTOS_INVESTIGACION.*, TAB_AREAS_TEMATICAS.NOMBRE as AREA_TEMATICA')
            ->join('TAB_AREAS_TEMATICAS', 'TAB_DOCUMENTOS_INVESTIGACION.ID_AREA_TEMATICA = TAB_AREAS_TEMATICAS.ID_AREA_TEMATICA')
            ->findAll();

        $data = [
            'title' => 'Gestión de Documentos de Investigación',
            'documentos' => $documentos
        ];

        return view('documentos/index', $data);
    }

    public function upload()
    {
        $data = [
            'title' => 'Subir Documento de Investigación',
            'areas_tematicas' => $this->areasTematicasModel->findAll()
        ];

        return view('documentos/upload', $data);
    }

    public function store()
    {
        $rules = [
            'titulo' => 'required|max_length[255]',
            'autores' => 'required',
            'resumen' => 'required',
            'id_area_tematica' => 'required|is_natural_no_zero',
            'archivo' => 'uploaded[archivo]|max_size[archivo,10240]|ext_in[archivo,pdf,doc,docx]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Manejar subida de archivo
        $archivo = $this->request->getFile('archivo');
        $uploadPath = WRITEPATH . 'uploads/documentos/';

        // Asegura que el directorio exista
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($archivo->isValid() && !$archivo->hasMoved()) {
            $nombreArchivo = $archivo->getRandomName();
            $archivo->move($uploadPath, $nombreArchivo);

            $datos = [
                'ID_AREA_TEMATICA' => $this->request->getPost('id_area_tematica'),
                'TITULO' => $this->request->getPost('titulo'),
                'AUTORES' => $this->request->getPost('autores'),
                'RESUMEN' => $this->request->getPost('resumen'),
                'VIABLE' => $this->request->getPost('viable') ? 1 : 0,
                'ARCHIVO' => $nombreArchivo
            ];

            if ($this->documentosModel->insert($datos)) {
                return redirect()->to('/documentos')->with('success', 'Documento subido exitosamente');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Archivo no válido o error al subir el archivo');
        }

        return redirect()->back()->withInput()->with('error', 'Error al subir el documento');
    }

    public function download($id)
    {
        $documento = $this->documentosModel->find($id);

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        $rutaArchivo = WRITEPATH . 'uploads/documentos/' . $documento['ARCHIVO'];

        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        return $this->response->download($rutaArchivo, null);
    }

    public function evaluar($id)
    {
        $documento = $this->documentosModel->find($id);

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        if ($this->request->getMethod() === 'post') {
            $viable = $this->request->getPost('viable') ? 1 : 0;

            if ($this->documentosModel->update($id, ['VIABLE' => $viable])) {
                $mensaje = $viable ? 'Documento marcado como viable' : 'Documento marcado como no viable';
                return redirect()->to('/documentos')->with('success', $mensaje);
            }

            return redirect()->back()->with('error', 'Error al actualizar la evaluación');
        }

        $data = [
            'title' => 'Evaluar Documento',
            'documento' => $documento
        ];

        return view('documentos/evaluar', $data);
    }
}