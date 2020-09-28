@extends('dashboard.layout')
@section('dashboardContent')
<div class="row justify-content-center">

    <div class="col-6">
        <figure class="highcharts-figure">
            <div id="barChart"></div>
        </figure>
    </div>
    <div class="col-6">
        <figure class="highcharts-figure">
            <div id="pieChart"></div>
        </figure>
    </div>
    <div class="col-12">
        <figure class="highcharts-figure">
            <div id="areaChart"></div>
        </figure>
    </div>



</div>
@endsection
