<?php

namespace App\Http\Controllers;

//Componentes Laravel
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//Traer modelos
use App\Socio;
use App\Cuota;
use App\Cumplimiento;
use App\Pago;
use Carbon\Carbon;

use App\User;
use App\Role;
use App\Menu;
use App\Menu_user;
//Usuario conectado en esta sesion
use Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = $this->getPermisos();
        $socios = Socio::all();

        return view('dashboard.index', compact('permisos', 'socios'));
    }

    public function ajaxRequest(Request $request)
    {
        $request->validate([
            'radioMetodoBusqueda'=>'required|string|max:255',
        ]);
        if ($request->radioMetodoBusqueda == 'cuota') {

        } else if ($request->radioMetodoBusqueda == 'mes') {

        } else if ($request->radioMetodoBusqueda == 'socio') {
            //ID del socio desde el select box del filtro socios
            $socioData = $this->getThisSocioData($request->selectSocio);
            $socio = Socio::find($request->selectSocio);

            $cumplimientosPorMes = collect([
                'name' => 'Cumplimiento',
                'data' => [],
            ]);
            $pagosPorMes = collect([
                'name' => 'Pagos',
                'data' => [],
            ]);
            $meses = [];
            $montoRetenidoPorMes = [];
            foreach ($socioData as $sdata) {
                //Meses
                array_push($meses, $sdata->pagos_fecha);
                //Barras cumplimiento y sus pagos
                $cumplimientosPorMes = $cumplimientosPorMes->mergeRecursive(['data' => (float)$sdata->cumplimientos_porcentaje]);
                $pagosPorMes = $pagosPorMes->mergeRecursive(['data' => (int)$sdata->pagos_montoPagar]);
                //Barras monto retenido por mes
                array_push($montoRetenidoPorMes, $sdata->pagos_montoRetencion);
            }
            return response()->json(['nombreSocio' => $socio->socios_nombre, 'socioData' => $socioData, 'metodoBusqueda' => $request->radioMetodoBusqueda, 'barraCumplimientos' => $cumplimientosPorMes, 'barraPagos' => $pagosPorMes, 'categoriaMeses' => $meses]);

        }
        return response()->json(['Estoy funcionando']);
    }

    private function getThisSocioData($idSocio) {
        $data = Cuota::where('cuotas.socios_id', $idSocio)
        ->join('pagos', 'pagos.cuotas_id', '=', 'cuotas.id')
        ->join('cumplimientos', function($subQuery) use ($idSocio) {
            $subQuery->on('cumplimientos.cumplimientos_fecha', '=', 'pagos.pagos_fecha')
            ->where('cumplimientos.socios_id', $idSocio);
        })
        ->get();
        return $data;
    }

    private function getThisMonthData($mes) {
        $data = [];
        return $data;
    }

    private function getThisCuotaData($cuota) {
        $data = [];
        return $data;
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

    private function getPermisos() {
        $permisos = Menu_user::where('users_id', Auth::user()->id)->get();
        $permisos = $permisos->pluck('menus_id')->toArray();
        return $permisos;
    }
}
