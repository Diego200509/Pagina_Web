<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos y Noticias</title>
    <link rel="stylesheet" href="styleEvents.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href='../Eventos_Noticias/eventos_noticias.php'><i class="fas fa-calendar-alt"></i> Eventos y
                Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

    <div id="events" class="content-section">
        <h2>Eventos</h2>
        <p class="section-description">Mantente informado con los eventos más relevantes de nuestra comunidad. </p>
        <p id="noEventsMessage">No hay eventos disponibles.</p>
        <div id="eventList">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event" data-party="<?php echo $event['NOM_PAR']; ?>">
                        <!-- Contenedor de imagen -->
                        <div class="event-image-container">
                            <img src="<?php echo !empty($event['IMAGEN_EVT_NOT']) ? $event['IMAGEN_EVT_NOT'] : '/Pagina_Web/Pagina_Web/Eventos_Noticias/img/evento_default.jpg'; ?>"
                                alt="Imagen del Evento" class="event-image">
                        </div>

                        <!-- Contenedor de información -->
                        <div class="event-info">
                            <h3 class="event-title"><?php echo $event['TIT_EVT_NOT']; ?></h3>
                            <p class="event-description"><?php echo $event['DESC_EVT_NOT']; ?></p>
                            <p class="event-date"><?php echo $event['FECHA_EVT_NOT']; ?></p>
                            <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo $event['UBICACION_EVT_NOT']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="pagination" id="eventPagination">
            <button id="prevPageEvents" onclick="changePage(-1, 'events')">Anterior</button>
            <button id="nextPageEvents" onclick="changePage(1, 'events')">Siguiente</button>
        </div>
    </div>

    <div id="news" class="content-section">
        <h2>Últimas Noticias</h2>
        <p class="section-description"> Mantente informado con las noticias más relevantes.</p>
        <p id="noNewsMessage">No hay noticias disponibles.</p>
        <div id="newsList">
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $newsItem): ?>
                    <div class="news" data-party="<?php echo $newsItem['NOM_PAR']; ?>">
                        <!-- Contenedor de imagen -->
                        <div class="news-image-container">
                            <img src="<?php echo !empty($newsItem['IMAGEN_EVT_NOT']) ? $newsItem['IMAGEN_EVT_NOT'] : '/Eventos_Noticias/img/noticia_default.jpg'; ?>"
                                alt="Imagen de la Noticia" class="news-image">
                        </div>

                        <!-- Contenedor de información -->
                        <div class="news-info">
                            <h3 class="news-title"><?php echo $newsItem['TIT_EVT_NOT']; ?></h3>
                            <p class="news-description"><?php echo $newsItem['DESC_EVT_NOT']; ?></p>
                            <p class="news-date"><?php echo $newsItem['FECHA_EVT_NOT']; ?></p>
                            <p class="news-location"><i class="fas fa-map-marker-alt"></i> <?php echo $newsItem['UBICACION_EVT_NOT']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="pagination" id="newsPagination">
            <button id="prevPageNews" onclick="changePage(-1, 'news')">Anterior</button>
            <button id="nextPageNews" onclick="changePage(1, 'news')">Siguiente</button>
        </div>
    </div>


    <div class="footer-rights">
        Derechos reservados UTA 2024
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <p id="modal-text"></p>
        </div>
    </div>

    <script src="scriptsEvents.js"></script>

</body>

</html>