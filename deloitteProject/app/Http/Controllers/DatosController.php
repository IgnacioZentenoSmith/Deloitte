<?php

namespace App\Http\Controllers;

use App\Socio;
use App\Cuota;
use App\Cumplimiento;
use App\Pago;
use App\Exceldata;

use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
//Imports
use App\Imports\RetencionesImport;

use Maatwebsite\Excel\Facades\Excel;
//Traer modelos
use App\Menu_user;
//Usuario conectado en esta sesion
use Auth;

class DatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function getImportarExcel() {
        $permisos = $this->getPermisos();
        return view('datos.importarExcel', compact('permisos'));
    }

    public function getEliminarExcel() {
        $excelData = Exceldata::all();
        $permisos = $this->getPermisos();
        return view('datos.eliminarExcel', compact('permisos', 'excelData'));
    }

    public function postImportarExcel(Request $request) {
        $importInstance = new RetencionesImport();
        Excel::import($importInstance, $request->file('file'));
        return redirect('datos/importarExcel')->with('success', 'Excel importado exitosamente.');
    }

    public function postEliminarExcel($id) {
        $excel = Exceldata::find($id);
        $excel->delete();
        return redirect('datos/eliminarExcel')->with('success', 'Excel eliminado exitosamente');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

    private function getPermisos() {
        $permisos = Menu_user::where('users_id', Auth::user()->id)->get();
        $permisos = $permisos->pluck('menus_id')->toArray();
        return $permisos;
    }
}
