<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedorcliente extends Model
{
    //
    protected $table="vendedor";
    protected $primaryKey ="id_vendedor";
    protected $fillable =['codigo_vendedor', 'nombre_vendedor', 'email_vendedor', 'id_empresa'];
    
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
