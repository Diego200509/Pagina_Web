<?php
include('../config/config.php');

$eventos_noticias = include('../src/inicio_queries.php');
include('../config/config.php');

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
        <h1>Proceso de Elecciones UTA  2024</h1>
    </div>
    <nav>
        <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="#candidatos"><i class="fas fa-user"></i> Candidatos</a>
        <a href="#propuestas"><i class="fas fa-bullhorn"></i> Propuestas</a>
        <a href="#eventos_noticias"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
        <a href="../Sugerencias/candidato1.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        <a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a> <!-- Nuevo campo -->

        </nav>
</header>

<section class="slider">
    <div class="fade"></div>
    <div class="slides">
        <div class="slide slide1 active">
            <div class="content">

            </div>
        </div>
        <div class="slide slide5">
            <div class="content">

            </div>
        </div>
    </div>
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
</section>

<section id="candidatos">
    <div class="candidatos-container">
        <div class="candidatos-text">
            <h1>Conoce a nuestros candidatos</h1>
            <a href="../Candidatos/candidatos.php" class="btn">Ver más información de los candidatos</a>
        </div>
    </div>
</section>

<section id="propuestas">
    <h1> <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        PROPUESTAS
    </span>  </h1>
    <div class="background">
        <div class="text">
            Tú <br> Nueva manera. <br> Nuevo comienzo.
            <p><a href="../Propuestas/Propuestas.php" class="button-link">Conoce más sobre las propuestas</a></p>
        </div>
    </div>
</section>
<section id ="eventos_noticias" class="eventos-container">
<h2>
    <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        Eventos y
    </span>  
    <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        Noticias
    </span>
</h2>
    <div class="eventos-grid">
        <?php foreach ($eventos_noticias as $evento_noticia): ?>
        <div class="evento-card">
            <img src="Img\eventosynoticias.jpg" alt="Evento Imagen">
            <div class="evento-info">
                <h3><?php echo htmlspecialchars($evento_noticia['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($evento_noticia['descripcion']); ?></p>
            </div>
            <div class="overlay">
                <a href="../Eventos_Noticias/eventos_noticias.php">Más información</a> <!-- Cambia la URL según sea necesario -->
                </div>
        </div>
        
        <?php endforeach; ?>


</section>

<section id="sugerencias">
    <h1 class="sugerencias-title"> <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        SUGERENCIAS
    </span>  </h1>
    <div class="sugerencias-container">
        <div class="sugerencia-card">
            <div class="sugerencia-content">
                <h2 class="sugerencia-subtitle"><?php echo htmlspecialchars($sugerencia_titulo); ?></h2>
                <p class="sugerencia-highlight">Partido: <?php echo htmlspecialchars($nombre_partido); ?></p>
                <p class="sugerencia-description"><?php echo htmlspecialchars($sugerencia_descripcion); ?></p>
                <a href="../Sugerencias/index.php" class="sugerencia-button">
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
