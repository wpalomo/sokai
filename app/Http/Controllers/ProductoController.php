<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Producto_bodega;
use Illuminate\Support\Facades\Hash;
use App\Models\Campo_adicional;
use App\Models\FormulaProducto;

class ProductoController extends Controller
{
    public function index(Request $request, $id)
    {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        } else {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
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
    public function guardar(Request $request)
    {

        /*$sel = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '" . $request->cta_prod . "'");
        if (!$sel) {
            return "cuentamal";
        }*/

        $sel = DB::select("SELECT cod_principal FROM `producto` ORDER BY id_producto DESC LIMIT 1");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->cod_principal;
            if (($dato + 1) >= 100) {
                $tot = $dato + 1;
                $principal = $tot;
            } else if (($dato + 1) >= 10) {
                $tot = $dato + 1;
                $principal = "0" . $tot;
            } else {
                $tot = $dato + 1;
                $principal = "00" . $tot;
            }
        } else {
            $principal = "001";
        }
        $datal = "";
        //datos adicionales
        if (count($request->agregados) >= 1) {
            for ($i = 0; $i < count($request->agregados); $i++) {
                $datal .= $request->agregados[$i]['descripcion'] . "||";
            }
            $datof = substr($datal, 0, -1);
        } else {
            $datof = "";
        }
        $producto = new Producto();
        //Agregar Producto 
        $producto->cod_principal = $principal;
        $producto->cod_alterno = $request->cod_alterno;

        $producto->nombre = $request->nombre;
        $producto->codigo_barras = $request->cod_barras;
        $producto->descripcion = $request->descripcion;
        $producto->caracteristicas = $request->caracteristicas;
        $producto->normativa = $request->normativa;
        $producto->uso = $request->uso;
        // Campos adicionales
        $producto->cuenta_contable = $request->cta_prod;
        $producto->form_prod = $request->form_prod;
        $producto->nombrec = $datof;
        //implode(";", $_POST["pdentales"]);
        //$producto->contenido = $request->contenido;
        //Línea de Producto: 
        //Línea de Producto: fk
        $producto->id_linea_producto = $request->linea_producto;
        //tipo_producto fk listar
        $producto->id_tipo_producto = $request->tipo_producto;
        $producto->id_marca = $request->marca;
        $producto->id_modelo = $request->modelo;
        $producto->id_presentacion = $request->presentacion;
        //$producto->id_bodega = $request->bodega;
        $producto->sector = $request->sector;
        $producto->tipo_servicio = $request->tipo_servicio;
        $producto->unidad_entrada = $request->unidad_entrada;
        $producto->unidad_salida = $request->unidad_salida;
        $producto->vencimiento = $request->vencimiento;
        $producto->existencia_maxima = $request->existencia_max;
        $producto->existencia_minima = $request->existencia_min;
        //Dimensiones del Producto:
        $producto->id_tipo_medida = $request->tipo_medida;
        $producto->id_unidad_medida = $request->unidad_medida;
        $producto->numero_unidad = $request->numero_unidad;
        $producto->estado = 1;
        //VEHICULO 
        $producto->vehiculo = $request->vehiculo;
        $producto->placa = $request->placa;
        $producto->pais_origen = $request->pais_origen;
        $producto->ano_fabricacionv = $request->ano_fabricacion;
        $producto->color = $request->color;
        $producto->carroceria = $request->carroceria;
        $producto->combustible = $request->combustible;
        $producto->motor = $request->motor;
        $producto->cilindraje = $request->cilindraje;
        $producto->chasis = $request->chasis;
        $producto->clase = $request->clase;
        $producto->subclase = $request->subclase;
        $producto->numero_pasajeros = $request->numero_pasajeros;
        //Rubros del Producto:
        $producto->iva = $request->iva;
        $producto->ice = $request->ice;
        $producto->arancel_advalorem = $request->arancel_advalorem;
        $producto->arancel_especifico = $request->arancel_especifico;
        $producto->arancel_fodinfa = $request->arancel_fodinfa;
        $producto->comision = $request->comision;
        $producto->salvaguardia = $request->salvaguardia;
        $producto->pvp_precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        $producto->precio5 = $request->precio5;
        $producto->descuento = $request->descuento;
        $producto->utilidad = $request->utilidad;
        $producto->fecha_fabricacion = $request->fecha_fabricacion;
        $producto->ultimo_costo = $request->ultimo_costo;
        $producto->costo_promedio = $request->costo_promedio;
        $producto->costo_total = $request->costo_total;
        $producto->existencia_total = $request->existencia_total;
        $producto->id_empresa = $request->id_empresa;
        $producto->save();
        $id = $producto->id_producto;
        if ($request->id_rol == 1) {
            if (count($request->agregados) >= 1) {
                for ($d = 0; $d < count($request->agregados); $d++) {
                    if (!empty($request->agregados[$d]['nombre'])) {
                        $productob = new Campo_adicional();
                        $productob->nombre = $request->agregados[$d]['nombre'];
                        $productob->id_empresa = 1;
                        $productob->save();
                    }
                }
            }
        }

        if ($request->form_prod) {
            $form_prod = new FormulaProducto;
            $form_prod->id_producto = $id;
            $form_prod->id_formula_produccion = $request->id_formu_prod;
            $form_prod->save();
        }
    }

    public function guardarimagen(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file');
        //obtenemos el nombre del archivo
        $nombre = $file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        //\Storage::disk('local')->put($nombre,  \File::get($file));
        //Image::make('image')->save('public/images/productos/'.$filename);
        $file->move(public_path() . '/images/productos/', $nombre);

        $producto = Producto::findOrFail($request->id);
        $producto->imagen = $nombre;
        $producto->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $datal = "";
        //datos adicionales
        if (count($request->agregados) >= 1) {
            for ($i = 0; $i < count($request->agregados); $i++) {
                $datal .= $request->agregados[$i]['descripcion'] . "||";
            }
            $datof = substr($datal, 0, -1);
        } else {
            $datof = "";
        }

        /*$sel = DB::select("SELECT `codcta` FROM `plan_cuentas` WHERE `codcta` = '" . $request->cta_prod . "'");
        if (!$sel) {
            return "cuentamal";
        }*/

        $producto = Producto::findOrFail($request->id);
        //Agregar Producto 
        $producto->cod_principal = $request->cod_principal;
        $producto->cod_alterno = $request->cod_alterno;
        $producto->nombre = $request->nombre;
        $producto->codigo_barras = $request->cod_barras;
        $producto->descripcion = $request->descripcion;
        $producto->caracteristicas = $request->caracteristicas;
        $producto->normativa = $request->normativa;
        $producto->uso = $request->uso;
        // Campos adicionales
        $producto->cuenta_contable = $request->cta_prod;
        $producto->form_prod = $request->form_prod;
        $producto->nombrec = $datof;
        //implode(";", $_POST["pdentales"]);
        //$producto->contenido = $request->contenido;
        //Línea de Producto: 
        //Línea de Producto: fk
        $producto->id_linea_producto = $request->linea_producto;
        //tipo_producto fk listar
        $producto->id_tipo_producto = $request->tipo_producto;
        $producto->id_marca = $request->marca;
        $producto->id_modelo = $request->modelo;
        $producto->id_presentacion = $request->presentacion;
        //$producto->id_bodega = $request->bodega;
        $producto->sector = $request->sector;
        $producto->tipo_servicio = $request->tipo_servicio;
        $producto->unidad_entrada = $request->unidad_entrada;
        $producto->unidad_salida = $request->unidad_salida;
        $producto->vencimiento = $request->vencimiento;
        $producto->existencia_maxima = $request->existencia_max;
        $producto->existencia_minima = $request->existencia_min;
        //Dimensiones del Producto:
        $producto->id_tipo_medida = $request->tipo_medida;
        $producto->id_unidad_medida = $request->unidad_medida;
        $producto->numero_unidad = $request->numero_unidad;
        $producto->estado = 1;
        //VEHICULO 
        $producto->vehiculo = $request->vehiculo;
        $producto->placa = $request->placa;
        $producto->pais_origen = $request->pais_origen;
        $producto->ano_fabricacionv = $request->ano_fabricacion;
        $producto->color = $request->color;
        $producto->carroceria = $request->carroceria;
        $producto->combustible = $request->combustible;
        $producto->motor = $request->motor;
        $producto->cilindraje = $request->cilindraje;
        $producto->chasis = $request->chasis;
        $producto->clase = $request->clase;
        $producto->subclase = $request->subclase;
        $producto->numero_pasajeros = $request->numero_pasajeros;
        //Rubros del Producto:
        $producto->iva = $request->iva;
        $producto->ice = $request->ice;
        $producto->arancel_advalorem = $request->arancel_advalorem;
        $producto->arancel_especifico = $request->arancel_especifico;
        $producto->arancel_fodinfa = $request->arancel_fodinfa;
        $producto->comision = $request->comision;
        $producto->salvaguardia = $request->salvaguardia;
        $producto->pvp_precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        $producto->precio5 = $request->precio5;
        $producto->descuento = $request->descuento;
        $producto->utilidad = $request->utilidad;
        $producto->fecha_fabricacion = $request->fecha_fabricacion;
        $producto->ultimo_costo = $request->ultimo_costo;
        $producto->costo_promedio = $request->costo_promedio;
        $producto->costo_total = $request->costo_total;
        $producto->existencia_total = $request->existencia_total;
        $producto->id_empresa = $request->id_empresa;
        $producto->save();
        $id = $producto->id_producto;

        //$recupera = DB::delete('DELETE FROM producto_bodega where id_producto = ' . $id);

        if ($request->id_rol == 1) {
            if (count($request->agregados) >= 1) {
                for ($d = 0; $d < count($request->agregados); $d++) {
                    if (!empty($request->agregados[$d]['nombre'])) {
                        $productob = new Campo_adicional();
                        $productob->nombre = $request->agregados[$d]['nombre'];
                        $productob->id_empresa = 1;
                        $productob->save();
                    }
                }
            }
        }

        if ($request->form_prod) {
            DB::delete("DELETE FROM formula_producto where id_producto = $id");
            $form_prod = new FormulaProducto;
            $form_prod->id_producto = $id;
            $form_prod->id_formula_produccion = $request->id_formu_prod;
            $form_prod->save();
        }
    }
    public function eliminar($id)
    {
        $recupera = DB::delete('DELETE FROM campo_adicional where id_empresa = 1');
        //  $recupera = DB::delete('DELETE FROM producto_bodega where id_producto = '. $id);
        Producto::destroy($id);
        /*$id = $request->id;
     $eliminar = Producto::find($request->id);
      $eliminar->delete();
*/
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM producto WHERE id_producto=' . $id);
        // $prbd = DB::select('SELECT * FROM producto_bodega WHERE id_producto =' . $id);
        return [
            "recupera" => $recupera,
            //"bodega" => $prbd
        ];
    }

    public function camposadicionales(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('select * from campo_adicional where id_empresa = 1');
        return $recupera;
    }
}
