<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoProducto extends Model
{
    protected $table = "proceso_producto";
    protected $primaryKey = 'id_proceso_producto';
    protected $fillable = ['id_producto', 'id_proceso_produccion', 'cantidad'];
    public $timestamps = false;
}
