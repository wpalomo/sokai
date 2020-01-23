<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiposustento;
use App\Models\Importacion;
use App\Models\FacturaCompra;
use App\Models\ProductoFactura;
use App\Models\Proveedor;
use App\Models\Pagocompra; 
use App\Models\RetencionFactura;
use App\Models\Creditocompra;
use App\Models\Detalle_factura_compra;
use App\Models\Cuentaporpagar;
use App\Models\Factura;
use App\Models\Provincia;
use App\Models\Ptoemision;
use App\Models\Cliente;
use App\Models\Moneda;


include 'class/lib/nusoap.php';
use nusoap_client;
include 'class/generarPDFretencion.php';
use DOMDocument;
use generarPDF;

class FacturacompraController extends Controller
{
    public function index(Request $request, $id){
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = FacturaCompra::addSelect(['nombre' => Proveedor::select('nombre_proveedor')
        ->whereColumn('id_proveedor', 'factura_compra.id_proveedor'),
        'provincias' => Proveedor::select('id_provincia')
        ->whereColumn('id_proveedor', 'factura_compra.id_proveedor')
        ])
        ->where('id_empresa', '=', $id)
        ->where('modo_orden','=',0)
        ->get();
        } else {
            $recupera = FacturaCompra::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('cod_factcompra', 'like', '%' . $buscar . '%')
                    ->orWhere('cotizacion', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_factcompra ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factc = new FacturaCompra();
        $factc->destino_pago = $request->destino_pago;
        $factc->gasto_import = $request->gasto_import;
        $factc->id_importacion = $request->nro_importacion;
        $factc->orden_compra = $request->orden_compra;
        $factc->descripcion = $request->descripcion;
        $factc->fech_emision = $request->fech_emision;
        $factc->fech_validez = $request->fech_validez;
        $factc->nro_autorizacion = $request->nro_autorizacion;

        $factc->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factc->subtotal_12 = $request->subtotal_12;
        $factc->subtotal_0 = $request->subtotal_0;
        $factc->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factc->descuento = $request->descuento;
        $factc->valor_ice = $request->valor_ice;
        $factc->valor_irbpnr = $request->valor_irbpnr;
        $factc->iva_12 = $request->iva_12;
        $factc->total_factura = $request->total_factura;

        $factc->id_sustento = $request->id_sustento;
        $factc->id_proveedor = $request->id_proveedor;
        $factc->id_cliente_asoc = $request->id_cliente_asoc;
        $factc->id_importacion = $request->id_importacion;
        $factc->id_user = $request->id;
        $factc->id_empresa = $request->id_empresa;
        $factc->save();
        $id = $factc->id_factcompra;    
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_factura_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($request->productos[$a]["p_descuento"]==1){
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
            }else{
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
            }
            $dfactc->iva = $request->productos[$a]["iva"];
            $dfactc->ice = $request->productos[$a]["ice"];
            $dfactc->irbpnr = 0;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            $dfactc->save();
        }
        if($request->verpagos){
            for ($a = 0; $a < count($request->pagos); $a++) {
                if ($request->pagos[$a]["metodo_pago"] != null && $request->pagos[$a]["cantidad_pago"] != 0) {
                    $cxc = new Cuentaporpagar();
                    $cxc->unidad_tiempo = "Dias";
                    $cxc->nro_cuota = 1;
                    $cxc->cuotas_totales = 1;
                    $cxc->forma_pago = $request->pagos[$a]["metodo_pago"];
                    $cxc->banco = $request->pagos[$a]["banco_pago"];
                    $cxc->nro_tarjeta = $request->pagos[$a]["tarjeta"];
                    $cxc->cta_contable = $request->pagos[$a]["cuenta"];
                    $cxc->monto = $request->pagos[$a]["cantidad_pago"];;
                    $cxc->abono = 0;
                    $cxc->saldo = 0;
                    $cxc->nro_trans = $request->pagos[$a]["nro_trans"];
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura = $id;
                    $cxc->save();
                }
            }
        }
        if($request->vercreditos){
            $cxc = new Cuentaporpagar();
            $cxc->unidad_tiempo = $request->periodo;
            $cxc->nro_cuota = 1;
            $cxc->cuotas_totales = $request->plazos;
            $cxc->forma_pago = "Crédito";
            $cxc->monto = $request->monto;
            $cxc->abono = 0;
            $cxc->saldo = $request->monto;
            $cxc->estado = 1;
            $cxc->tipo = 1;
            $cxc->id_factura = $id;
            $cxc->save();
        }
        if($request->verretencion){
            for ($i = 0; $i < count($request->retenciones); $i++) {
                if ($request->retenciones[$i]["iva"] != null && $request->retenciones[$i]["renta"] != null) {
                    $ret = new RetencionFactura();
                    $ret->id_factura = $id;
                    $ret->id_retencion_iva = $request->retenciones[$i]["iva"]["id_retencion"];
                    $ret->id_retencion_renta = $request->retenciones[$i]["renta"]["id_retencion"];

                    $ret->porcentajeiva = $request->retenciones[$i]["porcentajeiva"];
                    $ret->cantidadiva = $request->retenciones[$i]["cantidadiva"];
                    $ret->baserenta = $request->retenciones[$i]["baserenta"];
                    $ret->porcentajerenta = $request->retenciones[$i]["porcentajerenta"];
                    $ret->cantidadrenta = $request->retenciones[$i]["cantidadrenta"];
                    $ret->save();
                }
            }
        }
        return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
        ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("factura_compra.id_factcompra", "=", $id)
        ->orderByRaw('factura_compra.id_factcompra DESC')->get();
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
            // Agregar Encabezados
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
    public function firmaphp(Request $request){
        $mensaje = $request->mensaje;
        $id_empresa = $request->id_empresa;
        $file = fopen("../server/".$id_empresa."/comprobantes/retencion/facturaFirmada.xml", "w"); 
        fwrite($file, $mensaje . PHP_EOL);
        fclose($file);
    }
    public function validarComprobantephp(Request $request){
        header("Content-Type: text/plain");
        session_start();
        $id_empresa = $request->id_empresa;
        $content = file_get_contents("../server/".$id_empresa."/comprobantes/retencion/facturaFirmada.xml");
        $mensaje = base64_encode($content);

        $claveAcceso = $request->claveAcceso;
        $service = $request->service;

        //EndPoint
        $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"; //url del servicio
        $parametros = array(); //parametros de la llamada
        $parametros['xml'] = $mensaje;

        $client = new nusoap_client($servicio);

        $client->soap_defencoding = 'utf-8';

        $result = $client->call("validarComprobante", $parametros, "http://ec.gob.sri.ws.recepcion");
        $response = array();

        $file = fopen("../server/".$id_empresa."/comprobantes/retencion/errores/log.txt", "a+");
        fwrite($file, "Servicio: " . $service . PHP_EOL);
        fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);

        //var_dump($client->getError());die;


        $_SESSION['validarComprobante']=$result;

        if ($client->fault) {  
            $carpeta ="../server/".$id_empresa."/comprobantes/retencion/errores";
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $file_error = fopen("../server/".$id_empresa.'/comprobantes/retencion/errores/'.$claveAcceso.".txt", "w");
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
                $file_error = fopen("../server/".$id_empresa.'/comprobantes/retencion/errores/'.$claveAcceso.".txt", "w");
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
                    $file_error = fopen("../server/".$id_empresa.'/comprobantes/retencion/errores/'.$claveAcceso.".txt", "w");
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

        $recupera = DB::select("SELECT * FROM empresa WHERE id_empresa=".$id_empresa);

        //EndPoint
        $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"; //url del servicio
        $parametros = array(); //parametros de la llamada
        $parametros['claveAccesoComprobante'] = $claveAcceso;
        $client = new nusoap_client($servicio);
        $error = $client->getError();
        $client->soap_defencoding = 'utf-8';
        $result = $client->call("autorizacionComprobante", $parametros, "http://ec.gob.sri.ws.autorizacion");
        $_SESSION['autorizacionComprobante'] = $result;
        $response = array();
        $file = fopen("../server/".$id_empresa."/comprobantes/retencion/errores/log.txt", "a+");
        fwrite($file, "Servicio: " . $service . PHP_EOL);
        fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);
        if ($client->fault) {
            fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
            
            $file_error = fopen("../server/".$id_empresa."/comprobantes/retencion/errores/" . $claveAcceso . ".txt", "w");
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
                $file_error = fopen("../server/".$id_empresa."/comprobantes/retencion/errores/" . $claveAcceso . ".txt", "w");
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
                    $file_error = fopen("../server/".$id_empresa."/comprobantes/retencion/errores/" . $claveAcceso . ".txt", "w");
                    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                    fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
                    fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                    fclose($file_error);
                } else {
                    if (!empty($result['autorizaciones']['autorizacion']['comprobante'])) {
                        $file_comprobante = fopen('../server/'.$id_empresa.'/comprobantes/retencion/' . $claveAcceso . ".xml", "w");
                        $comprobante = $client->responseData;
                        $simplexml = simplexml_load_string($comprobante);
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);
                        fwrite($file_comprobante, $xml . PHP_EOL);
                        fclose($file_comprobante);
                        $dataComprobante = simplexml_load_string($result['autorizaciones']['autorizacion']['comprobante']);
                        $facturaPDF = new generarPDF();
                        $imagen = $recupera[0]->logo;
                        $facturaPDF->comprobanteRetencionPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen);
                    }
                }
            }
        }
        fwrite($file, "\n__________________________________________________________________\n" . PHP_EOL);
        fclose($file);
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
            $file = fopen("../error_log", "a+");
            $date = date('m/d/Y h:i:s a', time());
            fwrite($file, "Error: " .$date. ' Fecha vencimiento del certificado excedida'. PHP_EOL);
            echo "Valide las fechas de vencimiento del certificado";
        }
    }
    public function respfactura(Request $request){
        $fact = FacturaCompra::findOrFail($request->id);
        $fact->respuesta = $request->estado;
        $fact->save();
    }

    public function guardarProducto(Request $request)
    {
        $idfact=$request->id_factura;
        for($d = 0; $d < count($request->contenidopr); $d++){
            $prod=new ProductoFactura();
            $prod->nombre=$request->contenidopr[$d]["nombre"];
            $prod->cantidad=$request->contenidopr[$d]["cantidad"];
            $prod->precio=$request->contenidopr[$d]["precio"];
            $prod->descuento=$request->contenidopr[$d]["descuento"];
            $prod->subtotal=$request->subtotal;
            $prod->id_factcompra=$idfact;
            $prod->id_producto=$request->contenidopr[$d]["id_producto"];
            $prod->iva=$request->contenidopr[$d]["iva"];
            $prod->save();
        }
    }
    public function guardarPago(Request $request){
        $idfact=$request->id_factura;
        for($a=0; $a < count($request->valorpagos); $a++){
            if($request->valorpagos[$a]["metodo_pago"]!=null && $request->valorpagos[$a]["cantidad_pago"]!=0){
                $pagos = new Pagocompra();
                //, `id_ctacontable`
                $pagos->metodo_pago = $request->valorpagos[$a]["metodo_pago"];
                //$pagos->banco = $request->valorpagos[$a]["banco"];
                $pagos->tarjeta = $request->valorpagos[$a]["tarjeta"];
                //$pagos->cuenta_contable = $request->valorpagos[$a]["cuenta"];
                $pagos->cantidad_pago = $request->valorpagos[$a]["cantidad_pago"];
                $pagos->comentario_pago = $request->valorpagos[$a]["comentario_pago"];
                $pagos->id_factcompra = $idfact;
                $pagos->banco_pago=$request->valorpagos[$a]["banco_pago"];
                $pagos->id_empresa = $request->empresa;
                //$pagos->id_ctacontable=$request->valorpagos[$a]["cuenta"];
                $pagos->save();
            }
        }
    }
    public function guardarRetencion(Request $request){
        /*
        $idfact=$request->id_factura;
        for($a=0; $a < count($request->valorretencion); $a++){
                $pagos = new RetencionFactura();
                //`retencion`, , ``, ``, `id_impuesto`, `id_factcompra`, `id_empresa`, 
                $pagos->base = $request->valorretencion[$a]["base"];
                $pagos->porcentaje_fuente = $request->valorretencion[$a]["porcentaje_fuente"];
                $pagos->total_fuente = $request->valorretencion[$a]["total_fuente"];
                $pagos->id_retencion_fuente = $request->valorretencion[$a]["id_retencion_fuente"];
                //retencion iva
                /*$pagos->retencion = $request->valorretencion[$a]["base_iva"];
                $pagos->porcentaje_iva = $request->valorretencion[$a]["porcentaje_iva"];
                $pagos->total_iva = $request->valorretencion[$a]["total_iva"];
                $pagos->id_retencion_iva = $request->valorretencion[$a]["id_retencion_iva"];*/
                /*$pagos->id_factcompra = $idfact;
                $pagos->id_empresa = $request->empresa;
                $pagos->save();
        }*/
        //`retencion`, `base`, `porcentaje_iva`, `porcentaje_fuente`, `total_fuente`, `total_iva`, `id_retencion_fuente`, `id_retencion_iva`, `id_impuesto`, `id_factcompra`, `id_empresa`
        $id=$request->id_factura;
        for ($i = 0; $i < count($request->retencion); $i++) {
            if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                $ret = new RetencionFactura();
                $ret->base = $request->retencion[$i]["baserenta"];
                $ret->porcentaje_fuente = $request->retencion[$i]["porcentajerenta"];
                $ret->total_fuente = $request->retencion[$i]["cantidadrenta"];
                $ret->id_retencion_fuente = $request->retencion[$i]["renta"]["id_retencion"];
                //retencion iva
                //$ret->retencion = $request->retencion[$i]["base_iva"];
                $ret->porcentaje_iva = $request->retencion[$i]["porcentajeiva"];
                $ret->total_iva = $request->retencion[$i]["cantidadiva"];
                $ret->id_retencion_iva = $request->retencion[$i]["iva"]["id_retencion"];
                $ret->id_factcompra = $id;
                $ret->id_empresa = $request->empresa;
                $ret->save();
            }
        }
    }
    public function abrir(Request $request)
    {
        /*$id = $request->id;
        $recupera = DB::select('SELECT * FROM `factura_compra` WHERE id_factcompra='.$id);
        return $recupera;*/
        $id = $request->id;
        $recupera = FacturaCompra::addSelect(['nombre' => Proveedor::select('nombre_proveedor')
        ->whereColumn('id_proveedor', 'factura_compra.id_proveedor'),
        'provincias' => Proveedor::select('id_provincia')
        ->whereColumn('id_proveedor', 'factura_compra.id_proveedor')
    ])
        ->where('id_factcompra', '=', $id)
        ->get();
    
        return $recupera;
    }
    public function abrirCredito($id){
        $data = DB::select("SELECT * FROM `credito_compras` WHERE `id_factcompra` = ".$id);
        return $data;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $factcompra=FacturaCompra::find($request->id);
            $factcompra->nro_factprov=$request->nro_factprov;
            $factcompra->destino_pago=$request->destino_pago;
            $factcompra->fech_factcompra=$request->fech_factcompra;
            //$factcompra->especial=$request->especial;
            $factcompra->gasto_import=$request->gasto_import;
            $factcompra->cotizacion=$request->cotizacion;
            $factcompra->descripcion=$request->descripcion;
            //$factcompra->tipo_iva=$request->tipo_iva;
            //$factcompra->unid_destino=$request->unid_destino;
            $factcompra->fech_emision=$request->fech_emision;
            $factcompra->fech_validez=$request->fech_validez;
            $factcompra->serie=$request->serie;
            $factcompra->nro_autorizacion=$request->nro_autorizacion;
            $factcompra->tipo_pago=$request->tipo_pago;
            $factcompra->pago_ant=$request->pago_ant;
            $factcompra->metodo_pago=$request->metodo_pago;
            $factcompra->efectivo=$request->efectivo;
            $factcompra->cant_efectivo=$request->cant_efectivo;
            $factcompra->caja_chica=$request->caja_chica;
            $factcompra->cant_caja=$request->cant_caja;
            $factcompra->nro_caja=$request->nro_caja;
            $factcompra->transaccion=$request->transaccion;
            $factcompra->tipo_transaccion=$request->tipo_transaccion;
            $factcompra->nro_transacion=$request->nro_transacion;
            $factcompra->fech_transacion=$request->fech_transacion;
            $factcompra->tarjeta=$request->tarjeta;
            $factcompra->nro_tarjeta=$request->nro_tarjeta;
            $factcompra->cant_tarjeta=$request->cant_tarjeta;
            $factcompra->nro_pago=$request->nro_pago;
            $factcompra->plazo_pago=$request->plazo_pago;
            $factcompra->dias_pago_empresa=$request->dias_pago_empresa;
            $factcompra->total_neto=$request->total_neto;
            $factcompra->base_12=$request->base_12;
            $factcompra->base_0=$request->base_0;
            $factcompra->tarifa_no_graba=$request->tarifa_no_graba;
            $factcompra->otro_imp=$request->otro_imp;
            $factcompra->iva=$request->iva;
            $factcompra->total_factura=$request->total_factura;
            //$factcompra->compensacion=$request->compensacion;
            $factcompra->id_sustento=$request->id_sustento;
            $factcompra->id_proveedor=$request->id_proveedor;
            $factcompra->id_moneda=$request->id_moneda;
            $factcompra->id_provincia=$request->id_provincia;
            $factcompra->id_cliente_asoc=$request->id_cliente_asoc;
            //$factcompra->id_producto_fact=$request->id_producto_fact;
            $factcompra->id_retiva=$request->id_retiva;
            $factcompra->id_retfuente=$request->id_retfuente;
            $factcompra->id_caja=$request->id_caja;
            $factcompra->id_banco=$request->id_banco;
            $factcompra->id_importacion=$request->id_importacion;
            $factcompra->save();

            $idfact=$factcompra->id_factcompra;
            return $idfact;
    }
    public function actProducto(Request $request)
    {
        $idfact=$request->id_factura;
        //$prod=ProductoFactura::where('id_factcompra','=',$idfact)->get();
        
        for($d = 0; $d < count($request->contenidopr); $d++){
            //$prod=ProductoFactura::fill($request->all());
            $prod=ProductoFactura::updateOrCreate(
        ['nombre'=> $request->contenidopr[$d]["nombre"]],
        ['cantidad'=>$request->contenidopr[$d]["cantidad"]],
        ['precio'=>$request->contenidopr[$d]["precio"]],
        ['descuento'  => $request->contenidopr[$d]["descuento"]],
        ['id_producto'=> $request->contenidopr[$d]["id_producto"]]
    );
            /*$prod->nombre=$request->contenidopr[$d]["nombre"];
            $prod->cantidad=$request->contenidopr[$d]["cantidad"];
            $prod->precio=$request->contenidopr[$d]["precio"];
            $prod->descuento=$request->contenidopr[$d]["descuento"];
            $prod->subtotal=$request->subtotal;
            $prod->id_factcompra=$idfact;
            $prod->id_producto=$request->contenidopr[$d]["id_producto"];*/
            
            //$prod->save();
            /*DB::table('producto_factura')
            ->where('id_factcompra', $idfact)
            ->update(array( 'nombre' => $request->contenidopr[$d]["nombre"],
                        'cantidad'   => $request->contenidopr[$d]["cantidad"],
                        'precio'     => $request->contenidopr[$d]["precio"],
                        'descuento'  => $request->contenidopr[$d]["descuento"],
                        'id_producto'=> $request->contenidopr[$d]["id_producto"]
                    ));*/
        }
        return $prod;
    }
    public function actPago(Request $request)
    {
        $idfact=$request->id_factura;
        //$prod=ProductoFactura::where('id_factcompra','=',$idfact)->get();
        for($d = 0; $d < count($request->contenidopr); $d++){
            //$prod=ProductoFactura::fill($request->all());
            $prod=Pagocompra::updateOrCreate(['metodo_pago'=> $request->contenidopr[$d]["metodo_pago"]],
            ['cantidad_pago'=>$request->contenidopr[$d]["cantidad"]],
            ['descuento'  => $request->contenidopr[$d]["descuento"]],
            ['id_producto'=> $request->contenidopr[$d]["id_producto"]]);
        }
    }
    public function listarProduct(Request $request,$id){
        //$produc=ProductoFactura::where('id_factcompra','=',3);
        //return $produc;
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if($cantidadp < 1){
            $cantidadp=10;
        }
        if ($buscar==''){
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Detalle_factura_compra::select('*')
                    ->where('id_factura','=',$id)
                    ->orderByRaw('id_detalle_factura_compra ASC')->paginate($cantidadp);
        }else{
            $recupera = Detalle_factura_compra::select('*')
                    ->where(function($q) use ($buscar){
                        $q->where('nombre', '=', $buscar) ; 
                        
                    })
                    ->where('id_factura', '=', $idfactura)
                    ->orderByRaw('id_detalle_factura_compra ASC')->paginate($cantidadp);
        } 

        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total()/$cantidadp),
            ], 
            'recupera' => $recupera
        ];
        
    }
    public function listPagos(Request $request,$id){
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if($cantidadp < 1){
            $cantidadp=10;
        }
        if ($buscar==''){
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Pagocompra::select('*')
                    ->where('id_factcompra','=',$id)
                    ->orderByRaw('id_pagfactcompra ASC')->paginate($cantidadp);
        }else{
            $recupera = Pagocompra::select('*')
                    ->where(function($q) use ($buscar){
                        $q->where('metodo_pago', '=', $buscar) ; 
                        
                    })
                    ->where('id_factcompra', '=', $idfactura)
                    ->orderByRaw('id_pagfactcompra ASC')->paginate($cantidadp);
        } 
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total()/$cantidadp),
            ], 
            'recupera' => $recupera
        ];
    }
    public function eliminar ($id){
        Detalle_factura_compra::where('id_factura','=',$id)->delete();
        //Creditocompra::where('id_factcompra','=',$id)->delete();
        Cuentaporpagar::where('id_factura','=',$id)->delete();
        RetencionFactura::where('id_factura','=',$id)->delete();
        FacturaCompra::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
        */
    }
    public function getSustento(){
        $data=Tiposustento::all();
        return $data;
    }
    public function getProvincia(){
        $data=Provincia::all();
        return $data;
    }
    public function getImportacion(){
        $data=Importacion::all();
        return $data;
    }
    public function getPtoemision(){
        $data=Ptoemision::all();
        return $data;
    }
    public function getCliente(){
        $data=Cliente::all();
        return $data;
    }
    public function getMoneda(){
        $data=Moneda::all();
        return $data;
    }
    public function getRetencionFuente(){
        $termino="Retencion Fuente Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '".$termino."'");

        return $data;
    }
    public function getRetencionIva(){
        $termino="Retencion IVA Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '".$termino."'");

        return $data;
    }
    public function getPorcentaje(Request $request){
        $id=$request->id_retfuente;
        $data=DB::select("SELECT `porcen_retencion` FROM `retencion` WHERE `id_retencion` = ".$id);

        return $data;
    }
    public function getPorcentajeIva(Request $request){
        $id=$request->id_retiva;
        $data=DB::select("SELECT `porcen_retencion` FROM `retencion` WHERE `id_retencion` = ".$id);

        return $data;
    }
    public function getCaja(){
    // $id=$request->id_retiva;
        $data=DB::select("SELECT * FROM `caja` WHERE `id_empresa` = 1");

        return $data;
    }
    public function getBanco(){
        $data=DB::select("SELECT * FROM `banco`");

        return $data;
    }
}
