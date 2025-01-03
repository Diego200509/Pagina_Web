<?php
include('../config/config.php');


$navbarConfigPath = "../Login/navbar_config.json"; 

if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; 
} else {
    $navbarBgColor = '#00bfff'; 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
        :root {
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
                <img src="../Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">

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
