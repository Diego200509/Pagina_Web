console.log("Script cargado correctamente");

function filterProposals() {
    console.log("Evento onchange activado");

    var selectedFaculty = document.getElementById("faculty").value;
    console.log("Facultad seleccionada:", selectedFaculty);

    // Realizar una solicitud AJAX para obtener las propuestas desde el servidor
    fetch('http://localhost/Pagina_Web/Pagina_Web/src/propuestas_queries.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `category=${encodeURIComponent(selectedFaculty)}`
    })
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos del servidor:", data);
            displayProposals(data);
        })
        .catch(error => console.error('Error:', error));
}

function truncateText(text, maxLength) {
    if (text.length > maxLength) {
        return text.substring(0, maxLength) + "...";
    }
    return text;
}

function displayProposals(proposals) {
    const proposalsGrid = document.getElementById("proposalsGrid");
    proposalsGrid.innerHTML = ''; // Limpiar contenido previo

    if (proposals.length > 0) {
        proposals.forEach((proposal) => {
            const proposalCard = document.createElement("div");
            proposalCard.classList.add("proposal-card");

            const imageUrl = proposal.imagen_url.trim() !== ""
                ? proposal.imagen_url
                : "https://via.placeholder.com/300x200?text=Sin+Imagen";

            const truncatedText = truncateText(proposal.descripcion, 100);
            const isTruncated = truncatedText.endsWith('...');

            proposalCard.innerHTML = `
                <img src="${imageUrl}" class="proposal-image" alt="Imagen de la propuesta">
                <h3>${proposal.titulo}</h3>
                <p><strong>Categoría:</strong> ${proposal.categoria}</p>
                <p>
                    <strong>Descripción:</strong> 
                    <span class="proposal-description">${truncatedText}</span>
                </p>
                ${isTruncated ? `<button class="btn-view-more" onclick='openModal(${JSON.stringify(proposal)})'>Ver más</button>` : ''}
            `;

            proposalsGrid.appendChild(proposalCard);
        });
    } else {
        const noProposalsMessage = document.createElement("p");
        noProposalsMessage.innerText = "No hay propuestas disponibles para este filtro.";
        proposalsGrid.appendChild(noProposalsMessage);
    }
}


document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("proposalModal");
    if (modal) {
        modal.style.display = "none"; // Ocultar modal al cargar la página
    }
});

function openModal(proposal) {
    const modal = document.getElementById("proposalModal");
    document.getElementById("modalTitle").textContent = proposal.titulo;
    document.getElementById("modalImage").src = proposal.imagen_url || "https://via.placeholder.com/150";
    document.getElementById("modalCategory").textContent = proposal.categoria;
    document.getElementById("modalDescription").textContent = proposal.descripcion;

    modal.style.display = "flex"; // Mostrar el modal
}

function closeModal() {
    const modal = document.getElementById("proposalModal");
    modal.style.display = "none"; // Ocultar el modal
}


function closeModal() {
    const modal = document.getElementById("proposalModal");
    modal.style.display = "none";
}




// Inicializa con la primera opción
filterProposals();
