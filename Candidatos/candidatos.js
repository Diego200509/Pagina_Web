document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';

    const tableBody = document.getElementById('candidateList');
    const modal = document.getElementById('addCandidateModal');
    const modalTitle = document.getElementById('modalTitle');
    const candidateForm = document.getElementById('candidateForm');
    const closeModal = document.getElementById('closeModal');
    const openAddModal = document.getElementById('addCandidateBtn');

    // Cargar la tabla de candidatos
    function loadCandidates() {
        fetch(apiEndpoint)
            .then(response => response.json())
            .then(candidates => {
                tableBody.innerHTML = '';
                candidates.forEach(candidate => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${candidate.ID_CAN}</td>
                        <td>${candidate.NOM_CAN}</td>
                        <td>${candidate.BIOGRAFIA_CAN.substring(0, 50)}...</td>
                        <td>${candidate.EXPERIENCIA_CAN.substring(0, 50)}...</td>
                        <td>${candidate.VISION_CAN.substring(0, 50)}...</td>
                        <td>${candidate.LOGROS_CAN.substring(0, 50)}...</td>
                        <td>${candidate.ID_PAR_CAN}</td>
                        <td>
                            <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}" width="50">
                        </td>
                        <td>
                            <button class="edit-btn" data-id="${candidate.ID_CAN}">Editar</button>
                            <button class="delete-btn" data-id="${candidate.ID_CAN}">Eliminar</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                attachActions();
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Agregar eventos a los botones de editar y eliminar
    function attachActions() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                fetch(`${apiEndpoint}?id=${id}`)
                    .then(response => response.json())
                    .then(candidate => {
                        modalTitle.textContent = 'Editar Candidato';
                        document.getElementById('candidateId').value = candidate.ID_CAN;
                        document.getElementById('name').value = candidate.NOM_CAN;
                        document.getElementById('party_id').value = candidate.ID_PAR_CAN;
                        document.getElementById('bio').value = candidate.BIOGRAFIA_CAN;
                        document.getElementById('experience').value = candidate.EXPERIENCIA_CAN;
                        document.getElementById('vision').value = candidate.VISION_CAN;
                        document.getElementById('achievements').value = candidate.LOGROS_CAN;
                        modal.style.display = 'block';
                    });
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                if (confirm('¿Estás seguro de eliminar este candidato?')) {
                    fetch(`${apiEndpoint}?id=${id}`, { method: 'DELETE' })
                        .then(response => response.json())
                        .then(() => loadCandidates());
                }
            });
        });
    }

    // Abrir modal para agregar candidato
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Agregar Candidato';
        candidateForm.reset();
        modal.style.display = 'block';
    });

    // Cerrar modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Guardar candidato (crear o editar)
    candidateForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(candidateForm);

        fetch(apiEndpoint, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(() => {
                modal.style.display = 'none';
                loadCandidates();
            })
            .catch(error => console.error('Error al guardar candidato:', error));
    });

    // Cargar los partidos
    function loadParties() {
        fetch(`${apiEndpoint}?fetch=parties`)
            .then(response => response.json())
            .then(parties => {
                const partySelect = document.getElementById('party_id');
                partySelect.innerHTML = '<option value="" disabled selected>Seleccione un partido</option>';
                parties.forEach(party => {
                    const option = document.createElement('option');
                    option.value = party.ID_PAR;
                    option.textContent = party.NOM_PAR;
                    partySelect.appendChild(option);
                });
            });
    }

    // Inicialización
    loadParties();
    loadCandidates();
});
