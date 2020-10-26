<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'socios';
	protected $primaryKey = 'id';

    protected $fillable = [
        'socios_nombre',
    ];

}
