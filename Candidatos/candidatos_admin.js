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
                tableBody.innerHTML = ''; // Limpiar la tabla antes de agregar datos
                candidates.forEach(candidate => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${candidate.ID_CAN || 'No ID'}</td>
                        <td>${candidate.NOM_CAN || 'Sin nombre'}</td>
                        <td>${candidate.BIOGRAFIA_CAN ? candidate.BIOGRAFIA_CAN.substring(0, 50) : 'No definido'}...</td>
                        <td>${candidate.EXPERIENCIA_CAN ? candidate.EXPERIENCIA_CAN.substring(0, 50) : 'No definido'}...</td>
                        <td>${candidate.VISION_CAN ? candidate.VISION_CAN.substring(0, 50) : 'No definido'}...</td>
                        <td>${candidate.LOGROS_CAN ? candidate.LOGROS_CAN.substring(0, 50) : 'No definido'}...</td>
                        <td>${candidate.ID_PAR_CAN || 'No definido'}</td>
                        <td>
                            <img src="../${candidate.IMG_CAN || 'placeholder.png'}" alt="${candidate.NOM_CAN || 'Sin imagen'}" width="50">
                        </td>
                        <td>
                            <button class="edit-btn" data-id="${candidate.ID_CAN}">Editar</button>
                            <button class="delete-btn" data-id="${candidate.ID_CAN}">Eliminar</button>
                            <button class="toggle-status-btn" data-id="${candidate.ID_CAN}" data-status="${candidate.ESTADO_CAN}">
                                ${candidate.ESTADO_CAN === 'Activo' ? 'Ocultar' : 'Activar'}
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                attachActions(); // Asignar eventos a los botones
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Centralizar eventos con delegation
    tableBody.addEventListener('click', function (e) {
        const target = e.target;

        if (target.classList.contains('edit-btn')) {
            const id = target.dataset.id;
            editCandidate(id);
        }

        if (target.classList.contains('delete-btn')) {
            const id = target.dataset.id;
            deleteCandidate(id);
        }

        if (target.classList.contains('toggle-status-btn')) {
            const id = target.dataset.id;
            const currentStatus = target.dataset.status;
            toggleCandidateStatus(id, currentStatus);
        }
    });

    // Función para editar candidato
    function editCandidate(id) {
        fetch(`${apiEndpoint}?id=${id}`)
            .then(response => response.json())
            .then(candidate => {
                if (candidate.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: candidate.error,
                    });
                    return;
                }

                modalTitle.textContent = 'Editar Candidato';
                document.getElementById('candidateId').value = candidate.ID_CAN;
                document.getElementById('name').value = candidate.NOM_CAN;
                document.getElementById('party_id').value = candidate.ID_PAR_CAN;
                document.getElementById('bio').value = candidate.BIOGRAFIA_CAN;
                document.getElementById('experience').value = candidate.EXPERIENCIA_CAN;
                document.getElementById('vision').value = candidate.VISION_CAN;
                document.getElementById('achievements').value = candidate.LOGROS_CAN;
                modal.style.display = 'block';
            })
            .catch(error => console.error('Error al cargar el candidato:', error));
    }

    // Función para eliminar candidato
    function deleteCandidate(id) {
        if (confirm('¿Estás seguro de eliminar este candidato?')) {
            fetch(`${apiEndpoint}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.error,
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'Candidato eliminado correctamente.',
                        });
                        loadCandidates();
                    }
                })
                .catch(error => console.error('Error al eliminar el candidato:', error));
        }
    }

    // Función para cambiar estado del candidato
    function toggleCandidateStatus(id, currentStatus) {
        const newStatus = currentStatus === 'Activo' ? 'Oculto' : 'Activo';

        fetch(apiEndpoint, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, estado: newStatus })
        })
            .then(response => response.text())
            .then(result => {
                if (result.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.error,
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: `Estado del candidato actualizado a: ${newStatus}`,
                    });
                    loadCandidates();
                }
            })
            .catch(error => console.error('Error al cambiar el estado:', error));
    }

    // Abrir el modal para agregar un nuevo candidato
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Agregar Candidato';
        candidateForm.reset();
        document.getElementById('candidateId').value = '';
        modal.style.display = 'block';
    });

    // Cerrar el modal
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
            .then(result => {
                if (result.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.error,
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El candidato ha sido guardado correctamente.',
                    });
                    modal.style.display = 'none';
                    candidateForm.reset();
                    loadCandidates();
                }
            })
            .catch(error => console.error('Error al guardar el candidato:', error));
    });

    // Cargar los partidos para el select
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
            })
            .catch(error => console.error('Error al cargar los partidos:', error));
    }

    // Inicialización
    loadParties();
    loadCandidates();
});
