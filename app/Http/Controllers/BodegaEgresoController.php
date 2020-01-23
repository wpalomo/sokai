<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Models\BodegaEgreso;
use App\Models\ProductoBodega;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class BodegaEgresoController extends Controller
{
    

    public function store(Request $request)
    {
        for($d = 0; $d < count($request->contenidostock); $d++){
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = ". $request->contenidostock[$d]["id"] ." AND `id_bodega` =". $request->id_bodega);
            if(count($sel)==1){
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad - $request->contenidostock[$d]["cant_egreso"];
                $prb->save();
   
                $egreso = new BodegaEgreso();
                $egreso->num_egreso = $request->num_egreso;
                $egreso->fecha_egreso = $request->fecha_egreso;
                $egreso->tipo_egreso = $request->tipo_egreso;
                $egreso->observ_egreso= $request->observ_egreso;
                $egreso->precio= $request->contenidostock[$d]["precio_egreso"];
                $egreso->cantidad = $request->contenidostock[$d]["cant_egreso"];
                $egreso->costo= $request->contenidostock[$d]["cost_egreso"];     
                $egreso->id_producto_bodega = $sel[0]->id_producto_bodega;
                $egreso->id_empresa = $request->id_empresa;
                $egreso->save();
            } 
        }
    }
}
