<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Parroquia;
use App\Models\Grupocliente;
use App\Models\Vendedorcliente;
use App\Models\Tipocliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Cliente::select("*")->where("id_empresa", "=", $id)->get(); 
        }else{
            $recupera = Cliente::select("*")
            ->where(function($q) use ($buscar){
                $q->where('nombre', 'like', '%' . $buscar . '%')
                ->orWhere('codigo', 'like', '%' . $buscar . '%')
                ->orWhere('identificacion', 'like', '%' . $buscar . '%')
                ->orWhere('email', 'like', '%' . $buscar . '%');
            })
            ->where("id_empresa", "=", $id)
            ->orderByRaw('id_cliente', 'desc')
            ->get();
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
        $cliente = new Cliente();
        $cliente->codigo = $request->codigo;
        $cliente->nombre = $request->nombre;
        $cliente->tipo_identificacion = $request->tipo_identificacion;
        $cliente->identificacion= $request->ruc_ci;
        $cliente->direccion= $request->direccion;
        $cliente->email= $request->email;
        $cliente->telefono = $request->telefono;
        $cliente->contacto = $request->contacto;
        $cliente->estado= $request->estado;
        $cliente->cuenta_contable= $request->cuenta_contable;
        $cliente->comentario= $request->comentario;
        $cliente->descuento= $request->descuento;
        $cliente->num_pago = $request->num_pago;
      
        $cliente->id_grupo_cliente= $request->grupo_cliente;

        
        $cliente->id_tipo_cliente= $request->tipo_cliente;
        $cliente->grupo_tributario = $request->grupo_tributario;
        $cliente->canton = $request->canton;
        $cliente->parroquia= $request->parroquia;
        $cliente->provincia= $request->provincia;
        $cliente->parte_relacionada= $request->radios1;
      
        $cliente->id_vendedor= $request->vendedor;

        $cliente->lista_precios = $request->lista_precios;
        $cliente->limite_credito= $request->limite_credito;
        $cliente->formas_pago= $request->forma_pago;
        $cliente->codigo_pais =$request->codigopais;
        $cliente->id_empresa =$request->empresa;
        $cliente->save();
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
        $cliente = Cliente::find($request->id);
        $cliente->codigo = $request->codigo;
        $cliente->nombre = $request->nombre;
        $cliente->tipo_identificacion = $request->tipo_identificacion;
        $cliente->identificacion= $request->ruc_ci;
        $cliente->direccion= $request->direccion;
        $cliente->email= $request->email;
        $cliente->telefono = $request->telefono;
        $cliente->contacto = $request->contacto;
        $cliente->estado= $request->estado;
        $cliente->cuenta_contable= $request->cuenta_contable;
        $cliente->comentario= $request->comentario;
        $cliente->descuento= $request->descuento;
        $cliente->num_pago = $request->num_pago;
        $cliente->id_grupo_cliente= $request->grupo_cliente;
        $cliente->id_tipo_cliente= $request->tipo_cliente;
        $cliente->grupo_tributario = $request->grupo_tributario;
        $cliente->canton = $request->canton;
        $cliente->parroquia= $request->parroquia;
        $cliente->provincia= $request->provincia;
        $cliente->parte_relacionada= $request->radios1;
        $cliente->id_vendedor= $request->vendedor;
        $cliente->lista_precios = $request->lista_precios;
        $cliente->limite_credito= $request->limite_credito;
        $cliente->formas_pago= $request->forma_pago;
        $cliente->codigo_pais =$request->codigopais;
        $cliente->id_empresa =$request->empresa;
        $cliente->save();
    }
    public function vercliente(Request $request)
    {
        $id = $request->id;
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente =".$id);
        return $cliente;
    }
    public function getProvincia(){
        $data=Provincia::get();

        return response()->json($data);
    }
    public function getCiudad(Request $request){
        $data=Ciudad::where('id_provincia','=', $request->id_provincia)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getParroquia(Request $request){
        $data=Parroquia::where('codigo_ciudad','=', $request->id_ciudad)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getGrupoClientes($id){
        $data=Grupocliente::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
    public function getGrupoVendedor($id){
        $data=Vendedorcliente::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
    public function getTipoCliente(){
        $data=Tipocliente::get();
        return response()->json($data);
    }
    public function verificarcliente($id){
        $cliente = DB::select("SELECT * FROM cliente WHERE id_empresa = ".$id." ORDER BY id_cliente DESC limit 1");
        if($cliente){
            $dato = $cliente[0]->codigo;
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cliente::destroy($id);
    }
}
