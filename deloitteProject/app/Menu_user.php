<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_user extends Model
{
    protected $table = 'menu_user';
	protected $primaryKey = 'id';

    protected $fillable = [
        'menus_id',
        'users_id',
    ];
}
