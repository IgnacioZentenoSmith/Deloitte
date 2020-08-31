<?php

use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['menus_nombre'=>'Home', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Home',],
            ['menus_nombre'=>'Administracion', 'menus_tipo'=>'Menu', 'menus_idPadre'=>null, 'menus_descripcion'=>'Acceso al Menú de Administración',],
            ['menus_nombre'=>'Administracion_creacionUsuario', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>2, 'menus_descripcion'=>'Acceso al Submenú de creación de usuario del Menú de Administración',],
            ['menus_nombre'=>'Administracion_creacionUsuario_crearUsuario', 'menus_tipo'=>'Programa', 'menus_idPadre'=>3, 'menus_descripcion'=>'Acceso a la acción de crear un usuario dentro del Submenú de creación de usuario del Menú de Administración',],
            ['menus_nombre'=>'Administracion_listaUsuarios', 'menus_tipo'=>'Submenu', 'menus_idPadre'=>2, 'menus_descripcion'=>'Acceso al Submenú de lista de usuarios del Menú de Administración',],
            ['menus_nombre'=>'Administracion_listaUsuarios_editarUsuario', 'menus_tipo'=>'Programa', 'menus_idPadre'=>5, 'menus_descripcion'=>'Acceso a la acción de editar un usuario dentro del Submenú de lista de usuarios del Menú de Administración',],
            ['menus_nombre'=>'Administracion_listaUsuarios_editarPermisos', 'menus_tipo'=>'Programa', 'menus_idPadre'=>5, 'menus_descripcion'=>'Acceso a la acción de editar permisos de un usuario dentro del Submenú de lista de usuarios del Menú de Administración',],
            ['menus_nombre'=>'Administracion_listaUsuarios_reenviarVerificacion', 'menus_tipo'=>'Programa', 'menus_idPadre'=>5, 'menus_descripcion'=>'Acceso a la acción de reenviar el mail de verificación de un usuario dentro del Submenú de lista de usuarios del Menú de Administración',],
            ['menus_nombre'=>'Administracion_listaUsuarios_eliminarUsuario', 'menus_tipo'=>'Programa', 'menus_idPadre'=>5, 'menus_descripcion'=>'Acceso a la acción de eliminar un usuario dentro del Submenú de lista de usuarios del Menú de Administración',],
        ]);
    }
}
