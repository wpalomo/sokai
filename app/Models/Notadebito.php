<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notadebito extends Model
{
    //
    protected $table="notadebito";
    protected $primaryKey = "id";
    protected $fillable=['id', 'cliente_id', 'emisor_id', 'establecimiento_id', 'claveAcceso', 'numeroAutorizacion', 'fechaAutorizacion', 'estado', 'ambiente', 'tipoEmision', 'secuencial', 'fechaEmision', 'tipoDocMod', 'fechaEmisionDocMod', 'nroDocMod', 'nombreArchivo', 'totalSinImpuestos', 'subtotal12', 'subtotal0', 'subtotalNoIVA', 'subtotalExentoIVA', 'valorICE', 'iva12', 'valorTotal', 'firmado', 'enviarSiAutorizado', 'createdAt', 'updatedAt', 'ptoEmision_id', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
