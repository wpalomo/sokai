<?php
namespace App\Http\Controllers;
use App\Imports\clienteImport;
use App\Imports\PlanCuentasImport;
use App\Imports\ProveedorImport;
use App\Imports\VendedoresImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function import(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new clienteImport , $file, $id);
    }

    public function importPlanCuentas(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new PlanCuentasImport , $file, $id);

        
    }

    public function importProveedor(Request $request)
    {

        $file = $request->file('file');
        Excel::import(new ProveedorImport , $file);
    }
    public function ImportVendedores(Request $request)
    {

        $file = $request->file('file');
        Excel::import(new VendedoresImport , $file);
    }

}
