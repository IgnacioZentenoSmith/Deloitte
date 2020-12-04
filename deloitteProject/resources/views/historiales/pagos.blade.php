@extends('historiales.layout')
@section('historialesContent')


<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="col-md-6 offset-md-3">
            <form method="POST" id="cuotasForm" action="{{route('historiales.ajaxPagos')}}">
                @csrf

                <div class="form-row my-2" id="containerFiltrosMes">
                    <div class="form-group col-md-6">
                        <label for="mesFecha">Filtro por mes:</label>
                        <input type="month" id="mesFecha" name="mesFecha" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary" id="submitBusqueda">
                            Filtrar pagos
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-auto">
        <div class="col-md-12 my-1" id="montoContainer">
            <!-- Monto total a pagar -->
        </div>
        <div class="table-responsive" id="tablaContainer">
            <!-- Tabla de datos -->
        </div>
    </div>
</div>

<div id="modalesContainer">
    <!-- Modales -->
</div>

<script src="{{ asset('js/components/historiales/ajaxPagos.js')}}"></script>

@endsection
