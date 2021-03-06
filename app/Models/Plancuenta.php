<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plancuenta extends Model
{
    //
    protected $table="plan_cuentas";
    protected $primaryKey = "id_ctas";
    protected $fillable=[ 'id_empresa', 'codcta', 'nomcta', 'id_moneda', 'refcon', 'bansel', 'id_grupo'];
   
   // public $timestamps = false;

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
