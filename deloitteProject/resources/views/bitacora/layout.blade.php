@extends('layouts.app')
@section('content')

<div class="card shadow">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('bitacora*')) ? 'active' : '' }}" href="{{route('bitacora.index')}}">Bitácora de registros</a>
      </li>
    </ul>
  </div>

  <div class="card-body py-3 my-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          @yield('bitacoraContent')
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
