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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos</title>
    <link rel="stylesheet" href="candidatos_usuarios.css">
    <link rel="stylesheet" href="candidatos_usuarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
            <a href="../Sugerencias/candidato1.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
            <a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a> <!-- Nuevo campo -->

        </nav>
    </header>
    <main>
    <h2 class="title">Información del Candidato</h2>

    <div class="candidate-container">
        <button id="prevArrow" class="arrow left-arrow">
            <i class="fas fa-chevron-left"></i> <!-- Ícono de flecha izquierda -->
        </button>
        <div id="candidateContent" class="candidate-content">
            <!-- Aquí se cargará dinámicamente el candidato -->
        </div>
        <button id="nextArrow" class="arrow right-arrow">
            <i class="fas fa-chevron-right"></i> <!-- Ícono de flecha derecha -->
        </button>
    </div>
</main>

<div class="footer-rights">
        Derechos reservados UTA 2024
    </div>



    <script src="candidatos_usuarios.js"></script>
</body>
</html>
