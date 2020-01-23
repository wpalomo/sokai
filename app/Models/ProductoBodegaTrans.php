<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoBodegaTrans extends Model
{
    protected $table = "producto_bodega_trans";
    protected $primaryKey = 'id_producto_bodega_trans';
    protected $fillable = ['id_producto', 'cant_env', 'cant_recib', 'id_bodega_transferencia'];
    public $timestamps = false;

}
