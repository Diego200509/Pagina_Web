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

// Ruta al archivo JSON de configuración de colores
$configFile = "../Login/PaginaPropuestas.json";

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    $paginaPropuestasBgColor = $config['paginaPropuestasBgColor'] ?? "#000000"; // Color blanco por defecto
} else {
    $paginaPropuestasBgColor = "#000000"; // Color blanco por defecto si no existe el archivo
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
    $logo_path = "../Login/Img/logoMariCruz.png"; // Imagen por defecto
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuestas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilosPropuestas.css">
    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
            --pagina-bg-color: <?php echo $paginaPropuestasBgColor; ?>;

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
    <!-- Logo existente -->
    <img src="<?php echo htmlspecialchars($logo_path); ?>"  width="200px" style="margin-right: 20px;">

</div>



        </div>
        <ul class="navbar-menu"> 
        <li><a href="../Home/inicio.php"><i class="fa-solid fa-house"></i> <span>Inicio</span></a></li>
            <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/index.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
        </ul>
    </nav>


</navbar>

    <div class="container">
        <h2>Propuestas</h2>

        <!-- Filtro por categoría -->
        <div class="filter-box">
            <label for="faculty">Filtrar por Facultad o Interés:</label>
            <select id="faculty" onchange="filterProposals()">
                <option value="all">Mostrar Todas</option>
                <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                <option value="Ciencia e Ingeniería en Alimentos">Ciencia e Ingeniería en Alimentos</option>
                <option value="Jurisprudencia y Ciencias Sociales">Jurisprudencia y Ciencias Sociales</option>
                <option value="Contabilidad y Auditoría">Contabilidad y Auditoría</option>
                <option value="Ciencias Humanas y de la Educación">Ciencias Humanas y de la Educación</option>
                <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                <option value="Ingeniería Civil y Mecánica">Ingeniería Civil y Mecánica</option>
                <option value="Ingeniería en Sistemas, Electrónica e Industrial">Ingeniería en Sistemas, Electrónica e Industrial</option>
                <option value="infraestructura">Infraestructura</option>
                <option value="deportes">Deportes</option>
                <option value="cultura">Cultura</option>
                <option value="investigación">Investigación</option>
                <option value="vinculación">Vinculación con la Sociedad</option>
            </select>
        </div>

        <!-- Contenedor para las propuestas -->
        <div class="proposals-grid" id="proposalsGrid">
            <!-- Las propuestas se agregarán aquí dinámicamente -->
        </div>
    </div>

    <footer>
        Derechos reservados UTA 2024
    </footer>

    <script src="scriptsPropuestas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    // Escuchar cambios en el almacenamiento local
    window.addEventListener("storage", function (event) {
        if (event.key === "navbarColorUpdated" && (event.newValue === "true" || event.newValue === "reset")) {
            // Recargar la página cuando se detecte un cambio o restablecimiento
            window.location.reload();
        }
    });
});



</script>

</body>

</html>