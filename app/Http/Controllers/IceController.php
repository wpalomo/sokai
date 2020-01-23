<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ice;
class IceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Ice::orderByRaw('id_ice DESC')->paginate($cantidadp);
        } else {
            $recupera = Ice::select('*')
                ->where('nombre', 'like', '%' . $buscar . '%')
                ->orderByRaw('id_ice DESC')->paginate($cantidadp);
        }
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera
        ];
    }

    public function todo()
    {
        $tipo = Ice::all();
        return $tipo;
    }

    public function store(Request $request)
    {
        $tipo = new Ice();
        $tipo->nombre = $request->nombre;
        $tipo->save();
    }

    public function editar(Request $request)
    {
        $tipo= Ice::findOrFail($request->id);
        $tipo->nombre = $request->nombre;
        $tipo->save();  
    }

    public function eliminar ($id){
        Iva::destroy($id); 
    }
}
