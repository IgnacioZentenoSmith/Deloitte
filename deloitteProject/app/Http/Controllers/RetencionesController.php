<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Imports
use App\Imports\RetencionesImport;

use Maatwebsite\Excel\Facades\Excel;
//Traer modelos
use App\Menu_user;
//Usuario conectado en esta sesion
use Auth;

class RetencionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = $this->getPermisos();
        return view('retenciones.index', compact('permisos'));
    }

    public function getImportExcel() {
        $permisos = $this->getPermisos();
        return view('retenciones.importExcel', compact('permisos'));
    }

    public function postImportExcel(Request $request) {
        $rows = Excel::import(new RetencionesImport, $request->file('file'));
        dd($rows);
    }

    public function ajaxCuotas(Request $request) {
        return $request;
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
