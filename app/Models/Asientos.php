<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asientos extends Model
{
    //
    protected $table="asientos";
    protected $primaryKey="id_asientos";
    protected $fillable = ['comprobante', 'numero', 'fecha', 'razon_social', 'ruc_ci', 'concepto'];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
    
}
