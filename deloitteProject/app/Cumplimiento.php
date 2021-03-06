<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cumplimiento extends Model
{
    protected $table = 'cumplimientos';
	protected $primaryKey = 'id';

    protected $fillable = [
        'socios_id',
        'cumplimientos_porcentaje',
        'cumplimientos_fecha',
        'exceldata_id'
    ];
}
