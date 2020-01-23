<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Models\Importacion;

class LiquidacionController extends Controller
{
    //
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.total_factura) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion and importacion.id_punto_emision=".$id);
        }else{
            $recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.total_factura) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion");
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
        $recupera = FacturaCompra::where('id_importacion', '=', $id)->get();

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
