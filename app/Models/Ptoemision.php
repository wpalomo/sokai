<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ptoemision extends Model
{
    //
    protected $table="punto_emision";
    protected $primaryKey = "id_punto_emision";
    protected $fillable=['nombre', 'codigo', 'secuencial_factura', 'secuencial_nota_credito', 'secuencial_nota_debito', 'secuencial_guia_remision', 'secuencial_retencion', 'activo', 'fcrea', 'fmodifica', 'umodifica', 'ucrea', 'id_empresa', 'id_establecimiento'];
}
