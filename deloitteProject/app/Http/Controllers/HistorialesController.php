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

//Traer modelos
use App\Menu_user;
//Usuario conectado en esta sesion
use Auth;

class HistorialesController extends Controller
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

    public function getPagosIndex() {
        $permisos = $this->getPermisos();
        return view('historiales.pagos', compact('permisos'));
    }

    public function getRetencionesIndex() {
        $permisos = $this->getPermisos();
        return view('historiales.retenciones', compact('permisos'));
    }

    public function ajaxPagos(Request $request) {
        //Validar campos de datos, si uno falla devuelve error en la vista
        $request->validate([
            'mesFecha'=>'required|string|max:7',
        ]);
        $fecha = $request->mesFecha;

        $detalles = Pago::where('pagos_fecha', $fecha)
            ->where('pagos_montoPagar', '>', 0)
            ->join('cuotas', 'cuotas.id', '=', 'pagos.cuotas_id')
            ->join('socios', 'socios.id', '=', 'cuotas.socios_id')
            ->join('cumplimientos', 'cumplimientos.socios_id', '=', 'socios.id')
            ->where('cumplimientos.cumplimientos_fecha', $fecha)
            ->get();

        $tabla = collect([]);
        $socios = Socio::all();
        foreach ($socios as $socio) {
            if ($detalles->where('socios_nombre', $socio->socios_nombre)->count() > 0) {
                $totalPagar = $detalles->where('socios_nombre', $socio->socios_nombre)->sum('pagos_montoPagar');
                $cantidadCuotas = $detalles->where('socios_nombre', $socio->socios_nombre)->count();
                $cumplimiento = $detalles->where('socios_nombre', $socio->socios_nombre)->first();
                $data = collect([
                    'IDsocio' => $socio->id,
                    'socio' => $socio->socios_nombre,
                    'totalPagar' => $totalPagar,
                    'cumplimiento' => $cumplimiento->cumplimientos_porcentaje,
                    'cantidadCuotas' => $cantidadCuotas,
                ]);
                $tabla = $tabla->push($data);
            }
        }
        $montoTotal = $detalles->sum('pagos_montoPagar');

        return response()->json(['fecha' => $fecha, 'tabla' => $tabla, 'detalles' => $detalles, 'totalPagar' => $montoTotal]);
    }

    public function ajaxRetenciones(Request $request) {
        //Validar campos de datos, si uno falla devuelve error en la vista
        $request->validate([
            'radioMetodoBusqueda'=>'required|string|max:255',
        ]);
        if ($request->radioMetodoBusqueda == 'cuota') {
            //Validar fecha
            $year = $request->cuotaYear;
            if ($request->cuotaMes == 'Agosto') {
                $fecha = Carbon::createFromFormat('m-Y', '08-' . $year)->format('Y-m');
            } else if ($request->cuotaMes == 'Noviembre') {
                $fecha = Carbon::createFromFormat('m-Y', '11-' . $year)->format('Y-m');
            }
            //Sacar datos
            $cuotas = Cuota::where('cuotas_fecha', $fecha)
            ->join('socios', 'socios.id', '=', 'cuotas.socios_id')
            ->select('cuotas.*', 'socios.socios_nombre')
            ->get();

            $detalles = collect([]);
            foreach ($cuotas as $cuota) {
                //Saca pago y cumplimiento asociado a ese mes
                $pagos = Pago::where('cuotas_id', $cuota->id)
                ->join('cumplimientos', 'cumplimientos.cumplimientos_fecha', '=', 'pagos.pagos_fecha')
                ->where('socios_id', $cuota->socios_id)
                ->select('pagos.*', 'cumplimientos.cumplimientos_porcentaje', 'cumplimientos.socios_id')
                ->get();

                $detalles = $detalles->mergeRecursive($pagos);
            }
            return response()->json(['detalles' => $detalles, 'tabla' => $cuotas]);

        } else if ($request->radioMetodoBusqueda == 'mes') {
            $fecha = $request->mesFecha;
            //$fecha = Carbon::createFromFormat('Y-m', $fecha)->format('Y-m');

            $detalles = Pago::where('pagos_fecha', $fecha)
            ->join('cuotas', 'cuotas.id', '=', 'pagos.cuotas_id')
            ->join('socios', 'socios.id', '=', 'cuotas.socios_id')
            ->join('cumplimientos', 'cumplimientos.socios_id', '=', 'socios.id')
            ->where('cumplimientos.cumplimientos_fecha', $fecha)
            ->get();

            $tabla = collect([]);

            $socios = Socio::all();
            foreach ($socios as $socio) {
                $totalCuotas = $detalles->where('socios_nombre', $socio->socios_nombre)->sum('cuotas_montoCuota');
                $totalPagar = $detalles->where('socios_nombre', $socio->socios_nombre)->sum('pagos_montoPagar');
                $totalValorPorRendir = $detalles->where('socios_nombre', $socio->socios_nombre)->sum('cuotas_valorPorRendir');
                $totalRetenciones = $detalles->where('socios_nombre', $socio->socios_nombre)->sum('pagos_montoRetencion');
                $cumplimiento = $detalles->where('socios_nombre', $socio->socios_nombre)->first();
                $data = collect([
                    'IDsocio' => $socio->id,
                    'socio' => $socio->socios_nombre,
                    'totalCuotas' => $totalCuotas,
                    'totalPagar' => $totalPagar,
                    'totalValorPorRendir' => $totalValorPorRendir,
                    'totalRetenciones' => $totalRetenciones,
                    'cumplimiento' => $cumplimiento->cumplimientos_porcentaje,
                ]);
                $tabla = $tabla->push($data);
            }

            return response()->json(['detalles' => $detalles, 'tabla' => $tabla]);
            //return response()->json(['detalles' => $detalles, 'tabla' => $cuotas]);

        }
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
