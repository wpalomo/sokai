<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Factura;

include 'class/lib/nusoap.php';
use nusoap_client;
include 'class/generarPDF.php';
use DOMDocument;
use generarPDF;

class FacturacionController extends Controller
{
    public function respfactura(Request $request){
        $tipo = $request->tipo;
        if($tipo=='facturaventa'){
            $fact = Factura::findOrFail($request->id);
        }else{
            $fact = Factura::findOrFail($request->id);  
        } 
        $fact->respuesta = $request->estado;
        $fact->save();
    }
    public function firmaphp(Request $request){
        $mensaje = $request->mensaje;
        $id_empresa = $request->id_empresa;
        $tipo = $request->tipo;
        if($tipo=='facturaventa'){
            $file = fopen("../server/".$id_empresa."/comprobantes/factura/facturaFirmada.xml", "w"); 
        }else{
            $file = fopen("../server/".$id_empresa."/comprobantes/factura/facturaFirmada.xml", "w");
        }
        fwrite($file, $mensaje . PHP_EOL);
        fclose($file);
    }
    public function validarComprobantephp(Request $request){
        header("Content-Type: text/plain");
        session_start();
        $id_empresa = $request->id_empresa; 
        $service = $request->service;
        $claveAcceso = $request->claveAcceso;
        $tipo = $request->tipo;
        if($tipo=='facturaventa'){
            $contenido = "../server/".$id_empresa."/comprobantes/factura/facturaFirmada.xml";
            $errorlog = "../server/".$id_empresa."/comprobantes/factura/errores/log.txt";
            $errorfact = "../server/".$id_empresa.'/comprobantes/factura/errores/'.$claveAcceso.".txt";
        }else{
            $contenido = "../server/".$id_empresa."/comprobantes/factura/facturaFirmada.xml";
            $errorlog = "../server/".$id_empresa."/comprobantes/factura/errores/log.txt";
            $errorfact = "../server/".$id_empresa.'/comprobantes/factura/errores/'.$claveAcceso.".txt";
        }
        $content = file_get_contents($contenido);
        $mensaje = base64_encode($content);
        $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
        $parametros = array(); 
        $parametros['xml'] = $mensaje;
        echo serialize($servicio). "\n";
        $client = new nusoap_client($servicio);
        $client->soap_defencoding = 'utf-8';
        $result = $client->call("validarComprobante", $parametros, "http://ec.gob.sri.ws.recepcion");
        echo serialize($client). "\n";
        echo serialize($client);
        $response = array();
        $file = fopen($errorlog, "a+");
        fwrite($file, "Servicio: " . $service . PHP_EOL);
        fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);
        var_dump($client->getError());
        $_SESSION['validarComprobante']=$result;
        if ($client->fault) {  
            $file_error = fopen($errorfact, "w");
            fwrite($file_error, "Servicio: " . $service . PHP_EOL);
            fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
            fwrite($file_error, "Respuesta: " . print_r($result,true) . PHP_EOL);
            fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
            fclose($file_error);
            fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
            echo serialize($result);
            
        } else {
            $error = $client->getError();
            if ($error) {
                fwrite($file, "Respuesta: " . print_r($error,true) . PHP_EOL);
                $file_error = fopen($errorfact, "w");
                fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                fwrite($file_error, "Respuesta: " . print_r($error,true) . PHP_EOL);
                fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
                fclose($file_error);
                echo serialize($error);
            } else {
                if ($result['estado']=='RECIBIDA'){
                    fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
                }else {
                    fwrite($file, "Respuesta: " . print_r($result,true) . PHP_EOL);
                    $file_error = fopen($errorfact, "w");
                    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);            
                    fwrite($file_error, "Respuesta: " . print_r($result,true) . PHP_EOL);
                    fwrite($file_error, "\n__________________________________________________________________\n". PHP_EOL);
                    fclose($file_error);
                }
                echo serialize($result);
            }
        }
        fwrite($file, "\n__________________________________________________________________\n". PHP_EOL);
        fclose($file);
    }
    public function autorizacionComprobantephp(Request $request){
        session_start();
        $claveAcceso = $request->claveAcceso;
        $service = $request->service;
        $id_empresa = $request->id_empresa;
        $tipo = $request->tipo;
        if($tipo=='facturaventa'){
            $validado = '../server/'.$id_empresa.'/comprobantes/factura/' . $claveAcceso . ".xml";
            $errorlog = "../server/".$id_empresa."/comprobantes/factura/errores/log.txt";
            $errorfact = "../server/".$id_empresa.'/comprobantes/factura/errores/'.$claveAcceso.".txt";
        }else{
            $validado = '../server/'.$id_empresa.'/comprobantes/factura/' . $claveAcceso . ".xml";
            $errorlog = "../server/".$id_empresa."/comprobantes/factura/errores/log.txt";
            $errorfact = "../server/".$id_empresa.'/comprobantes/factura/errores/'.$claveAcceso.".txt";
        }
        $recupera = DB::select("SELECT * FROM empresa WHERE id_empresa=".$id_empresa);
        $imagen = $recupera[0]->logo;
        $empresas = $recupera[0];
        $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        $parametros = array();
        $parametros['claveAccesoComprobante'] = $claveAcceso;
        $client = new nusoap_client($servicio);
        $error = $client->getError();
        $client->soap_defencoding = 'utf-8';
        $result = $client->call("autorizacionComprobante", $parametros, "http://ec.gob.sri.ws.autorizacion");
        $_SESSION['autorizacionComprobante'] = $result;
        $response = array();
        $file = fopen($errorlog, "a+");
        fwrite($file, "Servicio: " . $service . PHP_EOL);
        fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);
        if ($client->fault) {
            fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
            $file_error = fopen($errorfact, "w");
            fwrite($file_error, "Servicio: " . $service . PHP_EOL);
            fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
            fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
            fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
            fclose($file_error);
            echo serialize($result);
        } else {
            $error = $client->getError();
            if ($error) {
                fwrite($file, "Respuesta: " . print_r($error, true) . PHP_EOL);
                $file_error = fopen($errorfact, "w");
                fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                fwrite($file_error, "Respuesta: " . print_r($error, true) . PHP_EOL);
                fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                fclose($file_error);
                echo serialize($error);
            } else {
                //echo serialize($result);
                fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                if ($result['autorizaciones']['autorizacion']['estado'] != 'AUTORIZADO') {
                    $file_error = fopen($errorfact, "w");
                    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                    fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
                    fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                    fclose($file_error);
                } else {
                    if (!empty($result['autorizaciones']['autorizacion']['comprobante'])) {
                        $file_comprobante = fopen($validado, "w");
                        $comprobante = $client->responseData;
                        $simplexml = simplexml_load_string($comprobante);
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);
                        fwrite($file_comprobante, $xml . PHP_EOL);
                        fclose($file_comprobante);
                        $dataComprobante = simplexml_load_string($result['autorizaciones']['autorizacion']['comprobante']);
                        if ($dataComprobante->infoFactura) {
                            $facturaPDF = new generarPDF();
                            $facturaPDF->facturaPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas);
                        }
                        if ($dataComprobante->infoNotaCredito) {
                            $facturaPDF = new generarPDF();
                            $facturaPDF->notaCreditoPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas);
                        }
                        if ($dataComprobante->infoCompRetencion) {
                            $facturaPDF = new generarPDF();
                            $facturaPDF->comprobanteRetencionPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas);
                        }
                        if ($dataComprobante->infoGuiaRemision) {
                            $facturaPDF = new generarPDF();
                            $facturaPDF->guiaRemisionPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas);
                        }
                        if ($dataComprobante->infoNotaDebito) {
                            $facturaPDF = new generarPDF();
                            $facturaPDF->notaDebitoPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas);
                        }
                    }
                }
            }
        }
        fwrite($file, "\n__________________________________________________________________\n" . PHP_EOL);
        fclose($file);
    }
    public function leerFactura(Request $request){
        session_start();
        $ruta = $request->ruta_factura;
        $myxmlfilecontent = file_get_contents($ruta);
        $text = trim(preg_replace('/\s+/', ' ', $myxmlfilecontent));
        $text = preg_replace("/(?<=\>)(\r?\n)|(\r?\n)(?=\<\/)/", '', $text);
        $text = trim(str_replace('> <', '><', $text));
        $text = utf8_encode($text);
        $xml = simplexml_load_string($text);
        $text = utf8_decode($text);
        if ($xml->attributes()->version) {
            $version = $xml->attributes()->version;
            $id = $xml->attributes()->id;
            $text = trim(preg_replace('/<factura version="' . $version . '" id="' . $id . '">/', '<factura id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<notaCredito version="' . $version . '" id="' . $id . '">/', '<notaCredito id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<notaDebito version="' . $version . '" id="' . $id . '">/', '<notaDebito id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<comprobanteRetencion version="' . $version . '" id="' . $id . '">/', '<comprobanteRetencion id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<guiaRemision version="' . $version . '" id="' . $id . '">/', '<guiaRemision id="' . $id . '" version="' . $version . '">', $text));
            $text = str_replace(
                    array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $text
            );
            $text = str_replace(
                    array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $text);
            $text = str_replace(
                    array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $text);
            $text = str_replace(
                    array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $text);
            $text = str_replace(
                    array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $text);
            $text = str_replace(
                    array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $text
            );
            $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã",
                "ÃŠ", "ÃŽ", "Ã", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã", "Ã‹", "Ñ", "*", "%");
            $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", ".", ".");
            $text = str_replace($no_permitidas, $permitidas, $text);
        }
        echo $text;
    }
    public function validarFechaCertificadophp(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $fechaInicio = substr($fechaInicio,4, 11);
        $fechaInicio = date('Y-m-d',strtotime($fechaInicio));

        $fechaFin = substr($fechaFin,4, 11);
        $fechaFin= date('Y-m-d',strtotime($fechaFin));

        $fechaActual=date('Y-m-d');

        if($fechaActual <= $fechaFin){
            echo "Certificado Vigente";
        }else{
            $file = fopen("../error_log.txt", "a+");
            $date = date('m/d/Y h:i:s a', time());
            fwrite($file, "Error: " .$date. ' Fecha vencimiento del certificado excedida'. PHP_EOL);
            echo "Valide las fechas de vencimiento del certificado";
        }
    }
}
