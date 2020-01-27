<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcesoProduccion;
use App\Models\ProcesoProducto;
use App\Models\ProcesoIngrediente;

class ProcesoProduccionController extends Controller
{

    public function index(Request $request, $id, $ide)
    {
        {
            $buscar = $request->buscar;
            if ($buscar == '') {
                //$impuestos = Impuesto::paginate($cantidadp); 
                $recupera = ProcesoProduccion::select("*")
                    ->where("proceso_produccion.id_empresa", "=", $id)
                    ->where("proceso_produccion.id_establecimento", "=", $ide)
                    ->orderByRaw('proceso_produccion.id_proceso_produccion DESC')->get();
            } else {
                $recupera = ProcesoProduccion::select("*")
                    ->where(function ($q) use ($buscar) {
                        $q->where('num_orden', 'like', '%' . $buscar . '%')
                            ->orWhere('detalle', 'like', '%' . $buscar . '%');
                    })
                    ->where("proceso_produccion.id_empresa", "=", $id)
                    ->where("proceso_produccion.id_establecimento", "=", $ide)
                    ->orderByRaw('proceso_produccion.id_proceso_produccion DESC')->get();
            }
            return [
                'recupera' => $recupera
            ];
        }
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
