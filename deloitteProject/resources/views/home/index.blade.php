@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">Home</div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-3 text-center my-2">
                            <h6>Home</h6>
                            <img src="{{asset('img/home/').'/'.'homeHouse.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Vista de recepción del sistema, entrega una breve descripción de cada uno de los módulos
                                existentes dentro de él.</p>
                        </div>
                        <div class="col-3 text-center my-2">
                            <h6>Admin</h6>
                            <img src="{{asset('img/home/').'/'.'homeAdmin.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Módulo encargado de la creación, eliminación y editado de los usuarios, tanto de sus
                                datos como de sus permisos.</p>
                        </div>
                        <div class="col-3 text-center my-2">
                            <h6>Datos</h6>
                            <img src="{{asset('img/home/').'/'.'homeData.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Módulo encargado de importar datos de los Excel y la eliminación de algún Excel en caso
                                de ser necesario.</p>
                        </div>
                        <div class="col-3 text-center my-2">
                            <h6>Historiales</h6>
                            <img src="{{asset('img/home/').'/'.'homeHistoriales.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Módulo encargado de mostrar el detalle de las retenciones y los pagos de los socios.</p>
                        </div>
                        <div class="col-3 text-center my-2">
                            <h6>Dashboard</h6>
                            <img src="{{asset('img/home/').'/'.'homeDashboard.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Módulo encargado de mostrar de forma resumida a través de gráficos la información de las
                                cuotas y de los socios.</p>
                        </div>
                        <div class="col-3 text-center my-2">
                            <h6>Bitácoras</h6>
                            <img src="{{asset('img/home/').'/'.'homeBitacora.svg'}}" class="img-fluid"
                                alt="Responsive image">
                            <p>Módulo encargado de mostrar, en base a los filtros especificados, todas las acciones
                                importantes
                                que han sido realizadas en el sistema.
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
