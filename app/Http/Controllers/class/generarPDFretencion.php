<?php

include 'lib/fpdf/fpdf.php';
include 'lib/barcode-php1/class/BCGcode128.barcode.php';
//include 'lib/barcode-php1/class/BCGColor.php';
include 'lib/barcode-php1/class/BCGDrawing.php';
//include 'lib/barcode-php1/class/BCGFontFile.php';
include 'sendEmailretencion.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of generarPDF
 * 
 * @author UESR
 */
class generarPDF {
    public function comprobanteRetencionPDF1($document, $claveAcceso,$id_empresa,$imagen) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Cell(40, 10, 'Hello World!');
        if ($document->infoCompRetencion->obligadoContabilidad == 'SI') {

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
        $pdf->MultiCell(100, 10, "Comprobante Retencion Nro: " . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0);
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
        $pdf->SetXY(110, 45);
        $this->generarCodigoBarras($claveAcceso,$id_empresa);
        $pdf->image('../server/'.$id_empresa.'/comprobantes/retencion/codigosbarras/codigo_'.$claveAcceso.'.png', null, null, 100, 20);
        $pdf->SetXY(110, 63);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(100, 10, $claveAcceso, 0, 0, "C", true);

        //informacion del cliente
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->SetXY(10, 35);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 10, "INFORMACION DEL SUJETO RETENIDO", 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 40);
        $pdf->MultiCell(100, 10, "RUC/CI: " . $document->infoCompRetencion->identificacionSujetoRetenido, 0);
        $pdf->SetXY(10, 45);
        $pdf->MultiCell(100, 10, "Razon Social/Nombre: " . $document->infoCompRetencion->razonSocialSujetoRetenido, 0);
        $pdf->SetXY(10, 50);
        $pdf->MultiCell(100, 10, "Direccion: " . $document->infoCompRetencion->dirEstablecimiento, 0);
        $pdf->SetXY(10, 55);
        $pdf->MultiCell(100, 10, "Fecha Emision: " . $document->infoCompRetencion->fechaEmision, 0);

        //detalle de la factura
        $pdf->SetXY(10, 70);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);
        $pdf->Cell(20, 10, "Impuesto", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Cod. Retenci", 1, 0, "C", true);
        $pdf->Cell(21, 10, "Base Imponible", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Porcentaje", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Sustento", 1, 0, "C", true);
        $pdf->Cell(30, 10, "Documento", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Ejercicio", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Fecha", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Valor Retenido", 1, 0, "C", true);

        $ejeX = 80;
        $pdf->SetXY(10, $ejeX);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $total = 0;
        foreach ($document->impuestos->impuesto as $a => $b) {
            if ($b->codigo == 1) {
                $pdf->Cell(20, 10, "IVA", 1, 0, "C", true);
            } else if ($b->codigo == 2) {
                $pdf->Cell(20, 10, "Renta", 1, 0, "C", true);
            } else {
                $pdf->Cell(20, 10, $b->codigo, 1, 0, "C", true);
            }

            $pdf->Cell(20, 10, $b->codigoRetencion, 1, 0, "C", true);
            $pdf->Cell(21, 10, $b->baseImponible, 1, 0, "C", true);
            $pdf->Cell(20, 10, $b->porcentajeRetener . "%", 1, 0, "C", true);
            if ($b->codDocSustento = '01') {
                $pdf->Cell(20, 10, 'Factura', 1, 0, "C", true);
            } else {
                $pdf->Cell(20, 10, $b->codDocSustento, 1, 0, "C", true);
            }
            $pdf->Cell(30, 10, $b->numDocSustento, 1, 0, "C", true);
            $pdf->Cell(20, 10, date("Y"), 1, 0, "C", true);
            $pdf->Cell(20, 10, $b->fechaEmisionDocSustento, 1, 0, "C", true);
            $pdf->Cell(20, 10, $b->valorRetenido, 1, 0, "C", true);
            $ejeX = $ejeX + 10;
            $pdf->SetXY(10, $ejeX);
            $total += floatval($b->valorRetenido);
        }

        //Total de la factura
        $ejeX = $ejeX + 5;
        $pdf->SetXY(155, $ejeX);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);

        $pdf->Cell(25, 10, "Total", 1, 0, "C", true);

        $pdf->SetXY(180, $ejeX);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(20, 10, number_format(floatval($total),2), 1, 0, "C");
        // Pie de pagina
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
        $pdf->SetXY(10, $ejeX + 30);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(100, 10, "Informacion Adicional", 0);
        $pdf->SetXY(10, $ejeX + 50);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(100, 5, "" . $infoAdicional . "", 0);

        $pdf->Output('../server/'.$id_empresa.'/comprobantes/retencion/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Comprobante de Retencion', $document->infoCompRetencion->razonSocialSujetoRetenido, $claveAcceso, $correo, $id_empresa);
    }

    public function comprobanteRetencionPDF($document, $claveAcceso,$id_empresa,$imagen)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);
        $pdf->SetXY(20, 15);
        $pdf->Image('../server/'.$id_empresa.'/imagen/'.$imagen, null, null, 80, 30);

        //variables de empresa que emite
        $emp_dir_sucursal = '';
        $emp_contri_especial = '';

        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($emp_dir_sucursal), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(36, 5, utf8_decode('Contribuyente Especial Nro.: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($emp_contri_especial), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoCompRetencion->obligadoContabilidad == 'SI') {

            $contabilidad = "Obligado a llevar contabilidad : SI";
        } else {
            $contabilidad = "Obligado a llevar contabilidad : NO";
        }
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $this->generarCodigoBarras($claveAcceso,$id_empresa);
        $pdf->image('../server/'.$id_empresa.'/comprobantes/retencion/codigosbarras/codigo_'.$claveAcceso.'.png', null, null, 100, 20);
        $pdf->SetXY(111, 65);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'C', 0);
        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('COMPROBANTE DE RETENCIÓN'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);


        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 12, 2, '1234', 'D');
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoCompRetencion->razonSocialSujetoRetenido), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Identificación: ' . $document->infoCompRetencion->identificacionSujetoRetenido), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $document->infoCompRetencion->fechaEmision), 0, 0, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 86);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(25, 8, utf8_decode('Comprobante'), 1, 'C', 1);
        $pdf->SetXY(35, 86);
        $pdf->MultiCell(30, 8, utf8_decode('Número'), 1, 'C', 1);
        $pdf->SetXY(65, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Fecha Emisión'), 1, 'C', 1);
        $pdf->SetXY(90, 86);
        $pdf->MultiCell(20, 4, utf8_decode('Ejercicio Fiscal'), 1, 'C', 1);
        $pdf->SetXY(110, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Base Imponible'), 1, 'C', 1);
        $pdf->SetXY(135, 86);
        $pdf->MultiCell(20, 8, utf8_decode('Impuesto'), 1, 'C', 1);
        $pdf->SetXY(155, 86);
        $pdf->MultiCell(20, 4, utf8_decode('Porcentaje de Retención'), 1, 'C', 1);
        $pdf->SetXY(175, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Valor Retenido'), 1, 'C', 1);
        //$pdf->Ln();

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $total = 0;
        foreach ($document->impuestos->impuesto as $a => $b) {
            if ($b->codDocSustento = '01') {
                $pdf->Cell(20, 6, 'FACTURA', 1, 0, "L", 0);
            } else {
                $pdf->Cell(20, 6, $b->codDocSustento, 1, 0, "L", 0);
            }
            $pdf->Cell(30, 6, $b->codDocSustento, 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->fechaEmisionDocSustento, 1, 0, 'C', 0);
            $pdf->Cell(20, 6, date("Y"), 1, 0, 'C', 0);
            $pdf->Cell(25, 6, $b->baseImponible, 1, 0, 'R', 0);
            if ($b->codigo == 1) {
                $pdf->Cell(20, 6, 'IVA', 1, 0, 'L', 0);
            } else if ($b->codigo == 2) {
                $pdf->Cell(20, 6, 'RENTA', 1, 0, 'L', 0);
            } else {
                $pdf->Cell(20, 6, $b->codigo, 1, 0, "L", 0);
            }
            $pdf->Cell(20, 6, $b->porcentajeRetener . "%", 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->valorRetenido, 1, 0, 'R', 0);
            $pdf->Ln();
            $total += floatval($b->valorRetenido);
        }
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
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(125, 6, utf8_decode('Información Adicional'), 1, 0, 'L', 0);
        $pdf->Cell(40, 6, 'Total', 'LTB', 0, 'R', 0);
        $pdf->Cell(25, 6, number_format(floatval($total),2), 'TBR', 1, 'R', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(125, 6, utf8_decode('Correo: ' . $correo), 'LR', 2, 'L', 0);
        $pdf->Cell(125, 6, utf8_decode('Observaciones: ' . $infoAdicional), 'LR', 2, 'L', 0);
        $pdf->Cell(125, 6, utf8_decode('Retencion aplicada a la factura No.: ' . $b->codDocSustento), 'LBR', 2, 'L', 0);

        $pdf->Output('../server/'.$id_empresa.'/comprobantes/retencion/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Comprobante de Retencion', $document->infoCompRetencion->razonSocialSujetoRetenido, $claveAcceso, $correo, $id_empresa);
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
        $drawing = new BCGDrawing('../server/'.$id_empresa.'/comprobantes/retencion/codigosbarras/codigo'.$claveAcceso.'.png', $colorBack);
        $drawing->setBarcode($code);

        $drawing->draw();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
        $this->redim('../server/'.$id_empresa.'/comprobantes/retencion/codigosbarras/codigo'.$claveAcceso.'.png', '../server/'.$id_empresa.'/comprobantes/retencion/codigosbarras/codigo_'.$claveAcceso.'.png', 1000, 200);
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
