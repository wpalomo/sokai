<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lineaproducto extends Model
{
    //
    protected $table="linea_producto";
    protected $primaryKey = "id_linea_producto";
    protected $fillable=['codigo', 'nombre', 'cta_compras_iva', 'cta_compras_iva_0', 'cta_ventas_iva', 'cta_ventas_iva_0', 'cuenta_costo', 'id_empresa'];
   
   
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
} 
