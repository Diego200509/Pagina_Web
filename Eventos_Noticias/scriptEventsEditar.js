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

document.getElementById('imagen').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            alert('Solo se permiten imágenes de tipo JPG, JPEG o PNG.');
            event.target.value = '';
        }
    }
});