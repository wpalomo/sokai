<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Producto;
use App\Models\Lineaproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class ProductosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
        //productos 
        foreach ($rows as $row) {
            Producto::create([

                'cod_principal' => $row['cod_principal'],
                'cod_alterno' => $row['cod_alterno'],
                'imagen' => $row['imagen'],
                'nombre' => $row['nombre'],
                'codigo_barras' => $row['codigo_barras'],
                'cuenta_contable' => $row['cuenta_contable'],
                'form_prod' => $row['form_prod'],
                'descripcion'=> $row['descripcion'],
                'nombrec' => $row['nombrec'],
                'sector' => $row['sector'],
                'ubicacion_fisica' => $row['ubicacion_fisica'],
                'unidad_entrada' => $row['unidad_entrada'],
                'unidad_salida' => $row['unidad_salida'],
                'vencimiento' => $row['vencimiento'],
                'existencia_maxima' => $row['existencia_maxima'],
                'existencia_minima' => $row['existencia_minima'],
                'numero_unidad' => $row['u_caja'],
                'estado' => $row['estado'],
                'vehiculo' => $row['vehiculo'],
                'placa' => $row['placa'],
                'pais_origen' => $row['pais_origen'],
                'ano_fabricacionv' => $row['ano_fabricacionv'],
                'color' => $row['color'],
                'carroceria' => $row['carroceria'],
                'combustible' => $row['combustible'],
                'motor' => $row['motor'],
                'cilindraje' => $row['cilindraje'],
                'chasis' => $row['chasis'],
                'clase' => $row['clase'],
                'subclase' => $row['subclase'],
                'numero_pasajeros' => $row['numero_pasajeros'],
                'iva' => $row['iva'],
                'ice' => $row['ice'],
                'arancel_advalorem' => $row['arancel_advalorem'],
                'arancel_especifico' => $row['arancel_especifico'],
                'arancel_fodinfa' => $row['arancel_fodinfa'],
                'comision' => $row['comision'],
                'salvaguardia' => $row['salvaguardia'],
                'pvp_precio1'=> $row['pvp_precio1'],
                'precio2'=> $row['precio2'],
                'precio3'=> $row['precio3'],
                'precio4' => $row['precio4'],
                'precio5' => $row['precio5'],
                'descuento' => $row['descuento'],
                'utilidad' => $row['utilidad'],
                'fecha_fabricacion' => $row['fecha_fabricacion'],
                'ultimo_costo' => $row['ultimo_costo'],
                'costo_promedio' => $row['costo_promedio'],
                'costo_total'=> $row['costo_total'],
                'existencia_total'=> $row['existencia_total'],
                'caracteristicas'=> $row['caracteristicas'],
                'normativa'=> $row['normativa'],
                'uso'=> $row['uso'],
                'id_linea_producto'=> $row['id_linea_producto'],
                'id_tipo_producto'=> $row['id_tipo_producto'],
                'id_marca'=> $row['id_marca'],
                'id_modelo'=> $row['id_modelo'],
                'id_presentacion'=> $row['id_presentacion'],
                'id_tipo_medida'=> $row['id_tipo_medida'],
                'id_unidad_medida'=> $row['id_unidad_medida'],
                'id_empresa'=> 1,


            ]);

            
        }
    
    }


    /*
     public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Producto::create([
                
                'cod_alterno'=> $row['cod_alterno'],
                'imagen'=> $row['imagen'],
                'nombre'=> $row['nombre'],
                'descripcion'=> $row['descripcion'],
                'caracteristicas'=> $row['caracteristicas'],
                'normativa'=> $row['normativa'],
                'uso'=> $row['uso'],
                'unidad_caja'=> $row['u_caja'],
                'pvp_precio1'=> $row['pvp_precio1'],
                'precio2'=> $row['precio2'],
                'precio3'=> $row['precio3'],
                'costo_total'=> $row['costo_total'],
                'existencia_total'=> $row['existencia_total'],
                'id_linea_producto'=> $row['id_linea_producto'],
                'id_tipo_producto'=> $row['id_tipo_producto'],
                'id_marca'=> $row['id_marca'],
                'id_modelo'=> $row['id_modelo'],
                'id_presentacion'=> $row['id_presentacion'],
                'id_tipo_medida'=> $row['id_tipo_medida'],
                'id_unidad_medida'=> $row['id_unidad_medida'],
                'id_empresa'=> 1,

            ]);
        }
        //otro
    }
    */

    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Productos' => new ProductosImport(),
           
        ];
    }
}
