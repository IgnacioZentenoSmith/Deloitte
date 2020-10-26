document.getElementsByName('radioMetodoBusqueda').forEach(input => {
    document.getElementById(input.id).addEventListener("change", onMetodoBusquedaChange, false);
});

function onMetodoBusquedaChange() {
    containerFiltrosMes = document.getElementById('containerFiltrosMes');
    containerFiltrosCuota = document.getElementById('containerFiltrosCuota');
    document.getElementById('labelFiltro').classList.remove("d-none");

    if (this.value == 'mes') {
        containerFiltrosCuota.classList.add("d-none");
        containerFiltrosMes.classList.remove("d-none");
        document.getElementById('cuotaMes').required = false;
        document.getElementById('cuotaYear').required = false;
        document.getElementById('mesFecha').required = true;

    } else if (this.value == 'cuota') {
        containerFiltrosCuota.classList.remove("d-none");
        containerFiltrosMes.classList.add("d-none");
        document.getElementById('cuotaMes').required = true;
        document.getElementById('cuotaYear').required = true;
        document.getElementById('mesFecha').required = false;
    }
}
