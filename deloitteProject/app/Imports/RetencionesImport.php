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
                if ($key == 'porcentaje_a_enero_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '01-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_febrero_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '02-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_marzo_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '03-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_abril_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '04-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_mayo_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '05-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_junio_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '06-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_julio_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '07-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_agosto_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '08-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_septiembre_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '09-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_octubre_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '10-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_noviembre_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '11-2020')->format('Y-m');
                }
                if ($key == 'porcentaje_a_diciembre_2020') {
                    $mes = Carbon::createFromFormat('m-Y', '12-2020')->format('Y-m');
                }

                $cumplimiento = Cumplimiento::where('socios_id', $socio->id)
                ->where('cumplimientos_fecha', $mes)
                ->first();
                if ($cumplimiento == null) {
                    $cumplimiento = new Cumplimiento([
                        'socios_id' => $socio->id,
                        'cumplimientos_porcentaje' => $row[$key],
                        'cumplimientos_fecha' => $mes,
                    ]);
                    $cumplimiento->save();
                }

                $pago = Pago::where('cuotas_id', $cuota->id)
                ->where('pagos_fecha', $mes)
                ->first();
                if ($pago == null) {
                    $retencion = false;
                    $montoRetencion = 0;
                    if ($cumplimiento->cumplimientos_porcentaje < 85) {
                        $retencion = true;
                        $montoRetencion = $cuota->cuotas_montoCuota*0.4;
                    }
                    $montoPagar = $cuota->cuotas_montoCuota - $cuota->cuotas_valorPorRendir - $montoRetencion;
                    $pago = new Pago([
                        'cuotas_id' => $cuota->id,
                        'pagos_fecha' => $mes,
                        'pagos_montoPagar' => $montoPagar,
                        'pagos_retencion' => $retencion,
                        'pagos_montoRetencion' => $montoRetencion,
                    ]);
                    $pago->save();
                }
            }
        }
    }
}
