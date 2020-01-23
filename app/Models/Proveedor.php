<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    protected $table="proveedor";
    protected $primaryKey = 'id_proveedor';
    protected $fillable =['cod_proveedor', 'nombre_proveedor', 'identif_proveedor', 'direccion_prov', 'telefono_prov', 'id_ciudad'];
}
