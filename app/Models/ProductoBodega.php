<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoBodega extends Model
{
    protected $table = "producto_bodega";
    protected $primaryKey = 'id_producto_bodega';
    protected $fillable = ['cantidad', 'id_producto', 'id_bodega'];
    public $timestamps = false;
}
