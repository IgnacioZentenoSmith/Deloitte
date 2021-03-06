@extends('admin.layout')
@section('adminContent')

<form method="POST" action="{{route('admin.update', $usuario['id'])}}">
    @csrf
    {{ method_field('PUT') }}
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">Nombre</label>
        <div class="col-md-6">
            <input id="name" type="text" class="form-control" name="name" value="{{$usuario['name']}}" required
                autofocus>
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="{{$usuario['email']}}" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="role" class="col-md-4 col-form-label text-md-right">Rol</label>

        <div class="col-md-6">
            <select class="form-control" id="role" name="role">
                @foreach ($roles as $role)
                <option value="{{$role['id']}}" @if ($usuario['roles_id'] == $role['id']) selected @endif>{{$role['roles_nombre']}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="notifications" class="col-md-4 col-form-label text-md-right">¿Recibe notificaciones?</label>
        <div class="col-md-6">

            <div class="pretty p-switch p-fill">
                <input type="radio" name="notifications" value="1" @if ($usuario['notifications']) checked @endif/>
                <div class="state p-success">
                    <label>Si</label>
                </div>
            </div>

            <div class="pretty p-switch p-fill">
                <input type="radio" name="notifications" value="0" @if (!$usuario['notifications']) checked @endif/>
                <div class="state p-success">
                    <label>No</label>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                Editar
            </button>
            <a class="btn btn-secondary" href="{{route('admin.index')}}" role="button">Cancelar</a>
        </div>
    </div>
</form>

@endsection
