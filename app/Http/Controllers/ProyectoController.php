<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
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
            $recupera = Proyecto::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_proyecto DESC')->get();
        } else {
            $recupera = Proyecto::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('codigo', 'like', '%' . $buscar . '%')
                    ->where('descripcion', 'like', '%' . $buscar . '%')
                    ->orWhere('ubicacion', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_proyecto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    
    

    

    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM proyecto WHERE id_proyecto = ".$id);
        Proyecto::destroy($id);
    }

    public function store(Request $request)
    {
        $tipo = new Proyecto();
        $tipo->codigo = $request->codigo;
        $tipo->descripcion = $request->descripcion;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function editar(Request $request)
    {
        $tipo= Proyecto::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->descripcion = $request->descripcion;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
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
