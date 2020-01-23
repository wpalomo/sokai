<?php

include ('ejecutar.php');

$ruta_factura= base64_encode('http://localhost/factura/factura.xml');
$ruta_certificado= base64_encode('http://localhost/factura/aire.p12');
$contraseña= base64_encode('Malawi77584');
$ruta_respuesta= base64_encode('http://localhost/factura/recibe.php');

$ejecutar = new ejecutar();
$domain_dir = $_SERVER['SERVER_NAME'];

//ValidarContraseña
header ("Location: /factura/app/validarContraseña.php?ruta_certificado=".$ruta_certificado."&contraseña=".$contraseña."&ruta_respuesta=".$ruta_respuesta); 

//validarVigencia
header ("Location: /factura/app/validarVigencia.php?ruta_certificado=".$ruta_certificado."&contraseña=".$contraseña."&ruta_respuesta=".$ruta_respuesta); 

//firmarFactura
header ("Location: /factura/app/firmarFactura.php?ruta_factura=".$ruta_factura."&ruta_certificado=".$ruta_certificado."&contraseña=".$contraseña."&ruta_respuesta=".$ruta_respuesta); 




