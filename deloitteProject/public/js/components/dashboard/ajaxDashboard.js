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
                if (response.metodoBusqueda == 'socio') {
                    rand = Math.floor(Math.random() * 900) / 10;
                    menosRand = 100 - rand;

                    pieData = {
                        data: [{
                            name: 'Retenido',
                            y: menosRand,
                        }, {
                            name: 'No retenido',
                            y: rand
                        }]
                    };

                    pieChartConstructor(
                        appendDivToParent('piePorcentajeMontos', 'col-6', 'graphsContainer'),
                        'Porcentaje de montos',
                        'Retenidos y pagados del total',
                        'Porcentaje',
                        pieData
                    );
                    barChartConstructor(
                        appendDivToParent('barCumplimientos', 'col-6', 'graphsContainer'),
                        response.categoriaMeses,
                        'Porcentaje de cumplimiento',
                        'Cumplimientos de ' + response.nombreSocio,
                        'detalle por meses',
                        response.barraCumplimientos
                    );
                    barChartConstructor(
                        appendDivToParent('barPagos', 'col-6', 'graphsContainer'),
                        response.categoriaMeses,
                        'Monto de los pagos por mes (en miles de pesos)',
                        'Pagos de '  + response.nombreSocio,
                        'detalle por meses',
                        response.barraPagos
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
