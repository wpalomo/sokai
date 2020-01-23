<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FacturaCompra;
use Illuminate\Http\Request;
use App\Models\Ordencompra;
use App\Models\ProductoFactura;
use App\Models\Productoorden;
use App\Models\Proveedor;
use App\Models\Detalle_factura_compra;
use App\Models\GrupoProveedor;


class OrdencompraController extends Controller
{
    //
    public function indexorden(Request $request,$id){
       /*
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Ordencompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'orden_compra.id_proveedor')
                ->where("orden_compra.id_empresa", "=", $id)
                ->orderByRaw('orden_compra.id_ordencompra DESC')->get();
        } else {
            $recupera = Ordencompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                //->join('proveedor', 'proveedor.id_proveedor', '=', 'orden_compra.id_proveedor')
                /*->where(function ($q) use ($buscar) {
                    $q->where('factura.ambiente', 'like', '%' . $buscar . '%')
                        ->orWhere('factura.tipo_emision', 'like', '%' . $buscar . '%');
                })

                ->where("orden_compra.id_empresa", "=", $id)
                ->orderByRaw('orden_compra.id_ordencompra DESC')->get();
        }*/
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = FacturaCompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
                //->where("factura_compra.id_empresa", "=", $id)
                ->where('factura_compra.modo_orden',1)
                ->where('factura_compra.id_empresa',$id)
                ->orderByRaw('factura_compra.id_factcompra DESC')->get();
        } else {
            $recupera = FacturaCompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
                /*->where(function ($q) use ($buscar) {
                    $q->where('factura.ambiente', 'like', '%' . $buscar . '%')
                        ->orWhere('factura.tipo_emision', 'like', '%' . $buscar . '%');
                })*/
                //->where("factura_compra.id_empresa", "=", $id)
                ->where('factura_compra.modo_orden',1)
                ->where('factura_compra.id_empresa',$id)
                ->where('proveedor.nombre_proveedor','like','%'.$buscar.'%')
                ->orderByRaw('factura_compra.id_factcompra DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {/*
        $ord=new Ordencompra();
        $ord->f_emision=$request->f_emision;
        $ord->f_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sinimp=$request->subtotal_sinimp;
        $ord->subtotal_iva12=$request->subtotal_iva12;
        $ord->valor_12=$request->valor_12;
        $ord->subtotal_iva0=$request->subtotal_iva0;
        $ord->sin_imp=$request->sin_imp;
        $ord->total_descuento=$request->total_descuento;
        $ord->total_orden=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_ordencompra;
        
        return $id;*/
        $sel = DB::select("SELECT orden_compra FROM factura_compra ORDER BY id_factcompra DESC LIMIT 1");
        $dato = $sel[0]->orden_compra;
        $principal ="";
        if($dato>=1){
            if(($dato+1) >= 100){
                $tot = $dato + 1; 
                $principal = $tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "0".$tot;
            }else{
                $tot = $dato + 1; 
                $principal = "00".$tot;
            }
        }else{
            $principal = 1;
        }
        $ord=new FacturaCompra();
        $ord->orden_compra=$principal;
        $ord->gasto_import=0;
        $ord->fech_emision=$request->f_emision;
        $ord->fech_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sin_impuesto=$request->subtotal_sinimp;
        $ord->subtotal_12=$request->subtotal_iva12;
        $ord->iva_12=$request->valor_12;
        $ord->subtotal_0=$request->subtotal_iva0;
        $ord->subtotal_no_obj_iva=$request->sin_imp;
        $ord->descuento=$request->total_descuento;
        $ord->total_factura=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->modo_orden=1;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_factcompra;
        return $id;
    }
    public function guardarProducto(Request $request){
        $id=$request->id_orden;
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_factura_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($request->productos[$a]["p_descuento"]==1){
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
            }else{
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
            }
            $dfactc->iva=$request->productos[$a]["iva"];
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            
            $dfactc->save();
        }
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {/*
        $ord=Ordencompra::find($request->id);
        $ord->f_emision=$request->f_emision;
        $ord->f_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sinimp=$request->subtotal_sinimp;
        $ord->subtotal_iva12=$request->subtotal_iva12;
        $ord->valor_12=$request->valor_12;
        $ord->subtotal_iva0=$request->subtotal_iva0;
        $ord->sin_imp=$request->sin_imp;
        $ord->total_descuento=$request->total_descuento;
        $ord->total_orden=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_ordencompra;
        
        return $id;*/

        $ord=FacturaCompra::find($request->id);
        $ord->gasto_import=0;
        $ord->fech_emision=$request->f_emision;
        $ord->fech_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sin_impuesto=$request->subtotal_sinimp;
        $ord->subtotal_12=$request->subtotal_iva12;
        $ord->iva_12=$request->valor_12;
        $ord->subtotal_0=$request->subtotal_iva0;
        $ord->subtotal_no_obj_iva=$request->sin_imp;
        $ord->descuento=$request->total_descuento;
        $ord->total_factura=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->modo_orden=1;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_factcompra;
        return $id;
    }
    public function actProducto(Request $request){
        $id=$request->id_orden;
        for ($a = 0; $a < count($request->productos); $a++) {
            if(!$request->productos[$a]["id_detalle_factura_compra"]){
                $dfactc = new Detalle_factura_compra();
                $dfactc->nombre = $request->productos[$a]["nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->precio = $request->productos[$a]["precio"];
                $dfactc->descuento = $request->productos[$a]["descuento"];
                $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                if($request->productos[$a]["p_descuento"]==1){
                    $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
                }else{
                    $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
                }
                $dfactc->iva=$request->productos[$a]["iva"];
                $dfactc->id_producto = $request->productos[$a]["id_producto"];
                $dfactc->id_factura = $id;
                
                $dfactc->save();
            }
            $dfactc =Detalle_factura_compra::find($request->productos[$a]["id_detalle_factura_compra"]);
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($request->productos[$a]["p_descuento"]==1){
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
            }else{
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
            }
            $dfactc->iva=$request->productos[$a]["iva"];
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            
            $dfactc->save();
        }

        return $dfactc;
            
    }
    public function abrir($id)
    {
        $recupera = FacturaCompra::select("*")
            ->addSelect(['nombre_prov' => Proveedor::select('nombre_proveedor')
            ->whereColumn('id_proveedor', 'factura_compra.id_proveedor')
        ])
        ->where('factura_compra.id_factcompra', '=', $id)->limit("1")->get();

        return $recupera;
    }
    public function traerProveedores(Request $request)
    {
        $id = $request->id;
        $recupera = Proveedor::select("proveedor.*", "factura_compra.id_factcompra")
            ->join("factura_compra", "factura_compra.id_proveedor", "=", "proveedor.id_proveedor")
            ->where("factura_compra.id_factcompra", "=", $id)->get();
        return $recupera;
    }
    public function traergrupoProvd(Request $request)
    {
        //$id = $request->id;
        $recupera = GrupoProveedor::all();
        return $recupera;
    }
    public function traerProductos(Request $request){
        $id=$request->id;
        $recuperap = DB::select('SELECT * FROM detalle_factura_compra  WHERE id_factura='.$id);
        return $recuperap;
    }
    
    public function eliminar($id){
        Detalle_factura_compra::where('id_factura','=',$id)->delete();
        FacturaCompra::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
        */
    }
}
