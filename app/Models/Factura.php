<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = "factura";
    protected $primaryKey = 'id_factura';
    protected $fillable = ['ambiente', 'tipo_emision', 'fecha_emision', 'forma_pago', 'clave_acceso', 'observacion', 'subtotal_sin_impuesto', 'subtotal_12%', 'subtotal_0%', 'subtotal_no_obj_iva', 'descuento', 'valor_ice', 'valor_irbpnr', 'iva_12%', 'propina', 'valor_total', 'id_cliente', 'id_empresa'];
}
