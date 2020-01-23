<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $table="cliente";
    protected $primaryKey ="id_cliente";
    protected $fillable =['codigo', 'nombre', 'identificacion', 'direccion', 'email', 'telefono', 'contacto', 'estado', 'cuenta_contable', 'comentario', 'descuento', 'num_pago', 'tipo_identificacion', 'codigo_pais', 'grupo_tributario', 'canton', 'parroquia', 'provincia', 'parte_relacionada', 'lista_precios', 'limite_credito', 'formas_pago','id_grupo_cliente','id_tipo_cliente','id_vendedor','id_empresa'];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}

