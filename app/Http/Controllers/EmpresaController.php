<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Plancuenta;

use App\Models\Establecimiento;
use App\Models\Ptoemision;

use Illuminate\Support\Facades\Hash;
use App\Usera;

class EmpresaController extends Controller
{
    //
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Empresa::all();
        }else{
            $recupera = Empresa::where('codigo_empresa','like','%'.$buscar.'%')
                        ->orWhere('nombre_empresa','like','%'.$buscar.'%')
                        ->orWhere('razon_social','like','%'.$buscar.'%')
                        ->orWhere('ruc_empresa','like','%'.$buscar.'%')
                        ->orWhere('telefono','like','%'.$buscar.'%')
                        ->orWhere('direccion_empresa','like','%'.$buscar.'%');
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function indexUsuario(){
        $recupera=DB::table('empresa')
        ->where('id_user',6)->get();
        return [$recupera];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sel = DB::select("SELECT codigo_empresa FROM empresa ORDER BY id_empresa DESC LIMIT 1");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->codigo_empresa;
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

        $identi= $request->identificacion_repr;
        trim($identi);
        $empresa = new Empresa();
        // $empresa->codigo_empresa=$request->codigo_empresa;
        $empresa->codigo_empresa=$principal;
        $empresa->periodo_empresa=$request->periodo_empresa;
        $empresa->nombre_empresa=$request->nombre_empresa;
        $empresa->razon_social=$request->nombre_comercial;
        $empresa->ruc_empresa=$request->ruc_empresa;
        $empresa->direccion_empresa=$request->direccion_empresa;
        $empresa->telefono=$request->telefono;
        $empresa->email_empresa=$request->email_empresa;
        $empresa->password=$request->password;
        $empresa->servidor_correo=$request->servidor_correo;
        $empresa->puerto_correo=$request->puerto_correo;
        $empresa->seguridad_correo=$request->seguridad_correo;
        $empresa->tipo_identidicacion_empresa=$request->tipo_identidicacion_empresa;
        $empresa->obligado_contabilidad=$request->contabilidad;
        $empresa->ruc_contador=$request->ruc_contador;
        $empresa->nombre_contador=$request->nombre_contador;
        $empresa->identificaion_rep=$identi;
        $empresa->nomb_representante=$request->nomb_representante;
        $empresa->clave_duracion=$request->clave_duracion;
        $empresa->periodo_inicio=$request->periodo_inicio;
        $empresa->periodo_fin=$request->periodo_fin;
        // $empresa->mascara_empresa=$request->mascara_empresa;
        $empresa->mascara_empresa="1";
        $empresa->recalculo=$request->recalculo;
        $empresa->balance=$request->balance;
        $empresa->empresa_asociada=$request->empresa_asociada;
        $empresa->noresolucion=$request->noresolucion;
        $empresa->nocontribuyente=$request->nocontribuyente;
        $empresa->codigo_entidad=$request->codigo_entidad;
        $empresa->contribuyente=$request->contribuyente;
        $empresa->tipo_ctas="Ctas Niif";
        $empresa->pass_firma=$request->password;
        //$empresa->ruta=$request->ruta;
        $empresa->fcierre=$request->fcierre;
        $empresa->fresolucion=$request->fresolucion;
        $empresa->id_moneda=$request->id_moneda;
        $empresa->id_provincia=$request->id_provincia;
        $empresa->id_ciudad=$request->id_ciudad;
        /*$empresa->cta_activo=$request->cta_activo;
        $empresa->cta_pasivo=$request->cta_pasivo;
        $empresa->cta_patrimonio=$request->cta_patrimonio;
        $empresa->cta_ingreso=$request->cta_ingreso;
        $empresa->cta_costo=$request->cta_costo;
        $empresa->cta_gasto=$request->cta_gasto;
        $empresa->cta_orden=$request->cta_orden;
        $empresa->cta_resultado=$request->cta_resultado;*/
        $empresa->tipo_emision=$request->tipoemision;
        $empresa->ambiente=$request->ambiente;
        $empresa->negativo=$request->negativo;
        $empresa->save();

        $id=$empresa->id_empresa;

        $establecimiento = new Establecimiento();
        $establecimiento->nombre = $request->nombre_empresa;
        $establecimiento->codigo = "001";
        $establecimiento->urlweb = $request->pag_web;
        $establecimiento->nombre_comercial = $request->nombre_comercial;
        $establecimiento->direccion = $request->direccion_empresa;
        $establecimiento->estado = "1";
        $establecimiento->id_empresa = $id;
        $establecimiento->save();

        $id_establecimeintos = $establecimiento->id_establecimiento;

        $emision = new Ptoemision();
        $emision->nombre = $request->nombre_empresa;
        $emision->codigo = "001";
        $emision->secuencial_factura = $request->secuencial_factura;
        $emision->secuencial_nota_credito = $request->secuencial_nota_credito;
        $emision->secuencial_nota_debito = $request->secuencial_nota_debito;
        $emision->secuencial_guia_remision = $request->secuencial_guia_remision;
        $emision->secuencial_retencion = $request->secuencial_retencion;
        $emision->secuencial_liquidacion_compra = $request->secuencial_liquidacion_compra;
        $emision->activo = $request->activo;
        $emision->id_empresa = $id;
        $emision->id_establecimiento = $id_establecimeintos;
        $emision->save();

        $id_punto_emision = $emision->id_punto_emision;

        $user = new Usera();
        $user->password = Hash::make($request->passusuario);
        $user->email = $request->emailusuario;
        $user->nombres = $request->nombreusuario;
        $user->apellidos = $request->apellidousuario;
        $user->estado = 1;
        $user->entrada = 1;
        $user->id_rol = 1;
        $user->id_empresa = $id;
        $user->id_establecimiento = $id_establecimeintos;
        $user->id_punto_emision = $id_punto_emision;
        $user->save();

        return $id;
    }
    public function guardarimagen1(Request $request){
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $carpeta ="../server/".$request->id;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 ="../server/".$request->id."/imagen";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $val1 = "../server/".$request->id."/imagen/";
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $request->file('file')->move($val1, $nombre);
        $producto = Empresa::findOrFail($request->id);
        $producto->logo = $nombre;
        $producto->save();
    }
    public function guardarimagen(Request $request){
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $carpeta ="../public/empresas/".$request->id;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 ="../public/empresas/".$request->id."/imagen";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $val = "../public/empresas/".$request->id."/imagen/";
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $request->file('file')->move($val, $nombre);
    }
    public function guardarfirma(Request $request){
        $now = new \DateTime();
        $carpeta ="../server/".$request->id;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 ="../server/".$request->id."/firma";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $val = "../server/".$request->id."/firma/";
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $request->file('file')->move($val, $nombre);
        $producto = Empresa::findOrFail($request->id);
        $producto->firma = $nombre;
        $producto->actualizacion_firma = $now->format('Y-m-d H:i:s');;
        $producto->fecha_firma = $now->format('Y-m-d H:i:s');;
        $producto->fecha_expiracion_firma = $request->fecha;
        $producto->pass_firma=$request->pass_firma;
        $producto->save();
    }
    public function guardarfirma1(Request $request){
        $carpeta ="../public/empresas/".$request->id;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 ="../public/empresas/".$request->id."/firma";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $val = "../public/empresas/".$request->id."/firma/";
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        $request->file('file')->move($val, $nombre);
    }
    public function eliminarfirma ($id){
        $recupera = DB::delete('UPDATE `empresa` SET `firma` = null WHERE `id_empresa` = '. $id);
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
        /*$identi= $request->identificacion_repr;
        trim($identi);

        $select1 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_activo."'");
        if(!$select1){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_activo
            ];
        }
        $select2 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_pasivo."'");
        if(!$select2){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_pasivo
            ];
        }
        $select3 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_patrimonio."'");
        if(!$select3){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_patrimonio
            ];
        }
        $select4 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_ingreso."'");
        if(!$select4){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_ingreso
            ];
        }
        $select5 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_costo."'");
        if(!$select5){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_costo
            ];
        }
        $select6 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_gasto."'");
        if(!$select6){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_gasto
            ];
        }
        $select7 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_orden."'");
        if(!$select7){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_orden
            ];
        }
        $select8 = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '". $request->cta_resultado."'");
        if(!$select8){
            return [
                "existe"=>"existe",
                "error"=>$request->cta_resultado
            ];
        }*/
        
        $id = $request->id;
        $id_establecimeinto = $request->id_establecimiento_id;
        $id_punto_emision = $request->id_punto_emision_id;

        $empresa = Empresa::find($request->id);
        //$empresa->codigo_empresa=$request->codigo_empresa;
        //$empresa->codigo_empresa=$principal;
        $empresa->periodo_empresa=$request->periodo_empresa;
        $empresa->nombre_empresa=$request->nombre_empresa;
        $empresa->razon_social=$request->nombre_comercial;
        $empresa->ruc_empresa=$request->ruc_empresa;
        $empresa->direccion_empresa=$request->direccion_empresa;
        $empresa->telefono=$request->telefono;
        $empresa->email_empresa=$request->email_empresa;
        $empresa->password=$request->password;
        $empresa->servidor_correo=$request->servidor_correo;
        $empresa->puerto_correo=$request->puerto_correo;
        $empresa->seguridad_correo=$request->seguridad_correo;
        $empresa->tipo_identidicacion_empresa=$request->tipo_identidicacion_empresa;
        $empresa->ruc_contador=$request->ruc_contador;
        $empresa->nombre_contador=$request->nombre_contador;
        $empresa->identificaion_rep=$request->identificacion_repr;
        $empresa->nomb_representante=$request->nomb_representante;
        $empresa->clave_duracion=$request->clave_duracion;
        $empresa->periodo_inicio=$request->periodo_inicio;
        $empresa->periodo_fin=$request->periodo_fin;
        // $empresa->mascara_empresa=$request->mascara_empresa;
        $empresa->mascara_empresa="1";
        $empresa->recalculo=$request->recalculo;
        $empresa->balance=$request->balance;
        $empresa->empresa_asociada=$request->empresa_asociada;
        $empresa->noresolucion=$request->noresolucion;
        $empresa->nocontribuyente=$request->nocontribuyente;
        $empresa->codigo_entidad=$request->codigo_entidad;
        $empresa->contribuyente=$request->contribuyente;
        $empresa->tipo_ctas="Ctas Niif";
        //$empresa->logo=$request->logo;
        //$empresa->firma=$request->firma;
        $empresa->pass_firma=$request->pass_firma;
        //$empresa->ruta=$request->ruta;
        $empresa->fcierre=$request->fcierre;
        $empresa->fresolucion=$request->fresolucion;
        $empresa->id_moneda=$request->id_moneda;
        $empresa->id_establecimiento=$request->id_establecimiento;
        $empresa->id_provincia=$request->id_provincia;
        $empresa->id_ciudad=$request->id_ciudad;
        $empresa->cta_activo=$request->cta_activo;
        $empresa->cta_pasivo=$request->cta_pasivo;
        $empresa->cta_patrimonio=$request->cta_patrimonio;
        $empresa->cta_ingreso=$request->cta_ingreso;
        $empresa->cta_costo=$request->cta_costo;
        $empresa->cta_gasto=$request->cta_gasto;
        $empresa->cta_orden=$request->cta_orden;
        $empresa->cta_resultado=$request->cta_resultado;
        $empresa->tipo_emision=$request->tipoemision;
        $empresa->ambiente=$request->ambiente;
        $empresa->negativo=$request->negativo;
        $empresa->save();


        $establecimiento = Establecimiento::find($id_establecimeinto);
        $establecimiento->nombre = $request->nombre_empresa;
        $establecimiento->urlweb = $request->pag_web;
        $establecimiento->nombre_comercial = $request->nombre_comercial;
        $establecimiento->direccion = $request->direccion_empresa;
        $establecimiento->id_empresa = $id;
        $establecimiento->save();

        $emision = Ptoemision::find($id_punto_emision);
        $emision->nombre = $request->nombre_empresa;
        $emision->secuencial_factura = $request->secuencial_factura;
        $emision->secuencial_nota_credito = $request->secuencial_nota_credito;
        $emision->secuencial_nota_debito = $request->secuencial_nota_debito;
        $emision->secuencial_guia_remision = $request->secuencial_guia_remision;
        $emision->secuencial_retencion = $request->secuencial_retencion;
        $emision->secuencial_liquidacion_compra = $request->secuencial_liquidacion_compra;
        $emision->activo = $request->activo;
        $emision->id_empresa = $id;
        $emision->id_establecimiento = $id_establecimeinto;
        $emision->save();

        $id_user = $request->id_user;

        $user = Usera::find($id_user);
        $user->email = $request->emailusuario;
        $user->nombres = $request->nombreusuario;
        $user->apellidos = $request->apellidousuario;
        $user->estado = 1;
        $user->entrada = 1;
        $user->id_rol = 1;
        $user->id_empresa = $id;
        $user->id_establecimiento = $id_establecimeinto;
        $user->id_punto_emision = $id_punto_emision;
        $user->save();

        return $id;
    }
    public function abrir($id)
    {
        $recupera = Empresa::select("empresa.*","establecimiento.id_establecimiento AS id_establecimiento_id","establecimiento.urlweb","punto_emision.secuencial_factura","punto_emision.secuencial_nota_credito", "punto_emision.id_punto_emision AS id_punto_emision_id","punto_emision.secuencial_nota_debito","punto_emision.secuencial_guia_remision","punto_emision.secuencial_retencion","punto_emision.secuencial_liquidacion_compra", "user.id as iduser", "user.nombres as nombresuser", "user.apellidos as apellidosuser", "user.email as emailuser")
            ->addSelect(['cuenta_resultado' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_resultado')
            ,'cuenta_ingreso' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_ingreso'),
            'cuenta_costo' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_costo'),
            'cuenta_activo' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_activo'),
            'cuenta_pasivo' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_pasivo'),
            'cuenta_patrimonio' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_patrimonio'),
            'cuenta_gasto' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_gasto'),
            'cuenta_orden' => Plancuenta::select('codcta')
            ->whereColumn('id_ctas', 'empresa.cta_orden'),
        ])
        ->join("establecimiento", "establecimiento.id_empresa", "=", "empresa.id_empresa")
        ->join("punto_emision", "punto_emision.id_establecimiento", "=", "establecimiento.id_establecimiento")
        ->join("user", "user.id_empresa", "=", "empresa.id_empresa")
        ->where("establecimiento.id_empresa", "=", $id)
        ->where("user.entrada", "=", 1)
        ->where('empresa.id_empresa', '=', $id)->limit("1")->get();

        return $recupera;
    }
    public function eliminar ($id){
        Empresa::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
        */
    }
    public function getMoneda(){
        $data=Moneda::all();

        return $data;
    }
    public function getProvincia(){
        $data=Provincia::all();
        return $data;
    }
    //public function getCiudad(Request $request)
    public function getCiudad(Request $request){
        //$data=Ciudad::get();
        $id=$request->id;
        $data=Ciudad::where('id_provincia','=', $request->id_provincia)->get();
        return response()->json($data);
    }
    public function getEmpresas(){
        $data=Empresa::all();
        return $data;
    }
}
