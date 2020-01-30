<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;

class CajaController extends Controller
{
    //
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            //$recupera = Caja::where('caja.id_empresa','=',$id); 
            $recupera=Caja::all();
        }else{
            $recupera = Caja::
            where(function($q) use ($buscar){
                $q->where('caja.descrip_caja','like','%'.$buscar.'%') 
                ->orWhere('caja.cuenta_contable','like','%'.$buscar.'%');
            })
            ->where("caja.id_empresa", "=", $id);

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
        $caja= new Caja();
        $caja->descrip_caja=$request->descrip_caja;
        $caja->cuenta_contable=$request->cuenta_contable;
        $caja->id_moneda=$request->id_moneda;
        $caja->id_empresa=$request->id_empresa;
        $caja->save();
        return $caja;
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
        $caja=Caja::find($request->id);
        $caja->descrip_caja=$request->descrip_caja;
        $caja->cuenta_contable=$request->cuenta_contable;
        $caja->id_moneda=$request->id_moneda;
        
        $caja->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `caja` WHERE id_caja='.$id);
        return $recupera;
    }

    public function eliminar ($id){
       Caja::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
}
