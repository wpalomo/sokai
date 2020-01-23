<?php

require_once('lib/PHPMailer-5.2.26/PHPMailerAutoload.php');

class sendEmail {

    public function enviarCorreo($tipo, $nombre,$claveAcceso,$email,$id_empresa) {

        $mail = new PHPMailer;

        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = 'wilianponce4g@gmail.com';          // SMTP username
        $mail->Password = 'songokussj2809'; // SMTP password
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                          // TCP port to connect to

        $mail->setFrom('wilianponce4g@gmail.com', 'Facturacion Electronica');

        $mail->addAddress('wilianponce2809@gmail.com');
        //$mail->addAddress($email);   // Add a recipient
       // $mail->addAddress("alexander2714@gmail.com");   // Add a recipient   
        
        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = "Estimado(a):<br><bold> " . $nombre . "</bold><br> 
        Le informamos que su comprobante electrónico ha sido emitido exitosamente y 
        se encuentra adjunto al presente correo.".$email;


        $mail->Subject = $tipo . ' Facturacion Electronica';
        $mail->Body = $bodyContent;
        $mail->addAttachment('../server/'.$id_empresa.'/comprobantes/retencion/' . $claveAcceso . '.pdf');
        $mail->addAttachment('../server/'.$id_empresa.'/comprobantes/retencion/' . $claveAcceso . ".xml");
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else { 
            return true;
        }
    }

}
