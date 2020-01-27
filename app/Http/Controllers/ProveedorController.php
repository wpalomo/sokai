<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Plancuenta;
use App\Models\Banco;
use App\Models\GrupoProveedor;
use App\Models\Impuesto;
use App\Models\Tipocomprobante;

class ProveedorController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Proveedor::select("*")
                        ->where("id_empresa", "=", $id)
                        ->orderByRaw('id_proveedor DESC')
                        ->get(); 
        }else{
            $recupera = Proveedor::select("*")
                        ->where(function($q) use ($buscar){
                            $q->where('cod_proveedor','like','%'.$buscar.'%')
                            ->orWhere('nombre_proveedor','like','%'.$buscar.'%');
                        })
                        ->where("id_empresa", "=", $id)
                        ->orderByRaw('id_proveedor DESC')
                        ->get();
        } 
        return [
            'recupera' => $recupera
        ];
    }
    public function busqueda(Request $request){
        /*$buscar = $request->ctacontable;
        $busqueda=Plancuenta::select('codcta','nomcta')->where('codcta','like','%'.$buscar.'%')
        ->orWhere('nomcta','like','%'.$buscar.'%');

        return ['busqueda'=> $busqueda];*/
        $buscar = $request->ctacontable;
        $select = DB::select('SELECT codcta,nomcta FROM `plan_cuentas` WHERE codcta = '."'$buscar'");
        if($select){
            return "existe";
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$sel = DB::select("SELECT cod_proveedor FROM proveedor ORDER BY id_proveedor DESC LIMIT 1");
        $dato = $sel[0]->cod_proveedor;
        $principal ="";
        if($dato>=1){
            if(($dato+1) >= 100){
                $tot = $dato + 1; 
                $principal = "PR010".$tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "PR0100".$tot;
            }else{
                $tot = $dato + 1; 
                $principal = "PR01000".$tot;
            }
        }else{
            $principal = "PR010";
        }*/
        

            $proveedor = new Proveedor(); 
            $proveedor->cod_proveedor=$request->cod_proveedor;
            $proveedor->grupo=$request->grupo;
            $proveedor->nombre_proveedor=$request->nombre_proveedor;
            $proveedor->tipo_identificacion=$request->tipo_identificacion; 
            $proveedor->identif_proveedor=$request->identif_proveedor; 
            //$proveedor->tipo_proveedor=$request->tipo_proveedor; 
            $proveedor->contribuyente=$request->contribuyente; 
            $proveedor->beneficiario=$request->beneficiario; 
            //$proveedor->identif_benefic=$request->identif_benefic; 
            $proveedor->contacto=$request->contacto; 
            $proveedor->direccion_prov=$request->direccion_prov; 
            $proveedor->nrcasa=$request->nrcasa; 
            $proveedor->telefono_prov=$request->telefono_prov; 
            $proveedor->estado_prov=$request->estado_prov; 
            $proveedor->tipo_cuenta=$request->tipo_cuenta; 
            $proveedor->cta_banco=$request->cta_banco; 
            $proveedor->id=$request->id; 
            //$proveedor->nrcta_interbancaria=$request->nrcta_interbancaria; 
            $proveedor->pagos=$request->pagos; 
            $proveedor->plazo=$request->plazo; 
            $proveedor->dias_pago=$request->dias_pago; 
            $proveedor->tip_comprob=$request->tip_comprob; 
            $proveedor->serie=$request->serie; 
            $proveedor->fvalidez=$request->fvalidez; 
            $proveedor->comentario=$request->comentario; 
            $proveedor->rangomax=$request->rangomax; 
            $proveedor->rangomin=$request->rangomin; 
            $proveedor->contribuye_sri=$request->contribuye_sri; 
            $proveedor->tip_electronico=$request->tip_electronico; 
            $proveedor->imp_retencion=$request->imp_retencion; 
            $proveedor->codsri_imp=$request->codsri_imp; 
            $proveedor->retencion_iva=$request->retencion_iva; 
            $proveedor->codsri_iva=$request->codsri_iva; 
            $proveedor->cta_contable=$request->cta_contable; 
            $proveedor->id_provincia=$request->id_provincia; 
            $proveedor->id_ciudad=$request->id_ciudad; 
            $proveedor->id_banco=$request->id_banco;
            $proveedor->id_empresa=$request->id_empresa;
            $proveedor->save();
            $id=$proveedor->id_proveedor;
            return $id;
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
        /*
        $sel = DB::select("SELECT cod_proveedor FROM proveedor ORDER BY id_proveedor DESC LIMIT 1");
        $dato = $sel[0]->cod_proveedor;
        
        $principal ="";
        if($dato>=1){
            if(($dato+1) >= 100){
                $tot = $dato + 1; 
                $principal = "PR010".$tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "PR0100".$tot;
            }else{
                $tot = $dato + 1; 
                $principal = "PR01000".$tot;
            }
        }else{
            $principal = "PR010";
        }*/
       /* $select = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        if(!$select){
            return "existe";
        }*/
      
        $proveedor = Proveedor::findOrFail($request->id_proveedor);
            $proveedor->cod_proveedor=$request->cod_proveedor;
            $proveedor->grupo=$request->grupo;
            $proveedor->nombre_proveedor=$request->nombre_proveedor;
            $proveedor->tipo_identificacion=$request->tipo_identificacion; 
            $proveedor->identif_proveedor=$request->identif_proveedor; 
            //$proveedor->tipo_proveedor=$request->tipo_proveedor; 
            $proveedor->contribuyente=$request->contribuyente; 
            $proveedor->beneficiario=$request->beneficiario; 
            //$proveedor->identif_benefic=$request->identif_benefic; 
            $proveedor->contacto=$request->contacto; 
            $proveedor->direccion_prov=$request->direccion_prov; 
            $proveedor->nrcasa=$request->nrcasa; 
            $proveedor->telefono_prov=$request->telefono_prov; 
            $proveedor->estado_prov=1; 
            $proveedor->tipo_cuenta=$request->tipo_cuenta; 
            $proveedor->cta_banco=$request->cta_banco; 
            $proveedor->id=$request->id; 
            //$proveedor->nrcta_interbancaria=$request->nrcta_interbancaria; 
            $proveedor->pagos=$request->pagos; 
            $proveedor->plazo=$request->plazo; 
            $proveedor->dias_pago=$request->dias_pago; 
            $proveedor->tip_comprob=$request->tip_comprob; 
            $proveedor->serie=$request->serie; 
            $proveedor->fvalidez=$request->fvalidez; 
            $proveedor->comentario=$request->comentario; 
            $proveedor->rangomax=$request->rangomax; 
            $proveedor->rangomin=$request->rangomin;
            $proveedor->nrautorizacion=$request->nrautorizacion;  
            $proveedor->contribuye_sri=$request->contribuye_sri; 
            $proveedor->tip_electronico=$request->tip_electronico; 
            $proveedor->imp_retencion=$request->imp_retencion; 
            $proveedor->codsri_imp=$request->codsri_imp; 
            $proveedor->retencion_iva=$request->retencion_iva; 
            $proveedor->codsri_iva=$request->codsri_iva; 
            $proveedor->cta_contable=$request->cta_contable; 
            $proveedor->id_provincia=$request->id_provincia; 
            $proveedor->id_ciudad=$request->id_ciudad; 
            $proveedor->id_banco=$request->id_banco;
            $proveedor->save();
            
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        //$recupera = DB::select('SELECT * FROM `empresa` WHERE id_empresa='.$id);
        $recupera = Proveedor::addSelect(['cuenta_resultado' => Plancuenta::select('codcta')
        ->whereColumn('id_ctas', 'proveedor.cta_contable')
        ])
        ->where('id_proveedor', '=', $id)
        ->get();

        return $recupera;
    }
    public function eliminar ($id){
        Proveedor::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
      public function getProvincia(){
        $data=Provincia::get();

        return response()->json($data);
    }
    //public function getCiudad(Request $request)
    public function getCiudad(Request $request){
        //$data=Ciudad::get();
        $data=Ciudad::where('id_provincia','=', $request->provincia)->get();
        return response()->json($data);
    }
    public function getBanco(){
        $data=Banco::get();

        return response()->json($data);
    }
    public function getGrupo(){
        $data=GrupoProveedor::all();

          return $data;
    }
    public function getImpFuente(Request $request){
        $termino=$request->porcen_impret;
        $data = DB::select("SELECT * FROM `impuesto` WHERE `porcen_imp` = $termino AND `tipo_imp`='Fuente'");

          return $data;
    }
    public function getImpIva(Request $request){
        $termino=$request->porcen_imp;
        $data = DB::select("SELECT * FROM `impuesto` WHERE `porcen_imp` = $termino AND `tipo_imp`='Iva'");

          return $data;
    }
    public function getRetencionFuente(){
        $termino="Retencion Fuente Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '".$termino."'");

          return $data;
    }
    public function getRetencionIva(){
        $termino="Retencion IVA Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '".$termino."'");

          return $data;
    }
    public function getTipComprob(){
        $data=Tipocomprobante::all();

         return $data;
    }

    public function codigo(Request $request){
        $id = $request->id;
        $proveedor = DB::select("SELECT * FROM proveedor WHERE id_empresa = ".$id." ORDER BY id_proveedor DESC limit 1");
        if($proveedor){
            $dato = $proveedor[0]->cod_proveedor;
            $var=0;
            for($i=strlen($dato); $i>0; $i--){
                if($dato[$i-1] =='-'){
                    $var = $i;
                    break;
                }
            }
           
            $numero = substr($dato,$var)+1;
            $cod = substr($dato,0,$var);
            return $cod.$numero;
        }else{
            return "vacio";
        }

    }
}
