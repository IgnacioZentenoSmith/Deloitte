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
        $adminPermisos = [10, 11];
        foreach ($adminPermisos as $adminPermiso) {
            if (!in_array($adminPermiso, $permisos)) {
                return redirect('home')->with('error', 'No tiene acceso a esta funcionalidad.');
            }
        }

        $socios = Socio::all();

        return view('dashboard.index', compact('permisos', 'socios'));
    }

    public function ajaxRequest(Request $request)
    {
        $colors = ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce',
        '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'];
        $request->validate([
            'radioMetodoBusqueda'=>'required|string|max:255',
        ]);
        // ***********************************************************************************
        if ($request->radioMetodoBusqueda == 'cuota') {
            //Validar fecha
            $year = $request->cuotaYear;
            if ($request->cuotaMes == 'Agosto') {
                $fecha = Carbon::createFromFormat('m-Y', '08-' . $year)->format('Y-m');
            } else if ($request->cuotaMes == 'Noviembre') {
                $fecha = Carbon::createFromFormat('m-Y', '11-' . $year)->format('Y-m');
            }

            $numeroCuotasRetenidas = 0;
            //Saca todas las cuotas de esta fecha
            $cuotas = Cuota::where('cuotas_fecha', $fecha)->get();
            $meses = [];

            foreach ($cuotas as $cuota) {
                $pagos = Pago::where('cuotas_id', $cuota->id)->get();
                //Esta retenida
                if ($pagos->count() == $pagos->sum('pagos_retencion')) {
                    $numeroCuotasRetenidas += 1;
                }

                //Montos por cada cuota
                foreach ($pagos as $pago) {
                    //No esta en meses este mes
                    if (!in_array($pago->pagos_fecha, $meses)) {
                        //Meses
                        array_push($meses, $pago->pagos_fecha);
                    }
                }
            }

            //Bar chart - promedio cumplimiento por cada mes
            $cumplimientosPorMes = collect([
                'name' => 'Promedio cumplimiento',
                'data' => [],
                'color' => $colors[0]
            ]);

            //Area chart - retenciones y pagos totales por mes
            $totalRetencionesEsteMes = collect([
                'name' => 'Retenciones',
                'data' => [],
                'color' => $colors[0]
            ]);
            $totalPagosEsteMes = collect([
                'name' => 'Pagos',
                'data' => [],
                'color' => $colors[2]
            ]);

            foreach ($meses as $mes) {
                $cumplimientos = Cumplimiento::where('cumplimientos_fecha', $mes)->get();
                $promedioCumplimiento = round($cumplimientos->sum('cumplimientos_porcentaje') / $cumplimientos->count(), 2);
                $cumplimientosPorMes = $cumplimientosPorMes->mergeRecursive(['data' => $promedioCumplimiento]);

                //Montos retenidos y pagados por mes
                $data = Cuota::where('cuotas_fecha', $fecha)
                ->join('pagos', function($subQuery) use ($mes) {
                    $subQuery->on('pagos.cuotas_id', '=', 'cuotas.id')
                    ->where('pagos.pagos_fecha', $mes);
                })
                ->get();
                $totalPagosEsteMes = $totalPagosEsteMes->mergeRecursive(['data' => $data->sum('pagos_montoPagar')]);
                $totalRetencionesEsteMes = $totalRetencionesEsteMes->mergeRecursive(['data' => $data->sum('pagos_montoRetencion')]);
            }

            $numeroCuotasPagadas = $cuotas->count() - $numeroCuotasRetenidas;
            //Pie chart - porcentaje socios retenidos vs pagados
            $cuotasRetenidas = collect([
                'name' => 'Retenidas',
                'y' => round($numeroCuotasRetenidas * 100 / $cuotas->count(), 2),
            ]);

            $cuotasPagadas = collect([
                'name' => 'Pagadas',
                'y' => round($numeroCuotasPagadas * 100 / $cuotas->count(), 2),
            ]);

            return response()->json(['metodoBusqueda' => $request->radioMetodoBusqueda,
            'piePorcentajeRetenidos' => [$cuotasRetenidas, $cuotasPagadas],
            'barPromedioCumplimientos' => [$cumplimientosPorMes], 'categoriaMeses' => $meses,
            'areaTotalMontosMes' => [$totalRetencionesEsteMes, $totalPagosEsteMes]]);
        }
        // ***********************************************************************************
        else if ($request->radioMetodoBusqueda == 'mes') {
            $fecha = $request->mesFecha;

            $detalles = Pago::where('pagos_fecha', $fecha)
            ->join('cuotas', 'cuotas.id', '=', 'pagos.cuotas_id')
            ->join('socios', 'socios.id', '=', 'cuotas.socios_id')
            ->join('cumplimientos', 'cumplimientos.socios_id', '=', 'socios.id')
            ->where('cumplimientos.cumplimientos_fecha', $fecha)
            ->get();

            $totalRetencionEsteMes = collect([
                'name' => 'Retención',
                'data' => [$detalles->sum('pagos_montoRetencion')],
                'color' => $colors[0]
            ]);
            $totalPagoEsteMes = collect([
                'name' => 'Pago',
                'data' => [$detalles->sum('pagos_montoPagar')],
                'color' => $colors[2]
            ]);
            $numeroCuotasRetenidas = 0;
            $nombreSocios = [];
            $cumplimientosEsteMes = collect([
                'data' => []
            ]);
            foreach ($detalles as $data) {
                //Esta retenida
                if ($data->pagos_fechaCumplimientoRetencion == null) {
                    $numeroCuotasRetenidas += 1;
                }
                //No esta en socios este socio
                if (!in_array($data->socios_nombre, $nombreSocios)) {
                    //Socios
                    array_push($nombreSocios, $data->socios_nombre);
                    $cumplimientosEsteMes = $cumplimientosEsteMes->mergeRecursive(['data' => (float)$data->cumplimientos_porcentaje]);
                }
            }
            $numeroCuotasPagadas = $detalles->count() - $numeroCuotasRetenidas;
            //Pie chart - porcentaje socios retenidos vs pagados
            $cuotasRetenidas = collect([
                'name' => 'Retenidas',
                'y' => round($numeroCuotasRetenidas * 100 / $detalles->count(), 2),
            ]);

            $cuotasPagadas = collect([
                'name' => 'Pagadas',
                'y' => round($numeroCuotasPagadas * 100 / $detalles->count(), 2),
            ]);

            return response()->json(['metodoBusqueda' => $request->radioMetodoBusqueda, 'data' => $detalles, 'mes' => [$fecha],
            'piePorcentajeRetenidos' => [$cuotasRetenidas, $cuotasPagadas],
            'barTotalMontos' => [$totalPagoEsteMes, $totalRetencionEsteMes],
            'barCumplimientosThisMonth' => [$cumplimientosEsteMes], 'nombreSocios' => $nombreSocios]);
        }
        // ***********************************************************************************
        else if ($request->radioMetodoBusqueda == 'socio') {
            //ID del socio desde el select box del filtro socios
            $socioData = $this->getThisSocioData($request->selectSocio);
            $socio = Socio::find($request->selectSocio);

            $cumplimientosPorMes = collect([
                'name' => 'Cumplimiento',
                'data' => [],
                'color' => $colors[0]
            ]);
            $pagosPorMes = collect([
                'name' => 'Pagos',
                'data' => [],
                'color' => $colors[2]
            ]);
            $retencionesPorMes = collect([
                'name' => 'Retenciones',
                'data' => [],
                'color' => $colors[8]
            ]);
            $montoCuotas = collect([
                'name' => 'Cuota',
                'data' => [],
                'color' => $colors[4]
            ]);
            $montoPagosPorRendir = collect([
                'name' => 'Pago por rendir',
                'data' => [],
                'color' => $colors[6]
            ]);

            //categorias de meses
            $meses = [];
            //categorias de las cuotas
            $fechasCuotas = [];

            foreach ($socioData as $sdata) {
                //No esta en meses este mes
                if (!in_array($sdata->pagos_fecha, $meses)) {
                    //Meses
                    array_push($meses, $sdata->pagos_fecha);
                    //Barras cumplimiento, pagos y retenciones
                    $totalPagoThisMes = $socioData->where('pagos_fecha', $sdata->pagos_fecha)->sum('pagos_montoPagar');
                    $totalRetencionThisMes = $socioData->where('pagos_fecha', $sdata->pagos_fecha)->sum('pagos_montoRetencion');

                    $cumplimientosPorMes = $cumplimientosPorMes->mergeRecursive(['data' => (float)$sdata->cumplimientos_porcentaje]);
                    $pagosPorMes = $pagosPorMes->mergeRecursive(['data' => $totalPagoThisMes]);
                    $retencionesPorMes = $retencionesPorMes->mergeRecursive(['data' => $totalRetencionThisMes]);
                }
            }

            $cuotas = Cuota::where('socios_id', $request->selectSocio)->get();
            $montoRetencion = 0;
            $montoPagado = 0;
            $totalCuotas = 0;
            $totalPorRendir  = 0;
            foreach ($cuotas as $cuota) {
                //Sacar todos los pagos de esta cuota
                $pagos = Pago::where('cuotas_id', $cuota->id)->get();
                //Esta retenida
                if ($pagos->count() == $pagos->sum('pagos_retencion')) {
                    $montoRetencion = $montoRetencion + $cuota->cuotas_montoCuota * 0.4;
                }

                $montoPagado = $montoPagado + $pagos->sum('pagos_montoPagar');
                $totalCuotas = $totalCuotas + $cuota->cuotas_montoCuota;
                $totalPorRendir = $totalPorRendir + $cuota->cuotas_valorPorRendir;
                //Meses de cuotas
                array_push($fechasCuotas, $cuota->cuotas_fecha);
                $montoCuotas = $montoCuotas->mergeRecursive(['data' => (int)$cuota->cuotas_montoCuota]);
                $montoPagosPorRendir = $montoPagosPorRendir->mergeRecursive(['data' => (int)$cuota->cuotas_valorPorRendir]);
            }

            $retenidoTotal = collect([
                'name' => 'Retenido',
                'y' => round($montoRetencion * 100 / $totalCuotas, 2),
            ]);

            $pagadoTotal = collect([
                'name' => 'Pagado',
                'y' => round($montoPagado * 100 / $totalCuotas, 2),
            ]);

            $pagoPorRendirTotal = collect([
                'name' => 'Valor por rendir',
                'y' => round($totalPorRendir * 100 / $totalCuotas, 2),
            ]);

            return response()->json(['nombreSocio' => $socio->socios_nombre, 'socioData' => $socioData, 'metodoBusqueda' => $request->radioMetodoBusqueda,
            'barraCumplimientos' => [$cumplimientosPorMes], 'barraPagos' => [$pagosPorMes, $retencionesPorMes],
            'categoriaMeses' => $meses, 'categoriaCuotas' => $fechasCuotas, 'barraCuotas' => [$montoCuotas, $montoPagosPorRendir],
            'piePorcentaje' => [$retenidoTotal, $pagadoTotal, $pagoPorRendirTotal]]);

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
