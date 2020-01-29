<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Plancuenta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class PlanCuentasImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            Plancuenta::create([

                'id_empresa'=> $row['id_empresa'],
                'codcta'=> $row['codcta'],
                'nomcta'=> $row['nomcta'],
                'id_moneda'=> $row['id_moneda'],
                'refcon'=> $row['refcon'],
                'bansel'=> $row['bansel'],
                'id_grupo'=> $row['id_grupo'],
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

            'PlanCuentas' => new PlanCuentasImport(),
           
        ];
    }
}
