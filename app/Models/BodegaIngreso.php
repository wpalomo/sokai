<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaIngreso extends Model
{
    protected $table = "bodega_ingreso";
    protected $primaryKey = 'id_bodega_ingreso';
    protected $fillable = ['num_ingreso', 'fecha_ingreso', 'tipo_ingreso', 'observ_ingreso', 'costo_unitario', 'costo_total', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_producto_bodega'];

}
