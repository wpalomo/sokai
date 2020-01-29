<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcesoProduccion;
use App\Models\ProcesoProducto;
use App\Models\ProcesoIngrediente;
use App\Models\FormulaProduccion;
use App\Models\FormulaProducto;
use App\Models\FormulaIngrediente;
use App\Models\Bodega;
use App\Models\Establecimiento;
use App\Models\ProductoBodega;
use App\Models\Producto;
use PhpParser\Node\Stmt\Return_;

class ProcesoOrdenController extends Controller
{

    public function getingred(Request $request)
    {
        $ide = $request->establecimiento;
        $idp = $request->pr;
        $formula = DB::select("SELECT id_formula_produccion FROM formula_producto INNER JOIN producto ON producto.id_producto = formula_producto.id_producto WHERE producto.id_producto =". $idp);
        $formula = $formula[0]->id_formula_produccion;
        $ingreds = FormulaIngrediente::select("formula_ingrediente.*", "producto.nombre", "bodega.nombre as nombre_bodega", "producto_bodega.cantidad as stock", "producto_bodega.cantidad as saldo", "formula_ingrediente.cant_unit_prod as canti")
        ->join("producto", "producto.id_producto", "=", "formula_ingrediente.id_producto")
        ->join("producto_bodega", "producto_bodega.id_producto", "=", "producto.id_producto")
        ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
        ->where("formula_ingrediente.id_formula_produccion", "=", $formula)
        ->where("bodega.id_establecimiento", "=", $ide)
        ->get();
        return $ingreds;

        /*$ide = $request->id_establecimeinto;
        $idp = $request->pr;
        $ingreds="";
        for($i = 0; $i<count($ide); $i++){
            $formula = DB::select("SELECT id_formula_produccion FROM formula_producto INNER JOIN producto ON producto.id_producto = formula_producto.id_producto WHERE producto.id_producto =". $idp[$i]["id"]);
            $formula = $formula[0]->id_formula_produccion;
            $ingreds .= FormulaIngrediente::select("formula_ingrediente.*", "producto.nombre", "bodega.nombre as nombre_bodega")
            ->join("producto", "producto.id_producto", "=", "formula_ingrediente.id_producto")
            ->join("producto_bodega", "producto_bodega.id_producto", "=", "producto.id_producto")
            ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
            ->where("formula_ingrediente.id_formula_produccion", "=", $formula)
            ->where("bodega.id_establecimiento", "=", $ide[$i])
            ->get();
        }
        return [
            "datos" => $ingreds,
        ];*/
    }


    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
