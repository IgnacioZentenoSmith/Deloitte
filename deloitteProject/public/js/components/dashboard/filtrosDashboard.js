document.getElementsByName('radioMetodoBusqueda').forEach(input => {
    document.getElementById(input.id).addEventListener("change", onMetodoBusquedaChange, false);
});

function onMetodoBusquedaChange() {
    containerFiltrosMes = document.getElementById('containerFiltrosMes');
    containerFiltrosCuota = document.getElementById('containerFiltrosCuota');
    containerFiltrosSocio = document.getElementById('containerFiltrosSocio');
    document.getElementById('labelFiltro').classList.remove("d-none");

    if (this.value == 'mes') {
        // Mostrar contenedor de los sub filtros
        containerFiltrosCuota.classList.add("d-none");
        containerFiltrosMes.classList.remove("d-none");
        containerFiltrosSocio.classList.add("d-none");
        // Hacer esos sub filtros requeridos
        document.getElementById('cuotaMes').required = false;
        document.getElementById('cuotaYear').required = false;
        document.getElementById('mesFecha').required = true;
        document.getElementById('selectSocio').required = false;

    }
    else if (this.value == 'cuota') {
        // Mostrar contenedor de los sub filtros
        containerFiltrosCuota.classList.remove("d-none");
        containerFiltrosMes.classList.add("d-none");
        containerFiltrosSocio.classList.add("d-none");
        // Hacer esos sub filtros requeridos
        document.getElementById('cuotaMes').required = true;
        document.getElementById('cuotaYear').required = true;
        document.getElementById('mesFecha').required = false;
        document.getElementById('selectSocio').required = false;

    }
    else if (this.value == 'socio') {
        // Mostrar contenedor de los sub filtros
        containerFiltrosCuota.classList.add("d-none");
        containerFiltrosMes.classList.add("d-none");
        containerFiltrosSocio.classList.remove("d-none");
        // Hacer esos sub filtros requeridos
        document.getElementById('cuotaMes').required = false;
        document.getElementById('cuotaYear').required = false;
        document.getElementById('mesFecha').required = false;
        document.getElementById('selectSocio').required = true;
    }
}
