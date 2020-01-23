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
    public function comprobanteRetencionPDF($document, $claveAcceso,$id_empresa,$imagen) {
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
