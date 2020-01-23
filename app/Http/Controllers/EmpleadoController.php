<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\Empleado_Cargo;
use App\Models\Empleado_Cargas;
use App\Models\Empleado_Documento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Parroquia;
use App\Models\Banco;
use App\Models\Nacionalidad;

class EmpleadoController extends Controller
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
            $recupera = Empleado::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_empleado DESC')->get();
        } else {
            $recupera = Empleado::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('primer_nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
                    ->orWhere('segundo_nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_empleado DESC')->get();
        }
        return ['recupera' => $recupera];
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
        $empleado = new Empleado();

        $empleado->tipo_dni = $request->tipo_dni;
        $empleado->dni = $request->dni;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->segundo_nombre = $request->segundo_nombre;
        $empleado->apellido_paterno = $request->apellido_paterno;
        $empleado->apellido_materno = $request->apellido_materno;
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->edad = $request->edad;
        $empleado->foto = $request->foto;
        $empleado->lugar_nacimiento = $request->lugar_nacimiento;
        $empleado->nacionalidad = $request->nacionalidad;
        $empleado->estado_civil = $request->estado_civil;
        $empleado->sexo = $request->sexo;
        $empleado->direccion_residencia = $request->direccion_residencia;
        $empleado->telefono = $request->telefono;
        $empleado->celular = $request->celular;
        $empleado->email = $request->email;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->profesion = $request->profesion;
        $empleado->discapacidad = $request->discapacidad;
        $empleado->discap_porcentaje = $request->discap_porcentaje;
        $empleado->tipo_iden_discap = $request->tipo_iden_discap;
        $empleado->num_iden_discap = $request->num_iden_discap;
        $empleado->num_iess = $request->num_iess;
        $empleado->num_libreta_militar = $request->num_libreta_militar;
        $empleado->banco = $request->banco;
        $empleado->tipo_cuenta = $request->tipo_cuenta;
        $empleado->num_cuenta = $request->num_cuenta;
        $empleado->num_cargas = $request->num_cargas;
        $empleado->estado = $request->estado;
        $empleado->observaciones = $request->observaciones;
        $empleado->id_empresa = $request->id_empresa;
        $empleado->save();
        $empleado->id_empleado;

        if (($empleado->id_empleado) != null) {
            $cargo = new Empleado_Cargo();
            $cargo->fecha_ingreso = $request->fecha_ingreso;
            $cargo->fecha_salida = $request->fecha_salida;
            $cargo->tipo_horario = $request->tipo_horario;
            $cargo->tipo_contrato = $request->tipo_contrato;
            $cargo->bonos = $request->bonos;
            $cargo->aporte_iess = $request->aporte_iess;
            $cargo->fondo_reserva = $request->fondo_reserva;
            $cargo->decimo_tercero = $request->decimo_tercero;
            $cargo->decimo_cuarto = $request->decimo_cuarto;
            $cargo->cuenta_contable = $request->cuenta_contable;
            $cargo->observaciones = $request->observaciones_dos;
            $cargo->id_empleado = $empleado->id_empleado;
            $cargo->idcargo = $request->idcargo;
            $cargo->idgrupo = $request->idgrupo;
            $cargo->save();

            $carga = new Empleado_Cargas();
            $carga->car_dni = $request->car_dni;
            $carga->car_nombre = $request->car_nombre;
            $carga->car_fecha_nacimiento = $request->car_fecha_nacimiento;
            $carga->car_edad = $request->car_edad;
            $carga->car_ocupacion = $request->car_ocupacion;
            $carga->car_parentezco = $request->car_parentezco;
            $carga->car_discapacidad = $request->car_discapacidad;
            $carga->car_discap_porcentaje = $request->car_discap_porcentaje;
            $carga->idempleado = $empleado->id_empleado;
            $carga->save();

            $docu = new Empleado_Documento();
            $docu->doc_url = $request->doc_url;
            $docu->doc_estado = $request->doc_estado;
            $docu->iddocu = $request->iddocu;
            $docu->idemple = $empleado->id_empleado;
            $docu->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $empleado = Empleado::find($request->id);
        $empleado->tipo_dni = $request->tipo_dni;
        $empleado->dni = $request->dni;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->segundo_nombre = $request->segundo_nombre;
        $empleado->apellido_paterno = $request->apellido_paterno;
        $empleado->apellido_materno = $request->apellido_materno;
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->edad = $request->edad;
        $empleado->foto = $request->foto;
        $empleado->lugar_nacimiento = $request->lugar_nacimiento;
        $empleado->nacionalidad = $request->nacionalidad;
        $empleado->estado_civil = $request->estado_civil;
        $empleado->sexo = $request->sexo;
        $empleado->direccion_residencia = $request->direccion_residencia;
        $empleado->telefono = $request->telefono;
        $empleado->celular = $request->celular;
        $empleado->email = $request->email;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->profesion = $request->profesion;
        $empleado->discapacidad = $request->discapacidad;
        $empleado->discap_porcentaje = $request->discap_porcentaje;
        $empleado->tipo_iden_discap = $request->tipo_iden_discap;
        $empleado->num_iden_discap = $request->num_iden_discap;
        $empleado->num_iess = $request->num_iess;
        $empleado->num_libreta_militar = $request->num_libreta_militar;
        $empleado->banco = $request->banco;
        $empleado->tipo_cuenta = $request->tipo_cuenta;
        $empleado->num_cuenta = $request->num_cuenta;
        $empleado->num_cargas = $request->num_cargas;
        $empleado->estado = $request->estado;
        $empleado->observaciones = $request->observaciones;
        $empleado->id_empresa = $request->id_empresa;
        $empleado->save();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        //
        Empleado::destroy($id);
    }
    public function getProvincia()
    {
        $data = Provincia::get();
        return response()->json($data);
    }
    public function getCiudad(Request $request)
    {
        $data = Ciudad::where('id_provincia', '=', $request->id_provincia)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getParroquia(Request $request)
    {
        $data = Parroquia::where('codigo_ciudad', '=', $request->id_ciudad)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getBanco()
    {
        $data = Banco::get();
        return response()->json($data);
    }
    public function getNacionalidad()
    {
        $data = Nacionalidad::get();

        return response()->json($data);
    }
    public function verEmpleado(Request $request)
    {
        /*
        $id = $request->id;

        $empleado = DB::select('SELECT * FROM empleado WHERE id_empleado =' . $id);
        return $empleado;
        */
        $id = $request->id;
        $id_empresa = $request->id_empresa;
        $empleado = DB::table('empleado')
            ->join('empleado_cargo', 'empleado.id_empleado', '=', 'empleado_cargo.id_empleado')
            ->join('cargas_empleado', 'empleado.id_empleado', '=', 'cargas_empleado.idempleado')
            ->join('docu_empleado', 'empleado.id_empleado', '=', 'docu_empleado.idemple')
            ->where('empleado.id_empleado', '=', $id)
            ->where('empleado.id_empresa', '=', $id_empresa)
            ->get();
        return $empleado;
    }
    public function listarInfoEmp(Request $request)
    {
        $info = DB::table('empleado')
            ->join('empleado_cargo', 'empleado.id_empleado', '=', 'empleado_cargo.id_empleado')
            //->join('cargas_empleado', 'empleado.id_empleado', '=', 'cargas_empleado.idempleado')
            ->select('empleado.*', 'empleado_cargo.*')
            ->where('empleado.id_empleado', '=', $request->id)
            ->get();
        return $info;
    }
}
