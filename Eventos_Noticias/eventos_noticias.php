<?php
include('../config/config.php');

$navbarConfigPath = "../Login/navbar_config.json"; // Ruta al archivo de configuración del Navbar

// Verificar si el archivo existe y cargar el color del Navbar
if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; // Azul por defecto
} else {
    $navbarBgColor = '#00bfff'; // Azul por defecto si no existe el archivo
}

// Obtener la ruta de la imagen para la sección 'logoNavbar'
$section_name = 'logoNavbar';
$stmt = $connection->prepare("SELECT image_path FROM imagenes_Inicio_Logo WHERE section_name = ?");
$stmt->bind_param("s", $section_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logo_path = $row['image_path'];
} else {
    $logo_path = "/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png"; // Imagen por defecto
}


$configFileEventos = "../Login/PaginaEventos.json";

if (file_exists($configFileEventos)) {
    $config = json_decode(file_get_contents($configFileEventos), true);
    $paginaEventosBgColor = $config['paginaEventosBgColor'] ?? "#f4f4f4";
} else {
    $paginaEventosBgColor = "#f4f4f4";
    
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
            --pagina-bg-color: <?php echo $paginaEventosBgColor; ?>;
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
    
        }
    </style>
</head>

<body>
    <navbar>
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-logo">
                <div class="text-center">
                </div>
                <img src="<?php echo htmlspecialchars($logo_path); ?>"  width="200px" style="margin-right: 20px;">
                </div>
            </div>
            <ul class="navbar-menu">
                <li><a href="../Home/inicio.php"><i class="fa-solid fa-house"></i> <span>Inicio</span></a></li>
                <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
                <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
                <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
                <li><a href="../Sugerencias/candidato1.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
                <li><a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            </ul>
        </nav>


    </navbar>

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
        <h2>Noticias</h2>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>