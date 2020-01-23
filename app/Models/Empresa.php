<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $table ="empresa"; 
    protected $primaryKey = "id_empresa";
    protected $fillable =['codigo_empresa', 'periodo_empresa', 'nombre_empresa','razon_social', 'ruc_empresa', 'direccion_empresa', 'telefono', 'email_empresa', 'password', 'servidor_correo', 'puerto_correo', 'seguridad_correo', 'tipo_identidicacion_empresa', 'ruc_contador', 'nombre_contador', 'identificacion_repr', 'nomb_representante', 'clave_duracion', 'periodo_inicio', 'periodo_fin', 'mascara_empresa', 'recalculo', 'balance', 'noresolucion', 'nocontribuyente', 'codigo_entidad', 'contribuyente', 'tipo_ctas', 'logo', 'firma', 'pass_firma', 'ruta', 'fcierre', 'fresolucion', 'id_moneda', 'id_establecimiento', 'id_provincia', 'id_ciudad', 'cta_resultado', 'cta_ingreso', 'cta_costo', 'cta_activo', 'cta_pasivo', 'cta_patrimonio', 'cta_gasto', 'cta_orden'];
}
