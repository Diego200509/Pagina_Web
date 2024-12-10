document.addEventListener("DOMContentLoaded", function () {
    // Selección de elementos
    const crudActionBtn = document.getElementById("crudActionBtn");
    const crudActionModal = document.getElementById("crudActionModal");
    const closeCrudModal = document.getElementById("closeCrudModal");

    // Abrir modal al hacer clic en el botón "¿Qué deseas hacer?"
    crudActionBtn.addEventListener("click", () => {
        crudActionModal.style.display = "flex"; // Mostrar el modal
    });

    // Cerrar modal al hacer clic en el botón "Cerrar"
    closeCrudModal.addEventListener("click", () => {
        crudActionModal.style.display = "none"; // Ocultar el modal
    });

    // Cerrar modal al hacer clic fuera del contenido del modal
    crudActionModal.addEventListener("click", (event) => {
        if (event.target === crudActionModal) {
            crudActionModal.style.display = "none";
        }
    });

    // Función para abrir el modal de información de un candidato
    function openCandidateModal(candidate) {
        const modalContent = document.getElementById("candidateModalContent");
        modalContent.innerHTML = `
            <h2>${candidate.NOM_CAN}</h2>
            <p>${candidate.BIOGRAFIA_CAN}</p>
            <p><strong>Experiencia:</strong> ${candidate.EXPERIENCIA_CAN}</p>
            <p><strong>Visión:</strong> ${candidate.VISION_CAN}</p>
            <p><strong>Logros:</strong> ${candidate.LOGROS_CAN}</p>
            <img src="../uploads/${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
        `;
        document.getElementById("candidateModal").style.display = "block";
    }

    // Cerrar el modal de candidato
    document.getElementById("closeCandidateModal").addEventListener("click", () => {
        document.getElementById("candidateModal").style.display = "none";
    });
});
