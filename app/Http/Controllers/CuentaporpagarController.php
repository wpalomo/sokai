<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuentaporpagar;
use App\Models\Creditocompra;

class CuentaporpagarController extends Controller
{
    //
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Cuentaporpagar::all(); 
        }else{
            $recupera = Cuentaporpagar::where('id_proveedor','like','%'.$buscar.'%');
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
        $pago= new Cuentaporpagar();
        $pago->nro_comprobante=$request->nro_comprobante;
        $pago->nro_cuota=$request->nro_cuota;
        $pago->forma_pago=$request->forma_pago;
        $pago->banco=$request->banco;
        $pago->nro_tarjeta=$request->nro_tarjeta;
        $pago->cta_contable=$request->cta_contable;
        $pago->monto=$request->monto;
        $pago->abono=$request->abono;
        $pago->saldo=$request->saldo;
        $pago->descuento=$request->descuento;
        $pago->estado=1;
        $pago->freguistro=$request->freguistro;
        $pago->fvencimiento=$request->fvencimiento;
        $pago->id_cliente=$request->id_cliente;
        $pago->save();
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
        $pago= Cuentaporpagar::find($request->id);
        $pago->nro_comprobante=$request->nro_comprobante;
        $pago->nro_cuota=$request->nro_cuota;
        $pago->forma_pago=$request->forma_pago;
        $pago->banco=$request->banco;
        $pago->nro_tarjeta=$request->nro_tarjeta;
        $pago->cta_contable=$request->cta_contable;
        $pago->monto=$request->monto;
        $pago->abono=$request->abono;
        $pago->saldo=$request->saldo;
        $pago->descuento=$request->descuento;
        $pago->estado=$request->estado;
        $pago->freguistro=$request->freguistro;
        $pago->fvencimiento=$request->fvencimiento;
        $pago->id_cliente=$request->id_cliente;
        $pago->save();
    }

    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `ctas_pagar` WHERE id_ctaspagar='.$id);
        return $recupera;
    }

    public function eliminar ($id){
        Cuentaporpagar::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }

      public function getProveedor(){
        $recupera = DB::select('SELECT * FROM `proveedor` Where `id_empresa`=1');
        return $recupera;
      }
}
