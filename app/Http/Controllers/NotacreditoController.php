<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $buscar = $request->buscar;
        if ($buscar == '') {
                $recupera = DB::select("select `nota_credito`.*, `nota_credito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_credito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_credito` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `nota_credito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `nota_credito`.`id_empresa` = ".$request->datos["id_empresa"]." and `nota_credito`.`modo` = 1 order by nota_credito.id_nota_credito DESC");
        } else {
            $recupera = DB::select("select `nota_credito`.*, `nota_credito`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `nota_credito`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `nota_credito` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `nota_credito`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR nota_credito.respuesta like '%$buscar%' OR nota_credito.clave_acceso like '%$buscar%') AND `nota_credito`.`id_empresa` = ".$request->datos["id_empresa"]." and `nota_credito`.`modo` = 1 order by nota_credito.id_nota_credito DESC");
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
