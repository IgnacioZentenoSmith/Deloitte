$(document).ready(function () {

    var form = $('#bitacorasForm');
    const headersTabla = ['ID', 'Usuario', 'Fecha', 'Acción', 'Tabla', 'Identificador', 'Detalles'];

    form.submit(function(e) {
        e.preventDefault();
        toggleSubmitButton(true);

        killChildren('modalesContainer');
        killChildren('tablaContainer');

        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serializeArray(),
            dataType: 'json',
            success : function (response)
            {
                toggleSubmitButton(false);
                console.log(response);
                generateTabla('tablaContainer', 'modalesContainer', response.bitacora, headersTabla)
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
