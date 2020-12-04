@extends('datos.layout')
@section('datosContent')


<div class="row justify-content-center">
    <div class="col-auto">
        <div class="table-responsive">
            <table id="tablaEliminarExcel" class="table table-hover w-auto text-nowrap btTable" data-show-export="true"
                data-pagination="true" data-click-to-select="true" data-show-columns="true" data-sortable="true"
                data-search="true" data-live-search="true" data-buttons-align="left" data-search-align="right"
                data-server-sort="false">
                <thead>
                    <tr>
                        <th scope="col" data-field="ID" data-sortable="true">ID</th>
                        <th scope="col" data-field="Nombre" data-sortable="true">Nombre</th>
                        <th scope="col" data-field="FechaCarga" data-sortable="true">Fecha de carga</th>
                        <th scope="col" data-field="Acciones" data-sortable="true">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($excelData as $excel)
                    <tr>
                        <td>{{$excel['id']}}</td>
                        <td>{{$excel['exceldata_name']}}</td>
                        <td>{{$excel['created_at']}}</td>

                        <td>
                            @if(in_array(9, $permisos))
                            <form style="display: inline-block;" action="{{ route('datos.postEliminarExcel', $excel['id']) }}"
                                method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Eliminar</button>
                            </form>
                            @endif
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('js/components/initBTtables.js')}}"></script>

@endsection
