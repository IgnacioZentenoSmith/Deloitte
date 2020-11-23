@extends('dashboard.layout')
@section('dashboardContent')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="col-md-6 offset-md-3">
            <form method="POST" id="cuotasForm" action="{{route('dashboard.ajax')}}">
                @csrf
                <div class="form-row">
                    <label class="form-check-label" for="containerMetodoBusqueda">Método de búsqueda:</label>
                </div>
                <div class="form-row my-2">
                    <div class="form-check form-check-inline" id="containerMetodoBusqueda">
                        <input class="form-check-input" type="radio" name="radioMetodoBusqueda" id="radioCuota"
                            value="cuota" required>
                        <label class="form-check-label" for="radioCuota">Por cuota</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioMetodoBusqueda" id="radioMes"
                            value="mes">
                        <label class="form-check-label" for="radioMes">Por mes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioMetodoBusqueda" id="radioSocio"
                            value="socio">
                        <label class="form-check-label" for="radioSocio">Por socio</label>
                    </div>
                </div>


                <div class="form-row d-none" id="labelFiltro">
                    <label class="form-check-label">Filtros:</label>
                </div>
                <div class="form-row my-2 d-none" id="containerFiltrosCuota">
                    <div class="form-group col-md-6">
                        <label for="cuotaMes">Cuota</label>
                        <select class="form-control" id="cuotaMes" name="cuotaMes">
                            <option value="Agosto">Agosto</option>
                            <option value="Noviembre">Noviembre</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cuotaYear">Año</label>
                        <input type="number" min="2000" max="3000" id="cuotaYear" name="cuotaYear" class="form-control"
                            step="1" />
                    </div>
                </div>

                <div class="form-row my-2 d-none" id="containerFiltrosMes">
                    <div class="form-group col-md-6">
                        <label for="mesFecha">Fecha</label>
                        <input type="month" id="mesFecha" name="mesFecha" class="form-control" />
                    </div>
                </div>

                <div class="form-row my-2 d-none" id="containerFiltrosSocio">
                    <div class="form-group col-md-6">
                        <label for="selectSocio">Seleccione socio:</label>
                        <select class="form-control" id="selectSocio" name="selectSocio">
                            @foreach ($socios as $socio)
                                <option value="{{ $socio['id'] }}">{{ $socio['socios_nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary" id="submitBusqueda">
                            Generar dashboard
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="row" id="graphsContainer">

</div>

<script src="{{ asset('js/libs/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('js/libs/highcharts/exporting.js')}}"></script>
<script src="{{ asset('js/libs/highcharts/export-data.js')}}"></script>
<script src="{{ asset('js/libs/highcharts/accessibility.js')}}"></script>

<script src="{{ asset('js/components/dashboard/pieChart.js')}}"></script>
<script src="{{ asset('js/components/dashboard/barChart.js')}}"></script>
<script src="{{ asset('js/components/dashboard/areaChart.js')}}"></script>

<script src="{{ asset('js/components/dashboard/filtrosDashboard.js')}}"></script>
<script src="{{ asset('js/components/dashboard/utils.js')}}"></script>
<script src="{{ asset('js/components/dashboard/ajaxDashboard.js')}}"></script>
@endsection
