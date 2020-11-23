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
        document.getElementById('submitBusqueda').innerHTML = 'Generar dashboard';
    }
}

//Asesina cruel y dolorosamente a todos los hijos del nodo padre
function killChildren(node) {
    let parentNode = document.getElementById(node)
    while (parentNode.firstChild) {
        parentNode.removeChild(parentNode.firstChild);
    }
}

function appendDivToParent(idName, parentElement, classname) {
    /*
    Objetivo:
    <div class="col-6">
        <figure class="highcharts-figure">
            <div id="idname"></div>
        </figure>
    </div>
    */
    let parent = document.getElementById(parentElement);
    let divcol6 = document.createElement('div');
    divcol6.className = classname;
    let figure = document.createElement('figure');
    figure.className = 'highcharts-figure';
    let divChartContainer = document.createElement('div');
    divChartContainer.id = idName;

    parent.appendChild(divcol6);
    divcol6.appendChild(figure);
    figure.appendChild(divChartContainer);
    return idName;
}
