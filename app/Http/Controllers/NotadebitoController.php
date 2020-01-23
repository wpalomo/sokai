<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notadebito;

class NotadebitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notadebito =Notadebito::all();
        return $notadebito;
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
        $notadebito = new Notadebito();
        $notadebito->claveAcceso= $request->claveAcceso;
        $notadebito->numeroautorizacion= $request->numeroautorizacion;
        $notadebito->fechaAutorizacion= $request->fechaAutorizacion;
        $notadebito->estado= $request->estado;
        $notadebito->ambiente= $request->ambiente;
        $notadebito->tipoEmision= $request->tipoEmision;
        $notadebito->secuencial= $request->secuencial;
        $notadebito->fechaEmision= $request->fechaEmision;
        $notadebito->tipoDocMod= $request->tipoDocMod;
        $notadebito->fechaEmisionDocMod= $request->fechaEmisionDocMod;
        $notadebito->nroDocMod= $request->nroDocMod;
        $notadebito->motivo= $request->motivo;
        $notadebito->nombreArchivo= $request->nombreArchivo;
        $notadebito->totalSinImpuestos= $request->totalSinImpuestos;
        $notadebito->subtotal12= $request->subtotal12;
        $notadebito->subtotal0= $request->subtotal0;
        $notadebito->subtotalNoIVA= $request->subtotalNoIVA;
        $notadebito->subtotalExentoIVA= $request->subtotalExentoIVA;
        $notadebito->valorICE= $request->valorICE;
        $notadebito->iva12= $request->iva12;
        $notadebito->valorTotal= $request->valorTotal;
        $notadebito->firmado= $request->firmado;
        $notadebito->enviarSiAutorizado= $request->enviarSiAutorizado;
        $notadebito->save();
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
        $notadebito = new Notadebito();
        $notadebito->claveAcceso= $request->claveAcceso;
        $notadebito->numeroautorizacion= $request->numeroautorizacion;
        $notadebito->fechaAutorizacion= $request->fechaAutorizacion;
        $notadebito->estado= $request->estado;
        $notadebito->ambiente= $request->ambiente;
        $notadebito->tipoEmision= $request->tipoEmision;
        $notadebito->secuencial= $request->secuencial;
        $notadebito->fechaEmision= $request->fechaEmision;
        $notadebito->tipoDocMod= $request->tipoDocMod;
        $notadebito->fechaEmisionDocMod= $request->fechaEmisionDocMod;
        $notadebito->nroDocMod= $request->nroDocMod;
        $notadebito->motivo= $request->motivo;
        $notadebito->nombreArchivo= $request->nombreArchivo;
        $notadebito->totalSinImpuestos= $request->totalSinImpuestos;
        $notadebito->subtotal12= $request->subtotal12;
        $notadebito->subtotal0= $request->subtotal0;
        $notadebito->subtotalNoIVA= $request->subtotalNoIVA;
        $notadebito->subtotalExentoIVA= $request->subtotalExentoIVA;
        $notadebito->valorICE= $request->valorICE;
        $notadebito->iva12= $request->iva12;
        $notadebito->valorTotal= $request->valorTotal;
        $notadebito->firmado= $request->firmado;
        $notadebito->enviarSiAutorizado= $request->enviarSiAutorizado;
        $notadebito->save();
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
