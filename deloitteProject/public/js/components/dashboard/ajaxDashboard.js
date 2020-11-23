$(document).ready(function () {

    var form = $('#cuotasForm');

    form.submit(function(e) {
        e.preventDefault();
        toggleSubmitButton(true);

        killChildren('graphsContainer');

        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serializeArray(),
            dataType: 'json',
            success : function (response)
            {
                toggleSubmitButton(false);
                console.log(response);
                if (response.metodoBusqueda == 'cuota') {
                    pieChartConstructor(
                        appendDivToParent('piePorcentajeRetenciones', 'graphsContainer', 'col-md-6 col-xs-12'),
                        'Porcentaje de cuotas',
                        'cuotas pagadas y retenidas',
                        'Porcentaje',
                        response.piePorcentajeRetenidos
                    );
                    barChartConstructor(
                        appendDivToParent('barPromediosPorMes', 'graphsContainer' ,'col-md-6 col-xs-12'),
                        response.categoriaMeses,
                        'Porcentaje del promedio de cumplimiento por meses',
                        'Promedio de cumplimientos',
                        'detalle por meses',
                        response.barPromedioCumplimientos,
                        'bar'
                    );

                    areaChartConstructor(
                        appendDivToParent('areaMontosMes', 'graphsContainer' ,'col'),
                        'Monto acumulado de pagos y retenciones por mes',
                        response.categoriaMeses,
                        'Monto en miles de pesos',
                        response.areaTotalMontosMes,
                    );
                }
                else if (response.metodoBusqueda == 'mes') {
                    pieChartConstructor(
                        appendDivToParent('piePorcentajeRetenciones', 'graphsContainer', 'col-md-6 col-xs-12'),
                        'Porcentaje de cuotas',
                        'cuotas pagadas y retenidas',
                        'Porcentaje',
                        response.piePorcentajeRetenidos
                    );
                    barChartConstructor(
                        appendDivToParent('barPromediosPorMes', 'graphsContainer' ,'col-md-6 col-xs-12'),
                        response.mes,
                        'Monto en miles de CLP',
                        'Monto de pagos y retenciones',
                        '',
                        response.barTotalMontos,
                        'bar'
                    );
                    barChartConstructor(
                        appendDivToParent('barCumplimientos', 'graphsContainer' ,'col'),
                        response.nombreSocios,
                        'Porcentaje de cumplimiento',
                        'Cumplimientos de los socios',
                        '',
                        response.barCumplimientosThisMonth,
                        'column',
                        true
                    );
                }
                else if (response.metodoBusqueda == 'socio') {

                    pieChartConstructor(
                        appendDivToParent('piePorcentajeMontos', 'graphsContainer', 'col-md-6 col-xs-12'),
                        'Porcentaje de montos',
                        'Retenidos y pagados del total',
                        'Porcentaje',
                        response.piePorcentaje
                    );
                    barChartConstructor(
                        appendDivToParent('barCumplimientos', 'graphsContainer' ,'col-md-6 col-xs-12'),
                        response.categoriaMeses,
                        'Porcentaje de cumplimiento',
                        'Cumplimientos de ' + response.nombreSocio,
                        'detalle por meses',
                        response.barraCumplimientos,
                        'bar'
                    );
                    barChartConstructor(
                        appendDivToParent('barPagos', 'graphsContainer' ,'col-md-6 col-xs-12'),
                        response.categoriaMeses,
                        'Monto de los pagos y retenciones por mes (en miles de pesos)',
                        'Pagos y retenciones de '  + response.nombreSocio,
                        'detalle por meses',
                        response.barraPagos,
                        'bar'
                    );
                    barChartConstructor(
                        appendDivToParent('barCuotas', 'graphsContainer' ,'col-md-6 col-xs-12'),
                        response.categoriaCuotas,
                        'Montos en miles de pesos',
                        'Cuotas y pagos por rendir de '  + response.nombreSocio,
                        'detalle por cuota',
                        response.barraCuotas,
                        'column'
                    );
                }

                //metodoBusqueda(response, form.serializeArray());
                // Success
                // Do something like redirect them to the dashboard...
            },
            error: function( json )
            {
                toggleSubmitButton(false);
                if(json.status === 422) {
                    var errors = json.responseJSON;
                    $.each(json.responseJSON, function (key, value) {
                        $('.'+key+'-error').html(value);
                    });
                } else {
                    // Error
                    // Incorrect credentials
                    // alert('Incorrect credentials. Please try again.')
                }
            }
        });
    });
});
