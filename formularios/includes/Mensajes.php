<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions


class Mensajes extends PHPMailer {

    private $destinatario;
    private $asunto;
    private $cuerpo;
    private $autologin;

    public function __construct($destinatario,$asunto,$cuerpo,$autologin)
    {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->cuerpo = $cuerpo;
        $this->autologin = $autologin;
    }

    public function enviarMensaje() {
        $mail = new PHPMailer(true);
        $Globales = new Globales();

        if ($this->cuerpo == 'recupero') {
            $this->setCuerpo( str_replace('******',$this->autologin,$Globales::EMAIL_RECUPERO));
        } else {
            $this->setCuerpo( str_replace('******',$this->autologin,$Globales::EMAIL_VERIFICAION));
        }
        

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.minnoc.com.ar';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'notificaciones@minnoc.com.ar';                     //SMTP username
            $mail->Password   = 'NotificamosForms';                               //SMTP password
            $mail->SMTPSecure = "ssl";            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('notificaciones@dominio.com', 'MINNOC');
            $mail->addAddress($this->getDestinatario());     //Add a recipient
            //$mail->addAddress('ellen@example.com');               //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
        
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->getAsunto();
            $mail->Body    = $this->getCuerpo();
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    /**
     * Get the value of destinatario
     */ 
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set the value of destinatario
     *
     * @return  self
     */ 
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get the value of asunto
     */ 
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set the value of asunto
     *
     * @return  self
     */ 
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get the value of cuerpo
     */ 
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set the value of cuerpo
     *
     * @return  self
     */ 
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    /**
     * Get the value of autologin
     */ 
    public function getAutologin()
    {
        return $this->autologin;
    }

    /**
     * Set the value of autologin
     *
     * @return  self
     */ 
    public function setAutologin($autologin)
    {
        $this->autologin = $autologin;

        return $this;
    }
}


?>