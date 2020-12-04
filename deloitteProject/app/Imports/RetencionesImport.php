<?php

namespace App\Imports;


use App\Socio;
use App\Cuota;
use App\Cumplimiento;
use App\Pago;
use App\Exceldata;
use Carbon\Carbon;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

HeadingRowFormatter::default('none');

class RetencionesImport implements ToCollection, WithHeadingRow, WithEvents
{

    public $sheetNames;
    public $sheetData;

    public function __construct(){
        $this->sheetNames = [];
        $this->sheetData = [];
    }
    public function collection(Collection $rows)
    {
        $excelName = $this->sheetNames[0];
        //Buscar o crear
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio','Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $excelData = Exceldata::firstOrCreate([
            'exceldata_name' => $excelName,
            ]);

        $excelNameExplode = explode(' ', $excelName);
        $excelYear = $excelNameExplode[1];
        $this->sheetData[$this->sheetNames[count($this->sheetNames)-1]] = $rows;
        foreach ($this->sheetData as $sheetData) {
            foreach ($sheetData as $data) {

                $keys = $data->keys();
                if ($keys->containsStrict('Cuota 1 de 2')) {
                    $fechaCuota = Carbon::createFromFormat('m-Y', '08-' . $excelYear)->format('Y-m');
                    $keyCuota = 'Cuota 1 de 2';
                } else if ($keys->containsStrict('Cuota 2 de 2')) {
                    $fechaCuota = Carbon::createFromFormat('m-Y', '11-' . $excelYear)->format('Y-m');
                    $keyCuota = 'Cuota 2 de 2';
                }

                $mesesCollection = $data->except(['Nombre', $keyCuota, 'Valor por rendir', 0, 1]);
                $mesesKeys = $mesesCollection->keys();

                //Validaciones
                if ($data['Valor por rendir'] == null) {
                    $data['Valor por rendir'] = 0;
                }
                if ($data[$keyCuota] == null) {
                    $data[$keyCuota] = 0;
                }

                //Si no encuentra el socio, lo crea, de lo contrario se salta.
                //Socios
                $socio = Socio::firstOrCreate(['socios_nombre' => $data['Nombre']]);
                //Cuotas
                $cuota = Cuota::where('socios_id', $socio->id)
                ->where('cuotas_fecha', $fechaCuota)
                ->first();
                if ($cuota == null) {
                    $cuota = new Cuota([
                        'socios_id' => $socio->id,
                        'cuotas_fecha' => $fechaCuota,
                        'cuotas_montoCuota' => $data[$keyCuota],
                        'cuotas_valorPorRendir' => $data['Valor por rendir'],
                        'exceldata_id' => $excelData->id
                    ]);
                    $cuota->save();
                }

                foreach ($mesesKeys as $key) {
                    // ej de string: porcentaje_a_enero_2020
                    $columnasCumplimientoExcel = explode(' ', $key);
                    // 2020
                    $year = array_pop($columnasCumplimientoExcel);
                    // enero
                    $month = array_pop($columnasCumplimientoExcel);
                    // array empieza de 0 (enero), carbon de 1 (enero)
                    $numeroMes = array_search($month, $meses) + 1;
                    // crear mes relativo al obtenido en excel
                    $mes = Carbon::createFromFormat('m-Y', $numeroMes . '-' . $year)->format('Y-m');

                    //Cumplimientos
                    $cumplimiento = Cumplimiento::where('socios_id', $socio->id)
                    ->where('cumplimientos_fecha', $mes)
                    ->first();
                    if ($cumplimiento == null) {
                        $cumplimiento = new Cumplimiento([
                            'socios_id' => $socio->id,
                            'cumplimientos_porcentaje' => $data[$key],
                            'cumplimientos_fecha' => $mes,
                            'exceldata_id' => $excelData->id
                        ]);
                        $cumplimiento->save();
                    }

                    //Pagos
                    $pago = Pago::where('cuotas_id', $cuota->id)
                        ->where('pagos_fecha', $mes)
                        ->first();
                    //Este pago no existe
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
                                $montoRetencion = round($cuota->cuotas_montoCuota*0.4);
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
                                $montoPagar += round($cuota->cuotas_montoCuota*0.4);
                            }
                        }
                        //Crear el pago
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
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            }
        ];
    }

    public function getSheetNames() {
        return $this->sheetNames;
    }

}
