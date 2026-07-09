<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailNotificaciones
{
    protected $email;
    protected $config;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
        
        // Configurar email
        $this->configurarEmail();
    }

    /**
     * Configurar parámetros del email
     */
    private function configurarEmail()
    {
        $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
        $this->email->setMailType('html');
    }

    /**
     * Enviar notificación de nueva práctica asignada
     */
    public function enviarNotificacionAsignacionPractica($datosEstudiante, $datosTutor, $datosPractica)
    {
        try {
            // Email para el estudiante
            $this->enviarEmailEstudiante($datosEstudiante, $datosPractica);
            
            // Email para el tutor
            $this->enviarEmailTutor($datosTutor, $datosPractica);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error enviando emails de notificación: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar email al estudiante
     */
    private function enviarEmailEstudiante($estudiante, $datosPractica)
    {
        $tipoPractica = $datosPractica['tipo'] == 'preprofesional' ? 'Práctica Preprofesional' : 'Servicio Comunitario';
        
        $asunto = "Nueva {$tipoPractica} Asignada - ITSI";
        
        $mensaje = $this->generarHTMLEmailEstudiante($estudiante, $datosPractica);
        
        $this->email->setTo($estudiante['email']);
        $this->email->setSubject($asunto);
        $this->email->setMessage($mensaje);
        
        return $this->email->send();
    }

    /**
     * Enviar email al tutor
     */
    private function enviarEmailTutor($tutor, $datosPractica)
    {
        $tipoPractica = $datosPractica['tipo'] == 'preprofesional' ? 'Práctica Preprofesional' : 'Servicio Comunitario';
        
        $asunto = "Nueva Tutoria Asignada - {$tipoPractica} - ITSI";
        
        $mensaje = $this->generarHTMLEmailTutor($tutor, $datosPractica);
        
        $this->email->setTo($tutor['email']);
        $this->email->setSubject($asunto);
        $this->email->setMessage($mensaje);
        
        return $this->email->send();
    }

    /**
     * Generar HTML para email del estudiante
     */
    private function periodoPracticaParaEmail(array $datosPractica): string
    {
        $ini = $datosPractica['fecha_inicio'] ?? '';
        $fin = $datosPractica['fecha_fin'] ?? '';
        if ($ini === '' || $ini === null) {
            return 'Por definir';
        }
        $iniFmt = date('d/m/Y', strtotime((string) $ini));
        if ($fin === '' || $fin === null) {
            return "{$iniFmt} (sin fecha fin registrada)";
        }
        return $iniFmt . ' - ' . date('d/m/Y', strtotime((string) $fin));
    }

    private function generarHTMLEmailEstudiante($estudiante, $datosPractica)
    {
        $tipoPractica = $datosPractica['tipo'] == 'preprofesional' ? 'Práctica Preprofesional' : 'Servicio Comunitario';
        $periodoTexto = $this->periodoPracticaParaEmail($datosPractica);
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Nueva Práctica Asignada</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                .info-card { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .info-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #eee; }
                .info-label { font-weight: bold; color: #555; }
                .info-value { color: #333; }
                .highlight { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .button { display: inline-block; background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎓 Instituto Tecnológico Superior de Ibarra</h1>
                    <h2>Nueva {$tipoPractica} Asignada</h2>
                </div>
                
                <div class='content'>
                    <p>Hola <strong>{$estudiante['nombre_completo']}</strong>,</p>
                    
                    <p>Nos complace informarte que se te ha asignado una nueva <strong>{$tipoPractica}</strong> en el sistema de gestión de prácticas del ITSI.</p>
                    
                    <div class='info-card'>
                        <h3>📋 Detalles de la Práctica</h3>
                        <div class='info-row'>
                            <span class='info-label'>Tipo de Práctica:</span>
                            <span class='info-value'>{$tipoPractica}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Institución:</span>
                            <span class='info-value'>{$datosPractica['institucion']}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Tutor Asignado:</span>
                            <span class='info-value'>{$datosPractica['tutor']}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Período:</span>
                            <span class='info-value'>{$periodoTexto}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Horas Totales:</span>
                            <span class='info-value'>{$datosPractica['horas']} horas</span>
                        </div>
                    </div>
                    
                    <div class='highlight'>
                        <h4>📝 Descripción de la Práctica</h4>
                        <p>{$datosPractica['descripcion']}</p>
                    </div>
                    
                    <p>Tu tutor se pondrá en contacto contigo pronto para coordinar el inicio de las actividades. Asegúrate de revisar regularmente el sistema para estar al tanto de las actualizaciones.</p>
                    
                    <div style='text-align: center;'>
                        <a href='" . base_url('estudiante/dashboard') . "' class='button'>Acceder al Sistema</a>
                    </div>
                    
                    <div class='footer'>
                        <p>Este es un mensaje automático del Sistema de Gestión de Prácticas ITSI.</p>
                        <p>Si tienes alguna pregunta, contacta con el coordinador del sistema.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Generar HTML para email del tutor
     */
    private function generarHTMLEmailTutor($tutor, $datosPractica)
    {
        $tipoPractica = $datosPractica['tipo'] == 'preprofesional' ? 'Práctica Preprofesional' : 'Servicio Comunitario';
        $periodoTexto = $this->periodoPracticaParaEmail($datosPractica);
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Nueva Tutoria Asignada</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                .info-card { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .info-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #eee; }
                .info-label { font-weight: bold; color: #555; }
                .info-value { color: #333; }
                .highlight { background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .button { display: inline-block; background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>👨‍🏫 Instituto Tecnológico Superior de Ibarra</h1>
                    <h2>Nueva Tutoria Asignada</h2>
                </div>
                
                <div class='content'>
                    <p>Estimado/a <strong>{$tutor['nombre_completo']}</strong>,</p>
                    
                    <p>Se te ha asignado una nueva tutoria de <strong>{$tipoPractica}</strong> en el sistema de gestión de prácticas del ITSI.</p>
                    
                    <div class='info-card'>
                        <h3>👨‍🎓 Información del Estudiante</h3>
                        <div class='info-row'>
                            <span class='info-label'>Estudiante:</span>
                            <span class='info-value'>{$datosPractica['estudiante']}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Tipo de Práctica:</span>
                            <span class='info-value'>{$tipoPractica}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Institución:</span>
                            <span class='info-value'>{$datosPractica['institucion']}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Período:</span>
                            <span class='info-value'>{$periodoTexto}</span>
                        </div>
                        <div class='info-row'>
                            <span class='info-label'>Horas Totales:</span>
                            <span class='info-value'>{$datosPractica['horas']} horas</span>
                        </div>
                    </div>
                    
                    <div class='highlight'>
                        <h4>📝 Descripción de la Práctica</h4>
                        <p>{$datosPractica['descripcion']}</p>
                    </div>
                    
                    <p>Como tutor asignado, tu responsabilidad incluye:</p>
                    <ul>
                        <li>Supervisar el progreso del estudiante</li>
                        <li>Revisar y aprobar documentos</li>
                        <li>Mantener comunicación regular</li>
                        <li>Evaluar el desempeño</li>
                    </ul>
                    
                    <div style='text-align: center;'>
                        <a href='" . base_url('docente/dashboard') . "' class='button'>Acceder al Sistema de Seguimiento</a>
                    </div>
                    
                    <div class='footer'>
                        <p>Este es un mensaje automático del Sistema de Gestión de Prácticas ITSI.</p>
                        <p>Para más información, contacta con el coordinador del sistema.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Enviar email de recordatorio
     */
    public function enviarRecordatorio($destinatario, $asunto, $mensaje)
    {
        $html = $this->generarHTMLGenerico($asunto, $mensaje);
        
        $this->email->setTo($destinatario['email']);
        $this->email->setSubject($asunto);
        $this->email->setMessage($html);
        
        return $this->email->send();
    }

    /**
     * Generar HTML genérico para emails
     */
    private function generarHTMLGenerico($titulo, $mensaje)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$titulo}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎓 Instituto Tecnológico Superior de Ibarra</h1>
                    <h2>{$titulo}</h2>
                </div>
                
                <div class='content'>
                    <div style='white-space: pre-line;'>{$mensaje}</div>
                </div>
                
                <div class='footer'>
                    <p>Este es un mensaje automático del Sistema de Gestión de Prácticas ITSI.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Verificar configuración de email
     */
    public function verificarConfiguracion()
    {
        try {
            $this->email->setTo('test@example.com');
            $this->email->setSubject('Test de configuración');
            $this->email->setMessage('Test');
            
            // No enviar realmente, solo verificar configuración
            return $this->email->validate();
        } catch (\Exception $e) {
            log_message('error', 'Error en configuración de email: ' . $e->getMessage());
            return false;
        }
    }
}
