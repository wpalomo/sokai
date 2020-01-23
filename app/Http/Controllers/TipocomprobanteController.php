<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipocomprobante;

class TipocomprobanteController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Tipocomprobante::select("*")->where("id_empresa","=", $id)->orderBy("id_tipcomprobante", "DESC")->get(); 
        }else{
            $recupera = Tipocomprobante::select("*")
                        ->where(function($q) use ($buscar){
                            $q->orWhere('cod_tipcomprob', 'like', '%' . $buscar . '%')
                            ->orWhere('descrip_tipcomprob', 'like', '%' . $buscar . '%');
                        })
                        ->where('id_empresa', '=', $id)->orderBy("id_tipcomprobante", "DESC")->get();
        } 
        return [
            'recupera' => $recupera
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        $select1 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_descuento'");
        $select3 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_anticipo'");
        if(!$select1 && !$select2 && !$select3){
            return "existe";
        }*/
        $tipo =new Tipocomprobante();
        $tipo->cod_tipcomprob=$request->cod_tipcomprob;
        $tipo->descrip_tipcomprob=$request->descrip_tipcomprob;
        $tipo->id_empresa=$request->id_empresa;
        //$tipo->venta_tipcomprob=$request->venta_tipcomprob;
        $tipo->save();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tipo =Tipocomprobante::findOrFail($request->id);
        $tipo->cod_tipcomprob=$request->cod_tipcomprob;
        $tipo->descrip_tipcomprob=$request->descrip_tipcomprob;
        $tipo->venta_tipcomprob=$request->venta_tipcomprob;
        $tipo->id_empresa=$request->id_empresa;
        $tipo->save();
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `tipo_comprobante` WHERE id_tipcomprobante='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        Tipocomprobante::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
}
