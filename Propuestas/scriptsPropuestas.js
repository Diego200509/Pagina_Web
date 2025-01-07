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

    // Limpiar el contenido anterior
    proposalsGrid.innerHTML = '';

    if (proposals.length > 0) {
        proposals.forEach((proposal) => {
            const proposalCard = document.createElement("div");
            proposalCard.classList.add("proposal-card");

            const imageUrl = proposal.imagen_url.trim() !== ""
                ? proposal.imagen_url
                : "https://via.placeholder.com/300x200?text=Sin+Imagen"; // Imagen predeterminada

            proposalCard.innerHTML = `
                <img src="${imageUrl}" class="proposal-image" alt="Imagen de la propuesta">
                <h3>${proposal.titulo}</h3>
                <p><strong>Categoría:</strong> ${proposal.categoria}</p>
                <p><strong>Descripción:</strong> <span class="proposal-description">${truncateText(proposal.descripcion, 100)}</span></p>
            `;


            proposalsGrid.appendChild(proposalCard);
        });
    } else {
        const noProposalsMessage = document.createElement("p");
        noProposalsMessage.innerText = "No hay propuestas disponibles para este filtro.";
        proposalsGrid.appendChild(noProposalsMessage);
    }
}




// Inicializa con la primera opción
filterProposals();
