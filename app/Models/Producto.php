<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $primaryKey = 'id_producto';
    protected $fillable = ['cod_principal', 'cod_alterno', 'imagen', 'nombre', 'codigo_barras', 'cuenta_contable', 'form_prod', 'descripcion', 'nombrec', 'sector', 'ubicacion_fisica', 'unidad_entrada', 'unidad_salida', 'vencimiento', 'existencia_maxima', 'existencia_minima', 'numero_unidad', 'estado', 'vehiculo', 'placa', 'pais_origen', 'ano_fabricacionv', 'color', 'carroceria', 'combustible', 'motor', 'cilindraje', 'chasis', 'clase', 'subclase', 'numero_pasajeros', 'iva', 'ice', 'arancel_advalorem', 'arancel_especifico', 'arancel_fodinfa', 'comision', 'salvaguardia', 'pvp_precio1', 'precio2', 'precio3', 'precio4', 'precio5', 'descuento', 'utilidad', 'fecha_fabricacion', 'ultimo_costo', 'costo_promedio', 'costo_total', 'existencia_total', 'caracteristicas', 'normativa', 'uso', 'id_linea_producto', 'id_tipo_producto', 'id_marca', 'id_modelo', 'id_presentacion', 'id_tipo_medida', 'id_unidad_medida', 'id_empresa'];


    
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
