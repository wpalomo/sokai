<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoIngrediente extends Model
{
    protected $table = "proceso_ingrediente";
    protected $primaryKey = 'id_proceso_ingrediente';
    protected $fillable = ['id_producto', 'id_proceso_produccion', 'cantidad'];
    public $timestamps = false;
}
