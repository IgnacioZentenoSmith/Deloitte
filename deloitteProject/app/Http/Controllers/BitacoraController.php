<?php

namespace App\Http\Controllers;

//Componentes Laravel
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
//Traer modelos
use App\Socio;
use App\Cuota;
use App\Cumplimiento;
use App\Bitacora;
use Carbon\Carbon;
use App\Permission;

use App\User;
use App\Role;
use App\Menu;
use App\Menu_user;

use Auth;


class BitacoraController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permisos = $this->getPermisos();

        $binnacles = Bitacora::whereNotNull('bitacoras_accion')
        ->join('users', 'users.id', '=', 'bitacoras.users_id')
        ->select('bitacoras.bitacoras_accion', 'bitacoras.bitacoras_tabla', 'bitacoras.users_id', 'users.name as userName')
        ->get();

        $uniqueUsers = $binnacles->unique(function ($item) {
            return $item['userName'];
        });

        $uniqueActions = $binnacles->unique(function ($item) {
            return $item['bitacoras_accion'];
        });

        $uniqueTables = $binnacles->unique(function ($item) {
            return $item['bitacoras_tabla'];
        });
        $binnacles = Bitacora::whereNull('bitacoras.users_id');

        return view('bitacora.index', compact('permisos', 'binnacles', 'uniqueUsers', 'uniqueActions', 'uniqueTables'));
    }

    public function ajaxBitacoras(Request $request) {
        $request->validate([
            'filter_usuarios'=> 'numeric|nullable',
            'filter_actions'=> 'string|max:50|nullable',
            'filter_tables'=> 'string|max:50|nullable',
            'filter_fecha_desde'=> 'date|nullable',
            'filter_fecha_hasta'=> 'date|nullable',
        ]);


        $binnacles = Bitacora::whereNotNull('bitacoras_accion')
        ->join('users', 'users.id', '=', 'bitacoras.users_id')
        ->select('bitacoras.*', 'users.name as userName')
        ->get();
        foreach ($binnacles as $binnacle) {
            if ($binnacle->bitacoras_preValores != null) {
                // convert json to array
                $arrayPreValues = json_decode($binnacle->bitacoras_preValores, true);
                //  create a new collection instance from the array
                $binnacle->bitacoras_preValores = collect($arrayPreValues);
            }
            if ($binnacle->bitacoras_postValores != null) {
                // convert json to array
                $arrayPostValues = json_decode($binnacle->bitacoras_postValores, true);
                //  create a new collection instance from the array
                $binnacle->bitacoras_postValores = collect($arrayPostValues);
            }
        }

        if (!is_null($request->filter_usuarios)) {
            $binnacles = $binnacles->where('users_id', $request->filter_usuarios);
        }
        if (!is_null($request->filter_actions)) {
            $binnacles = $binnacles->where('bitacoras_accion', $request->filter_actions);
        }
        if (!is_null($request->filter_tables)) {
            $binnacles = $binnacles->where('bitacoras_tabla', $request->filter_tables);
        }
        if (!is_null($request->filter_fecha_desde)) {
            $binnacles = $binnacles->where('created_at', '>=', $request->filter_fecha_desde);
        }
        if (!is_null($request->filter_fecha_hasta)) {
            $binnacles = $binnacles->where('created_at', '<=', $request->filter_fecha_hasta);
        }

        $bitacora = collect([]);
        foreach ($binnacles as $binnacle) {
            $bitacora = $bitacora->push($binnacle);
        }

        //return view('binnacle.index', compact('authPermisos', 'binnacles', 'uniqueUsers', 'uniqueActions', 'uniqueTables'));
        return response()->json(['bitacora' => $bitacora]);
    }

    /**
     * Create a new trait instance.
     * $action -> Accion de la tabla
     * $tableName -> Nombre de la tabla
     * $tableId -> ID afectado de la tabla
     * $preValues -> valores pre de la accion
     * $postValues -> valores post de la accion
     * @return void
     */

    public function reportBitacora($action, $tableName, $tableId, $preValues = null, $postValues = null) {
        /*
        CREATE -> $preValues = null, $postValues = model
        UPDATE -> $preValues = preModel, $postValues = postModel
        DELETE -> $preValues = model, $postValues = null
        */
        $userId = Auth::user()->id;
        $newBinnacle = new Bitacora([
            'users_id' => $userId,
            'bitacoras_accion' => $action,
            'bitacoras_tabla' => $tableName,
            'bitacoras_filaId' => $tableId,
            'bitacoras_preValores' => $preValues,
            'bitacoras_postValores' => $postValues,
        ]);
        $newBinnacle->save();
    }


    private function getPermisos() {
        $permisos = Menu_user::where('users_id', Auth::user()->id)->get();
        $permisos = $permisos->pluck('menus_id')->toArray();
        return $permisos;
    }
}
