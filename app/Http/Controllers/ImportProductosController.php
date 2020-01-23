<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProductosImport;
use App\Imports\LineaProductoImport; 
use App\Imports\TipoProductosImport;
use App\Imports\MarcaProductosImport;
use App\Imports\ModelosProductosImport;
use App\Imports\PresentacionProductosImport;

use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportProductosController extends Controller
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
        Excel::import(new ProductosImport , $file, $id);

        
    }

    public function importLineasProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new LineaProductoImport , $file, $id);

        
    }
    public function importTipoProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new TipoProductosImport , $file, $id);

        
    }

    public function imporMarcoProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new MarcaProductosImport , $file, $id);

        
    }

    public function imporModelosProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new ModelosProductosImport , $file, $id);

        
    }

    public function imporPresentacionProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new PresentacionProductosImport , $file, $id);

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
