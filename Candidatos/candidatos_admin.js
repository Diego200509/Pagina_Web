document.addEventListener("DOMContentLoaded", function () {
    // Función para manejar errores de respuesta del servidor
    const handleServerError = (response) => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.status}`);
        }
        return response.json();
    };

    // Función para manejar y mostrar errores
    const handleError = (error) => {
        console.error("Error detectado:", error);
        alert("Ocurrió un error. Por favor, verifica la consola para más detalles.");
    };

    // Selección de elementos principales
    const addCandidateBtn = document.getElementById("addCandidateBtn");
    const crudActionModal = document.getElementById("crudActionModal");
    const closeCrudModal = document.getElementById("closeCrudModal");
    const addCandidateModal = document.getElementById("addCandidateModal");
    const createActionBtn = document.getElementById("createActionBtn");
    const closeAddModal = document.getElementById("closeAddCandidateModal");

    // Abrir modal al hacer clic en el botón "¿Qué deseas hacer?"
    if (addCandidateBtn && crudActionModal) {
        addCandidateBtn.addEventListener("click", () => {
            crudActionModal.style.display = "flex";
        });

        if (closeCrudModal) {
            closeCrudModal.addEventListener("click", () => {
                crudActionModal.style.display = "none";
            });
        }

        crudActionModal.addEventListener("click", (event) => {
            if (event.target === crudActionModal) {
                crudActionModal.style.display = "none";
            }
        });
    } else {
        console.error("El botón 'addCandidateBtn' o el modal 'crudActionModal' no se encontraron.");
    }

    // Abrir modal de creación al hacer clic en "Crear Candidato"
    if (createActionBtn && addCandidateModal) {
        createActionBtn.addEventListener("click", () => {
            crudActionModal.style.display = "none";
            addCandidateModal.style.display = "flex";
        });

        if (closeAddModal) {
            closeAddModal.addEventListener("click", () => {
                addCandidateModal.style.display = "none";
            });
        }
    } else {
        console.error("El botón 'createActionBtn' o el modal 'addCandidateModal' no se encontraron.");
    }

    // Función para cargar los partidos desde la base de datos
    function loadParties() {
        fetch('../src/candidatos_queries.php?fetch=parties') // Endpoint para obtener los partidos
            .then(handleServerError)
            .then(parties => {
                const partySelect = document.getElementById("party_id");
                if (!partySelect) {
                    console.error("No se encontró el elemento <select> con id 'party_id'.");
                    return;
                }
                partySelect.innerHTML = '<option value="" disabled selected>Seleccione un partido</option>'; // Limpiar opciones previas
                parties.forEach(party => {
                    const option = document.createElement("option");
                    option.value = party.ID_PAR; // Campo de ID del partido
                    option.textContent = party.NOM_PAR; // Campo de nombre del partido
                    partySelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error al cargar los partidos:", error));
    }

    // Manejo del formulario de creación
    const addCandidateForm = document.getElementById("addCandidateForm");
    if (addCandidateForm) {
        addCandidateForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(addCandidateForm);

            fetch('../src/candidatos_queries.php', {
                method: 'POST',
                body: formData
            })
                .then(handleServerError)
                .then(result => {
                    if (result.error) {
                        alert("Error al agregar candidato: " + result.error);
                    } else {
                        alert("Candidato agregado exitosamente.");
                        loadCandidates(); // Recargar la lista de candidatos
                        addCandidateModal.style.display = "none";
                        addCandidateForm.reset();
                    }
                })
                .catch(handleError);
        });
    }

    // Función para cargar candidatos
    function loadCandidates() {
        fetch('../src/candidatos_queries.php')
            .then(handleServerError)
            .then(candidates => {
                const candidateList = document.getElementById("candidateList");
                if (!candidateList) return console.error("No se encontró el elemento 'candidateList' en el DOM.");

                candidateList.innerHTML = ''; // Limpiar la lista actual
                candidates.forEach(candidate => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="candidate-card">
                            <h3>${candidate.NOM_CAN}</h3>
                            <p>${candidate.BIOGRAFIA_CAN.substring(0, 100)}...</p>
                            <button class="open-modal" 
                                data-id="${candidate.ID_CAN}" 
                                data-modal="candidateModal"
                                data-img="${candidate.IMG_CAN}">Ver más</button>
                        </div>`;
                    candidateList.appendChild(li);
                });
                attachOpenModalEvent(); // Agregar eventos a los botones dinámicos
            })
            .catch(handleError);
    }

    // Función para agregar eventos a los botones de "Ver más"
    function attachOpenModalEvent() {
        document.querySelectorAll(".open-modal").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                const candidateId = this.getAttribute("data-id");
                const modalId = this.getAttribute("data-modal");
                const imgSrc = this.getAttribute("data-img");

                fetch(`../src/candidatos_queries.php?id=${candidateId}`)
                    .then(handleServerError)
                    .then(candidate => {
                        if (candidate.error) {
                            console.error(candidate.error);
                            return;
                        }

                        const imgElement = document.getElementById("candidate-img");
                        if (imgElement) imgElement.src = `../uploads/${imgSrc}`;

                        document.getElementById("candidate-name").textContent = candidate.NOM_CAN;
                        document.getElementById("candidate-bio").textContent = candidate.BIOGRAFIA_CAN;
                        document.getElementById("candidate-experience").textContent = candidate.EXPERIENCIA_CAN;
                        document.getElementById("candidate-vision").textContent = candidate.VISION_CAN;
                        document.getElementById("candidate-achievements").textContent = candidate.LOGROS_CAN;

                        const modal = document.getElementById(modalId);
                        if (modal) modal.style.display = "flex";
                    })
                    .catch(handleError);
            });
        });
    }

    // Llamar a las funciones al cargar la página
    loadParties(); // Cargar los partidos políticos en el select
    loadCandidates(); // Cargar candidatos
});
