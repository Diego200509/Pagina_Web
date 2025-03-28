document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';
    const candidateContent = document.getElementById('candidateContent');
    const prevArrow = document.getElementById('prevArrow');
    const nextArrow = document.getElementById('nextArrow');

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
        if (candidates.length === 0) {
            candidateContent.innerHTML = `<p>No hay candidatos disponibles.</p>`;
            return;
        }

        const candidate = candidates[index];
        candidateContent.innerHTML = `
            <div class="candidate-image">
                <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}">
            </div>
            <div class="candidate-info">
               <div class="candidate-info">
            <h3>${candidate.NOM_CAN} ${candidate.APE_CAN}</h3> <!-- Solo el nombre y apellido -->
        </div>
                <p><strong>Edad:</strong> ${candidate.EDAD_CAN|| 'No disponible'}</p>
                <p><strong>Cargo:</strong> ${candidate.CARGO_CAN || 'No disponible'}</p>
                <p><strong>Información:</strong> ${candidate.EDUCACION_CAN || 'No disponible'}</p>
                <p><strong>Experiencia:</strong> ${candidate.EXPERIENCIA_CAN || 'No disponible'}</p>
            </div>
        `;
    }

    // Eventos para las flechas
    prevArrow.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + candidates.length) % candidates.length; // Circular hacia atrás
        showCandidate(currentIndex);
    });

    nextArrow.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % candidates.length; // Circular hacia adelante
        showCandidate(currentIndex);
    });

    // Inicializar
    loadCandidates();
});
