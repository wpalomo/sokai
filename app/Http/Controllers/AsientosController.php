<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asientos;

class AsientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Asientos::paginate($cantidadp);
        } else {
            $recupera = Asientos::orderByRaw('id_asientos DESC')->select('*')
            ->where('comprobante', 'like', '%' . $buscar . '%')
            ->orWhere('numero', 'like', '%' . $buscar . '%')
            ->orWhere('fecha', 'like', '%' . $buscar . '%')
            ->orWhere('razon_social', 'like', '%' . $buscar . '%')
            ->orWhere('ruc_ci', 'like', '%' . $buscar . '%')
            ->orWhere('concepto', 'like', '%' . $buscar . '%')
            ->orderByRaw('id_asientos DESC')->paginate($cantidadp);
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

    public function store(Request $request)
    {
        $tipo = new Asientos();
        $tipo->comprobante = $request->comprobante;
        $tipo->numero = $request->numero;
        $tipo->fecha = $request->fecha;
        $tipo->razon_social = $request->razon_social;
        $tipo->ruc_ci = $request->ruc_ci;
        $tipo->concepto = $request->concepto;
        $tipo->save(); 
      
    }


    public function editar(Request $request)
    {
        $tipo= Asientos::find($request->id);
        $tipo->comprobante = $request->comprobante;
        $tipo->numero = $request->numero;
        $tipo->fecha = $request->fecha;
        $tipo->razon_social = $request->razon_social;
        $tipo->ruc_ci = $request->ruc_ci;
        $tipo->concepto = $request->concepto;
        $tipo->save();  
    }

    public function verasiento(Request $request)
    {
        $id = $request->id;
        $asiento = DB::select("SELECT * FROM asientos WHERE id_asientos =".$id);
        return $asiento;
    }


    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM asientos WHERE id_asientos = ".$id);
        Asientos::destroy($id);
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
