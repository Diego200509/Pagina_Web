let currentPageEvents = 1;  // Página actual de eventos
let currentPageNews = 1;    // Página actual de noticias
const itemsPerPage = 3;     // Número de elementos por página

let filteredEvents = [];    // Eventos filtrados
let filteredNews = [];      // Noticias filtradas

let events = []; // Todos los eventos
let news = [];   // Todas las noticias

// Función para mostrar los eventos paginados
function displayEvents() {
    const totalPagesEvents = Math.ceil(events.length / itemsPerPage);

    // Limpiar la lista de eventos actual
    document.getElementById('eventList').innerHTML = '';

    // Mostrar solo los eventos correspondientes a la página actual
    const start = (currentPageEvents - 1) * itemsPerPage;
    const end = currentPageEvents * itemsPerPage;
    const eventsToShow = events.slice(start, end);

    eventsToShow.forEach(event => {
        document.getElementById('eventList').appendChild(event);
    });

    const prevPageButton = document.getElementById("prevPageEvents");
    const nextPageButton = document.getElementById("nextPageEvents");

    // Mostrar u ocultar la paginación solo si hay más de 3 eventos
    if (events.length > itemsPerPage) {
        document.getElementById("eventPagination").style.display = 'flex';  // Mostrar botones
        prevPageButton.disabled = currentPageEvents === 1;
        nextPageButton.disabled = currentPageEvents === totalPagesEvents;
    } else {
        document.getElementById("eventPagination").style.display = 'none';  // Ocultar botones
    }
}


// Función para mostrar las noticias paginadas
function displayNews() {
    const totalPagesNews = Math.ceil(news.length / itemsPerPage);

    // Limpiar la lista de noticias actual
    document.getElementById('newsList').innerHTML = '';

    // Mostrar solo las noticias correspondientes a la página actual
    const start = (currentPageNews - 1) * itemsPerPage;
    const end = currentPageNews * itemsPerPage;
    const newsToShow = news.slice(start, end);

    newsToShow.forEach(newsItem => {
        document.getElementById('newsList').appendChild(newsItem);
    });

    const prevPageButton = document.getElementById("prevPageNews");
    const nextPageButton = document.getElementById("nextPageNews");

    // Mostrar u ocultar la paginación solo si hay más de 3 noticias
    if (news.length > itemsPerPage) {
        document.getElementById("newsPagination").style.display = 'flex';  // Mostrar botones
        prevPageButton.disabled = currentPageNews === 1;
        nextPageButton.disabled = currentPageNews === totalPagesNews;
    } else {
        document.getElementById("newsPagination").style.display = 'none';  // Ocultar botones
    }
}



// Función para filtrar por partido político
function filterByParty() {
    const selectedParty = document.getElementById('partySelect').value;

    // Filtrar eventos por partido
    events.forEach(event => {
        const eventParty = event.getAttribute('data-party');
        // Mostrar u ocultar según el partido seleccionado
        if (selectedParty === 'all' || eventParty === selectedParty) {
            event.style.display = 'block';
        } else {
            event.style.display = 'none';
        }
    });

    // Filtrar noticias por partido
    news.forEach(newsItem => {
        const newsParty = newsItem.getAttribute('data-party');
        // Mostrar u ocultar según el partido seleccionado
        if (selectedParty === 'all' || newsParty === selectedParty) {
            newsItem.style.display = 'block';
        } else {
            newsItem.style.display = 'none';
        }
    });

    // Reiniciar las páginas a 1 cuando cambie el filtro
    currentPageEvents = 1;
    currentPageNews = 1;

    displayEvents();  // Mostrar eventos filtrados y paginados
    displayNews();    // Mostrar noticias filtradas y paginadas
}



// Cambiar página para eventos
function changePage(offset, type) {
    if (type === 'events') {
        currentPageEvents += offset;
        displayEvents();
    } else if (type === 'news') {
        currentPageNews += offset;
        displayNews();
    }
}


document.addEventListener('DOMContentLoaded', function () {
    // Solicitud para obtener eventos y noticias desde PHP
    fetch('../src/eventos_noticias_queries.php')
        .then(response => response.json())
        .then(data => {
            // Procesar los eventos y noticias desde el servidor
            events = data.events.map(event => createEventHTML(event));
            news = data.news.map(newsItem => createNewsHTML(newsItem));

            // Ocultar mensajes si hay eventos y noticias
            if (events.length > 0) {
                document.querySelector("#noEventsMessage").style.display = 'none';
            } else {
                document.querySelector("#noEventsMessage").style.display = 'block';
            }

            if (news.length > 0) {
                document.querySelector("#noNewsMessage").style.display = 'none';
            } else {
                document.querySelector("#noNewsMessage").style.display = 'block';
            }

            // Filtrar y mostrar los datos iniciales
            filterByParty();
        })
        .catch(error => console.error('Error fetching events and news:', error));
});


// Función para mostrar la ventana emergente
function showModal(text) {
    const modal = document.getElementById('modal');
    const modalText = document.getElementById('modal-text');
    modalText.textContent = text;
    modal.style.display = 'block';

    // Cerrar ventana emergente
    document.querySelector('.close-button').onclick = function () {
        modal.style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
}

// Función para crear el HTML de un evento
function createEventHTML(event) {
    const eventDiv = document.createElement('div');
    eventDiv.classList.add('event');
    eventDiv.setAttribute('data-party', event.NOM_PAR);

    const isTruncated = event.DESC_EVT_NOT.length > 150; // Límite para mostrar "Ver más"

    eventDiv.innerHTML = `
        <div class="event-title">${event.TIT_EVT_NOT}</div>
        <img src="${event.IMAGEN_EVT_NOT || '/Eventos_Noticias/img/evento_default.jpg'}" alt="Imagen del Evento" class="event-image">
        <div class="event-description">
            ${isTruncated ? event.DESC_EVT_NOT.substring(0, 150) + '...' : event.DESC_EVT_NOT}
        </div>
        ${isTruncated ? '<button class="view-more">Ver más</button>' : ''}
        <div class="event-date-location">
            <span class="event-date">${event.FECHA_EVT_NOT}</span>
            |
            <span class="event-location">
                <i class="fas fa-map-marker-alt"></i>${event.UBICACION_EVT_NOT || 'No disponible'}
            </span>
        </div>
    `;

    // Agregar evento al botón "Ver más"
    if (isTruncated) {
        eventDiv.querySelector('.view-more').onclick = function () {
            showModal(event.DESC_EVT_NOT);
        };
    }

    return eventDiv;
}

// Función para crear el HTML de una noticia
function createNewsHTML(newsItem) {
    const newsDiv = document.createElement('div');
    newsDiv.classList.add('news');
    newsDiv.setAttribute('data-party', newsItem.NOM_PAR);

    const isTruncated = newsItem.DESC_EVT_NOT.length > 150; // Límite para mostrar "Ver más"

    newsDiv.innerHTML = `
        <div class="news-title">${newsItem.TIT_EVT_NOT}</div>
        <img src="${newsItem.IMAGEN_EVT_NOT || '/Eventos_Noticias/img/noticia_default.jpg'}" alt="Imagen de la Noticia" class="news-image">
        <div class="news-description">
            ${isTruncated ? newsItem.DESC_EVT_NOT.substring(0, 150) + '...' : newsItem.DESC_EVT_NOT}
        </div>
        ${isTruncated ? '<button class="view-more">Ver más</button>' : ''}
        <div class="news-date-location">
            <span class="news-date">${newsItem.FECHA_EVT_NOT}</span>
        </div>
    `;

    if (isTruncated) {
        newsDiv.querySelector('.view-more').onclick = function () {
            showModal(newsItem.DESC_EVT_NOT);
        };
    }

    return newsDiv;
}


