<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Lineaproducto;
use App\Models\Tipoproducto;

class LineaproductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Lineaproducto::select("*")->where("id_empresa", "=", $id)->get();
        } else {
            $recupera = Lineaproducto::orderByRaw('id_linea_producto DESC')->select('*')
                ->where(function($q) use ($buscar){
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('codigo', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_linea_producto DESC')->get();
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

    public function todo($id)
    {
        $tipo = Lineaproducto::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo = new Lineaproducto();
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->cta_compras_iva = $request->cta_civa;
        $tipo->cta_compras_iva_0 = $request->cta_civa0;
        $tipo->cta_ventas_iva = $request->cta_viva;
        $tipo->cta_ventas_iva_0 = $request->cta_viva0;
        $tipo->cuenta_costo = $request->cta_costo;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function editar(Request $request)
    {
        $tipo= Lineaproducto::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->cta_compras_iva = $request->cta_civa;
        $tipo->cta_compras_iva_0 = $request->cta_civa0;
        $tipo->cta_ventas_iva = $request->cta_viva;
        $tipo->cta_ventas_iva_0 = $request->cta_viva0;
        $tipo->cuenta_costo = $request->cta_costo;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function eliminar ($id){ 
        //Tipoproducto::where('id_linea_producto', $id)->delete();
        DB::delete("DELETE FROM tipo_producto WHERE id_linea_producto = ".$id);
        Lineaproducto::destroy($id);
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
