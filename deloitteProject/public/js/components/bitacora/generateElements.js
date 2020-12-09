function generateTabla(idTablaContainer, idModalesContainer, tableData, headersTabla) {
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
            '<td>' + data.id + '</td>'+
            '<td>' + data.userName + '</td>'+
            '<td>' + data.created_at + '</td>'+
            '<td>' + data.bitacoras_accion + '</td>'+
            '<td>' + data.bitacoras_tabla + '</td>'+
            '<td>' + data.bitacoras_filaId + '</td>'+
            '<td>'+
                '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' + data.id + '">Ver detalles</button>'+
            '</td>'+
        '</tr>';

        generateModales(idModalesContainer, data.bitacoras_preValores, data.bitacoras_postValores, data.id);

    });
    tableString +=
       '</tbody>'+
    '</table>';

    document.getElementById(idTablaContainer).innerHTML += tableString;
    initTable();
}

function generateModales(idModalesContainer, preValores, postValores, idModal) {
    let modalString =
    '<div class="modal fade" id="modal' + idModal + '" tabindex="-1" role="dialog" aria-labelledby="modal' + idModal + '"'+
        'aria-hidden="true">'+
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<h5 class="modal-title" id="exampleModalLabel_'+idModal+'">Detalle de datos modificados</h5>'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                    '</button>'+
                '</div>'+
                '<div class="modal-body">';


    if (preValores != null) {
        modalString +=
        '<div class="table-responsive">'+
            '<table class="table table-hover w-auto text-nowrap btTable"'+
                'data-click-to-select="true" data-sortable="true" data-server-sort="false">'+
                    '<thead>'+
                         '<tr>';
        let headersPreValores = Object.keys(preValores);
        headersPreValores.forEach(head => {
            modalString += '<th scope="col" data-field="' + head.replace(/ /g,'') + '" data-sortable="true">' + head + '</th>';
        });
        modalString +=
                        '</tr>'+
                    '</thead>'+
                    '<tbody>'+
                        '<tr>';

        for (let [key, value] of Object.entries(preValores)) {
            modalString +=
            '<td>' + value + '</td>';
        }
        modalString +=
                        '</tr>'+
                    '</tbody>'+
                '</table>'+
            '</div>';
    }

    if (postValores != null) {
        modalString +=
        '<div class="table-responsive">'+
            '<table class="table table-hover w-auto text-nowrap btTable"'+
                'data-click-to-select="true" data-sortable="true" data-server-sort="false">'+
                    '<thead>'+
                         '<tr>';
        let headersPostValores = Object.keys(postValores);
        headersPostValores.forEach(head => {
            modalString += '<th scope="col" data-field="' + head.replace(/ /g,'') + '" data-sortable="true">' + head + '</th>';
        });
        modalString +=
                        '</tr>'+
                    '</thead>'+
                    '<tbody>'+
                        '<tr>';

        for (let [key, value] of Object.entries(postValores)) {
            modalString +=
            '<td>' + value + '</td>';
        }
        modalString +=
                        '</tr>'+
                    '</tbody>'+
                '</table>'+
            '</div>';
    }

    /*
    detallesModal.forEach(modalData => {
         modalString +=
            '<tr>'+
                '<td>' + modalData.cuotas_fecha + '</td>'+
                '<td>' + modalData.cuotas_montoCuota + '</td>'+
                '<td>' + modalData.pagos_montoPagar + '</td>'+
                '<td>' + modalData.pagos_montoRetencion + '</td>'+
            '</tr>';
    });
    */
    modalString +=
                '</div>'+
                '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';
    document.getElementById(idModalesContainer).innerHTML += modalString;
}
