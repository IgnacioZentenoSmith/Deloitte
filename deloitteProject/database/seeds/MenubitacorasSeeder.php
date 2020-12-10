<?php

use Illuminate\Database\Seeder;

class MenubitacorasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['menus_nombre'=>'Bitacoras', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Bitácoras',],
            ['menus_nombre'=>'Bitacoras_index', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>18, 'menus_descripcion'=>'Acceso a consultar la Bitácora del sistema.',],
        ]);
    }
}
