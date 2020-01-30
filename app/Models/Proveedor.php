<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    protected $table="proveedor";
    protected $primaryKey = "id_proveedor";
    protected $fillable=[ 'cod_proveedor', 'grupo', 'nombre_proveedor', 'tipo_identificacion', 'identif_proveedor', 'contribuyente', 'beneficiario', 'contacto', 'direccion_prov', 'nrcasa', 'telefono_prov', 'estado_prov', 'tipo_cuenta', 'cta_banco', 'id', 'pagos', 'plazo', 'dias_pago', 'tip_comprob', 'serie', 'fvalidez', 'comentario', 'rangomax', 'rangomin', 'nrautorizacion', 'contribuye_sri', 'tip_electronico', 'imp_retencion', 'codsri_imp', 'retencion_iva', 'codsri_iva', 'cta_contable', 'fcrea', 'fmodifica', 'id_provincia', 'id_ciudad', 'id_banco', 'id_empresa' ];
    public $timestamps = false;

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
