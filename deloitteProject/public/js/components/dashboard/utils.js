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

function appendDivToParent(idName, className, parentElement) {
    let parent = document.getElementById(parentElement);
    let newDiv = document.createElement('div');
    newDiv.className = className;
    newDiv.id = idName;
    parent.appendChild(newDiv);
    return idName;
}
