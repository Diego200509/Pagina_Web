// Función para habilitar/deshabilitar el campo "ubicación"
function habilitarUbicacion() {
    const tipo = document.getElementById("tipo").value;
    const ubicacion = document.getElementById("ubicacion");
    ubicacion.disabled = (tipo === "NOTICIA");
}

// Función para eliminar un evento usando AJAX
function eliminarEvento(id) {
    if (confirm("¿Estás seguro de eliminar este evento?")) {
        fetch(`../src/eventos_noticias_admin_queries.php?action=delete&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    recargarTabla(); // Recargar la tabla después de eliminar
                    mostrarNotificacion(data.message);
                } else {
                    mostrarNotificacion(data.message);
                }
            })
            .catch(error => console.error("Error:", error));
    }
}

// Función para recargar la tabla
function recargarTabla() {
    fetch(`../src/eventos_noticias_admin_queries.php?action=fetch`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tabla-eventos tbody");
            tbody.innerHTML = ""; // Limpiar tabla

            if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                data.data.forEach(evento => {
                    const fila = document.createElement("tr");
                    fila.id = `fila-${evento.ID_EVT_NOT}`;
                    fila.innerHTML = `
                        <td>${evento.ID_EVT_NOT}</td>
                        <td>${evento.TIT_EVT_NOT}</td>
                        <td>${evento.FECHA_EVT_NOT}</td>
                        <td>${evento.TIPO_REG_EVT_NOT}</td>
                        <td>${evento.ESTADO_EVT_NOT}</td>
                        <td>
                            <button class="btn btn-warning btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarEvento(${evento.ID_EVT_NOT})">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                // Mostrar mensaje si no hay datos
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td colspan="6" class="text-center">No hay registros disponibles</td>
                `;
                tbody.appendChild(fila);
            }
        })
        .catch(error => console.error("Error al recargar la tabla:", error));
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
document.addEventListener("DOMContentLoaded", () => {
    habilitarUbicacion();
    recargarTabla(); // Cargar los datos al iniciar la página
});
