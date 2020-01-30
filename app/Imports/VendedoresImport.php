<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Vendedorcliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class VendedoresImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            Vendedorcliente::create([
                "codigo_vendedor"=> $row['codigo_vendedor'],
                "nombre_vendedor"=> $row['nombre_vendedor'],
                "email_vendedor"=> $row['email_vendedor'],
                "id_empresa"=> $row['id_empresa'],

                
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

            'Vendedores' => new VendedoresImport(),
           
        ];
    }
}
