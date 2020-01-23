<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notacredito extends Model
{
    //
    protected $table="notacredito";
    protected $primaryKey = "id";
    protected $fillable=['id', 'cliente_id', 'emisor_id', 'establecimiento_id', 'claveAcceso', 'numeroAutorizacion', 'fechaAutorizacion', 'estado', 'ambiente', 'tipoEmision', 'secuencial', 'fechaEmision', 'tipoDocMod', 'fechaEmisionDocMod', 'nroDocMod', 'motivo', 'nombreArchivo', 'totalSinImpuestos', 'subtotal12', 'subtotal0', 'subtotalNoIVA', 'subtotalExentoIVA', 'valorICE', 'valorIRBPNR', 'iva12', 'totalDescuento', 'valorTotal', 'firmado', 'enviarSiAutorizado', 'createdAt', 'updatedAt', 'ptoEmision_id', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
