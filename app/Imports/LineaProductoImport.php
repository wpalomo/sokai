<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Lineaproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class LineaProductoImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
        
        //linea de productos
        foreach ($rows as $row) {
            Lineaproducto::create([
                

                'codigo'=> $row['codigo'],
                'nombre'=> $row['nombre'],
                'cta_compras_iva'=> $row['cta_compras_iva'],
                'cta_compras_iva_0'=> $row['cta_compras_iva_0'],
                'cta_ventas_iva'=> $row['cta_ventas_iva'],
                'cta_ventas_iva_0'=> $row['cta_ventas_iva_0'],
                'cuenta_costo'=> $row['cuenta_costo'],
                'id_empresa'=>  1,
         
            ]);
        }
        
    }


    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

           
            'Linea de producto' => new LineaProductoImport(),
        ];
    }
}
