@extends('admin.layout')
@section('adminContent')
<div class="row justify-content-center">
    <div class="col-auto">
        <div class="table-responsive">
            <table id="tablaAdmin" class="table table-hover w-auto text-nowrap btTable" data-show-export="true"
                data-pagination="true" data-click-to-select="true" data-show-columns="true" data-sortable="true"
                data-search="true" data-live-search="true" data-buttons-align="left" data-search-align="right"
                data-server-sort="false">
                <thead>
                    <tr>
                        <th scope="col" data-field="ID" data-sortable="true">ID</th>
                        <th scope="col" data-field="Nombre" data-sortable="true">Nombre</th>
                        <th scope="col" data-field="Email" data-sortable="true">Email</th>
                        <th scope="col" data-field="Role" data-sortable="true">Rol</th>
                        <th scope="col" data-field="Notificaciones" data-sortable="true">Notificaciones</th>
                        <th scope="col" data-field="Verificado" data-sortable="true">Verificado</th>
                        <th scope="col" data-field="Acciones" data-sortable="true">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{$usuario['id']}}</td>
                        <td>{{$usuario['name']}}</td>
                        <td>{{$usuario['email']}}</td>
                        <td>{{$usuario['roles_nombre']}}</td>
                        <td class="text-center">
                            @if ($usuario['notifications'])
                            <span class="badge badge-success">Si</span>
                            @else
                            <span class="badge badge-dark">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($usuario['email_verified_at'])
                            <span class="badge badge-success">Si</span>
                            @else
                            <span class="badge badge-danger">No</span>
                            @endif
                        </td>

                        <td>
                            @if(in_array(6, $permisos))
                            <a class="btn btn-primary" href="{{ route('admin.edit', $usuario['id']) }}"
                            role="button">Editar</a>
                            @endif

                            @if(in_array(7, $permisos))
                            <a class="btn btn-secondary" href="{{ route('admin.editPermisos', $usuario['id']) }}"
                            role="button">Permisos</a>
                            @endif

                            @if(in_array(8, $permisos))
                            <form style="display: inline-block;" action="{{ route('admin.resendVerification', $usuario['id']) }}"
                            method="post">
                            @csrf
                            @method('POST')
                                <button class="btn btn-warning" @if ($usuario['email_verified_at']) disabled @endif type="submit">Reenviar verificación</button>
                            </form>
                            @endif

                            @if(in_array(9, $permisos))
                            <form style="display: inline-block;" action="{{ route('admin.destroy', $usuario['id']) }}"
                                method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Eliminar</button>
                            </form>
                            @endif

                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('js/components/initBTtables.js')}}"></script>
@endsection
