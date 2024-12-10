document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar los candidatos
    function loadCandidates() {
        fetch('../src/candidatos_queries.php')  // Asegúrate de que esta ruta sea correcta para obtener los candidatos
            .then(response => response.json())
            .then(candidates => {
                const party1List = document.getElementById("party1Candidates");
                const party2List = document.getElementById("party2Candidates");

                // Limpiar listas previas
                party1List.innerHTML = '';
                party2List.innerHTML = '';

                candidates.forEach(candidate => {
                    const card = document.createElement('div');
                    card.classList.add("card");

                    card.innerHTML = `
                        <img src="../uploads/${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
                        <div class="card-content">
                            <h3>${candidate.NOM_CAN}</h3>
                            <p>${candidate.BIOGRAFIA_CAN.substring(0, 100)}...</p>
                            <a href="#" class="open-modal" data-id="${candidate.ID_CAN}" data-modal="candidateModal" data-img="${candidate.IMG_CAN}">Ver más</a>
                        </div>
                    `;

                    if (candidate.ID_PAR_CAN == 1) {
                        party1List.appendChild(card);
                    } else if (candidate.ID_PAR_CAN == 2) {
                        party2List.appendChild(card);
                    }
                });
            })
            .catch(error => console.error("Error al cargar los candidatos:", error));
    }

    // Llamar a la función al cargar la página
    loadCandidates();
});
