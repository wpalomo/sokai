<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Models\Importacion;
use App\Models\Detalle_factura_compra;
use App\Models\Bodega;

class LiquidacionController extends Controller
{
    //
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion and importacion.id_punto_emision=".$id);
        }else{
            $recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion");
        } 
        return [
            
            'recupera' => $recupera
        ];
    }
    public function abrir(Request $request){
        $id=$request->id;
        $recupera = Importacion::where('importacion.id_importacion', '=', $id)->get();

        return $recupera;
    }
    public function abrirFactura($id){
        $recupera = FacturaCompra::where('id_importacion', '=', $id)
        ->addSelect(['producto' => Detalle_factura_compra::select('nombre')
        ->whereColumn('id_factura', 'factura_compra.id_factcompra')
        ->limit(1)
        ])
        ->get();

        return $recupera;
    }
    public function abrirBodega($pto){
        
        $recupera = Bodega::where('id_establecimiento', '=', $pto)
        ->get();

        return $recupera;
    }
    public function liquidar(Request $request){
        $import=Importacion::find($request->id);
        $import->estado="Liquidado";
        $import->total_facturas=$request->totalfac;
        $import->total_liquidacion=$request->total;
        $import->total_importacion=$request->total+$request->totalfac;
        $import->save();
    }

}
