<?php

use Illuminate\Database\Seeder;

class MenudashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['menus_nombre'=>'Dashboard', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Dashboard',],
            ['menus_nombre'=>'Dashboard_index', 'menus_tipo'=>'Programa', 'menus_idPadre'=>10, 'menus_descripcion'=>'Acceso a ver los gráficos del Dashboard',],
        ]);
    }
}
