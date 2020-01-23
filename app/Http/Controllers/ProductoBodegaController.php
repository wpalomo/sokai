<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductoBodega;
use App\Models\Bodega;

class ProductoBodegaController extends Controller
{
    public function productos($id)
    {
        $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad")
                    ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                    ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                    ->where("bodega.id_bodega","=",$id)->get();
        $recupera1 = Bodega::select("*")
                    ->where("id_bodega","=",$id)->get();
        return [
            "bodega"=>$recupera1[0],
            "datos"=>$recupera
        ];
    }
}
