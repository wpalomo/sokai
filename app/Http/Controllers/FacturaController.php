<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Detalle;
use App\Models\Cliente;
use App\Models\FacturaGuiaDeRemision;
use App\Models\Cuentaporcobrar;
use App\Models\Retencion;
use App\Models\Retencion_factura;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
                $recupera = DB::select("select `factura`.*, `factura`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `factura` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where `factura`.`id_empresa` = ".$request->datos["id_empresa"]." and `factura`.`modo` = 1 order by factura.id_factura DESC");
        } else {
            $recupera = DB::select("select `factura`.*, `factura`.`fmodifica` as `fecha_autorizacion`, `empresa`.*, `cliente`.*, `moneda`.`nomb_moneda` as `moneda`, `factura`.`descuento` as `descuentototal`, `establecimiento`.`codigo` as `codigoes`, `punto_emision`.`codigo` as `codigope`, `establecimiento`.`direccion` as `direccion_establecimiento` from `factura` inner join `empresa` on `empresa`.`id_empresa` = ".$request->datos["id_empresa"]." inner join `cliente` on `cliente`.`id_cliente` = `factura`.`id_cliente` inner join `establecimiento` on `establecimiento`.`id_establecimiento` = ".$request->datos["id_establecimiento"]." inner join `punto_emision` on `punto_emision`.`id_punto_emision` = ".$request->datos["id_punto_emision"]." inner join `moneda` on `moneda`.`id_moneda` = `empresa`.`id_moneda` where (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR factura.respuesta like '%$buscar%' OR factura.clave_acceso like '%$buscar%') AND `factura`.`id_empresa` = ".$request->datos["id_empresa"]." and `factura`.`modo` = 1 order by factura.id_factura DESC");
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function store(Request $request)
    {
        $factura = new Factura();
        $factura->modo = 1;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->forma_pago = $request->forma_pago;
        $factura->clave_acceso = $request->clave_acceso;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->estatus = 1;
        $factura->propina = $request->propina;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->totalpropinaf = $request->totalpropinaf;
        $factura->pp_descuento = $request->pp_descuento;
        $factura->save();
        $id = $factura->id_factura;
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->p_descuento = $request->productos[$a]["p_descuento"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
        if($request->verpagos){
            for ($a = 0; $a < count($request->valorpagos); $a++) {
                if ($request->valorpagos[$a]["metodo_pago"] != null && $request->valorpagos[$a]["cantidad_pago"] != 0) {
                    $cxc = new Cuentaporcobrar();
                    $cxc->unidad_tiempo = "Dias";
                    $cxc->nro_cuota = 1;
                    $cxc->cuotas_totales = 1;
                    $cxc->forma_pago = $request->valorpagos[$a]["metodo_pago"];
                    $cxc->banco = $request->valorpagos[$a]["banco"];
                    $cxc->nro_tarjeta = $request->valorpagos[$a]["tarjeta"];
                    $cxc->cta_contable = $request->valorpagos[$a]["cuenta"];
                    $cxc->monto = $request->valorpagos[$a]["cantidad_pago"];;
                    $cxc->abono = 0;
                    $cxc->saldo = 0;
                    $cxc->comentario = $request->valorpagos[$a]["comentario_pago"];
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura = $id;
                    $cxc->save();
                }
            }
        }
        if($request->vercreditos){
            $cxc = new Cuentaporcobrar();
            $cxc->unidad_tiempo = $request->periodo;
            $cxc->nro_cuota = 1;
            $cxc->cuotas_totales = $request->plazos;
            $cxc->forma_pago = "Crédito";
            $cxc->monto = $request->monto;
            $cxc->abono = 0;
            $cxc->saldo = $request->monto;
            $cxc->estado = 1;
            $cxc->tipo = 1;
            $cxc->id_factura = $id;
            $cxc->save();
        }
        if($request->verretenciones){
            for ($i = 0; $i < count($request->retencion); $i++) {
                if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                    $ret = new Retencion_factura();
                    $ret->id_factura = $id;
                    $ret->id_retencion_iva = $request->retencion[$i]["iva"]["id_retencion"];
                    $ret->id_retencion_renta = $request->retencion[$i]["renta"]["id_retencion"];

                    $ret->porcentajeiva = $request->retencion[$i]["porcentajeiva"];
                    $ret->cantidadiva = $request->retencion[$i]["cantidadiva"];
                    $ret->baserenta = $request->retencion[$i]["baserenta"];
                    $ret->porcentajerenta = $request->retencion[$i]["porcentajerenta"];
                    $ret->cantidadrenta = $request->retencion[$i]["cantidadrenta"];
                    $ret->save();
                }
            }
        }
        if ($request->transportista['nombre_transporte'] != "" && $request->guia) {
            $transportistas = new FacturaGuiaDeRemision();
            $transportistas->razon_social = $request->transportista['nombre_transporte'];
            $transportistas->tipo_identificacion = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado = $request->transportista['motivo_translado'];
            $transportistas->id_empresa = $request->id_empresa;// recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_factura = $id;// recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->save();
        }
        return Factura::select('factura.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'factura.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
        ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("factura.id_factura", "=", $id)
        ->orderByRaw('factura.id_factura DESC')->get();
    }
    public function indexEmpresa($id)
    {
        $recupera = DB::table('empresa')
            ->where('id_empresa', $id)->get();
        return $recupera;
    }
    public function verproductos(Request $request)
    {
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Detalle::select('*')
                ->orderByRaw('id_detalle DESC')->paginate($cantidadp);
        } else {
            $recupera = Detalle::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion', '=', $buscar)
                        ->orWhere('cantidad', '=', $buscar);
                })
                ->where('id_factura', '=', $idfactura)
                ->orderByRaw('id_detalle DESC')->paginate($cantidadp);
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
    public function buscarfac()
    {
        $recupera = "asdasd";
        return $recupera;
    }
    public function abrir()
    {
        //
        $respuesta = DB::select('*');
        return $respuesta;
    }
    public function clave($id)
    {
        $respuesta = DB::select("SELECT u.id_rol, u.id_empresa, u.id_establecimiento, u.id_punto_emision, e.ruc_empresa, e.ambiente, es.codigo AS establecimiento, pe.codigo AS punto_emision FROM user u INNER JOIN empresa e on e.id_empresa=u.id_empresa INNER JOIN establecimiento es on es.id_empresa=e.id_empresa INNER JOIN punto_emision pe on pe.id_empresa=e.id_empresa WHERE u.id = " . $id);
        $respuesta1 = DB::select("SELECT COUNT(*) AS numero FROM factura WHERE id_empresa = " . $respuesta[0]->id_empresa);
        return [
            'secuencial' => $respuesta1[0]->numero + 1,
            'recupera' => $respuesta
        ];
    }
    public function storeprof(Request $request)
    {
        $id = $request->id;
        $factura = Factura::findOrFail($id);
        $factura->modo = 1;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->forma_pago = $request->forma_pago;
        $factura->clave_acceso = $request->clave_acceso;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->estatus = 0;
        $factura->propina = $request->propina;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->save();
        DB::select('DELETE FROM detalle WHERE id_factura ='.$id);
        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
        for ($a = 0; $a < count($request->valorpagos); $a++) {
            if ($request->valorpagos[$a]["metodo_pago"] != null && $request->valorpagos[$a]["cantidad_pago"] != 0) {
                $cxc = new Cuentaporcobrar();
                $cxc->unidad_tiempo = "Dias";
                $cxc->nro_cuota = 1;
                $cxc->cuotas_totales = 1;
                $cxc->forma_pago = $request->valorpagos[$a]["metodo_pago"];
                $cxc->banco = $request->valorpagos[$a]["banco"];
                $cxc->nro_tarjeta = $request->valorpagos[$a]["tarjeta"];
                $cxc->cta_contable = $request->valorpagos[$a]["cuenta"];
                $cxc->monto = $request->valorpagos[$a]["cantidad_pago"];;
                $cxc->abono = 0;
                $cxc->saldo = 0;
                $cxc->comentario = $request->valorpagos[$a]["comentario_pago"];
                $cxc->estado = 1;
                $cxc->tipo = 2;
                $cxc->id_factura = $id;
                $cxc->save();
            }
        }
        $cxc = new Cuentaporcobrar();
        $cxc->unidad_tiempo = $request->periodo;
        $cxc->nro_cuota = 1;
        $cxc->cuotas_totales = $request->plazos;
        $cxc->forma_pago = "Crédito";
        $cxc->monto = $request->monto;
        $cxc->abono = 0;
        $cxc->saldo = $request->monto;
        $cxc->estado = 1;
        $cxc->tipo = 1;
        $cxc->id_factura = $id;
        $cxc->save();
        
        for ($i = 0; $i < count($request->retencion); $i++) {
            if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                $ret = new Retencion_factura();
                $ret->id_factura = $id;
                $ret->id_retencion_iva = $request->retencion[$i]["iva"];
                $ret->id_retencion_renta = $request->retencion[$i]["renta"];
                $ret->save();
            }
        }

        return Factura::select('factura.*', 'empresa.*', 'cliente.*', 'moneda.nomb_moneda as moneda', 'factura.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
        ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
        ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
        ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
        ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
        ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
        ->where("factura.id_factura", "=", $id)
        ->orderByRaw('factura.id_factura DESC')->get();
    }
    public function indexp(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Factura::select('*')
                ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                ->where(function($q) use ($buscar){
                    $q->where("factura.modo", "=", 0)
                    ->orWhere("factura.estatus", "=", 0);
                })
                ->where("factura.id_empresa", "=", $id)
                ->orderByRaw('factura.id_factura DESC')->get();
        } else {
            $recupera = Factura::select('*')
                ->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'factura.id_cliente')
                ->where(function ($q) use ($buscar) {
                    $q->where('factura.ambiente', 'like', '%' . $buscar . '%')
                        ->orWhere('factura.tipo_emision', 'like', '%' . $buscar . '%');
                })
                ->where(function($q) use ($buscar){
                    $q->where("factura.modo", "=", 0)
                    ->orWhere("factura.estatus", "=", 0);
                })
                ->where("factura.id_empresa", "=", $id)
                ->orderByRaw('factura.id_factura DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function storep(Request $request)
    {
        $factura = new Factura();
        $factura->modo = 0;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->fecha_expiracion = $request->fecha_expiracion;
        $factura->estatus = 0;
        $factura->forma_pago = $request->forma_pago;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->save();

        $id = $factura->id_factura;

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
    }
    public function editarprof(Request $request)
    {

        $factura = Factura::findOrFail($request->id);
        $factura->modo = 0;
        $factura->ambiente = $request->ambiente;
        $factura->tipo_emision = $request->tipo_emision;
        $factura->fecha_emision = $request->fecha_emision;
        $factura->fecha_expiracion = $request->fecha_expiracion;
        $factura->forma_pago = $request->forma_pago;
        $factura->observacion = $request->observacion;
        $factura->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factura->subtotal_12 = $request->subtotal_12;
        $factura->subtotal_0 = $request->subtotal_0;
        $factura->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factura->descuento = $request->descuento;
        $factura->valor_ice = $request->valor_ice;
        $factura->valor_irbpnr = $request->valor_irbpnr;
        $factura->iva_12 = $request->iva_12;
        $factura->propina = $request->propina;
        $factura->valor_total = $request->valor_total;
        $factura->id_cliente = $request->id_cliente;
        $factura->id_user = $request->id_user;
        $factura->id_punto_emision = $request->id_punto_emision;
        $factura->id_empresa = $request->id_empresa;
        $factura->id_establecimiento = $request->id_establecimiento;
        $factura->save();

        $id = $factura->id_factura;
        
        DB::delete("DELETE FROM detalle WHERE id_factura=".$id);

        for ($a = 0; $a < count($request->productos); $a++) {
            $detalle = new Detalle();
            $detalle->nombre = $request->productos[$a]["nombre"];
            $detalle->cantidad = $request->productos[$a]["cantidad"];
            $detalle->precio = $request->productos[$a]["precio"];
            $detalle->descuento = $request->productos[$a]["descuento"];
            $detalle->total = ($request->productos[$a]["cantidad"] * $request->productos[$a]["precio"]) - $request->productos[$a]["descuento"];
            $detalle->iva = $request->productos[$a]["iva"];
            $detalle->ice = $request->productos[$a]["ice"];
            $detalle->id_factura = $id;
            $detalle->id_producto = $request->productos[$a]["id_producto"];
            $detalle->save();
        }
    }
    public function eliminarprof($id)
    {
        Factura::destroy($id);
        DB::delete("DELETE FROM detalle WHERE id_factura=".$id);
    }
    public function abrirprof(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM factura WHERE id_factura=' . $id);
        return $recupera;
    }
    public function abrirprofc(Request $request)
    {
        $id = $request->id;
        $recupera = Cliente::select("cliente.*", "factura.id_factura")
            ->join("factura", "factura.id_cliente", "=", "cliente.id_cliente")
            ->where("factura.id_factura", "=", $id)->get();
        return $recupera;
    }
    public function abrirprofp(Request $request)
    {
        $id = $request->id;
        $recuperap = DB::select('SELECT d.*, p.cod_principal as codigo FROM detalle d INNER JOIN producto p on d.id_producto=p.id_producto WHERE d.id_factura=' . $id);
        return $recuperap;
    }
    public function listarretenciones(){
        $rec = Retencion::all("*");
        return $rec;
    }
    public function listarpretenciones($id){
        $rec = Retencion_factura::select("*")->where("id_factura", "=", $id)->get();
        return $rec;
    }
    public function abrircreditosp($id){
        $rec = Cuentaporcobrar::select("*")->where("id_factura", "=", $id)->where("tipo", "=", 2)->get();
        return $rec[0];
    }
    public function abrirpagosp($id){
        $rec1 = Cuentaporcobrar::select("*")->where("tipo", "=", 1)->where("id_factura", "=", $id)->get();
        return $rec1;
    }
    public function traercliente($id)
    {
        
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente =".$id);
        return $cliente;
    }
}
