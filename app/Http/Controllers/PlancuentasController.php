<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Plancuenta;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Caja;


class PlancuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request,$id)
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Plancuenta::select('plan_cuentas.*','moneda.nomb_moneda','grupo.nomb_grupo')
                ->join('moneda','moneda.id_moneda','=','plan_cuentas.id_moneda')
                ->join('grupo','grupo.id_grupo','=','plan_cuentas.id_grupo')
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')->get();
        }else{
            $recupera = Plancuenta::select('plan_cuentas.*','moneda.nomb_moneda','grupo.nomb_grupo')
                ->join('moneda','moneda.id_moneda','=','plan_cuentas.id_moneda')
                ->join('grupo','grupo.id_grupo','=','plan_cuentas.id_grupo')
                ->where(function($q) use ($buscar){
                    $q->where('plan_cuentas.codcta', 'like','%'.$buscar.'%') 
                    ->orWhere('plan_cuentas.nomcta', 'like','%'.$buscar.'%');
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')->get();
        } 
        return [
            'recupera' => $recupera
        ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $select = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->codcta'");
        if(count($select)>=1){
            return "existe";
        }
        $ctas=new Plancuenta();
        $ctas->id_empresa=$request->id_empresa;
        $ctas->codcta=$request->codcta;
        $ctas->nomcta=$request->nomcta;
        $ctas->id_moneda=$request->id_moneda;
        $ctas->bansel=$request->bansel;
        $ctas->id_grupo=$request->id_grupo;
        $ctas->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
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
        $ctas=Plancuenta::find($request->id);
        $ctas->id_empresa=$request->id_empresa;
        $ctas->codcta=$request->codcta;
        $ctas->nomcta=$request->nomcta;
        $ctas->id_moneda=$request->id_moneda;
        $ctas->bansel=$request->bansel;
        $ctas->id_grupo=$request->id_grupo;
        $ctas->save();
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
        $recupera = DB::select('SELECT * FROM `plan_cuentas` WHERE id_ctas='.$id);
        return $recupera;
    }

    public function eliminar ($id){
        Plancuenta::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }

      public function getEmpresa(){
        $data=Empresa::get();

        return response()->json($data);
      }
      public function getMoneda(){
        $data=Moneda::get();

        return response()->json($data);
      }
     
      
}
