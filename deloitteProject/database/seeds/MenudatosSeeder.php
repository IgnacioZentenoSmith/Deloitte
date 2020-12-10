<?php

use Illuminate\Database\Seeder;

class MenudatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['menus_nombre'=>'Datos', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Datos',],
            ['menus_nombre'=>'Datos_importarExcel', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>12, 'menus_descripcion'=>'Acceso a subir Excel de datos.',],
            ['menus_nombre'=>'Datos_eliminarExcel', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>12, 'menus_descripcion'=>'Acceso a eliminar Excel de datos en caso de ser necesario.',],
        ]);
    }
}
