<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
	protected $primaryKey = 'id';

    protected $fillable = [
        'socios_id',
        'cuotas_fecha',
        'cuotas_montoCuota',
        'cuotas_valorPorRendir',
    ];
}
