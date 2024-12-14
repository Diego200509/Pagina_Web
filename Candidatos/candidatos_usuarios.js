document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';
    const cardContainer = document.getElementById('candidateCards');

    // Función para cargar los candidatos
    function loadCandidates() {
        fetch(apiEndpoint)
            .then(response => response.json())
            .then(candidates => {
                cardContainer.innerHTML = ''; // Limpiar las tarjetas antes de agregar
                candidates
                    .filter(candidate => candidate.ESTADO_CAN === 'Activo') // Filtrar solo activos
                    .forEach(candidate => {
                        const card = document.createElement('div');
                        card.className = 'candidate-card';

                        // Crear tarjeta con información agrupada
                        card.innerHTML = `
                            <div class="card-image">
                                <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
                            </div>
                            <div class="card-content">
                                <h3>${candidate.NOM_CAN}</h3>
                                <p>
                                    
                                    <strong>Biografía:</strong> ${candidate.BIOGRAFIA_CAN}<br>
                                    <strong>Experiencia:</strong> ${candidate.EXPERIENCIA_CAN}<br>
                                    <strong>Visión:</strong> ${candidate.VISION_CAN}<br>
                                    <strong>Logros:</strong> ${candidate.LOGROS_CAN}
                                </p>
                                <p><strong>Partido:</strong> ${candidate.NOM_PAR || 'Sin partido'}</p>
                            </div>
                        `;

                        cardContainer.appendChild(card); // Agregar la tarjeta al contenedor
                    });
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Inicializar
    loadCandidates();
});
