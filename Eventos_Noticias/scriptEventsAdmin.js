// Función para habilitar/deshabilitar el campo "ubicación"
function habilitarUbicacion() {
    const tipo = document.getElementById("tipo").value;
    const ubicacion = document.getElementById("ubicacion");
    ubicacion.disabled = (tipo === "NOTICIA");
}

// Función para eliminar un evento
function eliminarEvento(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`../src/eventos_noticias_admin_queries.php?action=delete&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Eliminado!",
                            text: data.message,
                            icon: "success"
                        });
                        recargarTabla(); // Recargar tabla
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al eliminar el evento:", error);
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un error al eliminar el evento.",
                        icon: "error"
                    });
                });
        }
    });
}

// Función para editar un evento
function editarEvento(id) {
    fetch(`../src/eventos_noticias_admin_queries.php?action=fetchById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const evento = data.data;
                document.getElementById("id").value = evento.ID_EVT_NOT;
                document.getElementById("titulo").value = evento.TIT_EVT_NOT;
                document.getElementById("descripcion").value = evento.DESC_EVT_NOT;
                document.getElementById("fecha").value = evento.FECHA_EVT_NOT;
                document.getElementById("tipo").value = evento.TIPO_REG_EVT_NOT;
                document.getElementById("ubicacion").value = evento.UBICACION_EVT_NOT;
                document.getElementById("partido").value = evento.ID_PAR_EVT_NOT;
                document.getElementById("estado").value = evento.ESTADO_EVT_NOT;
                document.getElementById("imagen_actual").value = evento.IMAGEN_EVT_NOT;

                habilitarUbicacion(); // Ajustar la habilitación del campo ubicación

                openModal(); // Abrir modal para editar
            } else {
                Swal.fire({
                    title: "Error",
                    text: data.message,
                    icon: "error"
                });
            }
        })
        .catch(error => {
            console.error("Error al obtener los datos del evento:", error);
            Swal.fire({
                title: "Error",
                text: "Ocurrió un error al cargar los datos del evento.",
                icon: "error"
            });
        });
}

// Función para recargar la tabla
function recargarTabla() {
    fetch(`../src/eventos_noticias_admin_queries.php?action=fetch`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector(".table tbody");
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
                            <button class="btn btn-warning btn-sm" onclick="editarEvento(${evento.ID_EVT_NOT})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarEvento(${evento.ID_EVT_NOT})">Eliminar</button>
                            <button class="btn btn-info btn-sm" onclick="cambiarEstado(${evento.ID_EVT_NOT}, '${evento.ESTADO_EVT_NOT}')">
                                ${evento.ESTADO_EVT_NOT === "Activo" ? "Ocultar" : "Activar"}
                            </button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            } else {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td colspan="6" class="text-center">No hay registros disponibles</td>
                `;
                tbody.appendChild(fila);
            }
        })
        .catch(error => {
            console.error("Error al recargar la tabla:", error);
            Swal.fire({
                title: "Error",
                text: "Error al recargar la tabla.",
                icon: "error"
            });
        });
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

// Ejecutar la función para habilitar/deshabilitar ubicación al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    habilitarUbicacion();
    recargarTabla(); // Cargar los datos al iniciar la página
});

// Abrir el modal
function openModal() {
    document.getElementById("eventModal").style.display = "flex";
}

// Cerrar el modal
function closeModal() {
    document.getElementById("eventModal").style.display = "none";
}

// Cerrar el modal al hacer clic fuera del contenido
window.onclick = function (event) {
    const modal = document.getElementById("eventModal");
    if (event.target === modal) {
        closeModal();
    }
};
