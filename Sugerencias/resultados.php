<?php

include('../config/config.php');
$eventos_noticias = include('../src/resultado_queries.php');

$nombrePartido1 = obtenerNombrePartido(1);
$nombrePartido2 = obtenerNombrePartido(2);
$votosPorPartido = obtenerVotosPorPartido();
// Sumar los votos para calcular el total
$totalVotos = array_sum($votosPorPartido);

function calcularPorcentaje($votos, $total)
{
    return $total > 0 ? ($votos / $total) * 100 : 0; // Previene la división por cero
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="EstilosResultados.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Resultados Presidenciales Ecuador 2023</title>

</head>

<body>
    <header id="main-header">
        <div class="logo">
            <img src="Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/Candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y
                Noticias</a>
                <a href="../Sugerencias/candidato1.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
                </nav>
    </header>

    <div class="container">
        <div class="logo">
            <img src="Img\logo.png" alt="CNE Logo">
            <div>
                <h1>RESULTADOS OFICIALES</h1>
                <h2>RECTORA DE LA UNIVERSIDAD TECNICA DE AMBATO</h2>
                <h2>Elecciones Anticipadas 2024</h2>
            </div>
            <img src="Img\UTA.png" alt="Escudo UTA">
        </div>

        <div class="results">
            <div class="candidate">
                <h2><span
                        style="color: #a30280; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;"><?php echo htmlspecialchars($nombrePartido1); ?></span>
                </h2>
                <img src="Img\mari2.jpg" alt="Nombre del candidato2">
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
                <img src="Img\CANDIDATA2.jpg" alt="Nombre del candidato1">
                <div class="percentage">
                    <?php echo isset($votosPorPartido[2]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[2], $totalVotos), 2) . '%' : ''; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : ''; ?>
                </div>
                <!-- Aquí muestra los votos -->
            </div>
        </div>

        <!-- Botón de regresar -->
        <div class="back-button">
            <a href="index.php">Regresar</a>
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