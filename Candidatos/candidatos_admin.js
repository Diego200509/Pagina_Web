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
                        <td>${candidate.ID_CAN}</td>
                        <td>${candidate.NOM_CAN}</td>
                        <td>${candidate.BIOGRAFIA_CAN.substring(0, 50)}...</td>
                        <td>${candidate.EXPERIENCIA_CAN.substring(0, 50)}...</td>
                        <td>${candidate.VISION_CAN.substring(0, 50)}...</td>
                        <td>${candidate.LOGROS_CAN.substring(0, 50)}...</td>
                       <td>${candidate.ID_PAR_CAN || 'No definido'}</td>
                        <td>
                            <img src="../${candidate.IMG_CAN}" alt="${candidate.NOM_CAN}" width="50">
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

    // Asignar eventos a los botones de editar, eliminar y cambiar estado
    function attachActions() {
        // Botones de editar
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;

                // Obtener datos del candidato por ID
                fetch(`${apiEndpoint}?id=${id}`)
                    .then(response => response.json())
                    .then(candidate => {
                        if (candidate.error) {
                            alert('Error: ' + candidate.error);
                            return;
                        }

                        // Llenar el formulario con los datos del candidato
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
            });
        });

        // Botones de eliminar
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;

                if (confirm('¿Estás seguro de eliminar este candidato?')) {
                    fetch(`${apiEndpoint}`, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id }) // Enviar el ID en el cuerpo
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.error) {
                                alert('Error: ' + result.error);
                            } else {
                                alert('Candidato eliminado correctamente.');
                                loadCandidates(); // Recargar la tabla
                            }
                        })
                        .catch(error => console.error('Error al eliminar el candidato:', error));
                }
            });
        });

        // Botones de cambiar estado
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const currentStatus = this.dataset.status;

                const newStatus = currentStatus === 'Activo' ? 'Oculto' : 'Activo';

                fetch(`${apiEndpoint}`, {
                    method: 'PATCH', // Método para actualizar parcialmente
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, estado: newStatus }) // Enviar el ID y el nuevo estado
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.error) {
                            alert('Error: ' + result.error);
                        } else {
                            alert('Estado actualizado correctamente.');
                            loadCandidates(); // Recargar la tabla
                        }
                    })
                    .catch(error => console.error('Error al cambiar el estado:', error));
            });
        });
    }
    // Botones de cambiar estado
document.querySelectorAll('.toggle-status-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const currentStatus = this.dataset.status;

        // Determinar el nuevo estado
        const newStatus = currentStatus === 'Activo' ? 'Oculto' : 'Activo';

        fetch(apiEndpoint, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, estado: newStatus }) // Enviar el ID y el nuevo estado
        })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    alert('Error: ' + result.error);
                } else {
                    alert(`Estado del candidato actualizado a: ${newStatus}`);
                    loadCandidates(); // Recargar la tabla o lista
                }
            })
            .catch(error => console.error('Error al cambiar el estado:', error));
    });
});

    // Abrir el modal para agregar un nuevo candidato
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Agregar Candidato';
        candidateForm.reset(); // Limpiar el formulario
        document.getElementById('candidateId').value = ''; // Asegurarse de que el ID esté vacío
        modal.style.display = 'block';
    });

    // Cerrar el modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Guardar candidato (crear o editar)
    candidateForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(candidateForm); // Crear el formulario para enviar

        fetch(apiEndpoint, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    alert('Error: ' + result.error);
                } else {
                    alert('Candidato guardado correctamente.');
                    modal.style.display = 'none';
                    loadCandidates(); // Recargar la tabla
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
    loadParties(); // Cargar los partidos
    loadCandidates(); // Cargar la lista de candidatos
});
