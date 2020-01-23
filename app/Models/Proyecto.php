<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    //
    protected $table="proyecto";
    protected $primaryKey ="id_proyecto";
    protected $fillable =['codigo', 'descripcion', 'ubicacion', 'id_empresa' ];
    
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
