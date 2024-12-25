document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';
    const candidateContainer = document.getElementById('candidateContainer');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (!candidateContainer || !prevBtn || !nextBtn) {
        console.error('Uno o más elementos necesarios no se encontraron en el DOM.');
        return;
    }

    let candidates = [];
    let currentIndex = 0;

    // Función para cargar todos los candidatos
    function loadCandidates() {
        fetch(apiEndpoint)
            .then(response => response.json())
            .then(data => {
                candidates = data.filter(candidate => candidate.ESTADO_CAN === 'Activo'); // Filtrar solo activos
                showCandidate(currentIndex);
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Función para mostrar un candidato según el índice
    function showCandidate(index) {
        if (index >= 0 && index < candidates.length) {
            const candidate = candidates[index];
            candidateContainer.innerHTML = `
                <div class="candidate-image">
                    <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
                </div>
                <div class="candidate-info">
                    <h3>${candidate.NOM_CAN}</h3>
                    <p><strong>Fecha de Nacimiento:</strong> ${candidate.FECHA_NAC_CAN || 'No disponible'}</p>
                    <p><strong>Cargo:</strong> ${candidate.CARGO_CAN || 'No disponible'}</p>
                    <p><strong>Información:</strong> ${candidate.EDUCACION_CAN || 'No disponible'}</p>
                    <p><strong>Experiencia:</strong> ${candidate.EXPERIENCIA_CAN || 'No disponible'}</p>
                </div>
            `;
        }
        updatePaginationButtons();
    }

    // Función para actualizar el estado de los botones de paginación
    function updatePaginationButtons() {
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === candidates.length - 1;
    }

    // Eventos para los botones de navegación
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showCandidate(currentIndex);
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < candidates.length - 1) {
            currentIndex++;
            showCandidate(currentIndex);
        }
    });

    // Inicializar
    loadCandidates();
});
