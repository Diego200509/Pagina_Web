document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = '../src/candidatos_queries.php';
    const tableBody = document.getElementById('candidateList');
    const modal = document.getElementById('addCandidateModal');
    const modalTitle = document.getElementById('modalTitle');
    const candidateForm = document.getElementById('candidateForm');
    const closeModal = document.getElementById('closeModal');
    const openAddModal = document.getElementById('addCandidateBtn');

    // Aseguramos que el modal comience oculto al cargar la página
    window.addEventListener('load', () => {
        modal.style.display = 'none';
    });

    // Cargar candidatos desde el servidor
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
                        <td>${candidate.APE_CAN || 'Sin apellido'}</td>
                        <td>${candidate.FECHA_NAC_CAN || 'Sin fecha de nacimiento'}</td>
                        <td>${candidate.CARGO_CAN || 'Sin cargo'}</td>
                        <td>${candidate.EDUCACION_CAN || 'Sin educación'}</td>
                        <td>${candidate.EXPERIENCIA_CAN || 'Sin experiencia'}</td>
                        <td>${candidate.ESTADO_CAN || 'Inactivo'}</td>
                        <td>
                            <img src="../${candidate.IMG_CAN || 'placeholder.png'}" alt="${candidate.NOM_CAN || 'Sin imagen'}" width="50">
                        </td>
                        <td>
                            <div class="action-container">
                                <button class="action-btn toggle-actions-btn" data-id="${candidate.ID_CAN}">
                                    <i class="bi bi-pencil-square"></i> <!-- Ícono de editar -->
                                </button>
                                <!-- Menú de acciones (oculto por defecto) -->
                                <div class="actions-dropdown" data-id="${candidate.ID_CAN}" style="display: none;">
                                    <button class="action-btn edit-btn">Editar</button>
                                    <button class="action-btn delete-btn">Eliminar</button>
                                    <button class="action-btn toggle-status-btn">Activar</button>
                                </div>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al cargar candidatos:', error));
    }

    // Mostrar/ocultar el menú de acciones al hacer clic en el ícono de editar
    tableBody.addEventListener('click', function (e) {
        const target = e.target;

        // Si el objetivo es el ícono de editar, alternamos la visibilidad del menú de acciones
        if (target.classList.contains('toggle-actions-btn')) {
            const actionsDropdown = target.closest('td').querySelector('.actions-dropdown');
            // Alternamos la visibilidad del menú de acciones
            const isVisible = actionsDropdown.style.display === 'block';
            actionsDropdown.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
        }

        // Si se hace clic en "Editar", abrir el modal de edición
        if (target.classList.contains('edit-btn')) {
            const id = target.closest('tr').querySelector('.toggle-actions-btn').dataset.id;
            editCandidate(id);
        }

        // Si se hace clic en "Eliminar", eliminar el candidato
        if (target.classList.contains('delete-btn')) {
            const id = target.closest('tr').querySelector('.toggle-actions-btn').dataset.id;
            deleteCandidate(id);
        }

        // Si se hace clic en "Activar", cambiar el estado del candidato
        if (target.classList.contains('toggle-status-btn')) {
            const id = target.closest('tr').querySelector('.toggle-actions-btn').dataset.id;
            const currentStatus = target.dataset.status;
            toggleCandidateStatus(id, currentStatus);
        }
    });

    // Función para editar el candidato
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
                document.getElementById('surname').value = candidate.APE_CAN;
                document.getElementById('birth_date').value = candidate.FECHA_NAC_CAN;
                document.getElementById('position').value = candidate.CARGO_CAN;
                document.getElementById('education').value = candidate.EDUCACION_CAN;
                document.getElementById('experience').value = candidate.EXPERIENCIA_CAN;
                modal.style.display = 'flex'; // Mostrar el modal centrado
            })
            .catch(error => console.error('Error al cargar el candidato:', error));
    }

    // Función para eliminar un candidato
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

    function toggleCandidateStatus(id, currentStatus) {
        // Determina el nuevo estado basado en el estado actual
        const newStatus = currentStatus === 'Activo' ? 'Oculto' : 'Activo';
    
        // Realiza la solicitud para actualizar el estado en el servidor
        fetch(apiEndpoint, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, estado: newStatus })
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
                        text: `Estado del candidato actualizado a: ${newStatus}`,
                    });
    
                    // Actualiza el estado en la tabla sin recargar todos los datos
                    const row = tableBody.querySelector(`.toggle-actions-btn[data-id='${id}']`).closest('tr');
                    const statusCell = row.querySelector('td:nth-child(8)'); // Columna del estado
                    statusCell.textContent = newStatus;
    
                    // Actualiza el atributo `data-status` del botón
                    const toggleButton = row.querySelector('.toggle-status-btn');
                    toggleButton.dataset.status = newStatus;
                    toggleButton.textContent = newStatus === 'Activo' ? 'Ocultar' : 'Activar';
                }
            })
            .catch(error => console.error('Error al cambiar el estado:', error));
    }


    // Abrir el modal para agregar un nuevo candidato
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Agregar Candidato';
        candidateForm.reset();
        document.getElementById('candidateId').value = '';
        modal.style.display = 'flex'; // Mostrar el modal centrado
    });

    // Cerrar el modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none'; // Ocultar el modal
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

    // Inicializar la lista de candidatos
    loadCandidates();
});
