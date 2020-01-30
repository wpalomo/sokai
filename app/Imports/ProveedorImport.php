<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProveedorImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    /*
    public function model(array $row)
    {
        return new Proveedor([
        'id_proveedor'=> $row['id_proveedor'],
        'cod_proveedor'=> $row['cod_proveedor'],
        'grupo'=> $row['grupo'],
        'nombre_proveedor'=> $row['nombre_proveedor'],
        'tipo_identificacion'=> $row['tipo_identificacion'],
        'identif_proveedor'=> $row['identif_proveedor'],
        'contribuyente'=> $row['contribuyente'],
        'beneficiario'=> $row['beneficiario'],
        'contacto'=> $row['contacto'],
        'direccion_prov'=> $row['direccion_prov'],
        'nrcasa'=> $row['nrcasa'],
        'telefono_prov'=> $row['telefono_prov'],
        'estado_prov'=> $row['estado_prov'],
        'tipo_cuenta'=> $row['tipo_cuenta'],
        'cta_banco'=> $row['cta_banco'],
        'id'=> $row['id'],
        'pagos'=> $row['pagos'],
        'plazo'=> $row['plazo'],
        'dias_pago'=> $row['dias_pago'],
        'tip_comprob'=> $row['tip_comprob'],
        'serie'=> $row['serie'],
        'fvalidez'=> $row['fvalidez'],
        'comentario'=> $row['comentario'],
        'rangomax'=> $row['rangomax'],
        'rangomin'=> $row['rangomin'],
        'nrautorizacion'=> $row['nrautorizacion'],
        'contribuye_sri'=> $row['contribuye_sri'],
        'tip_electronico'=> $row['tip_electronico'],
        'imp_retencion'=> $row['imp_retencion'],
        'codsri_imp'=> $row['codsri_imp'],
        'retencion_iva'=> $row['retencion_iva'],
        'codsri_iva'=> $row['codsri_iva'],
        'cta_contable'=> $row['cta_contable'],
        'id_provincia'=> $row['id_provincia'],
        'id_ciudad'=> $row['id_ciudad'],
        'id_banco'=> $row['id_banco'],
        'id_empresa'=> $row['id_empresa'],
        
        ]);
    }
*/
    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            Proveedor::create([

                
                'cod_proveedor'=> $row['cod_proveedor'],
                'grupo'=> $row['grupo'],
                'nombre_proveedor'=> $row['nombre_proveedor'],
                'tipo_identificacion'=> $row['tipo_identificacion'],
                'identif_proveedor'=> $row['identif_proveedor'],
                'contribuyente'=> $row['contribuyente'],
                'beneficiario'=> $row['beneficiario'],
                'contacto'=> $row['contacto'],
                'direccion_prov'=> $row['direccion_prov'],
                'nrcasa'=> $row['nrcasa'],
                'telefono_prov'=> $row['telefono_prov'],
                'estado_prov'=> $row['estado_prov'],
                'tipo_cuenta'=> $row['tipo_cuenta'],
                'cta_banco'=> $row['cta_banco'],
                'id'=> $row['id'],
                'pagos'=> $row['pagos'],
                'plazo'=> $row['plazo'],
                'dias_pago'=> $row['dias_pago'],
                'tip_comprob'=> $row['tip_comprob'],
                'serie'=> $row['serie'],
                'fvalidez'=> $row['fvalidez'],
                'comentario'=> $row['comentario'],
                'rangomax'=> $row['rangomax'],
                'rangomin'=> $row['rangomin'],
                'nrautorizacion'=> $row['nrautorizacion'],
                'contribuye_sri'=> $row['contribuye_sri'],
                'tip_electronico'=> $row['tip_electronico'],
                'imp_retencion'=> $row['imp_retencion'],
                'codsri_imp'=> $row['codsri_imp'],
                'retencion_iva'=> $row['retencion_iva'],
                'codsri_iva'=> $row['codsri_iva'],
                'cta_contable'=> $row['cta_contable'],
                'id_provincia'=> $row['id_provincia'],
                'id_ciudad'=> $row['id_ciudad'],
                'id_banco'=> $row['id_banco'],
                'id_empresa'=> $row['id_empresa'],
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

            'SheetJS' => new ProveedorImport(),
           
        ];
    }

}
