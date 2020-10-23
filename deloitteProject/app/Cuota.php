<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
	protected $primaryKey = 'id';

    protected $fillable = [
        'socios_id',
        'cuotas_monto',
        'cuotas_valorPorRendir',
        'cuotas_retencion',
        'cuotas_retencionMonto',
        'cuotas_fecha',
        'cuotas_fechaCumplimiento',
    ];

}
