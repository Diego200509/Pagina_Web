// Abrir el modal
function openModal() {
    document.getElementById("eventModal").style.display = "flex";
}

// Cerrar el modal
function closeModal() {
    document.getElementById("eventModal").style.display = "none";
}

// Cerrar el modal al hacer clic fuera del contenido
window.onclick = function(event) {
    const modal = document.getElementById("eventModal");
    if (event.target === modal) {
        closeModal();
    }
};
