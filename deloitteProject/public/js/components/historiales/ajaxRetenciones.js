$(document).ready(function () {

    var form = $('#cuotasForm');

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
                metodoBusqueda(response, form.serializeArray());
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
        document.getElementById('submitBusqueda').innerHTML = 'Filtrar retenciones';
    }
}

//Asesina cruel y dolorosamente a todos los hijos del nodo padre
function killChildren(node) {
    let parentNode = document.getElementById(node)
    while (parentNode.firstChild) {
        parentNode.removeChild(parentNode.firstChild);
    }
}

function metodoBusqueda(response, formData) {
    let headersTabla = [];
    let headersDetalles = [];
    let busqueda = '';
    if (formData[1].value == 'cuota') {
        headersTabla = ['ID socio', 'Socio', 'Monto de la cuota', 'Valor por rendir', 'Fecha de la Cuota', 'Detalles'];
        headersDetalles = ['Fecha', 'Cumplimiento', 'Monto a pagar', 'Retencion'];
        busqueda = 'cuota';
    }
    else if (formData[1].value == 'mes') {
        headersTabla = ['ID socio', 'Socio', 'Total cuotas', 'Total valor a pagar', 'Total valor por rendir', 'Total retenciones', 'Cumplimiento', 'Detalles'];
        headersDetalles = ['Cuota', 'Valor a pagar', 'Valor por rendir', 'Retencion', 'Fecha de la cuota'];
        busqueda = 'mes';
    }
    else if (formData[1].value == 'socio') {

    }
    generateTabla('modalesContainer', 'tablaContainer', response, headersDetalles, headersTabla, busqueda);
}

function generateTabla(idModalesContainer, idTablaContainer, response, headersDetalles, headersTabla, busqueda) {
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

    response.tabla.forEach(data => {
        if (busqueda == 'cuota') {
            tableString +=
            '<tr>'+
                '<td>' + data.socios_id + '</td>'+
                '<td>' + data.socios_nombre + '</td>'+
                '<td>' + data.cuotas_montoCuota + '</td>'+
                '<td>' + data.cuotas_valorPorRendir + '</td>'+
                '<td>' + data.cuotas_fecha + '</td>'+
                '<td>'+
                    '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' + data.socios_id + '"> Ver detalle</button>'+
                '</td>'+
            '</tr>';
            let detallesModal = response.detalles.filter(dataModal => {
                return dataModal.socios_id == data.socios_id;
            });

            generateModales(idModalesContainer, detallesModal, headersDetalles, busqueda, data.socios_id, data.socios_nombre);
        } else if (busqueda == 'mes') {
            tableString +=
            '<tr>'+
                '<td>' + data.IDsocio + '</td>'+
                '<td>' + data.socio + '</td>'+
                '<td>' + data.totalCuotas + '</td>'+
                '<td>' + data.totalPagar + '</td>'+
                '<td>' + data.totalValorPorRendir + '</td>'+
                '<td>' + data.totalRetenciones + '</td>'+
                '<td>' + data.cumplimiento + '</td>'+
                '<td>'+
                    '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' + data.IDsocio + '"> Ver detalle</button>'+
                '</td>'+
            '</tr>';
            let detallesModal = response.detalles.filter(dataModal => {
                return dataModal.socios_id == data.IDsocio;
            });

            generateModales(idModalesContainer, detallesModal, headersDetalles, busqueda, data.IDsocio, 'nombre');
        }
    });
    tableString +=
       '</tbody>'+
    '</table>';

    document.getElementById(idTablaContainer).innerHTML += tableString;
    initTable();
}

function generateModales(idModalesContainer, detallesModal, headersDetalles, busqueda, idModal, socioNombre) {
    let modalString =
    '<div class="modal fade" id="modal' + idModal + '" tabindex="-1" role="dialog" aria-labelledby="modal' + idModal + '"'+
        'aria-hidden="true">'+
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<h5 class="modal-title" id="exampleModalLabel_'+idModal+'">Detalles de pago de la cuota del socio: <br><strong>' + socioNombre + '</strong></h5>'+
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
    headersDetalles.forEach(head => {
        modalString += '<th scope="col" data-field="' + head.replace(/ /g,'') + '" data-sortable="true">' + head + '</th>';
    });
    modalString +=
            '</tr>'+
        '</thead>'+
        '<tbody>';

    detallesModal.forEach(modalData => {
        if (busqueda == 'cuota') {
            modalString +=
            '<tr>'+
                '<td>' + modalData.pagos_fecha + '</td>'+
                '<td>' + modalData.cumplimientos_porcentaje + '</td>'+
                '<td>' + modalData.pagos_montoPagar + '</td>'+
                '<td>' + modalData.pagos_montoRetencion + '</td>'+
            '</tr>';
        } else if (busqueda == 'mes') {
            modalString +=
            '<tr>'+
                '<td>' + modalData.cuotas_montoCuota + '</td>'+
                '<td>' + modalData.pagos_montoPagar + '</td>'+
                '<td>' + modalData.cuotas_valorPorRendir + '</td>'+
                '<td>' + modalData.pagos_montoRetencion + '</td>'+
                '<td>' + modalData.cuotas_fecha + '</td>'+
            '</tr>';

        } else if (busqueda == 'socio') {

        }
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
