<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Configuración de correo para el sistema.
 *
 * Para recuperación de contraseña (y notificaciones) debes configurar:
 *
 * 1) Correo remitente (obligatorio):
 *    - fromEmail: correo desde el que se envía (ej: tu_cuenta@gmail.com)
 *    - fromName: nombre que verá el destinatario
 *
 * 2) Envío por SMTP (recomendado en XAMPP/Windows; mail() suele no funcionar):
 *    - protocol = 'smtp'
 *    - SMTPHost, SMTPUser, SMTPPass, SMTPPort, SMTPCrypto
 *
 * Ejemplo Gmail:
 *    protocol = 'smtp'
 *    SMTPHost = 'smtp.gmail.com'
 *    SMTPUser = 'tu_cuenta@gmail.com'
 *    SMTPPass = 'contraseña_de_aplicacion'  (crear en Cuenta Google > Seguridad > Contraseñas de app)
 *    SMTPPort = 587
 *    SMTPCrypto = 'tls'
 */
class Email extends BaseConfig
{
    /** Correo desde el que se envían los mensajes (obligatorio para recuperación de contraseña) */
    public string $fromEmail  = '';

    /** Nombre del remitente */
    public string $fromName   = 'Sistema de Vinculación';

    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * Protocolo: 'mail', 'sendmail' o 'smtp'. En Windows/XAMPP usa 'smtp'.
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server (ej: smtp.gmail.com, smtp.office365.com)
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * Usuario SMTP (normalmente tu correo)
     */
    public string $SMTPUser = '';

    /**
     * Contraseña SMTP (en Gmail: contraseña de aplicación)
     */
    public string $SMTPPass = '';

    /**
     * Puerto: 587 (TLS), 465 (SSL), 25 (sin cifrado)
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 10;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'text';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
