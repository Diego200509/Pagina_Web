document.addEventListener("DOMContentLoaded", function () {
    function loadCandidates() {
        fetch('../src/candidatos_queries.php')  
            .then(response => response.json())
            .then(candidates => {
                const party1List = document.getElementById("party1Candidates");
                const party2List = document.getElementById("party2Candidates");

                // Limpiar las listas previas
                party1List.innerHTML = '';
                party2List.innerHTML = '';

                // Verificar si los candidatos son cargados correctamente
                if (candidates.length === 0) {
                    alert("No se encontraron candidatos.");
                }

                candidates.forEach(candidate => {
                    const card = document.createElement('div');
                    card.classList.add("card");

                    // Crear la ruta de la imagen
                    const imgPath = `../${candidate.IMG_CAN}`;

                    card.innerHTML = `
                        <img src="${imgPath}" alt="${candidate.NOM_CAN}">
                        <div class="card-content">
                            <h3>${candidate.NOM_CAN}</h3>
                            <p>${candidate.BIOGRAFIA_CAN.substring(0, 100)}...</p>
                            <a href="#" class="open-modal" data-id="${candidate.ID_CAN}" data-modal="candidateModal" data-img="${candidate.IMG_CAN}">Ver más</a>
                        </div>
                    `;

                    // Agregar el candidato al partido correspondiente
                    if (candidate.ID_PAR_CAN == 1) {
                        party1List.appendChild(card);
                    } else if (candidate.ID_PAR_CAN == 2) {
                        party2List.appendChild(card);
                    }
                });

                // Agregar el evento de "Ver más" a los enlaces
                document.querySelectorAll('.open-modal').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault();

                        const candidateId = this.getAttribute('data-id');
                        const modalId = this.getAttribute('data-modal');
                        const imgSrc = this.getAttribute('data-img');

                        // Obtener detalles del candidato
                        fetch(`../src/candidatos_queries.php?id=${candidateId}`)
                            .then(response => response.json())
                            .then(candidate => {
                                if (candidate.error) {
                                    alert("Error al obtener los detalles del candidato.");
                                    return;
                                }

                                // Verificar que todos los datos están disponibles
                                console.log(candidate);  // Verificar la respuesta del candidato

                                if (candidate.NOM_CAN && candidate.BIOGRAFIA_CAN && candidate.EXPERIENCIA_CAN && candidate.VISION_CAN && candidate.LOGROS_CAN) {
                                    document.getElementById('candidate-name').textContent = candidate.NOM_CAN;
                                    document.getElementById('candidate-bio').textContent = candidate.BIOGRAFIA_CAN;
                                    document.getElementById('candidate-experience').textContent = candidate.EXPERIENCIA_CAN;
                                    document.getElementById('candidate-vision').textContent = candidate.VISION_CAN;
                                    document.getElementById('candidate-achievements').textContent = candidate.LOGROS_CAN;
                                
                                    // Usamos la variable imgSrc correctamente
                                    const imgSrc = this.getAttribute('data-img'); 
                                    document.getElementById('candidate-img').src = `../Img/${imgSrc}`;  // Corregir el nombre de la variable a imgSrc
                                    alert('Datos cargados correctamente');
                                } else {
                                    alert('Faltan algunos datos para este candidato.');
                                }
                                // Mostrar el modal
                                document.getElementById(modalId).style.display = 'flex';
                            })
                            .catch(error => {
                                alert('Error al obtener la información del candidato');
                                console.error('Error al obtener la información del candidato:', error);
                            });
                    });
                });
            })
            .catch(error => {
                alert("Ocurrió un error al cargar los candidatos: " + error);
                console.error("Error al cargar los candidatos:", error);
            });
    }

    // Cerrar el modal
    document.getElementById('closeCandidateModal').addEventListener('click', function () {
        document.getElementById('candidateModal').style.display = 'none';
    });

    // Cerrar el modal si el usuario hace clic fuera del contenido del modal
    document.getElementById('candidateModal').addEventListener('click', function (event) {
        if (event.target === this) {
            document.getElementById('candidateModal').style.display = 'none';
        }
    });

    loadCandidates();
});
