<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Sirve las imágenes de códigos QR configuradas por admin (prácticas preprofesionales y servicio comunitario).
 * Se muestran en el perfil del estudiante.
 */
class QrController extends BaseController
{
    public function practicas()
    {
        $path = WRITEPATH . 'uploads/qr/practicas_preprofesionales.png';
        if (!file_exists($path) || !is_readable($path)) {
            $path = FCPATH . 'sistema/assets/images/practicas/formatos-practicas-laborales-qr.png';
        }
        return $this->serveImage($path);
    }

    public function servicio()
    {
        $path = WRITEPATH . 'uploads/qr/servicio_comunitario.png';
        if (!file_exists($path) || !is_readable($path)) {
            $path = FCPATH . 'sistema/assets/images/practicas/formatos-servicio-comunitario-qr.png';
        }
        return $this->serveImage($path);
    }

    private function serveImage($path)
    {
        if (!file_exists($path) || !is_readable($path)) {
            return $this->response->setStatusCode(404)->setBody('Imagen no encontrada');
        }
        $mime = mime_content_type($path);
        $this->response->setHeader('Content-Type', $mime);
        $this->response->setHeader('Content-Length', (string) filesize($path));
        return $this->response->setBody(file_get_contents($path));
    }
}
