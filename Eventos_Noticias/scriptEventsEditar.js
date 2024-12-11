document.addEventListener("DOMContentLoaded", function () {
    const tipo = document.getElementById("tipo");
    const ubicacion = document.getElementById("ubicacion");

    let valorInicialUbicacion = ubicacion.value;
    function manejarUbicacion() {
        if (tipo.value === "NOTICIA") {
            valorInicialUbicacion = ubicacion.value; 
            ubicacion.value = ""; 
            ubicacion.disabled = true; 
        } else {
            ubicacion.disabled = false; 
            ubicacion.value = valorInicialUbicacion;
        }
    }

    manejarUbicacion();

    tipo.addEventListener("change", manejarUbicacion);
});