<?php

namespace App\Imports;

use App\Socio;
use App\Cuota;
use App\Cumplimiento;
use App\Pago;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RetencionesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        //Si no encuentra el socio, lo crea, de lo contrario se salta.
        foreach ($rows as $row) {
            $keys = $row->keys();
            if ($keys->contains('cuota_1_de_2')) {
                $fechaCuota = Carbon::createFromFormat('m-Y', '08-2020')->format('Y-m');
                $keyCuota = 'cuota_1_de_2';
            } else {
                $fechaCuota = Carbon::createFromFormat('m-Y', '11-2020')->format('Y-m');
                $keyCuota = 'cuota_2_de_2';
            }

            $mesesCollection = $row->except(['nombre', $keyCuota, 'valor_por_rendir', '']);
            $mesesKeys = $mesesCollection->keys();

            //Validaciones
            if ($row['valor_por_rendir'] == null) {
                $row['valor_por_rendir'] = 0;
            }
            if ($row[$keyCuota] == null) {
                $row[$keyCuota] = 0;
            }
            //$exceldate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha']);
            //$fecha = Carbon::createFromFormat('d-m-Y', $exceldate);

            //Socios
            $socio = Socio::firstOrCreate(['socios_nombre' => $row['nombre']]);
            //Cuotas
            $cuota = Cuota::where('socios_id', $socio->id)
                ->where('cuotas_fecha', $fechaCuota)
                ->first();
            if ($cuota == null) {
                $cuota = new Cuota([
                    'socios_id' => $socio->id,
                    'cuotas_fecha' => $fechaCuota,
                    'cuotas_montoCuota' => $row[$keyCuota],
                    'cuotas_valorPorRendir' => $row['valor_por_rendir'],
                ]);
                $cuota->save();
            }

            foreach ($mesesKeys as $key) {
                //Cumplimientos por mes
                $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                    'julio','agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                // ej de string: porcentaje_a_enero_2020
                $columnasCumplimientoExcel = explode('_', $key);
                // 2020
                $year = array_pop($columnasCumplimientoExcel);
                // enero
                $month = array_pop($columnasCumplimientoExcel);
                // array empieza de 0 (enero), carbon de 1 (enero)
                $numeroMes = array_search($month, $meses) + 1;
                // crear mes relativo al obtenido en excel
                $mes = Carbon::createFromFormat('m-Y', $numeroMes . '-' . $year)->format('Y-m');

                $cumplimiento = Cumplimiento::where('socios_id', $socio->id)
                ->where('cumplimientos_fecha', $mes)
                ->first();
                if ($cumplimiento == null) {
                    $auxValue = $row[$key];
                    if ($row[$key] == null) {
                        $auxValue = 100;
                    };

                    $cumplimiento = new Cumplimiento([
                        'socios_id' => $socio->id,
                        'cumplimientos_porcentaje' => $auxValue,
                        'cumplimientos_fecha' => $mes,
                    ]);
                    $cumplimiento->save();
                }

                $pago = Pago::where('cuotas_id', $cuota->id)
                ->where('pagos_fecha', $mes)
                ->first();
                if ($pago == null) {
                    //Ha habido un cumplimiento de 85% antes?
                    $is_85Antes = Pago::where('cuotas_id', $cuota->id)
                        ->where('pagos_retencion', false)
                        ->first();
                    //Si hay
                    if ($is_85Antes != null) {
                        //Ya no hay retencion
                        $montoPagar = 0;
                        $retencion = false;
                        $montoRetencion = 0;
                        $fechaCumplimientoRetencion = $is_85Antes->pagos_fechaCumplimientoRetencion;
                    }
                    //No hay
                    else {
                        $montoPagar = 0;
                        //Es el primer pago de la cuota?
                        if ($fechaCuota == $mes) {
                            $montoPagar = $cuota->cuotas_montoCuota - $cuota->cuotas_valorPorRendir;
                        }

                        //Si el cumplimiento es menor a 85%, retener
                        if ($cumplimiento->cumplimientos_porcentaje < 85) {
                            $retencion = true;
                            $montoRetencion = $cuota->cuotas_montoCuota*0.4;
                            $fechaCumplimientoRetencion = null;
                            if ($montoPagar != 0) {
                                $montoPagar -= $montoRetencion;
                            }
                        }
                        //Cumplimiento mayor a 85%
                        else {
                            $retencion = false;
                            $montoRetencion = 0;
                            $fechaCumplimientoRetencion = $mes;
                            $montoPagar += $cuota->cuotas_montoCuota*0.4;
                        }
                    }


                    $pago = new Pago([
                        'cuotas_id' => $cuota->id,
                        'pagos_fecha' => $mes,
                        'pagos_montoPagar' => $montoPagar,
                        'pagos_retencion' => $retencion,
                        'pagos_montoRetencion' => $montoRetencion,
                        'pagos_fechaCumplimientoRetencion' => $fechaCumplimientoRetencion,
                    ]);
                    $pago->save();
                }
            }
        }
    }
}
