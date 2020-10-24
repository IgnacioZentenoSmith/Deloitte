<?php

namespace App\Imports;

use App\Socio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RetencionesImport implements FromCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public function collection()
    {
        return Socio::all();
    }

    public function headingRow(): int
    {
        return 6;
    }
}
