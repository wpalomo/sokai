<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Presentacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class PresentacionProductosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            Presentacion::create([
                'nombre'=> $row['nombre'],
                'descripcion'=> $row['descripcion'],
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

           
            'Presentación' => new PresentacionProductosImport(),
        ];
    }
}
