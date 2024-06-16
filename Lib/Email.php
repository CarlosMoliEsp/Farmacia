<?php 
namespace Lib;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    static public function send($asunto,$contenido,$para,$nombre){
        try {
            // Contenido del correo
            if (!filter_var($para, FILTER_VALIDATE_EMAIL)) {
              throw new Exception('Dirección de correo electrónico no válida.');
            }
       
            // Intancia de PHPMailer
            $mail  = new PHPMailer();
         
            // Es necesario para poder usar un servidor SMTP como gmail
            $mail->isSMTP();
         
            // Si estamos en desarrollo podemos utilizar esta propiedad para ver mensajes de error
            //SMTP::DEBUG_OFF    = off (for production use) 0
            //SMTP::DEBUG_CLIENT = client messages 1 
            //SMTP::DEBUG_SERVER = client and server messages 2
            $mail->SMTPDebug     = SMTP::DEBUG_OFF;
         
            //Set the hostname of the mail server
            $mail->Host          = 'smtp.gmail.com';
            $mail->Port          = 465; // o 587
         
            // Propiedad para establecer la seguridad de encripción de la comunicación
            $mail->SMTPSecure    = PHPMailer::ENCRYPTION_SMTPS; // tls o ssl para gmail obligado
         
            // Para activar la autenticación smtp del servidor
            $mail->SMTPAuth      = true;
       
            // Credenciales de la cuenta
            $email              = 'carlosaldeire@gmail.com';
            $mail->Username     = $email;
            $mail->Password     = 'dxcmtsocfgoyyfnm';
         
            // Quien envía este mensaje
            $mail->setFrom($email, 'Farmacia Aldeire');
       
            // Si queremos una dirección de respuesta
           // $mail->addReplyTo('replyto@panchos.com', 'Pancho Doe');
         
            // Destinatario
            $mail->addAddress($para, $nombre);
         
            // Asunto del correo
            $mail->Subject = $asunto;
       
            // Contenido
            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Body    = sprintf('<h1>Gracias por confiar en Farmacia Aldeire:</h1><br><p>%s</p>', $contenido);
         
            // Texto alternativo
           // $mail->AltBody = 'No olvides suscribirte a nuestro canal.';
       
            // Agregar algún adjunto
            //$mail->addAttachment(IMAGES_PATH.'logo.png');
         
            // Enviar el correo
            if (!$mail->send()) {
              throw new Exception($mail->ErrorInfo);
            }
            
            //echo 'Mensaje enviado';

       
          } catch (Exception $e) {
            echo $e;
          }
            
    }
    
}


