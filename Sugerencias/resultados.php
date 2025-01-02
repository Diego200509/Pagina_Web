<?php

include('../config/config.php');
$eventos_noticias = include('../src/resultado_queries.php');

$nombrePartido1 = obtenerNombrePartidoResultados(1);
$nombrePartido2 = obtenerNombrePartidoResultados(2);
$votosPorPartido = obtenerVotosPorPartidoResultados();
// Sumar los votos para calcular el total
$totalVotos = array_sum($votosPorPartido);

function calcularPorcentaje($votos, $total)
{
    return $total > 0 ? ($votos / $total) * 100 : 0; // Previene la división por cero
}

include('../config/config.php');


$navbarConfigPath = "../Login/navbar_config.json"; // Ruta al archivo de configuración del Navbar

// Verificar si el archivo existe y cargar el color del Navbar
if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; // Azul por defecto
} else {
    $navbarBgColor = '#00bfff'; // Azul por defecto si no existe el archivo
}


//Obtener las imágenes desde la base de datos
$imagenesActuales = obtenerImagenesResultados();
if (!$imagenesActuales) {
    $imagenesActuales = array_fill(0, 6, '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/default.jpg');
}

$imagenCandidato1 = isset($imagenesActuales[3]) ? $imagenesActuales[3] : '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/default.jpg';
$imagenCandidato2 = isset($imagenesActuales[4]) ? $imagenesActuales[4] : '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/default.jpg';
// Imagen de fondo
$imagenFondo = isset($imagenesActuales[5]) ? $imagenesActuales[5] : '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/default.jpg';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="EstilosResultados.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
        }


        body, html {
    margin: 0;
    padding: 0;
    height: 116%; /* Ocupa toda la altura de la ventana */
    font-family: Arial, sans-serif;
    background-image: url('<?php echo htmlspecialchars($imagenFondo); ?>');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover; /* Ajusta la imagen para cubrir toda la pantalla */
    color: white;
    text-align: center;
    box-sizing: border-box;
}
    </style>
    <title>Resultados Elecciones 2023</title>

</head>


<body>
    <navbar>

<!-- Navbar -->
<nav class="navbar">
<div class="navbar-logo">
<div class="text-center">
</div>
<!-- Logo existente -->
<img src="Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">

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
            <div>
                <h1>RESULTADOS OFICIALES</h1>
                <h2>RECTORA DE LA UNIVERSIDAD TECNICA DE AMBATO</h2>
                <h2>Elecciones Anticipadas 2024</h2>
        </div>

        <div class="results">
            <div class="candidate">
                <h2><span
                        style="color: #a30280; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;"><?php echo htmlspecialchars($nombrePartido1); ?></span>
                </h2>
                <img src="<?php echo htmlspecialchars($imagenCandidato1); ?>" alt="Candidato 1" 
     style="width: 100%; max-width: 300px; height: 460px; object-fit: cover; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <div class="percentage">
                    <?php echo isset($votosPorPartido[1]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[1], $totalVotos), 2) . '%' : ''; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[1]) ? $votosPorPartido[1] : ''; ?>
                </div>

            </div>
            <div class="candidate">
                <h2><span
                        style="color: blue; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;"><?php echo htmlspecialchars($nombrePartido2); ?></span>
                </h2>
                <img src="<?php echo htmlspecialchars($imagenCandidato2); ?>" alt="Candidato 2"
                style="width: 100%; max-width: 300px; height: 460px; object-fit: cover; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <div class="percentage">
                    <?php echo isset($votosPorPartido[2]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[2], $totalVotos), 2) . '%' : ''; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : ''; ?>
                </div>
                <!-- Aquí muestra los votos -->
            </div>
        </div>


    </div>

    <div class="footer-rights">
        Derechos reservados Team Sangre 2024
    </div>
    <script>
        // Código para manejar el scroll y ocultar/mostrar el header
        let lastScrollTop = 0;
        const header = document.querySelector('header');

        window.addEventListener('scroll', function () {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                // Scroll hacia abajo
                header.style.top = "-100px"; // Esconde el header
            } else {
                // Scroll hacia arriba
                header.style.top = "0"; // Muestra el header
            }
            lastScrollTop = scrollTop;
        });
    </script>

</body>

</html>