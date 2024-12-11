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
                    mostrarNotificacion(data.message); // Notificación primero
                    recargarTabla(); // Recargar tabla después de eliminar
                } else {
                    mostrarNotificacion(data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarNotificacion("Ocurrió un error al eliminar el evento.");
            });
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
                            <a href="eventos_noticias_admin_editar.php?id=${evento.ID_EVT_NOT}" class="btn btn-warning btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm" onclick="eliminarEvento(${evento.ID_EVT_NOT})">Eliminar</button>
                            <button class="btn btn-info btn-sm" onclick="cambiarEstado(${evento.ID_EVT_NOT}, '${evento.ESTADO_EVT_NOT}')">
                                ${evento.ESTADO_EVT_NOT === "Activo" ? "Ocultar" : "Activar"}
                            </button>
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
        .catch(error => {
            console.error("Error al recargar la tabla:", error);
            mostrarNotificacion("Error al recargar la tabla.");
        });
}

// Mostrar la notificación de eliminación
function mostrarNotificacion(mensaje) {
    const toastEl = document.getElementById("toastNotificacion");
    const toastBody = toastEl.querySelector(".toast-body");
    toastBody.innerText = mensaje;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Cambiar estado de un evento
function cambiarEstado(id, estadoActual) {
    const nuevoEstado = estadoActual === "Activo" ? "Oculto" : "Activo";

    fetch(`../src/eventos_noticias_admin_queries.php?action=changeState&id=${id}&newState=${nuevoEstado}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Estado cambiado!',
                    text: `El registro se ha actualizado a estado "${nuevoEstado}".`,
                    icon: 'success'
                }).then(() => {
                    recargarTabla(); // Actualizar tabla sin recargar la página
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error al cambiar el estado:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al cambiar el estado.',
                icon: 'error'
            });
        });
}

// Ejecutar la función para habilitar/deshabilitar ubicación al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    habilitarUbicacion();
    recargarTabla(); // Cargar los datos al iniciar la página
});
