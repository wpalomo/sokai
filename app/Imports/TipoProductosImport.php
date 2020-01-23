<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Tipoproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class TipoProductosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            Tipoproducto::create([

                'codigo'=> $row['codigo'],
                'nombre'=> $row['nombre'],
                'utilidad'=> $row['utilidad'],
                'id_linea_producto'=>$row['id_linea_producto'],
                'id_empresa'=> 1,
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

           
            'Tipo de Producto' => new TipoProductosImport(),
        ];
    }
}
