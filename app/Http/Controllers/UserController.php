<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Usera;
use App\Models\Rol;
use App\Models\Roles;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Usera::addSelect([
                'nombre_rol' => Rol::select('nombre')
                    ->whereColumn('id_rol', 'user.id_rol'),
            ])->where("id_empresa", "=", $id)->orderByRaw('id DESC')->get();
        } else {
            $recupera = Usera::addSelect([
                'nombre_rol' => Rol::select('nombre')
                    ->where('nombre', 'like', '%' . $buscar . '%')
                    ->whereColumn('id_rol', 'user.id_rol'),
            ])
                ->where(function ($q) use ($buscar) {
                    $q->orWhere('nombres', 'like', '%' . $buscar . '%')
                        ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                        ->orWhere('email', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function listar($id)
    {
        $dato = Usera::select()->where("id", "=", $id)->get();
        $roles = Roles::select()->where("id_user", "=", $id)->get();
        
        return [
            "datos" => $dato[0],
            "roles" => $roles,
        ];
    }
    public function registro(Request $request)
    {
        $dato = new Usera();
        $dato->password = Hash::make($request->password);
        $dato->email = $request->email;
        $dato->nombres = $request->nombre;
        $dato->apellidos = $request->apellido;        
        $dato->estado = $request->estado;        
        $dato->id_rol = 2;
        $dato->id_empresa = $request->empresa;
        $dato->id_establecimiento = $request->establecimeinto;
        $dato->id_punto_emision = $request->punto_emision;
        $dato->save();

        $id = $dato->id;
  
        for ($i = 0; $i < count($request->items); $i++) {
            $rol = new Roles();
            $rol->nombre = $request->items[$i]["nombre"]; 
            $rol->value = $request->items[$i]["value"]; 
            $rol->ver = $request->items[$i]["ver"]; 
            $rol->editar = $request->items[$i]["editar"]; 
            $rol->crear = $request->items[$i]["crear"]; 
            $rol->eliminar = $request->items[$i]["eliminar"]; 
            $rol->id_user = $id;
            $rol->save();
        }
    }
    public function editar(Request $request)
    {
        $dato = Usera::findOrFail($request->id);
        if (strlen($request->password) >= 1) {
            $dato->password = Hash::make($request->password);
        }
        $dato->email = $request->email;
        $dato->nombres = $request->nombre;
        $dato->apellidos = $request->apellido;
        $dato->estado = $request->estado;
        $dato->id_rol = $request->rol;
        $dato->id_empresa = $request->empresa;
        $dato->id_establecimiento = $request->establecimeinto;
        $dato->id_punto_emision = $request->punto_emision;
        $dato->save();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Usera::destroy($id);
    }
}
