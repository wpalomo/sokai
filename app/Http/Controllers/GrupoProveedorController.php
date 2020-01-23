<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupoProveedor;
use App\Models\Plancuenta;

class GrupoProveedorController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = GrupoProveedor::select("*")->where("id_empresa","=",$id)->get();
        } else {
            $recupera = GrupoProveedor::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('nombre_grupoprov', 'like', '%' . $buscar . '%')
                    ->orWhere('cod_grupoprov', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_grupoprov DESC')->get();
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
        $select1 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_descuento'");
        $select3 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_anticipo'");
        if(!$select1 || !$select2 && !$select3){
            return "existe";
        }
        $grupo =new GrupoProveedor();
        $grupo->cod_grupoprov=$request->cod_grupoprov;
        $grupo->nombre_grupoprov=$request->nombre_grupoprov;
        $grupo->importador=$request->importador;
        $grupo->cta_contable=$request->cta_contable;
        $grupo->cta_descuento=$request->cta_descuento;
        $grupo->cta_anticipo=$request->cta_anticipo;
        $grupo->id_empresa=$request->id_empresa;
        $grupo->save();
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
        $select1 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        $select2 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_descuento'");
        $select3 = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_anticipo'");
        if(!$select1 && !$select2 && !$select3){
            return "existe";
        }
        $grupo =GrupoProveedor::findOrFail($request->id);
        $grupo->cod_grupoprov=$request->cod_grupoprov;
        $grupo->nombre_grupoprov=$request->nombre_grupoprov;
        $grupo->importador=$request->importador;
        $grupo->cta_contable=$request->cta_contable;
        $grupo->cta_descuento=$request->cta_descuento;
        $grupo->cta_anticipo=$request->cta_anticipo;
        $grupo->id_empresa=$request->id_empresa;
        $grupo->save();
    } 

    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `grupo_proveedor` WHERE id_grupoprov='.$id);
        return $recupera;
    }
    public function eliminar ($id){
        GrupoProveedor::destroy($id);
    }
}
