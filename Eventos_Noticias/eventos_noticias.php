<?php
$configFileEventos = "../Login/PaginaEventos.json";

if (file_exists($configFileEventos)) {
    $config = json_decode(file_get_contents($configFileEventos), true);
    $paginaBgColor = $config['paginaBgColor'] ?? "#33FF58";
} else {
    $paginaBgColor = "#33FF58";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos y Noticias</title>
    <link rel="stylesheet" href="styleEvent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --pagina-bg-color: <?php echo $paginaBgColor; ?>;
        }
    </style>
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

    <div class="select-box">
        <label for="partySelect">Selecciona un partido político:</label>
        <select id="partySelect" onchange="filterByParty()">
            <option value="all">Todos</option>
            <option value="Sueña, crea, innova">Sueña, crea, innova</option>
            <option value="Juntos por el cambio">Juntos por el cambio</option>
        </select>
    </div>

    <div id="events" class="content-section">
        <h2>Eventos</h2>
        <p id="noEventsMessage">No hay eventos disponibles.</p> 
        <div id="eventList">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event" data-party="<?php echo $event['NOM_PAR']; ?>">
                        <div class="event-title"><?php echo $event['TIT_EVT_NOT']; ?></div>

                        <img src="<?php echo !empty($event['IMAGEN_EVT_NOT']) ? $event['IMAGEN_EVT_NOT'] : '/Pagina_Web/Pagina_Web/Eventos_Noticias/img/evento_default.jpg'; ?>"
                            alt="Imagen del Evento" class="event-image">

                        <div class="event-description"><?php echo $event['DESC_EVT_NOT']; ?></div>
                        <div class="event-date">Fecha: <?php echo $event['FECHA_EVT_NOT']; ?> | Ubicación:
                            <?php echo $event['UBICACION_EVT_NOT']; ?>
                        </div>
                        <div class="event-party">Partido: <?php echo $event['NOM_PAR']; ?></div>
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
        <p id="noNewsMessage">No hay noticias disponibles.</p>
        <div id="newsList">
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $newsItem): ?>
                    <div class="news" data-party="<?php echo $newsItem['NOM_PAR']; ?>">
                        <div class="news-title"><?php echo $newsItem['TIT_EVT_NOT']; ?></div>
                        <img src="<?php echo !empty($newsItem['IMAGEN_EVT_NOT']) ? $newsItem['IMAGEN_EVT_NOT'] : '/Eventos_Noticias/img/noticia_default.jpg'; ?>"
                            alt="Imagen de la Noticia" class="news-image">

                        <div class="news-description"><?php echo $newsItem['DESC_EVT_NOT']; ?></div>
                        <div class="news-date">Fecha: <?php echo $newsItem['FECHA_EVT_NOT']; ?></div>
                        <div class="news-party">Partido: <?php echo $newsItem['NOM_PAR']; ?></div>
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

    <script src="scriptsEvents.js"></script>

</body>

</html>