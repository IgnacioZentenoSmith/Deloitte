@extends('bitacora.layout')
@section('bitacoraContent')

<div class="row justify-content-center">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
            </div>
            <div class="card-body">


            <form method="POST" action="{{route('bitacora.ajaxBitacoras')}}" class="form-inline" id="bitacorasForm">
                    @csrf

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-2">
                            <label for="filter_usuarios" class="col-md-12 col-form-label text-md-right">Usuario:
                            </label>
                            <div class="col-md-12">
                                <select class="form-control" id="filter_usuarios" name="filter_usuarios">
                                    <option value="" selected>Ninguno seleccionado</option>
                                    <!-- Permitir solo clientes padres desde Backend -->
                                    @foreach($uniqueUsers as $uniqueUser)
                                    <option value="{{$uniqueUser['users_id']}}">{{$uniqueUser['userName']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <label for="filter_actions" class="col-md-12 col-form-label text-md-right">Acción: </label>
                            <div class="col-md-12">
                                <select class="form-control" id="filter_actions" name="filter_actions">
                                    <option value="" selected>Ninguno seleccionado</option>
                                    <!-- Permitir solo clientes padres desde Backend -->
                                    @foreach($uniqueActions as $uniqueAction)
                                    <option value="{{$uniqueAction['bitacoras_accion']}}">
                                        {{$uniqueAction['bitacoras_accion']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <label for="filter_tables" class="col-md-12 col-form-label text-md-right">Tabla: </label>
                            <div class="col-md-12">
                                <select class="form-control" id="filter_tables" name="filter_tables">
                                    <option value="" selected>Ninguno seleccionado</option>
                                    <!-- Permitir solo clientes padres desde Backend -->
                                    @foreach($uniqueTables as $uniqueTable)
                                    <option value="{{$uniqueTable['bitacoras_tabla']}}">
                                        {{$uniqueTable['bitacoras_tabla']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <label for="filter_fecha_desde" class="col-md-12 col-form-label text-md-right">Desde:
                            </label>
                            <div class="col-md-12">
                                <input id="filter_fecha_desde" type="date" class="form-control" name="filter_fecha_desde">
                            </div>
                        </div>


                        <div class="col-md-2">
                            <label for="filter_fecha_hasta" class="col-md-12 col-form-label text-md-right">Hasta:
                            </label>
                            <div class="col-md-12">
                                <input id="filter_fecha_hasta" type="date" class="form-control" name="filter_fecha_hasta">
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mt-3">
                        <div class="alert alert-info" role="alert">
                            <strong>Nota:</strong> Filtrar sin seleccionar filtros traerá todos los datos de la bitácora almacenados en el sistema.
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBusqueda">Filtrar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <div class="col-auto">
        <div class="table-responsive" id="tablaContainer">
            <!-- Tabla de datos -->
        </div>
    </div>
</div>

<div id="modalesContainer">
    <!-- Modales -->
</div>

<script src="{{ asset('js/components/bitacora/utils.js')}}"></script>
<script src="{{ asset('js/components/bitacora/generateElements.js')}}"></script>
<script src="{{ asset('js/components/bitacora/ajaxBitacora.js')}}"></script>
@endsection
