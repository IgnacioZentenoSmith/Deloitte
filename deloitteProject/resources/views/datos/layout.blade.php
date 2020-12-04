@extends('layouts.app')
@section('content')

<div class="card shadow">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">

    @if(in_array(5, $permisos))
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('datos/importarExcel')) ? 'active' : '' }}" href="{{route('datos.importarExcel')}}">Importar excel de datos</a>
      </li>
    @endif

    @if(in_array(3, $permisos))
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('datos/eliminarExcel')) ? 'active' : '' }}" href="{{route('datos.eliminarExcel')}}">Eliminar excel de datos</a>
      </li>
    @endif

    </ul>
  </div>

  <div class="card-body py-3 my-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          @yield('datosContent')
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
