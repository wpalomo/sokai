<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
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
            $recupera = Marca::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_marca DESC')->get();
        } else {
            $recupera = Marca::select('*')
                ->where(function($q) use ($buscar){
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('descripcion', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_marca DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo($id)
    {
        $tipo = Marca::select("*")->where("id_empresa", "=", $id)->get();
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Marca();
        $tipo->nombre = $request->nombre;
        $tipo->descripcion = $request->descripcion;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Marca::findOrFail($request->id);
        $tipo->nombre = $request->nombre;
        $tipo->descripcion = $request->descripcion;
        $tipo->id_empresa = $request->id_empresa;
        $tipo->save();  
    }

    public function eliminar ($id){
        Marca::destroy($id); 
    }
}
