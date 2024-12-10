<?php
// Manejo de la lógica del backend aquí, si es necesario
// Esto puede incluir validaciones o configuraciones específicas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos</title>
    <link rel="stylesheet" href="candidatos.css">
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
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="content">
        <div class="heart">&#10084;</div>
        <div class="text">
            <span class="pink">SUEÑA,</span><br>
            <span class="blue">CREA,</span><br>
            <span class="pink">INNOVA.</span>
        </div>
    </div>
    <main>
    <section id="party1" class="candidates-section">
    <h2>Partido 1</h2>
    <div id="party1Candidates" class="cards-container">
        <!-- Los candidatos del Partido 1 se cargarán aquí dinámicamente -->
    </div>
</section>

<section id="party2" class="candidates-section">
    <h2>Partido 2</h2>
    <div id="party2Candidates" class="cards-container">
        <!-- Los candidatos del Partido 2 se cargarán aquí dinámicamente -->
    </div>
</section>
</main>

    <!-- Modales para mostrar detalles de los candidatos -->
    <div id="candidateModal" class="modal">
        <div class="modal-content">
            <span id="closeCandidateModal" class="close-btn">&times;</span>
            <h2 id="candidate-name"></h2>
            <img id="candidate-img" src="" alt="Imagen del candidato">
            <p><strong>Biografía:</strong> <span id="candidate-bio"></span></p>
            <p><strong>Experiencia:</strong> <span id="candidate-experience"></span></p>
            <p><strong>Visión:</strong> <span id="candidate-vision"></span></p>
            <p><strong>Logros:</strong> <span id="candidate-achievements"></span></p>
        </div>
    </div>

    <script src="candidatos.js"></script>
</body>
</html>

