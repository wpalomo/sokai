<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    //
    protected $table="retencion";
    protected $primaryKey = 'id_retencion';
    protected $fillable=['cod_retencion', 'descrip_retencion', 'porcen_retencion', 'tipo_retencion', 'tipoiva_retencion', 'cta_contable', 'id_moneda', 'id_impuesto', 'id_empresa',];
}
