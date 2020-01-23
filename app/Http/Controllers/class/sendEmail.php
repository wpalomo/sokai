<?php

require_once('lib/PHPMailer-5.2.26/PHPMailerAutoload.php');

class sendEmail {

    public function enviarCorreo($tipo, $nombre,$claveAcceso,$email,$id_empresa,$empresas) {

        $correo = $empresas->email_empresa;
        $correopass = $empresas->password;
        $correoservidor = $empresas->servidor_correo;
        $correopuerto = $empresas->puerto_correo;
        $correoseguridad = $empresas->seguridad_correo;

        $mail = new PHPMailer;

        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = $correoservidor;             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = $correo;          // SMTP username
        $mail->Password = $correopass; // SMTP password
        $mail->SMTPSecure = $correoseguridad;                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $correopuerto;                          // TCP port to connect to

        $mail->setFrom($correo, 'Facturacion Electronica');

        $mail->addAddress('wilianponce2809@gmail.com');
        $mail->addAddress($email);   // Add a recipient
        $mail->addAddress($correo);   // Add a recipient
       //$mail->addAddress("alexander2714@gmail.com");   // Add a recipient   
        
        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = "Estimado(a):<br><bold> " . $nombre . "</bold><br> 
        Le informamos que su comprobante electrónico ha sido emitido exitosamente y 
        se encuentra adjunto al presente correo.".$email;


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
