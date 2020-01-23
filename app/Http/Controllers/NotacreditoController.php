<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notacredito;

class NotacreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $cantidadp = $request->cantidadp;
        if($cantidadp < 1){
            $cantidadp=10;
        }
        if ($buscar==''){
            $recupera = Notacredito::paginate($cantidadp); 
        }else{
            $recupera = Notacredito::where($criterio,'like','%'.$buscar.'%')
            ->orderByRaw('id', 'desc')
            ->paginate($cantidadp);
        } 
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => round($recupera->total()/$cantidadp),
            ], 
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
        $notacredito = new Notacredito();
        $notacredito->claveAcceso= $request->claveAcceso;
        $notacredito->numeroautorizacion= $request->numeroautorizacion;
        $notacredito->fechaAutorizacion= $request->fechaAutorizacion;
        $notacredito->estado= $request->estado;
        $notacredito->ambiente= $request->ambiente;
        $notacredito->tipoEmision= $request->tipoEmision;
        $notacredito->secuencial= $request->secuencial;
        $notacredito->fechaEmision= $request->fechaEmision;
        $notacredito->tipoDocMod= $request->tipoDocMod;
        $notacredito->fechaEmisionDocMod= $request->fechaEmisionDocMod;
        $notacredito->nroDocMod= $request->nroDocMod;
        $notacredito->motivo= $request->motivo;
        $notacredito->nombreArchivo= $request->nombreArchivo;
        $notacredito->totalSinImpuestos= $request->totalSinImpuestos;
        $notacredito->subtotal12= $request->subtotal12;
        $notacredito->subtotal0= $request->subtotal0;
        $notacredito->subtotalNoIVA= $request->subtotalNoIVA;
        $notacredito->subtotalExentoIVA= $request->subtotalExentoIVA;
        $notacredito->valorIRBPNR= $request->valorIRBPNR;
        $notacredito->iva12= $request->iva12;
        $notacredito->totalDescuento= $request->totalDescuento;
        $notacredito->valorTotal= $request->valorTotal;
        $notacredito->firmado= $request->firmado;
        $notacredito->enviarSiAutorizado= $request->enviarSiAutorizado;
        $notacredito->save();
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
        $notacredito = Notacredito::findOrFail($request->id);
        $notacredito->claveAcceso= $request->claveAcceso;
        $notacredito->numeroautorizacion= $request->numeroautorizacion;
        $notacredito->fechaAutorizacion= $request->fechaAutorizacion;
        $notacredito->estado= $request->estado;
        $notacredito->ambiente= $request->ambiente;
        $notacredito->tipoEmision= $request->tipoEmision;
        $notacredito->secuencial= $request->secuencial;
        $notacredito->fechaEmision= $request->fechaEmision;
        $notacredito->tipoDocMod= $request->tipoDocMod;
        $notacredito->fechaEmisionDocMod= $request->fechaEmisionDocMod;
        $notacredito->nroDocMod= $request->nroDocMod;
        $notacredito->motivo= $request->motivo;
        $notacredito->nombreArchivo= $request->nombreArchivo;
        $notacredito->totalSinImpuestos= $request->totalSinImpuestos;
        $notacredito->subtotal12= $request->subtotal12;
        $notacredito->subtotal0= $request->subtotal0;
        $notacredito->subtotalNoIVA= $request->subtotalNoIVA;
        $notacredito->subtotalExentoIVA= $request->subtotalExentoIVA;
        $notacredito->valorIRBPNR= $request->valorIRBPNR;
        $notacredito->iva12= $request->iva12;
        $notacredito->totalDescuento= $request->totalDescuento;
        $notacredito->valorTotal= $request->valorTotal;
        $notacredito->firmado= $request->firmado;
        $notacredito->enviarSiAutorizado= $request->enviarSiAutorizado;
        $notacredito->save();
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
