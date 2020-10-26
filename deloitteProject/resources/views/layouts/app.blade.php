<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy"
    content="default-src 'self';
    style-src 'self' 'unsafe-inline';
    script-src 'self';
    font-src *;">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

  	<!-- CSS STYLES -->
    <link href="{{ asset('css/libs/familyTitilliumWeb.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
	<!-- Bootstrap CSS -->
    <link href="{{ asset('css/libs/bootstrap.min.css') }}" rel="stylesheet">
	<!-- Font Awesome CSS -->
    <link href="{{ asset('css/libs/fontAwesomeAll.css') }}" rel="stylesheet">
    <link href="{{ asset('css/libs/pretty-checkbox.min.css') }}" rel="stylesheet">
	<!-- JS -->
	<!-- jQuery JS -->
    <script src="{{ asset('js/libs/jquery-3.4.1.min.js')}}"></script>
	<!-- Popper JS -->
    <script src="{{ asset('js/libs/popper.min.js')}}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/libs/bootstrap.min.js')}}"></script>
    <!-- Font Awesome JS -->
    <script src="{{ asset('js/libs/solid.js')}}"></script>
    <script src="{{ asset('js/libs/fontawesome.js')}}"></script>
	<!-- Bootstrap Tables-->
    <link href="{{ asset('css/libs/bootstrap-table.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/bootstrap-table.min.js')}}"></script>
	<!-- Bootstrap Tables Export Extension -->
    <script src="{{ asset('js/libs/bootstrap-table-export.min.js')}}"></script>
	<!-- JS Exports plugin Bootstrap Tables-->
    <script src="{{ asset('js/libs/tableExport.min.js')}}"></script>
    <script src="{{ asset('js/libs/jspdf.min.js')}}"></script>
    <script src="{{ asset('js/libs/jspdf.plugin.autotable.js')}}"></script>

</head>
<body>
    <div id="app" class="d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow d-flex align-items-start">
            <div class="container-fluid">
                <ul class="navbar-nav mr-auto text-center">
                    <a class="navbar-brand">
                        Sistema Deloitte
                    </a>
                </ul>
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav m-auto text-center">
                    @guest
                    @else

                            {{-- <li class="nav-item mx-2 {{ (request()->is('/')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('home.index')}}">
                                    <span style="font-size: 1.5em;">
                                        <i class="fas fa-home"></i>
                                    </span><br>
                                    Home
                                </a>
                            </li>
                        @if(in_array(1, $authPermisos))
                            <li class="nav-item mx-2 {{ (request()->is('admin*')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('admin.index')}}">
                                    <span style="font-size: 1.5em;">
                                        <i class="fas fa-shield-alt"></i>
                                    </span><br>
                                    Admin
                                </a>
                            </li>
                        @endif --}}

                        @if(in_array(1, $permisos))
                        <li class="nav-item mx-2 {{ (request()->is('/')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('home.index')}}">
                                <span style="font-size: 1.5em;">
                                    <i class="fas fa-home"></i>
                                </span><br>
                                Home
                            </a>
                        </li>
                        @endif

                        @if(in_array(2, $permisos))
                        <li class="nav-item mx-2 {{ (request()->is('admin*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('admin.index')}}">
                                <span style="font-size: 1.5em;">
                                    <i class="fas fa-shield-alt"></i>
                                </span><br>
                                Admin
                            </a>
                        </li>
                        @endif

                        @if(in_array(2, $permisos))
                        <li class="nav-item mx-2 {{ (request()->is('retenciones*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('retenciones.index')}}">
                                <span style="font-size: 1.5em;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </span><br>
                                Retenciones
                            </a>
                        </li>
                        @endif

                        @if(in_array(10, $permisos))
                        <li class="nav-item mx-2 {{ (request()->is('dashboard*')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('dashboard.index')}}">
                                <span style="font-size: 1.5em;">
                                    <i class="fas fa-chart-line"></i>
                                </span><br>
                                Dashboard
                            </a>
                        </li>
                        @endif

                    @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }} <i class="fas fa-sign-in-alt"></i></a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" id="logout">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
            </div>
        </nav>
        @include('flashMessages')

    <main class="py-4 my-4 d-flex align-items-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                @guest
                    <!-- NO ESTA DENTRO DEL SISTEMA -->
                    @yield('content')
                @else
                    <!-- ESTA DENTRO DEL SISTEMA -->
                    @yield('content')
                @endguest

                </div>
            </div>
        </div>
    </main>

    <footer class="text-white d-flex align-items-end bg-dark mt-auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3 mt-2">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mt-2"><i class="fab fa-linkedin"></i> LinkedIn</p>
                                <p class="mt-1"><i class="fab fa-youtube"></i> Youtube
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mt-2"><i class="fab fa-facebook-square"></i> Facebook</p>
                                <p class="mt-1"><i class="fab fa-twitter-square"></i> Twitter
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt-2 text-center">
                        <img src="{{asset('img/footer/').'/'.'deloitte.svg'}}" alt="" style="width:150px; height:30px;">
                        <hr class="bg-white">
                        <p class="mt-1">© 2020 Deloitte S.A. Todos los derechos reservados.</p>
                    </div>
                    <div class="col-sm-3 mt-2">
                        <div class="row">
                            <div class="col-sm-6">&nbsp;</div>
                                <div class="col-sm-6">
                                    <p class="my-1"><img src="{{asset('img/footer/').'/'.'mail.svg'}}" alt=""> Mail de contacto:</p>
                                    <p class="my-0">rzenteno</p>
                                    <p class="my-0">cportilla</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('js/components/logout.js')}}"></script>
</body>
</html>
