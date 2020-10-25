@extends('retenciones.layout')
@section('retencionesContent')


<div class="row justify-content-center">
    <div class="col-auto">
        <div class="col-12">
            <form method="POST" id="cuotasForm" action="{{route('retenciones.index')}}">
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
                            step="1"/>
                    </div>
                </div>

                <div class="form-row my-2 d-none" id="containerFiltrosMes">
                    <div class="form-group col-md-6">
                        <label for="mesFecha">Fecha</label>
                        <input type="month" id="mesFecha" name="mesFecha" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            Filtrar retenciones
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="tablaAdmin" class="table table-hover w-auto text-nowrap btTable" data-show-export="true"
                data-pagination="true" data-click-to-select="true" data-show-columns="true" data-sortable="true"
                data-search="true" data-live-search="true" data-buttons-align="left" data-search-align="right"
                data-server-sort="false">
                <thead>
                    <tr>
                        <th scope="col" data-field="Nombre" data-sortable="true">Socio</th>
                        <th scope="col" data-field="Email" data-sortable="true">Cuota</th>
                        <th scope="col" data-field="Verificado" data-sortable="true">Valor por rendir</th>
                        <th scope="col" data-field="Role1" data-sortable="true">Fecha</th>
                        <th scope="col" data-field="Role" data-sortable="true">Retenido</th>
                        <th scope="col" data-field="Notificaciones" data-sortable="true">Monto retenido</th>
                        <th scope="col" data-field="Role3" data-sortable="true">Detalles</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Roberto</td>
                        <td>20000</td>
                        <td>3000</td>
                        <td>Octubre 2017</td>
                        <td>Si</td>
                        <td>8000</td>

                        <td><button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#exampleModal1">
                                Ver detalle
                            </button></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalles de pago de la cuota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table id="tdetalle1" class="table table-hover w-auto text-nowrap btTable"
                        data-click-to-select="true" data-sortable="true" data-server-sort="false">
                        <thead>
                            <tr>
                                <th scope="col" data-field="Fecha" data-sortable="true">Fecha</th>
                                <th scope="col" data-field="Cumplimiento" data-sortable="true">Cumplimiento</th>
                                <th scope="col" data-field="Verificado" data-sortable="true">Monto a pagar</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>Octubre 2017</td>
                                <td>50%</td>
                                <td>9000</td>
                            </tr>

                            <tr>
                                <td>Noviembre 2017</td>
                                <td>64%</td>
                                <td>0</td>
                            </tr>

                            <tr>
                                <td>Diciembre 2017</td>
                                <td>68%</td>
                                <td>0</td>
                            </tr>

                            <tr>
                                <td>Enero 2018</td>
                                <td>93%</td>
                                <td>8000</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/components/initBTtables.js')}}"></script>
<script src="{{ asset('js/components/retenciones/ajaxFormularioRetenciones.js')}}"></script>
<script src="{{ asset('js/components/retenciones/filtrosRetenciones.js')}}"></script>
@endsection
