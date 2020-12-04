$(document).ready(function () {

    var form = $('#cuotasForm');
    const headersTabla = ['ID socio', 'Socio', 'Monto a pagar', 'Cumplimiento', 'Detalles'];
    const headersModales = ['Fecha cuota', 'Monto cuota', 'Monto a pagar', 'Retención'];

    form.submit(function(e) {
        e.preventDefault();
        toggleSubmitButton(true);
        killChildren('modalesContainer');
        killChildren('tablaContainer');
        killChildren('montoContainer');

        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serializeArray(),
            dataType: 'json',
            success : function (response)
            {
                toggleSubmitButton(false);
                console.log(response);
                //response.pagosData
                generateTotalPagar('montoContainer', response.totalPagar);
                generateTabla('tablaContainer', response.tabla, headersTabla, 'modalesContainer', response.detalles, headersModales);
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

function initTable() {
    document.getElementsByClassName('btTable').forEach(table => {
        $('#' + table.id).bootstrapTable({
            pageSize: 50,
            exportDataType: 'all',
        });
    });
}

function toggleSubmitButton(isAjaxRequest) {
    if (isAjaxRequest) {
        document.getElementById('submitBusqueda').disabled = true;
        document.getElementById('submitBusqueda').innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
        ' Cargando datos...';
    } else {
        document.getElementById('submitBusqueda').disabled = false;
        document.getElementById('submitBusqueda').innerHTML = 'Filtrar pagos';
    }
}

//Asesina cruel y dolorosamente a todos los hijos del nodo padre
function killChildren(node) {
    let parentNode = document.getElementById(node)
    while (parentNode.firstChild) {
        parentNode.removeChild(parentNode.firstChild);
    }
}

function generateTabla(idTablaContainer, tableData, headersTabla, idModalesContainer, modalesData, headersModales) {
    let tableString =
    '<table id="tablaDatos" class="table table-hover w-auto text-nowrap btTable" data-show-export="true"'+
        'data-pagination="true" data-click-to-select="true" data-show-columns="true" data-sortable="true"'+
        'data-search="true" data-live-search="true" data-buttons-align="left" data-search-align="right">'+
        '<thead>'+
            '<tr>';
    headersTabla.forEach(head => {
        tableString += '<th scope="col" data-field="' + head.replace(/ /g,'') + '" data-sortable="true">' + head + '</th>';
    });
    tableString +=
            '</tr>'+
        '</thead>'+
        '<tbody>';

    tableData.forEach(data => {
        tableString +=
        '<tr>'+
            '<td>' + data.IDsocio + '</td>'+
            '<td>' + data.socio + '</td>'+
            '<td>' + data.totalPagar + '</td>'+
            '<td>' + data.cumplimiento + '% </td>'+
            '<td>'+
                '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' + data.IDsocio + '"> Ver cuotas <span class="badge badge-light">' + data.cantidadCuotas + '</span></button>'+
            '</td>'+
        '</tr>';

        let detallesModal = modalesData.filter(dataModal => {
            return dataModal.socios_id == data.IDsocio;
        });

        generateModales(idModalesContainer, detallesModal, headersModales, data.IDsocio);

    });
    tableString +=
       '</tbody>'+
    '</table>';

    document.getElementById(idTablaContainer).innerHTML += tableString;
    initTable();
}

function generateModales(idModalesContainer, detallesModal, headersModales, idModal) {
    let modalString =
    '<div class="modal fade" id="modal' + idModal + '" tabindex="-1" role="dialog" aria-labelledby="modal' + idModal + '"'+
        'aria-hidden="true">'+
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<h5 class="modal-title" id="exampleModalLabel_'+idModal+'">Detalles de las cuotas con pagos</h5>'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                    '</button>'+
                '</div>'+
                '<div class="modal-body">'+
                    '<div class="table-responsive">'+
                        '<table class="table table-hover w-auto text-nowrap btTable"'+
                            'data-click-to-select="true" data-sortable="true" data-server-sort="false">'+
                            '<thead>'+
                                '<tr>';
    headersModales.forEach(head => {
        modalString += '<th scope="col" data-field="' + head.replace(/ /g,'') + '" data-sortable="true">' + head + '</th>';
    });
    modalString +=
            '</tr>'+
        '</thead>'+
        '<tbody>';

    detallesModal.forEach(modalData => {
         modalString +=
            '<tr>'+
                '<td>' + modalData.cuotas_fecha + '</td>'+
                '<td>' + modalData.cuotas_montoCuota + '</td>'+
                '<td>' + modalData.pagos_montoPagar + '</td>'+
                '<td>' + modalData.pagos_montoRetencion + '</td>'+
            '</tr>';
    });
    modalString +=
                            '</tbody>'+
                        '</table>'+
                    '</div>'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';
    document.getElementById(idModalesContainer).innerHTML += modalString;
}

function generateTotalPagar(idMontoContainer, totalPagar) {
    console.log(totalPagar);
    let totalPagarString =
    '<div class="alert alert-info" role="alert">'+
        'Monto total a pagar en esta fecha: ' + totalPagar +
    '</div>';
    document.getElementById(idMontoContainer).innerHTML += totalPagarString;
}
