<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuentaporcobrar;
use App\Models\Cliente;


class CuentaporcobrarController extends Controller
{
    //
    public function index(Request $request)
    {
        //$plancuentas = Plancuentas::all();
        //return $plancuentas;
        /*
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $cantidadp = $request->cantidadp;
        if($cantidadp < 1){
            $cantidadp=10;
        }
        if ($buscar==''){
            $recupera = Cuentaporcobrar::paginate($cantidadp); 
        }else{
            $recupera = Cuentaporcobrar::where($criterio,'like','%'.$buscar.'%')
            ->orderByRaw('id_ctascobrar', 'desc')
            ->paginate($cantidadp);
        } 
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => round($recupera->total()/$cantidadp),
            ], 
            'recupera' => $recupera
        ];*/
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $cantidadp = $request->cantidadp;
        if($cantidadp < 1){
            $cantidadp=10;
        }
        if ($buscar==''){
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Cuentaporcobrar::select('ctas_cobrar.*','cliente.nombre')
                    ->join('cliente','cliente.id_cliente','=','ctas_cobrar.id_cliente')
                    ->orderByRaw('ctas_cobrar.id_ctascobrar ASC')->paginate($cantidadp);
        }else{
            $recupera = Cuentaporcobrar::select('ctas_cobrar.*','cliente.nombre')
                        ->join('cliente','cliente.id_cliente','=','ctas_cobrar.id_cliente')
                    ->where($criterio,$buscar)
                    //->orWhere('cliente.nombre','like','%'.$buscar.'%')
                    ->orderByRaw('ctas_cobrar.id_ctascobrar ASC')->paginate($cantidadp);
        } 
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total()/$cantidadp),
            ], 
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
        $pago= new Cuentaporcobrar();
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
        $pago= Cuentaporcobrar::find($request->id);
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
        $recupera = DB::select('SELECT * FROM `ctas_cobrar` WHERE id_ctascobrar='.$id);
        return $recupera;
    }

    public function eliminar ($id){
        Cuentaporcobrar::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }

      public function getCliente(){
          $data=Cliente::get();
          return response()->json($data);
      }
}
