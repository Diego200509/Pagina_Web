// Función para habilitar/deshabilitar el campo "ubicación"
function habilitarUbicacion() {
    const tipo = document.getElementById("tipo").value;
    const ubicacion = document.getElementById("ubicacion");
    ubicacion.disabled = (tipo === "NOTICIA");
}

// Mostrar la notificación de eliminación
function mostrarNotificacion(mensaje) {
    const toastEl = document.getElementById("toastNotificacion");
    const toastBody = toastEl.querySelector(".toast-body");
    toastBody.innerText = mensaje;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Ejecutar la función para habilitar/deshabilitar ubicación al cargar la página
document.addEventListener("DOMContentLoaded", habilitarUbicacion);
