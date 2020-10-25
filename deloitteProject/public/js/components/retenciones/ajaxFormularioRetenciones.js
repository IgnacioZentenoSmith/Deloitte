$(document).ready(function () {

    var form = $('#cuotasForm');

    form.submit(function(e) {
        console.log(form.serializeArray());
        e.preventDefault();
        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serializeArray(),
            dataType: 'json',
            success : function ( json )
            {
                console.log(json);
                // Success
                // Do something like redirect them to the dashboard...
            },
            error: function( json )
            {
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
