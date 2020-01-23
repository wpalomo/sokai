<?php
namespace App\Http\Controllers;
use App\Imports\clienteImport;
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

}
