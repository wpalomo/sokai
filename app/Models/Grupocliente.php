<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupocliente extends Model
{
    //
    protected $table="grupo_cliente";
    protected $primaryKey ="id_grupo_cliente";
    protected $fillable =['codigo', 'nombre_grupo', 'cuenta', 'cuenta_descuento', 'cuenta_anticipo', 'cuenta_servicio','fcrea','fmodifica','ucrea','umodifica'];
    public $timestamps = false;

}
