<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
	protected $primaryKey = 'id';

    protected $fillable = [
        'menus_tipo',
        'menus_nombre',
        'menus_descripcion',
        'menus_idPadre',
    ];
}
