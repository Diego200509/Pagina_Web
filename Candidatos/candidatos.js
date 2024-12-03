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

    // Actualizar un candidato
    function openUpdateCandidateModal(candidateId) {
        fetch(`../src/candidatos_queries.php?id=${candidateId}`)
        .then(response => response.json())
        .then(candidate => {
            if (candidate.error) {
                alert("Error al cargar candidato para actualizar.");
                return;
            }

            // Llenar los campos del formulario de actualización
            document.getElementById("updateCandidateName").value = candidate.name;
            document.getElementById("updateCandidateBio").value = candidate.bio;
            document.getElementById("updateCandidateExperience").value = candidate.experience;
            document.getElementById("updateCandidateVision").value = candidate.vision;
            document.getElementById("updateCandidateAchievements").value = candidate.achievements;

            // Mostrar el modal de actualización
            document.getElementById("updateCandidateModal").style.display = "flex";

            // Agregar la lógica para el botón de actualizar
            document.getElementById("updateCandidateForm").onsubmit = function (e) {
                e.preventDefault();

                const updatedData = new FormData(this);
                updatedData.append("id", candidateId);

                fetch('../src/candidatos_queries.php', {
                    method: 'POST',
                    body: updatedData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        alert("Error al actualizar candidato: " + result.error);
                    } else {
                        alert("Candidato actualizado exitosamente.");
                        loadCandidates();  // Actualizar la lista de candidatos
                        document.getElementById("updateCandidateModal").style.display = "none";  // Cerrar el modal
                    }
                })
                .catch(error => console.error("Error al actualizar candidato:", error));
            };
        })
        .catch(error => console.error("Error al cargar los datos del candidato:", error));
    }

    // Eliminar un candidato
    function deleteCandidate(candidateId) {
        if (confirm("¿Estás seguro de que deseas eliminar este candidato?")) {
            fetch(`../src/candidatos_queries.php?id=${candidateId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    alert("Error al eliminar candidato: " + result.error);
                } else {
                    alert("Candidato eliminado exitosamente.");
                    loadCandidates();  // Actualizar la lista de candidatos
                }
            })
            .catch(error => console.error("Error al eliminar candidato:", error));
        }
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
