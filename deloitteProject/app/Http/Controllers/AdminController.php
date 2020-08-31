<?php

namespace App\Http\Controllers;

//Componentes Laravel
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//Traer modelos
use App\User;
use App\Role;
use App\Menu;
use App\Menu_user;
//Usuario conectado en esta sesion
use Auth;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = $this->getPermisos();
        $usuarios = User::whereNotNull('email')
        ->join('roles', 'roles.id', '=', 'users.roles_id')
        ->select('users.*', 'roles.roles_nombre')
        ->get();
        return view('admin.index', compact('usuarios', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permisos = $this->getPermisos();
        $roles = Role::all();
        return view('admin.create', compact('roles', 'permisos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validar campos de datos, si uno falla devuelve error en la vista
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email:rfc|unique:users,email',
            'role'=>'required|numeric|min:1',
            'notifications' => 'required|boolean',
        ]);

        //Crear usuario
        $newUser = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->email),
            'roles_id' => $request->role,
            'notifications' => $request->notifications,
        ]);
        //Guardar en la base de datos
        $newUser->save();
        $newUser->sendEmailVerificationNotification();

        //Crear los permisos asociados al rol
        $this->generatePermisosMenus($newUser->roles_id, $newUser->id);
        //Devolver resultado de la operación
        return redirect('admin')->with('success', 'Usuario agregado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permisos = $this->getPermisos();
        $usuario = User::find($id);
        $roles = Role::all();
        return view('admin.edit', compact('usuario', 'roles', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validar campos de datos, si uno falla devuelve error en la vista
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email:rfc|unique:users,email,'.$id,
            'role'=>'required|numeric|min:1',
            'notifications' => 'required|boolean',
        ]);

        //Usuario existente
        $user = User::find($id);
        $rol = $user->roles_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->roles_id = $request->role;
        $user->notifications = $request->notifications;

        //Si ha habido algun cambio, guardar datos
        if ($user->isDirty()) {
            //Si se ha cambiado el rol
            if ($rol != $user->roles_id) {
                $this->deletePermisosMenus($user->id);
                $this->generatePermisosMenus($user->roles_id, $user->id);
            }
            $user->save();
            return redirect('admin')->with('success', 'Usuario editado exitosamente.');
        } else {
            return redirect('admin');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('admin')->with('success', 'Usuario eliminado exitosamente');
    }

    public function editPermisos($id) {
        $permisos = $this->getPermisos();
        $usuario = User::find($id);
        $permisosUsuario = Menu_user::where('users_id', $id)->get();
        $permisosUsuario = $permisosUsuario->pluck('menus_id')->toArray();
        $menus = Menu::all();
        return view('admin.editPermisos', compact('usuario', 'permisosUsuario', 'menus', 'permisos'));
    }

    public function updatePermisos(Request $request, $id) {
        $menus = Menu::all();
        $largoTabla = $menus->count();
        //Validar campos de datos, si uno falla devuelve error en la vista
        $request->validate([
            'statusMenu'=> 'required|array|min:' . $largoTabla,
            'statusMenu.*'=> 'required|boolean',
        ]);
        //Borrar todos los permisos de este usuario
        $this->deletePermisosMenus($id);
        //Generar los permisos que se habilitaron en esta tabla
        foreach ($request->statusMenu as $menu => $estado) {
            if ($estado) {
                $newMenu_user = new Menu_user([
                    'menus_id' => $menu,
                    'users_id' => $id,
                ]);
                //Guardar en la base de datos
                $newMenu_user->save();
            }
        }
        return redirect('admin')->with('success', 'Permisos del usuario editados exitosamente.');
    }

    private function deletePermisosMenus($users_id) {
        $permisosMenus = Menu_user::where('users_id', $users_id)->get();
        foreach ($permisosMenus as $permisosMenu) {
            $permisosMenu->delete();
        }
    }

    private function generatePermisosMenus($roles_id, $users_id) {
        //Trae todos los datos de este id de rol (id y nombre)
        $rol = Role::find($roles_id);
        $menus = Menu::all();
        if ($rol->roles_nombre == "Manager") {
            $accesoMenus = [1, 2, 5];
        } else if ($rol->roles_nombre == "Administrador") {
            //Todos los permisos menos eliminar usuarios
            $accesoMenus = [1, 2, 3, 4, 5, 6, 7, 8];
        } else if ($rol->roles_nombre == "Desarrollador") {
            //Todos los permisos
            $accesoMenus = range(1, $menus->count());
        }

        foreach ($menus as $menu) {
            foreach ($accesoMenus as $accesoMenu) {
                //Si el id de este menu esta dentro del acceso de este rol, agregarlo
                if ($menu->id == $accesoMenu) {
                    //Crear permiso
                    $newMenu_user = new Menu_user([
                        'menus_id' => $menu->id,
                        'users_id' => $users_id,
                    ]);
                    //Guardar en la base de datos
                    $newMenu_user->save();
                }
            }
        }
    }

    public function resendVerification($id) {
        $user = User::find($id);
        $user->sendEmailVerificationNotification();
        return redirect('admin')->with('success', 'Correo enviado exitosamente.');
    }

    private function getPermisos() {
        $permisos = Menu_user::where('users_id', Auth::user()->id)->get();
        $permisos = $permisos->pluck('menus_id')->toArray();
        return $permisos;
    }
}
