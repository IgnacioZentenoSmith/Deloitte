@extends('layouts.app')
@section('content')

<div class="card shadow">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">

    @if(in_array(11, $permisos))
      <li class="nav-item">
        <a class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}" href="{{route('dashboard.index')}}">Dashboard</a>
      </li>
    @endif

    </ul>
  </div>

  <div class="card-body py-3 my-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          @yield('dashboardContent')
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
