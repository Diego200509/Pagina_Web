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



$configFileCandidatos = "../Login/PaginaCandidatos.json";

if (file_exists($configFileCandidatos)) {
    $config = json_decode(file_get_contents($configFileCandidatos), true);
    $paginaBgColor = $config['paginaBgColor'] ?? "#f4f4f4";
} else {
    $paginaBgColor = "#f4f4f4";
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
    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
            --pagina-bg-color: <?php echo $paginaBgColor; ?>;
            body {
    background-color: var(--pagina-bg-color);
}


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