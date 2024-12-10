// Función para habilitar/deshabilitar el campo "ubicación"
function habilitarUbicacion() {
    const tipo = document.getElementById("tipo").value;
    const ubicacion = document.getElementById("ubicacion");
    ubicacion.disabled = (tipo === "NOTICIA");
}

// Función para eliminar un evento usando AJAX
function eliminarEvento(id) {
    if (confirm("¿Estás seguro de eliminar este evento?")) {
        fetch(`eventos_noticias_admin_queries.php?action=delete&id=${id}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const fila = document.getElementById(`fila-${id}`);
                    fila.remove();
                    mostrarNotificacion("El registro se eliminó correctamente.");
                } else {
                    mostrarNotificacion("Error al eliminar el registro.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                mostrarNotificacion("Error de comunicación con el servidor.");
            });
    }
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
