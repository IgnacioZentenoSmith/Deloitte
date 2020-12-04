<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exceldata extends Model
{
    protected $table = 'exceldata';
	protected $primaryKey = 'id';

    protected $fillable = [
        'exceldata_name',
    ];
}
