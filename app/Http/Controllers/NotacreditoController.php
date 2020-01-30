<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Notacredito;
use App\Models\Detalle_nota_credito;


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

        $notacredito->modo = 1;
        $notacredito->ambiente = $request->ambiente;
        $notacredito->tipo_emision = $request->tipo_emision;
        $notacredito->fecha_emision = $request->fecha_emision;
        $notacredito->forma_pago = $request->forma_pago;
        $notacredito->autorizacionfactura= $request->claveAcceso;
        $notacredito->fechaAutorizacion= $request->dateauto;
        $notacredito->observacion = $request->observacion;
        $notacredito->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $notacredito->subtotal_12 = $request->subtotal_12;
        $notacredito->subtotal_0 = $request->subtotal_0;
        $notacredito->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $notacredito->descuento = $request->descuento;
        $notacredito->valor_ice = $request->valor_ice;
        $notacredito->valor_irbpnr = $request->valor_irbpnr;
        $notacredito->iva_12 = $request->iva_12;
        $notacredito->propina = $request->propina;
        $notacredito->estatus = 1;
        $notacredito->propina = $request->propina;
        $notacredito->propina = $request->propina;
        $notacredito->valor_total = $request->valor_total;
        $notacredito->id_cliente = $request->id_cliente;
        $notacredito->id_user = $request->id_user;
        $notacredito->id_punto_emision = $request->id_punto_emision;
        $notacredito->id_empresa = $request->id_empresa;
        $notacredito->id_establecimiento = $request->id_establecimiento;
        $notacredito->totalpropinaf = $request->totalpropinaf;
        $notacredito->pp_descuento = $request->pp_descuento;
        $notacredito->save();

        $id = $notacredito->id_nota_credito;

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle_nota_credito(); 
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
        $fact = Notacredito::join("empresa", "empresa.id_empresa", "=", "nota_credito.id_empresa")->where("nota_credito.id_nota_credito", "=", $id)->get();
        $detalle = Detalle_nota_credito::where("id_factura", "=", $id);
        return [
            "factura" => $fact,
            "detalle" => $detalle
        ];
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
        $notacredito->autorizacion= $request->claveAcceso; 
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

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle(); 
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = (($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"]);
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
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
