<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendedorcliente;
use Illuminate\Support\Facades\DB;

class VendedorclienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public fid_vendedorunction index(Request $request)
    {
        //
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Vendedorcliente::paginate($cantidadp);
        } else {
            $recupera = Vendedorcliente::orderByRaw('id_vendedor DESC')->select('*')
                ->where('codigo_vendedor', 'like', '%' . $buscar . '%')
                ->orwhere('nombre_vendedor','like','%'.$buscar.'%')
                ->orwhere('email_vendedor','like','%'.$buscar.'%')
                ->orderByRaw(' DESC')->paginate($cantidadp);
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
    */

    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Vendedorcliente::select("*")->where("id_empresa", "=", $id)->get(); 
        }else{
            $recupera = Vendedorcliente::select("*")
            ->where(function($q) use ($buscar){
                $q->where('codigo_vendedor', 'like', '%' . $buscar . '%')
                ->orWhere('nombre_vendedor', 'like', '%' . $buscar . '%')
                ->orWhere('email_vendedor', 'like', '%' . $buscar . '%');
            })
            ->where("id_empresa", "=", $id)
            ->orderByRaw('id_vendedor', 'desc')
            ->get();
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function store(Request $request)
    {
        $tipo = new Vendedorcliente();
        $tipo->codigo_vendedor = $request->codigo_vendedor;
        $tipo->nombre_vendedor = $request->nombre_vendedor;
        $tipo->email_vendedor = $request->email_vendedor;
        $tipo->id_empresa =$request->empresa;
        $tipo->save();  
    }
    public function editar(Request $request)
    {
        $tipo= Vendedorcliente::find($request->id);
        $tipo->codigo_vendedor = $request->codigo_vendedor;
        $tipo->nombre_vendedor = $request->nombre_vendedor;
        $tipo->email_vendedor = $request->email_vendedor;
       
        $tipo->id_empresa =$request->empresa;
        $tipo->save();  
    }
    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM vendedor WHERE id_vendedor = ".$id);
        Vendedorcliente::destroy($id);
    }
    public function vervendedor(Request $request)
    {
        $id = $request->id;
        $vendedor = DB::select("SELECT * FROM vendedor WHERE id_vendedor =".$id);
        return $vendedor;
    }
    public function todo()
    {
        $tipo = Vendedorcliente::all();
        return $tipo;
    }
    public function ver(Request $request)
    {
        $id = $request->id;
        $tipo = Vendedorcliente::select("*")->where("id_vendedor", "=", $id)->get();
        return $tipo;
    }
}
