<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';
	protected $primaryKey = 'id';

    protected $fillable = [
        'users_id',
        'bitacoras_accion',
        'bitacoras_tabla',
        'bitacoras_filaId',
        'bitacoras_preValores',
        'bitacoras_postValores',
    ];
}
