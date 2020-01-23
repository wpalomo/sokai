<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupocliente;
use Illuminate\Support\Facades\DB;

class GrupoclienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Grupocliente::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_grupo_cliente DESC')->get();
        } else {
            $recupera = Grupocliente::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('nombre_grupo', 'like', '%' . $buscar . '%')
                    ->orWhere('codigo', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_grupo_cliente DESC')->get();
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
        $tipo = new Grupocliente();
        $tipo->codigo = $request->codigo;
        $tipo->nombre_grupo = $request->nombre_grupo;
        $tipo->cuenta = $request->cuenta;
        $tipo->cuenta_descuento= $request->cuenta_descuento;
        $tipo->cuenta_anticipo = $request->cuenta_anticipo;
        $tipo->cuenta_servicio = $request->cuenta_servicio;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function editar(Request $request)
    {
        $tipo= Grupocliente::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->nombre_grupo = $request->nombre_grupo;
        $tipo->cuenta = $request->cuenta;
        $tipo->cuenta_descuento= $request->cuenta_descuento;
        $tipo->cuenta_anticipo = $request->cuenta_anticipo;
        $tipo->cuenta_servicio = $request->cuenta_servicio;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM grupo_cliente WHERE id_grupo_cliente = ".$id);
        Grupocliente::destroy($id);
    }

    
    public function todo($id)
    {
        $tipo = Grupocliente::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
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
    public function update(Request $request, $id)
    {
        //
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
}
