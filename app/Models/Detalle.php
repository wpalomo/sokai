<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detalle extends Model
{
    protected $table="detalle";
    protected $primaryKey = "id_detalle";
    protected $fillable =['nombre', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'p_descuento', 'iva', 'ice', 'irbpnr', 'fcrea', 'fmodifica', 'id_producto', 'id_nota_credito','id_factura'];
}
