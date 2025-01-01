<?php
include('../config/config.php');

$eventos_noticias = include('../src/inicio_queries.php');
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
    $logo_path = "../Login/Img/logoMariCruz.png"; // Imagen por defecto
}




// Definir rutas por defecto
$slide1_path = "../Home/Img/FONDOMARI.jpg";
$slide5_path = "../Home/Img/FONDOMARI2.jpg";

// Consultar las rutas desde la base de datos
$stmt = $connection->prepare("SELECT section_name, image_path FROM imagenes_Inicio_Logo WHERE section_name IN ('slide1', 'slide5')");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['section_name'] === 'slide1' && file_exists($row['image_path'])) {
        $slide1_path = $row['image_path'];
    } elseif ($row['section_name'] === 'slide5' && file_exists($row['image_path'])) {
        $slide5_path = $row['image_path'];
    }
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos a Rector</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWM3FAa+UP7B7aXQFJ59Y3RF53p50eA88LvNCwD5zZoOMMDzBtF1UeJ0cEtCU" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Estilos.css">
    <style>
    :root {
        --navbar-bg-color: <?php echo $navbarBgColor; ?>;
    }
    .slide1 {
            background: url('<?php echo $slide1_path; ?>') no-repeat center center/cover;
            height: 300px;
        }

        .slide5 {
            background: url('<?php echo $slide5_path; ?>') no-repeat center center/cover;
            height: 300px;
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
    <img src="<?php echo htmlspecialchars($logo_path); ?>" width="200px" style="margin-right: 20px;">

</div>



        </div>
        <ul class="navbar-menu"> 
            <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/index.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
        </ul>
    </nav>


</navbar>

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

<section id="candidatos" style="background-color: <?php echo htmlspecialchars($candidatosBgColor); ?>;">
    <h1>Conoce a nuestros candidatos</h1>
</section>

<section id="propuestas">
    <h1> <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        PROPUESTAS
    </span>  </h1>
</section>

<section id ="eventos">
<h2>
    <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        Eventos y
    </span>  
    <span style="color: red; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
        Noticias
    </span>
</h2>
</section>

<footer class="footer-rights">
    <p>Todos los derechos reservados Team Sangre © 2024</p>
</footer>


<script src="Scripts.js"></script> <!-- Enlace al archivo JavaScript -->

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
