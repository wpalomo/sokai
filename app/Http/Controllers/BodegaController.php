<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bodega;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $ide)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Bodega::select("*")->where("id_empresa", "=", $id)->where("id_establecimiento", "=", $ide)->orderByRaw('id_bodega DESC')->get();
        } else {
            $recupera = Bodega::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('codigo', 'like', '%' . $buscar . '%')    
                    ->orWhere('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('serie_factura', 'like', '%' . $buscar . '%')
                    ->orWhere('ubicacion', 'like', '%' . $buscar . '%')
                    ->orWhere('direccion', 'like', '%' . $buscar . '%')
                    ->orWhere('telefono', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->where("id_establecimiento", "=", $ide)
                ->orderByRaw('id_bodega DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo($id)
    {
        $tipo = Bodega::select("*")->where("id_empresa","=", $id)->get();
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Bodega();
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->serie_factura = $request->serie;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->direccion = $request->direccion;
        $tipo->telefono = $request->telefono;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->id_establecimiento = $request->id_establecimiento;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Bodega::findOrFail($request->id);
        $tipo->codigo = $request->codigo;
        $tipo->nombre = $request->nombre;
        $tipo->serie_factura = $request->serie;
        $tipo->ubicacion = $request->ubicacion;
        $tipo->direccion = $request->direccion;
        $tipo->telefono = $request->telefono;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->id_establecimiento = $request->id_establecimiento;
        $tipo->save();  
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM bodega WHERE id_bodega=' . $id);
       // $prbd = DB::select('SELECT * FROM producto_bodega WHERE id_producto =' . $id);
        return [
            "recupera" => $recupera,
            //"bodega" => $prbd
        ];
    }

    public function eliminar ($id){
        Bodega::destroy($id);
    }
}
