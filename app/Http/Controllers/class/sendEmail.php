<?php

require_once('lib/PHPMailer-5.2.26/PHPMailerAutoload.php');

class sendEmail {

    public function enviarCorreo($tipo, $nombre,$claveAcceso,$email,$id_empresa,$empresas) {

        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo); 

        /*$mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;*/

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;             
        $mail->Port = $correopuerto;

        $mail->setFrom($correo, 'Facturacion Electronica');
        $mail->addAddress($email);   // Add a recipient
        $mail->addAddress($correo);   // Add a recipient

        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = "Estimado(a):<br><bold> " . $nombre . "</bold><br> 
        Le informamos que su comprobante electrónico ha sido emitido exitosamente y 
        se encuentra adjunto al presente correo. " . $email;

        $mail->Subject = $tipo . ' Facturacion Electronica';
        $mail->Body = $bodyContent;
        $mail->addAttachment('../server/'.$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.pdf');
        $mail->addAttachment('../server/'.$id_empresa.'/comprobantes/factura/' . $claveAcceso . ".xml");
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }

}
