<?php
error_reporting(0);
include ('ejecutar.php');

$firma = $_GET["firma"];
$p12 = $_GET["acceso"];
$xml = $_GET["factura"];
$id = $_GET["id"];


$firmaFactura = $_POST['respuestaFirmarFactura'];
$validarContraseña = $_POST['respuestaValidarContraseña'];
$validarVigencia = $_POST['respuestaValidarVigencia'];

echo $firmaFactura;
echo $validarContraseña;
echo $validarVigencia;

if(!empty($validarContraseña)){
      //Put Code

    $file = fopen("recibe.txt", "a+");
    fwrite($file, $validarContraseña .PHP_EOL);
    var_dump($validarContraseña);


}
if(!empty($validarVigencia)){

  //Put Code

    $file = fopen("recibe.txt", "w+");
    fwrite($file,$validarVigencia[0] .PHP_EOL);
    fwrite($file, $validarVigencia[1] .PHP_EOL);
    fwrite($file, $validarVigencia[2].PHP_EOL);
    var_dump($validarVigencia);

  //Put Code
}
if (!empty($firmaFactura)) {

    $validarComprobante = $firmaFactura[0];
    $autorizacionComprobante = $firmaFactura[1];

    var_dump($validarComprobante);
    var_dump($autorizacionComprobante);

    //Put Code

}

//$ruta_factura= 'http://localhost/lib_firma_sri/factura.xml';
//$ruta_factura='http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/factura.xml';
//$ruta_certificado= 'http://localhost/lib_firma_sri/angel_martin_pisculla_coque.p12';
//$ruta_certificado='http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/angel_martin_pisculla_coque.p12';
//$contraseña= 'Alejandro2014';
//$ruta_respuesta= 'http://localhost/lib_firma_sri/example.php';
//$ruta_respuesta='http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/example.php';

$ruta_factura = 'http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/xmlsokai/emite/1/facturas/0401202001093008746500110010010000000562809280911.xml';
$ruta_certificado = 'http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/xmlsokai/emite/1/firma/danny_javier_quintuna_esmeraldas.p12';
$contraseña = 'LunaZaky0914';

$ruta_respuesta='http://localhost/PROYECTO-SOKAI/base%20de%20datos/factura/example.php'; 

$ejecutar = new ejecutar();
$domain_dir = $_SERVER['SERVER_NAME'];

//Validar Contraseña del certificado
    //$ejecutar->validarContraseña($ruta_certificado,$contraseña,$ruta_respuesta);


//Validar Vigencia del certificado
    //$ejecutar->validarVigencia($ruta_certificado,$contraseña,$ruta_respuesta);


//Firmar Factura y enviar a SRI
$res = $ejecutar->firmarFactura($ruta_factura,$ruta_certificado,$contraseña,$ruta_respuesta);

echo $res;