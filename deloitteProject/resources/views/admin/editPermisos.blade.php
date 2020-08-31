@extends('admin.layout')
@section('adminContent')

<div class="row justify-content-center">
    <div class="col-auto">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                Editar permisos del usuario: <strong>{{$usuario['name']}}</strong>
            </div>
        </div>

        <form method="POST" action="{{route('admin.updatePermisos', $usuario['id'])}}">
            @csrf
            {{ method_field('PUT') }}
            <div class="table-responsive">
                <table id="tablaDistributions" class="table table-hover w-auto text-nowrap btTable"
                    data-click-to-select="true" data-sortable="true"
                    data-search="true" data-live-search="true" data-search-align="right">
                    <thead>
                        <tr>
                            <th scope="col" data-field="ID" data-sortable="true">ID</th>
                            <th scope="col" data-field="menus_nombre" data-sortable="true">Nombre</th>
                            <th scope="col" data-field="menus_tipo" data-sortable="true">Tipo</th>
                            <th scope="col" data-field="menus_idPadre" data-sortable="true">ID Padre</th>
                            <th scope="col" data-field="activo" data-sortable="true">Activo</th>
                            <th scope="col" data-field="inactivo" data-sortable="true">Inactivo</th>
                            <th scope="col" data-field="menus_descripcion" data-sortable="true">Información del menú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>
                            {{$menu['id']}}
                                <input type="hidden" id="menu_id[{{$menu['id']}}]" name="menu_id[]" required
                                value="{{$menu['id']}}">
                            </td>
                            <td>{{$menu['menus_nombre']}}</td>
                            <td>{{$menu['menus_tipo']}}</td>
                            <td class="text-center">
                            {{$menu['menus_idPadre']}}
                                <input type="hidden" id="menu_idPadre[{{$menu['id']}}]" name="menu_idPadre[]" required
                                value="{{$menu['menus_idPadre']}}">
                            </td>

                            <td class="text-center">
                                <div class="pretty p-switch p-fill">
                                    <input type="radio" id="statusMenu[{{$menu['id']}}]" name="statusMenu[{{$menu['id']}}]" value="1" @if(in_array($menu['id'], $permisosUsuario)) checked @endif/>
                                    <div class="state p-success">
                                        <label></label>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="pretty p-switch p-fill">
                                    <input type="radio" id="statusMenu[{{$menu['id']}}]" name="statusMenu[{{$menu['id']}}]" value="0" @if(!in_array($menu['id'], $permisosUsuario)) checked @endif/>
                                    <div class="state p-success">
                                        <label></label>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="bottom" title="Descripción" data-content="{{$menu['menus_descripcion']}}">
                                Más información
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 mt-4">
                <button type="submit" class="btn btn-primary">
                    Guardar
                </button>
            </div>
        </form>


    </div>
</div>
<script src="{{ asset('js/components/initBTtables.js')}}"></script>
<script src="{{ asset('js/components/initPopovers.js')}}"></script>
@endsection
