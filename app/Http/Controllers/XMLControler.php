<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mtownsend\XmlToArray\XmlToArray;
use XMLWriter;
use App\Models\Detalle;
use App\Models\Detalle_factura_compra;
use App\Models\Pagos;
use App\Models\Cuentaporcobrar;
use App\Models\Empresa;
use App\Models\Retencion_factura;
use App\Models\Retencion_factura_comp;
use App\Models\Retencion;

class XMLControler extends Controller
{
    public function efactura(Request $re)
    {
        $xml = new XMLWriter(); 

        $carpeta = "../server/" . $re->id_empresa . "/comprobantes";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 = "../server/" . $re->id_empresa . "/comprobantes/factura";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = "../server/" . $re->id_empresa . "/comprobantes/factura/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = "../server/" . $re->id_empresa . "/comprobantes/factura/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        $carpeta4 = "../server/" . $re->id_empresa . "/comprobantes/retencion";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 ="../server/".$re->id_empresa."/comprobantes/retencion/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 ="../server/".$re->id_empresa."/comprobantes/retencion/codigosbarras";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }

        $xml->openUri('../server/' . $re->id_empresa . '/comprobantes/factura/' . $re->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('factura');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");


        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text($re->ambiente);
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text($re->tipo_emision);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($re->razon_social);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($re->nombre_empresa);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($re->ruc_empresa);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($re->clave_acceso);
        $xml->endElement();
        $xml->startElement("codDoc");
        $xml->text('01');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($re->clave_acceso, -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($re->direccion_empresa);
        $xml->endElement();

        $xml->endElement();


        $xml->startElement('infoFactura');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fecha_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($re->direccion_establecimiento);
        $xml->endElement();
        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        if ($re->tipo_identificacion == "Cédula de Identidad") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('05');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionComprador");
            $xml->text('07');
            $xml->endElement();
        }

        /*$xml->startElement("guiaRemision");
        $xml->text($re->direccion_empresa);
        $xml->endElement();*/

        $xml->startElement("razonSocialComprador");
        $xml->text($re->nombre);
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($re->identificacion);
        $xml->endElement();

        $xml->startElement("direccionComprador");
        $xml->text($re->direccion);
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text($re->subtotal_sin_impuesto);
        $xml->endElement();

        $xml->startElement("totalDescuento");
        $xml->text($re->descuentototal);
        $xml->endElement();

        $xml->startElement("totalConImpuestos");

        $det = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno", "producto.descripcion")->join("producto", "producto.id_producto", "=", "detalle.id_producto")->where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($det); $i++) {
            $xml->startElement("totalImpuesto");
            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            if ($det[$i]["iva"] == 1) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(0);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 2) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(2);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 3) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(6);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 4) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(7);
                $xml->endElement();
            }
            $xml->startElement("baseImponible");
            $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ' '));
            $xml->endElement();
            if ($det[$i]["iva"] == 2) {
                $xml->startElement("tarifa");
                $xml->text(12);
                $xml->endElement();
                $xml->startElement("valor");
                $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) * 0.12), 2, '.', ' '));
                $xml->endElement();
            } else {
                $xml->startElement("tarifa");
                $xml->text(0);
                $xml->endElement();
                $xml->startElement("valor");
                $xml->text(0.00);
                $xml->endElement();
            }
            $xml->endElement();
        }

        $xml->endElement();
        if ($re->propina > 0) {
            $xml->startElement("propina");
            $xml->text($re->propina);
            $xml->endElement();
        } else {
            $xml->startElement("propina");
            $xml->text("0.00");
            $xml->endElement();
        }

        $xml->startElement("importeTotal");
        $xml->text($re->valor_total);
        $xml->endElement();

        $xml->startElement("moneda");
        $xml->text(strtoupper($re->moneda));
        $xml->endElement();

        $xml->startElement("pagos");
        $cxc = Cuentaporcobrar::select("*")->where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($cxc); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            if ($cxc[$i]["forma_pago"] == 'Efectivo') {
                $xml->text('01');
            }
            if ($cxc[$i]["forma_pago"] == 'Deposito') {
                $xml->text('13');
            }
            if ($cxc[$i]["forma_pago"] == 'Cheque') {
                $xml->text('02');
            }
            if ($cxc[$i]["forma_pago"] == 'Transferencia') {
                $xml->text('07');
            }
            if ($cxc[$i]["forma_pago"] == 'Tarjeta Credito') {
                $xml->text('10');
            }
            if ($cxc[$i]["forma_pago"] == 'Otro') {
                $xml->text('20');
            }
            if ($cxc[$i]["forma_pago"] == 'Crédito') {
                $xml->text('01');
            }
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($cxc[$i]["monto"]);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text($cxc[$i]["cuotas_totales"]);
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text($cxc[$i]["unidad_tiempo"]);
            $xml->endElement();
            $xml->endElement();
        }
        $rfi = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfi); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfi[$i]["cantidadiva"]);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Días");
            $xml->endElement();
            $xml->endElement();
        }

        $rfr = retencion_factura::where("id_factura", "=", $re->id_factura)->get();
        for ($i = 0; $i < count($rfr); $i++) {
            $xml->startElement("pago");
            $xml->startElement("formaPago");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("total");
            $xml->text($rfr[$i]["cantidadrenta"]);
            $xml->endElement();
            $xml->startElement("plazo");
            $xml->text('01');
            $xml->endElement();
            $xml->startElement("unidadTiempo");
            $xml->text("Días");
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();

        $xml->endElement();

        $xml->startElement('detalles');
        for ($i = 0; $i < count($det); $i++) {
            $xml->startElement('detalle');
            $xml->startElement('codigoPrincipal');
            $xml->text($det[$i]["cod_principal"]);
            $xml->endElement();
            if ($det[$i]["cod_alterno"]) {
                $xml->startElement('codigoAuxiliar');
                $xml->text($det[$i]["cod_alterno"]);
                $xml->endElement();
            }
            $xml->startElement('descripcion');
            $xml->text($det[$i]["nombre"]);
            $xml->endElement();
            $xml->startElement('cantidad');
            $xml->text($det[$i]["cantidad"]);
            $xml->endElement();
            $xml->startElement('precioUnitario');
            $xml->text($det[$i]["precio"]);
            $xml->endElement();
            if ($det[$i]["descuento"]) {
                $xml->startElement('descuento');
                $xml->text($det[$i]["descuento"]);
                $xml->endElement();
            } else {
                $xml->startElement('descuento');
                $xml->text(0);
                $xml->endElement();
            }

            $xml->startElement('precioTotalSinImpuesto');
            $xml->text(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]);
            $xml->endElement();
            if ($det[$i]["descripcion"]) {
                $xml->startElement('detallesAdicionales');
                $xml->startElement('detAdicional');
                $xml->writeAttribute("descripcion", $det[$i]["descripcion"]);
                $xml->endElement();
                $xml->endElement();
            }
            $xml->startElement('impuestos');
            $xml->startElement('impuesto');

            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            if ($det[$i]["iva"] == 1) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(0);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 2) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(2);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 3) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(6);
                $xml->endElement();
            } else if ($det[$i]["iva"] == 4) {
                $xml->startElement("codigoPorcentaje");
                $xml->text(7);
                $xml->endElement();
            }
            if ($det[$i]["iva"] == 2) {
                $xml->startElement("tarifa");
                $xml->text(12);
                $xml->endElement();
            } else {
                $xml->startElement("tarifa");
                $xml->text(0);
                $xml->endElement();
            }
            $xml->startElement('baseImponible');
            $xml->text(number_format(($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"], 2, '.', ' '));
            $xml->endElement();
            if ($det[$i]["iva"] == 2) {
                $xml->startElement("valor");
                $xml->text(number_format(((($det[$i]["precio"] * $det[$i]["cantidad"]) - $det[$i]["descuento"]) * 0.12), 2, '.', ' '));
                $xml->endElement();
            } else {
                $xml->startElement("valor");
                $xml->text(0.00);
                $xml->endElement();
            }
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();

        $xml->startElement('infoAdicional');
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text($re->direccion);
        $xml->endElement();

        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Teléfono");
        $xml->text($re->telefono);
        $xml->endElement();

        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Email");
        $xml->text($re->email);
        $xml->endElement();

        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function e_comproretenc(Request $re)
    {
        $xml = new XMLWriter();
        $xml->openUri("../server/" . $re->id_empresa . "/comprobantes/retencion/" . $re->nro_autorizacion . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("comprobanteRetencion");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria
        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text($re->ambiente);
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text($re->tipo_emision);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($re->razon_social);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($re->nombre_empresa);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($re->ruc_empresa);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($re->nro_autorizacion);
        $xml->endElement();
        $xml->startElement("codDoc");
        $xml->text('07');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($re->codigoes, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($re->codigope, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($re->nro_autorizacion, -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($re->direccion_empresa);
        $xml->endElement();

        $xml->endElement();
        //infoCompRetencion
        $xml->startElement("infoCompRetencion");

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($re->direccion_prov);
        $xml->endElement();

        if ($re->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }

        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        if ($re->tipo_identificacion == "Cédula de Identidad") {
            $xml->startElement("tipoIdentificacionSujetoRetenido");
            $xml->text('05');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Ruc") {
            $xml->startElement("tipoIdentificacionSujetoRetenido");
            $xml->text('04');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Pasaporte") {
            $xml->startElement("tipoIdentificacionSujetoRetenido");
            $xml->text('06');
            $xml->endElement();
        } else if ($re->tipo_identificacion == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionSujetoRetenido");
            $xml->text('07');
            $xml->endElement();
        }

        $xml->startElement("razonSocialSujetoRetenido");
        $xml->text($re->nombre_proveedor);
        $xml->endElement();

        $xml->startElement("identificacionSujetoRetenido");
        $xml->text($re->identif_proveedor);
        $xml->endElement();

        $xml->startElement("periodoFiscal");
        $xml->text(date('m/Y', strtotime($re->fech_emision)));
        $xml->endElement();

        $xml->endElement();
        //impuestos
        $xml->startElement("impuestos");

        $det = Retencion_factura_comp::select("*")->where("id_factura", "=", "$re->id_factcompra")->get();
        for ($i = 0; $i < count($det); $i++) {
            if (strlen($det[$i]["id_retencion_iva"]) >= 1) {
                $xml->startElement("impuesto");
                $xml->startElement("codigo");
                $xml->text(2);
                $xml->endElement();
                if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 0) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("8");
                    $xml->endElement();
                } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 10) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("9");
                    $xml->endElement();
                } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 20) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("10");
                    $xml->endElement();
                } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 30) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("1");
                    $xml->endElement();
                } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 50) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("11");
                    $xml->endElement();
                } else if (str_replace("%", "", $det[$i]["porcentajeiva"]) <= 70) {
                    $xml->startElement("codigoRetencion");
                    $xml->text("2");
                    $xml->endElement();
                } else {
                    $xml->startElement("codigoRetencion");
                    $xml->text("3");
                    $xml->endElement();
                }
                $xml->startElement("baseImponible");
                $xml->text(($det[$i]["cantidadiva"] * 100) / str_replace("%", "", $det[$i]["porcentajeiva"]));
                $xml->endElement();
                $xml->startElement("porcentajeRetener");
                $xml->text(str_replace("%", "", $det[$i]["porcentajeiva"]));
                $xml->endElement();
                $xml->startElement("valorRetenido");
                $xml->text(number_format($det[$i]["cantidadiva"], 2, '.', ' '));
                $xml->endElement();
                $xml->startElement("codDocSustento");
                $xml->text("01");
                $xml->endElement();
                /*$xml->startElement("numDocSustento");
                                $xml->text($det[$i]["cantidadiva"]);
                                $xml->endElement();*/
                $xml->startElement("fechaEmisionDocSustento");
                $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                $xml->endElement();
                $xml->endElement();
            }
        }

        $det = Retencion_factura_comp::select("retencion_factura_comp.*", "retencion.cod_retencion as codigoreten")->join("retencion", "retencion.id_retencion", "=", "retencion_factura_comp.id_retencion_renta")->where("retencion_factura_comp.id_factura", "=", "$re->id_factcompra")->get();
        for ($i = 0; $i < count($det); $i++) {
            if (strlen($det[$i]["id_retencion_renta"]) >= 1) {
                $xml->startElement("impuesto");
                $xml->startElement("codigo");
                $xml->text(1);
                $xml->endElement();
                $xml->startElement("codigoRetencion");
                $xml->text($det[$i]["codigoreten"]);
                $xml->endElement();
                $xml->startElement("baseImponible");
                $xml->text($det[$i]["baserenta"]);
                $xml->endElement();
                $xml->startElement("porcentajeRetener");
                $xml->text(str_replace("%", "", $det[$i]["porcentajerenta"]));
                $xml->endElement();
                $xml->startElement("valorRetenido");
                $xml->text($det[$i]["cantidadrenta"]);
                $xml->endElement();
                $xml->startElement("codDocSustento");
                $xml->text('01');
                $xml->endElement();
                /*$xml->startElement("numDocSustento");
                                $xml->text($det[$i]["cantidadrenta"]);
                                $xml->endElement();*/
                $xml->startElement("fechaEmisionDocSustento");
                $xml->text(date('d/m/Y', strtotime($re->fech_emision)));
                $xml->endElement();
                $xml->endElement();
            }
        }

        $xml->endElement();
        //infoAdicional
        $xml->startElement("infoAdicional");

        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text($re->direccion_prov);
        $xml->endElement();
        if (strlen($re->telefono_prov) >= 1) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($re->telefono_prov);
            $xml->endElement();
        }
        if (strlen($re->nrcasa) >= 1) {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Número de casa");
            $xml->text($re->nrcasa);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endElement(); //fin comprobanteRetencion
        $xml->endDocument();

        $recupera = Empresa::select("*")->where("id_empresa", "=", $re->id_empresa)->get();
        return ["recupera" => $recupera[0]];
    }
    public function enotacredito()
    {
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/emite/ejemplofactura.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('factura');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");


        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codDoc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("secuencial");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        //INFORNOTACREDICO
        $xml->startElement('infoNotaCredito');
        $xml->startElement("fechaEmision");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("tipoIdentificacionComprador");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("razonSocialComprador");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("identificacionComprador");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("obligadoContabilidad");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codDocModificado");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("numDocModificado");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("fechaEmisionDocSustento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("totalSinImpuestos");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("valorModificacion");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("moneda");
        $xml->text("1");
        $xml->endElement();
        $xml->endDocument();
        //totalConImpuestos1
        $xml->startElement('totalConImpuestos');
        $xml->startElement("totalImpuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        //totalConImpuestos2
        $xml->startElement("totalImpuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        //totalConImpuestos3
        $xml->startElement("totalImpuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        $xml->startElement("motivo");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        //DETALLE
        $xml->startElement("detalles");
        //contenido detalle1
        $xml->startElement("detalle");
        $xml->startElement("codigoInterno");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoAdicional");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descripcion");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioUnitario");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descuento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioTotalSinImpuesto");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioTotalSinImpuesto");
        $xml->writeAttribute('nombre', 'honorario');
        $xml->writeAttribute('valor', '1');
        $xml->endElement();
        $xml->startElement("impuestos");
        $xml->startElement("impuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("tarifa");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("valor");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        //2
        $xml->startElement("detalle");
        $xml->startElement("codigoInterno");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoAdicional");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descripcion");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioUnitario");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descuento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioTotalSinImpuesto");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("detallesAdicionales");
        $xml->text("1");
        $xml->writeAttribute("nombre", "aaaaa");
        $xml->writeAttribute("valor", "a");
        $xml->endElement();
        $xml->startElement("detallesAdicionales");
        $xml->text("1");
        $xml->writeAttribute("nombre", "bbbbb");
        $xml->writeAttribute("valor", "b");
        $xml->endElement();
        $xml->startElement("detallesAdicionales");
        $xml->text("1");
        $xml->writeAttribute("nombre", "cccc");
        $xml->writeAttribute("valor", "c");
        $xml->endElement();
        $xml->endElement();

        $xml->startElement("secuencia");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioUnitario");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descuento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioTotalSinImpuesto");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("impuestos");
        $xml->startElement("impuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("tarifa");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("valor");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioUnitario");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("descuento");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("precioTotalSinImpuesto");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("impuestos");
        $xml->startElement("impuesto");
        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("tarifa");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("valor");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();

        $xml->endElement();


        $xml->startElement('infoFactura');

        $xml->startElement("fechaEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoIdentificacionComprador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("guiaRemision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocialComprador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("direccionComprador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("totalDescuento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("totalConImpuestos");

        $xml->startElement("totalImpuesto");

        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codigoPorcentaje");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tarifa");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("valor");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->startElement("propina");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("importeTotal");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("moneda");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("pagos");

        $xml->startElement("pago");

        $xml->startElement("formaPago");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("total");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("campoAdicional");
        $xml->writeAttribute('nombre', 'Email');
        $xml->text("2");
        $xml->endElement();

        $xml->startElement("unidadTiempo");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();
    }
    public function enotadebito()
    {
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/notaeje.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('notaDebito');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        $xml->startElement('infoTributaria');

        $xml->startElement("ambiente");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text("1");
        $xml->endElement();
        $xml->startElement("ptoEmi");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement('detalles');

        $xml->startElement('detalle');

        $xml->startElement('detalle');

        $xml->startElement('codigoPrincipal');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('codigoAuxiliar');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('descripcion');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('cantidad');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('precioUnitario');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('descuento');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('precioTotalSinImpuesto');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("impuestos");

        $xml->startElement('detAdicional');
        $xml->writeAttribute("nombre", "1");
        $xml->writeAttribute("valor", "1");
        $xml->endElement();

        $xml->endElement();

        $xml->startElement('impuestos');

        $xml->startElement('impuesto');

        $xml->startElement("valor");
        $xml->text("1");
        $xml->endElement();
        $xml->endElement();

        $xml->startElement('codigoPorcentaje');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('tarifa');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('baseImponible');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement('valor');
        $xml->text('1');
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->startElement('infoAdicional');
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "1");
        $xml->text('1');
        $xml->endElement();
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();
    }
    public function r_factura()
    {
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/r_factura.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("comprobanteRetencion");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria
        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        //infoCompRetencion
        $xml->startElement("infoCompRetencion");

        $xml->startElement("fechaEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoIdentificacionSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocialSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("identificacionSujetoRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("periodoFiscal");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        //impuestos
        $xml->startElement("impuestos");

        $xml->startElement("impuesto");

        $xml->startElement("codigo");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codigoRetencion");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("baseImponible");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("porcentajeRetener");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("valorRetenido");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("numDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("fechaEmisionDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();
        //infoAdicional
        $xml->startElement("infoAdicional");

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Teléfono");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "Email");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();
        $xml->endElement(); //fin comprobanteRetencion
        $xml->endDocument();

        $xml->startElement("plazo");
        $xml->text("1");
        $xml->endElement();
    }
    public function e_guiaremision()
    {
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/guiaremision.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("guiaRemision");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria
        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement('precioUnitario');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirPartida");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocialTransportista");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipoIdentificacionTransportista");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("rucTransportista");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("fechaIniTransporte");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("fechaFinTransporte");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("placa");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement('tarifa');
        $xml->text('1');
        $xml->endElement();

        $xml->startElement("destinatario");

        $xml->startElement("identificacionDestinatario");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("razonSocialDestinatario");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("dirDestinatario");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("motivoTraslado");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("docAduaneroUnico");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codEstabDestino");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("ruta");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("numDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("numAutDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("fechaEmisionDocSustento");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("detalles");

        $xml->startElement("detalle");

        $xml->startElement("codigoInterno");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codigoAdicional");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("descripcion");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("detallesAdicionales");

        $xml->startElement("detAdicional");
        $xml->writeAttribute("nombre", "honorario");
        $xml->writeAttribute("valor", "1");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->startElement('ds.DigestValue');


        $xml->endElement();

        $xml->endElement();

        $xml->startElement("detalle");

        $xml->startElement("codigoInterno");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("codigoAdicional");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("descripcion");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("cantidad");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("detallesAdicionales");

        $xml->startElement("detAdicional");

        $xml->writeAttribute("nombre", "aaaaa");
        $xml->writeAttribute("valor", "a");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("detAdicional");

        $xml->writeAttribute("nombre", "bbbbb");
        $xml->writeAttribute("valor", "b");
        $xml->text("1");

        $xml->endElement();

        $xml->startElement("detAdicional");

        $xml->writeAttribute("nombre", "cccc");
        $xml->writeAttribute("valor", "c");
        $xml->text("1");

        $xml->endElement();

        $xml->endElement();

        $xml->endElement();


        $xml->startElement('ds:KeyValue');
        $xml->startElement('ds:RSAKeyValue');

        $xml->startElement('ds:Modulus');
        $xml->endElement();

        $xml->startElement('ds:Exponent');
        $xml->text('AQAB');
        $xml->endElement();

        $xml->endElement(); //detalles1

        //$xml->endElement();//1


        $xml->endElement();

        $xml->startElement('ds:Object');
        $xml->writeAttribute("Id", "Signature620397-Object231987");

        $xml->startElement('etsi:QualifyingProperties');
        $xml->writeAttribute("Target", "#Signature620397");

        $xml->endElement(); //des2

        $xml->endElement(); //des1
        //infoAdicional
        $xml->startElement("infoAdicional");

        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "DATO PRUEBA");
        $xml->endElement();

        $xml->endElement();
        $xml->endElement(); //fin
        $xml->endDocument();

        $xml->startElement('etsi:DigestMethod');

        $xml->endElement();


        $xml->startElement('etsi:DigestValue');

        $xml->endElement();
    }
    public function r_facturas()
    {
        $xml = new XMLWriter();
        $xml->openUri("../base de datos/factura/xmlsokai/r_factura.xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("autorizacion");
        $xml->writeAttribute("version", "1.0.0");

        $xml->startElement('etsi:X509IssuerName');
        $xml->endElement();
        $xml->startElement('etsi:X509SerialNumber');
        $xml->endElement();

        $xml->endElement();


        $xml->endElement();

        $xml->endElement();

        $xml->endElement();

        $xml->startElement('etsi:SignedDataObjectProperties');

        $xml->startElement('etsi:DataObjectFormat');
        $xml->writeAttribute("ObjectReference", "#Reference-ID-363558");

        $xml->startElement("mensaje");

        $xml->startElement("mensaje");

        $xml->startElement("identificador");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("mensaje");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("tipo");
        $xml->text("1");
        $xml->endElement();

        $xml->startElement("InformacionAdicional");
        $xml->text("1");
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();


        $xml->endElement();
        $xml->endElement(); //fin
        $xml->endDocument();
    }
}
