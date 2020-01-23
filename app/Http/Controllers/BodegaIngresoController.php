<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Models\BodegaIngreso;
use App\Models\ProductoBodega;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class BodegaIngresoController extends Controller
{
    
    public function store(Request $request)
    {
        for($d = 0; $d < count($request->contenidopr); $d++){
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = ". $request->contenidopr[$d]["id"] ." AND `id_bodega` =". $request->id_bodega);
            if(count($sel)<=0){
                $prb = new ProductoBodega();
                $prb->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $prb->id_producto = $request->contenidopr[$d]["id"];
                $prb->id_bodega = $request->id_bodega;
                $prb->id_empresa = $request->id_empresa;
                $prb->save();

                $id = $prb->id_producto_bodega;
                
                $ingreso = new BodegaIngreso();
                $ingreso->num_ingreso = $request->num_ingreso;
                $ingreso->fecha_ingreso = $request->fecha_ingreso;
                $ingreso->tipo_ingreso = $request->tipo_ingreso;
                $ingreso->observ_ingreso= $request->observ_ingreso;
                $ingreso->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $ingreso->costo_unitario= $request->contenidopr[$d]["cost_unit_ingreso"];
                $ingreso->costo_total= $request->contenidopr[$d]["cant_ingreso"]*$request->contenidopr[$d]["cost_unit_ingreso"];    
                $ingreso->id_producto_bodega = $id; 
                $ingreso->id_empresa = $request->id_empresa;
                $ingreso->save();
            }else{
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad + $request->contenidopr[$d]["cant_ingreso"];
                $prb->save();
                
                $ingreso = new BodegaIngreso();
                $ingreso->num_ingreso = $request->num_ingreso;
                $ingreso->fecha_ingreso = $request->fecha_ingreso;
                $ingreso->tipo_ingreso = $request->tipo_ingreso;
                $ingreso->observ_ingreso= $request->observ_ingreso;
                $ingreso->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $ingreso->costo_unitario= $request->contenidopr[$d]["cost_unit_ingreso"];
                $ingreso->costo_total= $request->contenidopr[$d]["cant_ingreso"]*$request->contenidopr[$d]["cost_unit_ingreso"]; 
                $ingreso->id_producto_bodega = $sel[0]->id_producto_bodega;    
                $ingreso->id_empresa = $request->id_empresa;
                $ingreso->save();
            } 
        }
    }

}
