<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table="bodega";
    protected $primaryKey = "id_bodega";
    protected $fillable =['codigo', 'nombre', 'serie_factura', 'ubicacion', 'direccion', 'telefono', 'fcrea', 'fmodifica', 'ucrea', 'umodifica'];
}
