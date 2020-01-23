<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaEgreso extends Model
{
    protected $table = "bodega_egreso";
    protected $primaryKey = 'id_bodega_egreso';
    protected $fillable = ['num_egreso', 'fecha_egreso', 'tipo_egreso', 'observ_egreso', 'precio', 'cantidad', 'costo', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_producto_bodega'];

}
