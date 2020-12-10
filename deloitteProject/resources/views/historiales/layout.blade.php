@extends('layouts.app')
@section('content')

<div class="card shadow">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">

    @if(in_array(16, $permisos))
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('historiales/retenciones')) ? 'active' : '' }}" href="{{route('historiales.retenciones')}}">Historial de retenciones</a>
      </li>
    @endif

    @if(in_array(17, $permisos))
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('historiales/pagos')) ? 'active' : '' }}" href="{{route('historiales.pagos')}}">Historial de pagos</a>
      </li>
    @endif

    </ul>
  </div>

  <div class="card-body py-3 my-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          @yield('historialesContent')
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
