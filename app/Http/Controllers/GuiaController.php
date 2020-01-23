<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guia;

class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guia = Guia::all();
        return $guia;
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
        $guia = new Guia();
        $guia->claveAcceso= $request->claveAcceso;
        $guia->numeroAutorizacion= $request->numeroAutorizacion;
        $guia->fechaAutorizacion= $request->fechaAutorizacion;
        $guia->estado= $request->estado;
        $guia->ambiente= $request->ambiente;
        $guia->tipoEmision= $request->tipoEmision;
        $guia->secuencial= $request->secuencial;
        $guia->nombreArchivo= $request->nombreArchivo;
        $guia->dirPartida= $request->dirPartida;
        $guia->razonSocialTransportista= $request->razonSocialTransportista;
        $guia->rucTransportista= $request->rucTransportista;
        $guia->fechaIniTransporte= $request->fechalniTransporte;
        $guia->fechaFinTransporte= $request->fechaFinTransporte;
        $guia->placa= $request->placa;
        $guia->motivoTraslado= $request->motivoTraslado;
        $guia->firmado= $request->firmado;
        $guia->enviarSiAutorizado= $request->enviarSiAutorizado;
        $guia->save();
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
        $guia = Guia::findOrFail($request->id);
        $guia->claveAcceso= $request->claveAcceso;
        $guia->numeroAutorizacion= $request->numeroAutorizacion;
        $guia->fechaAutorizacion= $request->fechaAutorizacion;
        $guia->estado= $request->estado;
        $guia->ambiente= $request->ambiente;
        $guia->tipoEmision= $request->tipoEmision;
        $guia->secuencial= $request->secuencial;
        $guia->nombreArchivo= $request->nombreArchivo;
        $guia->dirPartida= $request->dirPartida;
        $guia->razonSocialTransportista= $request->razonSocialTransportista;
        $guia->rucTransportista= $request->rucTransportista;
        $guia->fechaIniTransporte= $request->fechalniTransporte;
        $guia->fechaFinTransporte= $request->fechaFinTransporte;
        $guia->placa= $request->placa;
        $guia->motivoTraslado= $request->motivoTraslado;
        $guia->firmado= $request->firmado;
        $guia->enviarSiAutorizado= $request->enviarSiAutorizado;
        $guia->save();
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
