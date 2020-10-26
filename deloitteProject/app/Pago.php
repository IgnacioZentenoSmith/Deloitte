<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
	protected $primaryKey = 'id';

    protected $fillable = [
        'cuotas_id',
        'pagos_fecha',
        'pagos_montoPagar',
        'pagos_retencion',
        'pagos_montoRetencion',
    ];
}
