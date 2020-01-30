<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoProveedor extends Model
{
    //
    protected $table="grupo_proveedor";
    protected $primaryKey = 'id_grupoprov';
    protected $fillable =['nombre_grupoprov','importador','cta_contable','cta_descuento','cta_anticipo'];
    //public $timestamps = false;
}