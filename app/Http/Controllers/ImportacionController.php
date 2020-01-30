<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Importacion;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\ProductoFactura;
use App\Models\Proveedorimportacion;

class ImportacionController extends Controller
{
   //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Importacion::select('importacion.*')
            //->join('factur','producto.id_producto','=','importacion.id_producto')
            ->where('id_punto_emision',$id)
            ->orderByRaw('importacion.id_importacion ASC')->get();
        }else{
            $recupera = Importacion::select('importacion.*')
            //->join('producto','producto.id_producto','=','importacion.id_producto')
            //->where('producto.nombre','like','%'.$buscar.'%')
            ->orderByRaw('importacion.id_importacion ASC')->get();
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
    {
        $sel = DB::select("SELECT cod_importacion FROM importacion ORDER BY id_importacion DESC LIMIT 1");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->cod_importacion;
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
        } else {
            $principal = "001";
        }
        // `cod_importacion`, `estado`, `periodo_inicio`, `periodo_fin`, `fech_embarque`, `fech_arribo`, `total_facturas`, `total_liquidacion`, `total_importacion`, `id_proveedor`, `id_orden`, `id_user`, `id_empresa`, `id_punto_emision`
        $import =new Importacion();
        $import->cod_importacion=$principal;
        $import->estado="Inicial";
        $import->periodo_inicio=$request->periodo_inicio;
        $import->periodo_fin=$request->periodo_fin;
        $import->fech_embarque=$request->fech_embarque;
        $import->fech_arribo=$request->fech_arribo;
        $import->total_importacion=$request->total_importacion;
        $import->id_proveedor=$request->id_proveedor;
        $import->id_orden=$request->nro_orden;
        $import->id_user=$request->id_user;
        $import->id_empresa=$request->id_empresa;
        $import->id_punto_emision=$request->id_punto_emision;
        $import->save();
        $id=$import->id_importacion;
        return $id;
    }
    public function guardarProv(Request $request){
        $id=$request->id_import;
        for ($a = 0; $a < count($request->provds); $a++) {
            if($request->provds[$a]["nombre"]!=null){
                $dfactc = new Proveedorimportacion();
            $dfactc->nombre = $request->provds[$a]["nombre"];
            $dfactc->telefono = $request->provds[$a]["telefono"];
            $dfactc->grupo = $request->provds[$a]["grupo"];
            $dfactc->tipo_identificacion = $request->provds[$a]["tipo_identificacion"];
            $ident=trim($request->provds[$a]["identificacion"]);
            $dfactc->identificacion = $ident;
            $dfactc->direccion = $request->provds[$a]["direccion"];
            $dfactc->id_proveedor = $request->provds[$a]["id_proveedor"];
            $dfactc->id_importacion = $id;
            $dfactc->id_empresa=$request->id_empresa;
            $dfactc->save();
            }

        }
        return $dfactc;
    }
    public function guardarProd(Request $request){
        $id=$request->id_import;
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new ProductoFactura();
            $dfactc->codigo = $request->productos[$a]["codigo"];
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
            $dfactc->id_importacion = $id;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            
            $dfactc->save();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $import =Importacion::findOrFail($request->id);
        //$import->estado=$request->estado;
        $import->periodo_inicio=$request->periodo_inicio;
        $import->periodo_fin=$request->periodo_fin;
        $import->fech_embarque=$request->fech_embarque;
        $import->fech_arribo=$request->fech_arribo;
        $import->total_importacion=$request->total_importacion;
        $import->id_proveedor=$request->id_proveedor;
        $import->id_orden=$request->nro_orden;
        //$import->id_user=$request->id_user;
        //$import->id_empresa=$request->id_empresa;
        //$import->id_punto_emision=$request->id_punto_emision;
        //$import->id_producto=$request->id_producto;
        $import->save();
        $id=$import->id_importacion;
        return $id;
    }
    public function actProducto(Request $request){
        $id=$request->id_orden;
        for ($a = 0; $a < count($request->productos); $a++) {
            if(!$request->productos[$a]["id_prodimp"]){
                $dfactc = new ProductoFactura();
                $dfactc->codigo = $request->productos[$a]["codigo"];
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
            $dfactc->id_importacion = $id;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];

            $dfactc->save();
            }
            $dfactc =ProductoFactura::find($request->productos[$a]["id_prodimp"]);
            $dfactc->codigo = $request->productos[$a]["codigo"];
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
            $dfactc->id_importacion = $id;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            
            $dfactc->save();
        }

        return $dfactc;
            
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `importacion` WHERE id_importacion='.$id);
        return $recupera;
    }
    public function abrirProducto(Request $request)
    {
        $id=$request->id;
        $recuperap = DB::select('SELECT * FROM `producto_importacion` WHERE id_importacion ='.$id);
        return $recuperap;
    }
    public function abrirProvedor(Request $request)
    {
        $id=$request->id;
        $recuperap = DB::select('SELECT * FROM `proveedor_importacion` WHERE id_importacion ='.$id);
        return $recuperap;
    }
    public function traerProvedor($id)
    {
        
        $recuperap = DB::select('SELECT * FROM `proveedor` WHERE id_proveedor ='.$id);
        return $recuperap;
    }
    public function eliminar ($id){
        ProductoFactura::where('id_importacion',$id)->delete();
        Proveedorimportacion::where('id_importacion',$id)->delete();
        Importacion::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
      public function getProveedor($id){
        //$data=Proveedor::all();
        $data = DB::select('SELECT * FROM `proveedor` WHERE id_empresa = '.$id);
        return $data;
      }
      public function getProducto($id){
        $data = DB::select('SELECT * FROM `producto` WHERE id_empresa = '.$id);

        return $data;
      }
      public function getOrden($id){
        $data = DB::select('SELECT * FROM `factura_compra` WHERE modo_orden = 1 AND id_empresa ='.$id);

        return $data;
      }
}
