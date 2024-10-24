<?php
$eventos_noticias = include('../Home/src/inicio_queries.php');
include('../Config/config.php');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos a Rector</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Estilos.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="Img\logo.png" alt="UTA Logo"> 
        <h1>Proceso de Elecciones UTA 2024</h1>
    </div>
    <nav>
        <a href="#"><i class="fas fa-home"></i> Inicio</a>
        <a href="#candidatos"><i class="fas fa-user"></i> Candidatos</a>
        <a href="#propuestas"><i class="fas fa-bullhorn"></i> Propuestas</a>
        <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
        <a href="#sugerencias"><i class="fas fa-comment-dots"></i> Sugerencias</a>
    </nav>
</header>

<section class="slider">
    <div class="fade"></div>
    <div class="slides">
        <div class="slide slide1 active">
            <div class="content">
                <h1><?php echo htmlspecialchars($candidato_nombre); ?></h1>
                <p><?php echo htmlspecialchars($candidato_biografia); ?></p>
                <a href="#" class="btn">Más Información</a>
            </div>
            <img src="Img\fo1.jpg" alt="Candidate Image">
        </div>
        <div class="slide slide2">
            <div class="content">
                <h1><?php echo htmlspecialchars($propuesta_titulo); ?></h1>
                <p><?php echo htmlspecialchars($propuesta_descripcion); ?></p>
                <a href="#" class="btn">Más Información</a>
            </div>

            <img src="Img\mari2.jpg" alt="Candidate Image">

            <img src="Img\mari2.jpg" alt="Propuesta Image">

            <img src="Img\mari2.jpg" alt="Propuesta Image">
            <img src="Img\mari2.jpg" alt="Candidate Image">

        </div>
        <div class="slide slide2">
            <div class="content">
                <h1><?php echo htmlspecialchars($evento_titulo); ?></h1>
                <p><?php echo htmlspecialchars($evento_descripcion); ?></p>
                <a href="#" class="btn">Más Información</a>
            </div>

            <img src="Img\fo2.jpg" alt="Candidate Image">

            <img src="Img\fo2.jpg" alt="Evento Image">

            <img src="Img\fo2.jpg" alt="Evento Image">
            <img src="Img\fo2.jpg" alt="Candidate Image">
        </div>
        <div class="slide slide2">
            <div class="content">
            <h1><?php echo htmlspecialchars($sugerencia_titulo); ?></h1>
            <p><?php echo htmlspecialchars($sugerencia_descripcion); ?></p>
            <a href="#" class="btn">Más Información</a>
            </div>

            <img src="Img\mari.jpg" alt="Candidate Image">
        </div>  

            <img src="Img\mari.jpg" alt="Sugerencias Image">
        </div>

            <img src="Img\mari.jpg" alt="Sugerencias Image">
        </div>
            <img src="Img\mari.jpg" alt="Candidate Image">
        </div>  
>>>>>>> Inicio
    </div>
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
</section>

<section id="candidatos">
    <div class="candidatos-container">
        <div class="candidatos-text">
            <h1>Conoce a nuestros candidatos</h1>
            <a href="#" class="btn">Ver más información de los candidatos</a>
        </div>
    </div>
</section>

<section id="propuestas">
    <h1>Propuestas</h1>
    <p>Puedes ver nuestras propuestas a continuación.</p>
    <div class="background">
        <div class="text">
            Tú <br> Nueva manera. <br> Nuevo comienzo.
            <p><a href="#url_destino" class="button-link">Conoce más sobre las propuestas</a></p>
        </div>
    </div>
</section>
<section class="eventos-container">
    <h2>Eventos y Noticias</h2>
    <div class="eventos-grid">
        <?php foreach ($eventos_noticias as $evento_noticia): ?>
        <div class="evento-card">

            <img src="Img\anuncio.jpg" alt="Evento 1">

            <img src="Img\anuncio.jpg" alt="Evento Imagen">

            <img src="Img\anuncio.jpg" alt="Evento Imagen">
            <img src="Img\anuncio.jpg" alt="Evento 1">
            <div class="evento-info">
                <h3><?php echo htmlspecialchars($evento_noticia['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($evento_noticia['descripcion']); ?></p>
            </div>
            <div class="overlay">
                <a href="pagina_evento1.html">Más información</a> <!-- Cambia la URL según sea necesario -->
                </div>
        </div>

        <?php endforeach; ?>
        <div class="evento-card">
            <img src="Img\anuncio2.jpg" alt="Evento 2">
            <div class="evento-info">
                <h3>Hackathon 2024</h3>
                <p>Participa en nuestro hackathon y demuestra tus habilidades de programación en un ambiente competitivo y colaborativo.</p>
            </div>
            <div class="overlay">
                <a href="pagina_evento2.html">Más información</a> <!-- Cambia la URL según sea necesario -->
            </div>
        </div>
        <div class="evento-card">
            <img src="Img\anuncio2.jpg" alt="Evento 2">
            <div class="evento-info">
                <h3>Hackathon 2024</h3>
                <p>Participa en nuestro hackathon y demuestra tus habilidades de programación en un ambiente competitivo y colaborativo.</p>
            </div>
            <div class="overlay">
                <a href="pagina_evento2.html">Más información</a> <!-- Cambia la URL según sea necesario -->
            </div>
        </div>
        <div class="evento-card">
            <img src="Img\anuncio2.jpg" alt="Evento 2">
            <div class="evento-info">
                <h3>Hackathon 2024</h3>
                <p>Participa en nuestro hackathon y demuestra tus habilidades de programación en un ambiente competitivo y colaborativo.</p>
            </div>
            <div class="overlay">
                <a href="pagina_evento2.html">Más información</a> <!-- Cambia la URL según sea necesario -->
            </div>
        </div>
    </div>

</section>

<section id="sugerencias">
    <h1 class="sugerencias-title">Sugerencias</h1>
    <div class="sugerencias-container">
        <div class="sugerencia-card">
            <div class="sugerencia-content">
                <h2 class="sugerencia-subtitle"><?php echo htmlspecialchars($sugerencia_titulo); ?></h2>
                <p class="sugerencia-highlight">Partido: <?php echo htmlspecialchars($nombre_partido); ?></p>
                <p class="sugerencia-description"><?php echo htmlspecialchars($sugerencia_descripcion); ?></p>
                <a href="" class="sugerencia-button">
                    <button>ver más</button>
                </a>
            </div>
            <div class="sugerencia-image-container">
                <img src="Img\anuncio.jpg" alt="Concierto" class="sugerencia-image">
            </div>
        </div>
    </div>
</section>

<script src="Scripts.js"></script> <!-- Enlace al archivo JavaScript -->

<footer class="footer-rights">
    <p>Todos los derechos reservados Team Sangre © 2024</p>
</footer>

</body>
</html>