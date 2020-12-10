<?php

use Illuminate\Database\Seeder;

class MenuhistorialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['menus_nombre'=>'Historiales', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Historiales',],
            ['menus_nombre'=>'Historiales_retenciones', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>15, 'menus_descripcion'=>'Acceso a filtrar datos de los historiales de retenciones.',],
            ['menus_nombre'=>'Historiales_pagos', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>15, 'menus_descripcion'=>'Acceso a filtrar datos de los historiales de pagos.',],
        ]);
    }
}
