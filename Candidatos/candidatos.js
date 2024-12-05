document.addEventListener("DOMContentLoaded", function () {
    // Escucha el clic en todos los botones de 'Ver más' para abrir los modales
    document.querySelectorAll(".open-modal").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const candidateId = this.getAttribute("data-id"); // Obtener el ID del candidato desde el data-id
            const modalId = this.getAttribute("data-modal"); // Obtener el ID del modal
            const imgSrc = this.getAttribute("data-img"); // Obtener el nombre de la imagen

            // Fetch para obtener los datos del candidato usando el ID
            fetch(`../src/candidatos_queries.php?id=${candidateId}`)
                .then(response => response.json())
                .then(candidate => {
                    if (candidate.error) {
                        console.error(candidate.error);
                        return;
                    }

                    // Actualizar la imagen del modal
                    const imgElement = document.getElementById(`candidate-img-${candidateId}`);
                    imgElement.src = `./Img/${imgSrc}`;

                    // Actualizar la información en el modal
                    document.getElementById(`candidate-name-${candidateId}`).textContent = candidate.name;
                    document.getElementById(`candidate-bio-${candidateId}`).textContent = candidate.bio;
                    document.getElementById(`candidate-experience-${candidateId}`).textContent = candidate.experience;
                    document.getElementById(`candidate-vision-${candidateId}`).textContent = candidate.vision;
                    document.getElementById(`candidate-achievements-${candidateId}`).textContent = candidate.achievements;

                    // Mostrar el modal
                    document.getElementById(modalId).style.display = "flex";
                })
                .catch(error => console.error("Error al cargar los datos del candidato:", error));
        });
    });

    // Cerrar el modal al hacer clic en el botón de cierre
    document.querySelectorAll(".close-modal").forEach(closeButton => {
        closeButton.addEventListener("click", function () {
            this.closest(".modal").style.display = "none";
        });
    });

    // Cerrar el modal al hacer clic fuera del contenido del modal
    document.querySelectorAll(".modal").forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {  // Verifica si el clic fue en el fondo (fuera del contenido)
                modal.style.display = "none";
            }
        });
    });

    // Crear un nuevo candidato
    const addCandidateForm = document.getElementById("addCandidateForm");
    addCandidateForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(addCandidateForm);

        fetch('../src/candidatos_queries.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.error) {
                alert("Error al agregar candidato: " + result.error);
            } else {
                alert("Candidato agregado exitosamente.");
                loadCandidates();  // Actualizar la lista de candidatos
                document.getElementById("addCandidateModal").style.display = "none";  // Cerrar el modal
            }
        })
        .catch(error => console.error("Error al agregar candidato:", error));
    });

    // Manejo del modal CRUD
    const addCandidateBtn = document.getElementById("addCandidateBtn");
    const crudActionModal = document.getElementById("crudActionModal");
    const closeCrudModal = document.getElementById("closeCrudModal");

    addCandidateBtn.addEventListener("click", () => {
        crudActionModal.style.display = "flex";
    });

    closeCrudModal.addEventListener("click", () => {
        crudActionModal.style.display = "none";
    });

    // Acción para "Crear Candidato"
    const createActionBtn = document.getElementById("createActionBtn");
    const addCandidateModal = document.getElementById("addCandidateModal");
    const closeAddModal = document.getElementById("closeAddModal");

    createActionBtn.addEventListener("click", () => {
        crudActionModal.style.display = "none";
        addCandidateModal.style.display = "flex";
    });

    closeAddModal.addEventListener("click", () => {
        addCandidateModal.style.display = "none";
    });

    // Otras acciones CRUD (lectura, actualización, eliminación)
    const readActionBtn = document.getElementById("readActionBtn");
    const updateActionBtn = document.getElementById("updateActionBtn");
    const deleteActionBtn = document.getElementById("deleteActionBtn");

    readActionBtn.addEventListener("click", () => {
        crudActionModal.style.display = "none";
        alert("Mostrar lógica para leer candidatos.");
    });

    updateActionBtn.addEventListener("click", () => {
        crudActionModal.style.display = "none";
        alert("Mostrar lógica para actualizar candidatos.");
    });

    deleteActionBtn.addEventListener("click", () => {
        crudActionModal.style.display = "none";
        alert("Mostrar lógica para eliminar candidatos.");
    });

    // Actualizar un candidato
    function openUpdateCandidateModal(candidateId) {
        // Aquí colocas tu lógica de actualización
    }

    // Eliminar un candidato
    function deleteCandidate(candidateId) {
        // Aquí colocas tu lógica de eliminación
    }

    // Cargar los candidatos
    function loadCandidates() {
        fetch('../src/candidatos_queries.php') // Aquí puedes usar tu endpoint para obtener todos los candidatos
        .then(response => response.json())
        .then(candidates => {
            const candidateList = document.getElementById('candidateList');
            candidateList.innerHTML = ''; // Limpiar la lista actual
            candidates.forEach(candidate => {
                const li = document.createElement('li');
                li.textContent = candidate.name;
                candidateList.appendChild(li);
            });
        })
        .catch(error => {
            console.error("Error al cargar la lista de candidatos:", error);
        });
    }
});
