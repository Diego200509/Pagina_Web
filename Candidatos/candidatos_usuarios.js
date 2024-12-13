document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';
    const cardContainer = document.getElementById('candidateCards'); // Cambia el contenedor

    // Función para cargar los candidatos
    function loadCandidates() {
        fetch(apiEndpoint)
            .then(response => response.json())
            .then(candidates => {
                cardContainer.innerHTML = ''; // Limpiar las tarjetas antes de agregar
                candidates.forEach(candidate => {
                    // Crear cada tarjeta
                    const card = document.createElement('div');
                    card.className = 'candidate-card';
                    card.innerHTML = `
                        <div class="card-image">
                            <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
                        </div>
                        <div class="card-content">
                            <h3>${candidate.NOM_CAN}</h3>
                            <p><strong>Biografía:</strong> ${candidate.BIOGRAFIA_CAN.substring(0, 100)}...</p>
                            <p><strong>Experiencia:</strong> ${candidate.EXPERIENCIA_CAN.substring(0, 100)}...</p>
                            <p><strong>Visión:</strong> ${candidate.VISION_CAN.substring(0, 100)}...</p>
                            <p><strong>Logros:</strong> ${candidate.LOGROS_CAN.substring(0, 100)}...</p>
                            <p><strong>Partido:</strong> ${candidate.ID_PAR_CAN}</p>
                        </div>
                    `;
                    cardContainer.appendChild(card); // Agregar la tarjeta al contenedor
                });
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Inicialización
    loadCandidates();
});
