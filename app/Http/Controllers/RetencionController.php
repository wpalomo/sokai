<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retencion;
use Illuminate\Support\Facades\DB;
use App\Models\Moneda;
use App\Models\Impuesto;

class RetencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        /*$empresa = Emisor::all();
        return $empresa;*/
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Retencion::select('*')->addSelect(['cod_imp' => Impuesto::select('cod_imp')
                        ->whereColumn('id_imp', 'retencion.id_impuesto'),
                    ])
                    ->where("id_empresa","=",$id)
                    ->orderByRaw('retencion.id_retencion DESC')->get();
        }else{
            $recupera = Retencion::select('*')->addSelect(['cod_imp' => Impuesto::select('cod_imp')
                        ->whereColumn('id_imp', 'retencion.id_impuesto'),
                    ])
                    ->where(function($q) use ($buscar){
                        $q->orWhere('cod_imp', 'like', '%' . $buscar . '%')
                        ->orWhere('descrip_imp', 'like', '%' . $buscar . '%')
                        ->orWhere('tipo_imp', 'like', '%' . $buscar . '%')
                        ->orWhere('porcen_imp', 'like', '%' . $buscar . '%');
                    })
                    ->where("id_empresa","=",$id)
                    ->orderByRaw('retencion.id_retencion DESC')->get();
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
     * Retencion
     */
    public function store(Request $request)
    {
        $retencion=new Retencion();
        $retencion->cod_retencion=$request->cod_retencion;
        $retencion->descrip_retencion=$request->descrip_retencion;
        $retencion->porcen_retencion=$request->porcen_retencion;
        $retencion->tipo_retencion=$request->tipo_retencion;
        $retencion->tipoiva_retencion=$request->tipoiva_retencion;
        $retencion->cta_contable=$request->cta_contable;
        $retencion->id_moneda=$request->id_moneda;
        $retencion->id_impuesto=$request->id_impuesto;
        $retencion->id_empresa=$request->id_empresa;
        $retencion->save();

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
        $retencion=Retencion::findOrFail($request->id);
        $retencion->cod_retencion=$request->cod_retencion;
        $retencion->descrip_retencion=$request->descrip_retencion;
        $retencion->porcen_retencion=$request->porcen_retencion;
        $retencion->tipo_retencion=$request->tipo_retencion;
        $retencion->tipoiva_retencion=$request->tipoiva_retencion;
        $retencion->cta_contable=$request->cta_contable;
        $retencion->id_moneda=$request->id_moneda;
        $retencion->id_impuesto=$request->id_impuesto;
        $retencion->id_empresa=$request->id_empresa;
        $retencion->save();
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `retencion` WHERE id_retencion='.$id);
        /*$recupera = Retencion::addSelect(['codigo_impuesto' => Impuesto::select('cod_imp')
        ->whereColumn('id_imp', 'retencion.id_impuesto')])
        ->where('id_retencion','=',$id);*/
        return $recupera;
    }
    public function eliminar ($id){
        Retencion::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
      public function getMoneda(){
        $data=Moneda::all();

        return $data;
    }
    public function getImpuesto(Request $request){
        $id=$request->porcen_imp;
        //$data=Impuesto::where('porcen_imp','=', 8.00)->get();
        //return response()->json($data);
        $recupera = DB::select('SELECT * FROM `impuesto` WHERE porcen_imp='.$id);
        return $recupera;
    }
}
