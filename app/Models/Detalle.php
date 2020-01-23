<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detalle extends Model
{
    protected $table="detalle";
    protected $primaryKey = "id_detalle";
    protected $fillable =['descripcion', 'cantidad', 'precio', 'descuento', 'total', 'ice', 'irbpnr', 'id_factura', 'id_producto'];
}
