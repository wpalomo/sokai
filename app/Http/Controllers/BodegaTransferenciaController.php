<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Models\BodegaIngreso;
use App\Models\BodegaTransferencia;
use App\Models\ProductoBodega;
use App\Models\ProductoBodegaTrans;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class BodegaTransferenciaController extends Controller
{
    public function store(Request $request)
    {
        $trans = new BodegaTransferencia();
        $trans->num_trans = $request->num_transe;
        $trans->f_iniciacion = $request->f_ini_transe;
        $trans->f_finalizacion = $request->f_fin_transe;
        $trans->motivo_trans = $request->motivo_transe;
        $trans->emisor_trans = $request->emisor_transe;
        $trans->receptor_trans = $request->receptor_transe;
        $trans->observ_trans = $request->observ_transe;
        $trans->transporte = $request->transport_transe;
        $trans->marcav = $request->marca_transe;
        $trans->placasv = $request->placa_transe;
        $trans->colorv = $request->color_transe;
        $trans->transportista = $request->sr_transe;
        $trans->id_empresa = $request->id_empresa;
        $trans->save();

        $id = $trans->id_bodega_transferencia;

        for ($d = 0; $d < count($request->contenidotranse); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->contenidotranse[$d]["id"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) == 1) {
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad - $request->contenidotranse[$d]["cant_enviadae"];
                $prb->save();

                $pbt = new ProductoBodegaTrans();
                $pbt->id_producto = $request->contenidotranse[$d]["id"];
                $pbt->cant_env = $request->contenidotranse[$d]["cant_enviadae"];
                $pbt->cant_recib = $request->cant_recibidae;
                $pbt->id_bodega_transferencia = $id;
                $pbt->id_empresa = $request->id_empresa;
                $pbt->save();
            }
        }
    }

    public function recibida(Request $request)
    {


        $id = $request->id_bodega_transferencia;

        for ($d = 0; $d < count($request->contenidoproductostr); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->contenidoproductostr[$d]["id_producto"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) >= 1) {
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad + ($request->contenidoproductostr[$d]["cant_recib"] - $request->contenidoproductostr[$d]["cant_ant"]);
                $prb->save();

                $pbt =  ProductoBodegaTrans::findOrFail($request->contenidoproductostr[$d]["id_producto_bodega_trans"]);
                $pbt->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                $pbt->cant_env = $request->contenidoproductostr[$d]["cant_env"]; 
                $pbt->cant_recib = $request->contenidoproductostr[$d]["cant_recib"];
                $pbt->id_bodega_transferencia = $id;
                $pbt->id_empresa = $request->id_empresa;
                $pbt->save();
            }else{
                $prb = new ProductoBodega();
                $prb->cantidad = $request->contenidoproductostr[$d]["cant_recib"];
                $prb->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                $prb->id_bodega = $request->id_bodega;
                $prb->id_empresa = $request->id_empresa;
                $prb->save();

                $pbt =  ProductoBodegaTrans::findOrFail($request->contenidoproductostr[$d]["id_producto_bodega_trans"]);
                $pbt->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                $pbt->cant_env = $request->contenidoproductostr[$d]["cant_env"]; 
                $pbt->cant_recib = $request->contenidoproductostr[$d]["cant_recib"];
                $pbt->id_bodega_transferencia = $id;
                $pbt->id_empresa = $request->id_empresa;
                $pbt->save();
            }
        }
    }

    public function gettransr($id)
    {
        $transferencia = BodegaTransferencia::select("*")->where('receptor_trans', "=", $id)->get();
        //$transferencia = DB::select("SELECT *, (SELECT sum(cant_env - cant_recib) FROM producto_bodega_trans WHERE id_bodega_transferencia = bodega_transferencia.id_bodega_transferencia) AS prb FROM bodega_transferencia WHERE (SELECT sum(cant_env - cant_recib) FROM producto_bodega_trans WHERE id_bodega_transferencia = bodega_transferencia.id_bodega_transferencia)>0");
        return [
            "datos" => $transferencia,
        ];
    }

    public function gettransrpproductos($id)
    {
        $transferencia = ProductoBodegaTrans::select("producto_bodega_trans.*", "producto_bodega_trans.cant_recib as cant_ant","producto.cod_principal as podereditar", "producto.cod_principal", "producto.nombre")
                        ->join("producto", "producto.id_producto", "=", "producto_bodega_trans.id_producto")
                        ->where("id_bodega_transferencia", "=", $id)->get();
        return [
            "datos" => $transferencia,
        ];
    }
}
