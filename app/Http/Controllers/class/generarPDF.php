<?php
include 'lib/fpdf/fpdf.php';
include 'lib/barcode-php1/class/BCGcode128.barcode.php';
//include 'lib/barcode-php1/class/BCGColor.php';
include 'lib/barcode-php1/class/BCGDrawing.php';
//include 'lib/barcode-php1/class/BCGFontFile.php';
include 'sendEmail.php';

class generarPDF {
    public function facturaPDF1($document, $claveAcceso,$id_empresa,$imagen, $empresas) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Cell(40, 10, 'Hello World!');
        if ($document->infoFactura->obligadoContabilidad == 'SI') {

            $contabilidad = "Obligado a llevar contabilidad : SI";
        } else {
            $contabilidad = "Obligado a llevar contabilidad : NO";
        }
        $pdf->SetXY(10, 0);
        $pdf->image('../server/'.$id_empresa.'/imagen/'.$imagen, null, null, 80, 30);

        $pdf->SetXY(110, 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(100, 10, "RUC: " . $document->infoTributaria->ruc, 0, 'J', true);
        $pdf->SetXY(110, 15);
        $pdf->MultiCell(100, 10, "Factura Nro: " . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(110, 20);
        $pdf->MultiCell(100, 10, 'Nro Autorizacion: ', 0);
        $pdf->SetXY(110, 25);
        $pdf->MultiCell(100, 10, $claveAcceso, 0);
        $pdf->SetXY(110, 30);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->MultiCell(100, 10, 'Ambiente: ' . $ambiente, 0);
        $pdf->SetXY(110, 35);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->MultiCell(100, 10, 'Emision: ' . $emision, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 20);
        $pdf->MultiCell(100, 10, $document->infoTributaria->razonSocial, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 25);
        $pdf->MultiCell(100, 10, $document->infoTributaria->dirMatriz, 0);
        $pdf->SetXY(10, 30);
        $pdf->MultiCell(100, 10, $contabilidad, 0);
        //Codigo de barras
        $pdf->SetXY(110, 60);
        $this->generarCodigoBarras($claveAcceso,$id_empresa);
        $pdf->image('../server/'.$id_empresa.'/comprobantes/factura/codigosbarras/codigo_'.$claveAcceso.'.png', null, null, 100, 20);
        $pdf->SetXY(110, 78);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(100, 10, $claveAcceso, 0, 0, "C", true);

        //informacion del cliente
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->SetXY(10, 35);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 10, "INFORMACION DEL CLIENTE", 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 40);
        $pdf->MultiCell(100, 10, "RUC/CI: " . $document->infoFactura->identificacionComprador, 0);
        $pdf->SetXY(10, 45);
        $pdf->MultiCell(100, 10, "Razon Social/Nombre: " . $document->infoFactura->razonSocialComprador, 0);
        $pdf->SetXY(10, 50);
        $pdf->MultiCell(100, 10, "Direccion: " . $document->infoFactura->direccionComprador, 0);
        $pdf->SetXY(10, 55);
        $pdf->MultiCell(100, 10, "Fecha Emision: " . $document->infoFactura->fechaEmision, 0);

        $ejeX = 65;

        $pdf->SetXY(10, $ejeX);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 10, "FORMAS DE PAGO", 0);
        $pdf->SetFont('Arial', '', 8);
        $ejeX = $ejeX + 10;
        $pdf->SetXY(10, $ejeX);
        foreach ($document->infoFactura->pagos->pago as $e => $f) {
            if ($f->formaPago == '01') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Sin utilizacion del sistema financiero', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '15') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Compensacion de deudas', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '16') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Tarjeta debito', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '17') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Dinero Electronico', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '18') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Tarjeta Prepago', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '19') {
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(20, 10, 'Tarjeta de credito', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(1, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '20') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Otros con utilizacion del sistema financiero', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }
            if ($f->formaPago == '21') {
                $pdf->SetXY(22, $ejeX);
                $pdf->Cell(30, 10, 'Endoso de titulos', 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(4, $ejeX);
                $pdf->Cell(30, 10, 'Total: ' . $f->total, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(0, $ejeX);
                $pdf->Cell(30, 10, 'Plazo: ' . $f->plazo, 0, 0, "C", true);
                $ejeX = $ejeX + 6;
                $pdf->SetXY(10, $ejeX);
                $pdf->Cell(30, 10, 'Unidad de tiempo: ' . $f->unidadTiempo, 0, 0, "C", true);
            }

            $ejeX = $ejeX + 10;
            $pdf->SetX($ejeX);
        }
        //detalle de la factura
        $pdf->SetXY(10, $ejeX + 10);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);
        $pdf->Cell(30, 10, "Codigo", 1, 0, "C", true);
        $pdf->Cell(25, 10, "Cod. Aux", 1, 0, "C", true);
        $pdf->Cell(45, 10, "Descripcion", 1, 0, "C", true);
        $pdf->Cell(25, 10, "Cantidad", 1, 0, "C", true);
        $pdf->Cell(25, 10, "Precio", 1, 0, "C", true);
        $pdf->Cell(25, 10, "% Desc", 1, 0, "C", true);
        $pdf->Cell(25, 10, "Total", 1, 0, "C", true);

        $ejeX = $ejeX + 20;
        $pdf->SetXY(10, $ejeX);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        foreach ($document->detalles->detalle as $a => $b) {
            $pdf->Cell(30, 10, $b->codigoPrincipal, 1, 0, "C", true);
            $pdf->Cell(25, 10, $b->codigoAuxiliar, 1, 0, "C", true);
            $pdf->Cell(45, 10, $b->descripcion, 1, 0, "C", true);
            $pdf->Cell(25, 10, $b->cantidad, 1, 0, "C", true);
            $pdf->Cell(25, 10, number_format(floatval($b->precioUnitario), 2), 1, 0, "C", true);
            $pdf->Cell(25, 10, $b->descuento, 1, 0, "C", true);
            $pdf->Cell(25, 10, $b->precioTotalSinImpuesto, 1, 0, "C", true);
            $ejeX = $ejeX + 10;
            $pdf->SetXY(10, $ejeX);
        }
        //Total de la factura
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    //    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR = $b->valor;
            }
        }
        $pdf->SetXY(130, $ejeX + 10);
        $pdf->Cell(25, 10, "Subtotal 12%: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 10);
        $pdf->Cell(25, 10, " $subtotal12 ", 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 16);
        $pdf->Cell(25, 10, "SubTotal 0%: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 16);
        $pdf->Cell(25, 10, $subtotal0, 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 22);
        $pdf->Cell(25, 10, "SubTotal no sujeto de IVA: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 22);
        $pdf->Cell(25, 10, $subtotal_no_impuesto, 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 28);
        $pdf->Cell(25, 10, "SubTotal exento de IVA: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 28);
        $pdf->Cell(25, 10, $subtotal_no_iva, 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 34);
        $pdf->Cell(25, 10, "SubTotal sin Impuestos: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 34);
        $pdf->Cell(25, 10, $document->infoFactura->totalDescuento, 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 40);
        $pdf->Cell(25, 10, "Descuento: ", 0, 0, "L", true);
        $pdf->SetXY(180, $ejeX + 40);
        $pdf->Cell(25, 10, $document->infoFactura->totalDescuento, 0, 0, "R", true);
        $pdf->SetXY(130, $ejeX + 46);
        $pdf->Cell(25, 10, "IVA 12%: ", 0, 0, "L");
        $pdf->SetXY(180, $ejeX + 46);
        $pdf->Cell(25, 10, $iva, 0, 0, "R");
        $pdf->SetXY(130, $ejeX + 52);
        $pdf->Cell(25, 10, "ICE: ", 0, 0, "L");
        $pdf->SetXY(180, $ejeX + 52);
        $pdf->Cell(25, 10, $ice, 0, 0, "R");
        $pdf->SetXY(130, $ejeX + 58);
        $pdf->Cell(25, 10, "IRBPNR: ", 0, 0, "L");
        $pdf->SetXY(180, $ejeX + 58);
        $pdf->Cell(25, 10, $IRBPNR, 0, 0, "R");
        $pdf->SetXY(130, $ejeX + 64);
        $pdf->Cell(25, 10, "Valor Total: ", 0, 0, "L");
        $pdf->SetXY(180, $ejeX + 64);
        $pdf->Cell(25, 10, $document->infoFactura->importeTotal, 0, 0, "R");
        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        $pdf->SetXY(10, $ejeX + 10);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(100, 10, "Informacion Adicional", 0);
        $pdf->SetXY(10, $ejeX + 30);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(100, 5, "" . $infoAdicional . "", 0);
        $pdf->Output('../server/'.$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Factura', $document->infoFactura->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas);
        return "bien";
    }

    public function facturaPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);

        $pdf->Image('../server/' . $id_empresa . '/imagen/' . $imagen, null, null, 80, 30);

        //variables de empresa que emite
        $emp_email = '';
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(11, 41);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode('Dirección Matriz: ' . $document->infoTributaria->dirMatriz), 0, 2, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Email: ' . $emp_email), 0, 2, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Contribuyente Especial.'), 0, 2, 'L', 0);
        if ($document->infoFactura->obligadoContabilidad == 'SI') {

            $contabilidad = "SI";
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD: ' . $contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(88, 5, utf8_decode('FACTURA '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: ') . $fecha_actual, 0, 2, 'L', 0);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(88, 5, utf8_decode('AMBIENTE: ' . $ambiente), 0, 2, 'L', 0);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(88, 5, utf8_decode('EMISIÓN: ' . $emision), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 61);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image('../server/' . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 88, 7);

        
        $cli_telefono = '';
        $cli_guias = '';
        $emp_vendedor = '';
        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 27, 2, '1234', 'D');
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoFactura->razonSocialComprador), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('RUC / CI.: ' . $document->infoFactura->identificacionComprador), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Correo: ' . $correo), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $document->infoFactura->fechaEmision), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Usuario: ' . $emp_vendedor), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Dirección: ' . $document->infoFactura->direccionComprador), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Teléfonos: ' . $cli_telefono), 0, 0, 'L', 0); //ojo
        $pdf->Cell(29, 5, utf8_decode('Guías de Remisión: ' . $cli_guias), 0, 0, 'L', 0); //ojo

        //tabla de productos
        $pdf->SetXY(10, 101);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->Cell(25, 8, utf8_decode('Código Principal'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Cantidad'), 1, 0, 'C', 0);
        $pdf->Cell(75, 8, utf8_decode('Descripción'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Precio Unitario'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Descuento'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Precio Total'), 1, 0, 'C', 0);
        $pdf->Ln();

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        foreach ($document->detalles->detalle as $a => $b) {
            $pdf->Cell(25, 6, $b->codigoPrincipal, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'R', 0);
            $pdf->Cell(75, 6, $b->descripcion, 1, 0, 'L', 0);
            $pdf->Cell(25, 6, number_format(floatval($b->precioUnitario), 2), 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->descuento, 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->precioTotalSinImpuesto, 1, 0, 'R', 0);
            $pdf->Ln();
        }
        // $ejeX = 65;
        // $ejeX = $ejeX + 20;
        // $pdf->SetXY(10, $ejeX);
        foreach ($document->infoFactura->pagos->pago as $e => $f) {
            if ($f->formaPago == '01') {
                $formaPago = 'Sin utilizacion del sistema financiero';
            }
            if ($f->formaPago == '15') {
                $formaPago = 'Compensacion de deudas';
            }
            if ($f->formaPago == '16') {
                $formaPago = 'Tarjeta debito';
            }
            if ($f->formaPago == '17') {
                $formaPago = 'Dinero Electronico';
            }
            if ($f->formaPago == '18') {
                $formaPago = 'Tarjeta Prepago';
            }
            if ($f->formaPago == '19') {
                $formaPago = 'Tarjeta de credito';
            }
            if ($f->formaPago == '20') {
                $formaPago = 'Otros con utilizacion del sistema financiero';
            }
            if ($f->formaPago == '21') {
                $formaPago = 'Endoso de titulos';
            }
        }

        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    //    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR = $b->valor;
            }
        }
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal12, 1, 1, 'R', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, 'PAGOS:', 'LTR', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(50, 6, 'SUBTOTAL 0%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO SRI: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($formaPago), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal_no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('TOTAL: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->total), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal_no_iva, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('PLAZO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->plazo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $document->infoFactura->totalDescuento, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('UNID. DE TIEMPO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->unidadTiempo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'DESCUENTO', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $document->infoFactura->totalDescuento, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode(''), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $ice, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(50, 6, '12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $iva, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $IRBPNR, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'PROPINA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $propina, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $document->infoFactura->importeTotal, 1, 1, 'R', 0);
        
        // $pdf->SetXY(10, $ejeX + 10);
        // $pdf->SetFont('Arial', 'B', 7);
        // $pdf->MultiCell(100, 10, "Informacion Adicional", 0);
        // $pdf->SetXY(10, $ejeX + 30);
        // $pdf->SetFont('Arial', '', 7);
        // $pdf->MultiCell(100, 5, "" . $infoAdicional . "", 0);
        $pdf->Output('../server/' . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $valor = $email->enviarCorreo('Factura', $document->infoFactura->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas);
        return var_dump($valor);



        //$pdf->Output("ejemplo.pdf", "I");
    }


    public function generarCodigoBarras($claveAcceso, $id_empresa) {
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);
        $code = new BCGcode128();
        $code->setScale(4);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);
        $code->parse($claveAcceso);
        $drawing = new BCGDrawing('../server/'.$id_empresa.'/comprobantes/factura/codigosbarras/codigo'.$claveAcceso.'.png', $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
        $this->redim('../server/'.$id_empresa.'/comprobantes/factura/codigosbarras/codigo'.$claveAcceso.'.png', '../server/'.$id_empresa.'/comprobantes/factura/codigosbarras/codigo_'.$claveAcceso.'.png', 1000, 200);
    }
    public function redim($ruta1, $ruta2, $ancho, $alto) {
        # se obtene la dimension y tipo de imagen 
        $datos = getimagesize($ruta1);

        $ancho_orig = $datos[0]; # Anchura de la imagen original 
        $alto_orig = $datos[1];    # Altura de la imagen original 
        $tipo = $datos[2];

        if ($tipo == 1) { # GIF 
            if (function_exists("imagecreatefromgif"))
                $img = imagecreatefromgif($ruta1);
            else
                return false;
        }
        else if ($tipo == 2) { # JPG 
            if (function_exists("imagecreatefromjpeg"))
                $img = imagecreatefromjpeg($ruta1);
            else
                return false;
        }
        else if ($tipo == 3) { # PNG 
            if (function_exists("imagecreatefrompng"))
                $img = imagecreatefrompng($ruta1);
            else
                return false;
        }

        # Se calculan las nuevas dimensiones de la imagen 
        if ($ancho_orig > $alto_orig) {
            $ancho_dest = $ancho;
            $alto_dest = ($ancho_dest / $ancho_orig) * $alto_orig;
        } else {
            $alto_dest = $alto;
            $ancho_dest = ($alto_dest / $alto_orig) * $ancho_orig;
        }

        // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        $img2 = @imagecreatetruecolor($ancho_dest, $alto_dest) or $img2 = imagecreate($ancho_dest, $alto_dest);

        // Redimensionar 
        // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        @imagecopyresampled($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig) or imagecopyresized($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig);

        // Crear fichero nuevo, según extensión. 
        if ($tipo == 1) // GIF 
            if (function_exists("imagegif"))
                imagegif($img2, $ruta2);
            else
                return false;

        if ($tipo == 2) // JPG 
            if (function_exists("imagejpeg"))
                imagejpeg($img2, $ruta2);
            else
                return false;

        if ($tipo == 3)  // PNG 
            if (function_exists("imagepng"))
                imagepng($img2, $ruta2);
            else
                return false;

        return true;
    }
}
