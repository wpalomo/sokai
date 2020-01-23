<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
//class clienteImport implements ToModel,WithHeadingRow,WithMultipleSheets
class clienteImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{
    // use WithConditionalSheets;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    /* public function model(array $row)
    {
        return new Cliente([
            'id_cliente' => $row['id_cliente'],
            'codigo' => $row['codigo'],
            'nombre' => $row['nombre'],
            'identificacion' => $row['identificacion'],
            'direccion' => $row['direccion'],
            'email' => $row['email'],
            'telefono' => $row['telefono'],
            'contacto' => $row['contacto'],
            'estado' => $row['estado'],
            'cuenta_contable' => $row['cuenta_contable'],
            'comentario' => $row['comentario'],
            'descuento' => $row['descuento'],
            'num_pago' => $row['num_pago'],
            'tipo_identificacion' => $row['tipo_identificacion'],
            'codigo_pais' => $row['codigo_pais'],
            'grupo_tributario' => $row['grupo_tributario'],
            'canton' => $row['canton'],
            'parroquia' => $row['parroquia'],
            'provincia' => $row['provincia'],
            'parte_relacionada' => $row['parte_relacionada'],
            'lista_precios' => $row['lista_precios'],
            'limite_credito' => $row['limite_credito'],
            'formas_pago' => $row['formas_pago'],
            'id_grupo_cliente' => $row['id_grupo_cliente'],
            'id_empresa' => $row['id_empresa'],
            'id_tipo_cliente' => $row['id_tipo_cliente'],
            'id_vendedor' => $row['id_vendedor'],

        ]);
    }*/


    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row) {
            Cliente::create([
                'id_cliente' => $row['id_cliente'],
                'codigo' => $row['codigo'],
                'nombre' => $row['nombre'],
                'identificacion' => $row['identificacion'],
                'direccion' => $row['direccion'],
                'email' => $row['email'],
                'telefono' => $row['telefono'],
                'contacto' => $row['contacto'],
                'estado' => $row['estado'],
                'cuenta_contable' => $row['cuenta_contable'],
                'comentario' => $row['comentario'],
                'descuento' => $row['descuento'],
                'num_pago' => $row['num_pago'],
                'tipo_identificacion' => $row['tipo_identificacion'],
                'codigo_pais' => $row['codigo_pais'],
                'grupo_tributario' => $row['grupo_tributario'],
                'canton' => $row['canton'],
                'parroquia' => $row['parroquia'],
                'provincia' => $row['provincia'],
                'parte_relacionada' => $row['parte_relacionada'],
                'lista_precios' => $row['lista_precios'],
                'limite_credito' => $row['limite_credito'],
                'formas_pago' => $row['formas_pago'],
                'id_grupo_cliente' => $row['id_grupo_cliente'],
                'id_empresa' => $row['id_empresa'],
                'id_tipo_cliente' => $row['id_tipo_cliente'],
                'id_vendedor' => $row['id_vendedor'],

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

            'SheetJS' => new clienteImport(),
        ];
    }
}
