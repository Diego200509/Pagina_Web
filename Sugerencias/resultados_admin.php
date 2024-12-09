<?php
include('../config/config.php');
$eventos_noticias = include('../src/resultado_queries.php');

$nombrePartido1 = obtenerNombrePartido(1);
$nombrePartido2 = obtenerNombrePartido(2);
$votosPorPartido = obtenerVotosPorPartido();

// Sumar los votos para calcular el total
$totalVotos = array_sum($votosPorPartido);

// Función para calcular el porcentaje
function calcularPorcentaje($votos, $total)
{
    return $total > 0 ? ($votos / $total) * 100 : 0; // Previene la división por cero
}

// Función para obtener los detalles de votantes por partido
function obtenerDetallesVotantes($idPartido)
{
    global $connection;

    $sql = "SELECT u.NOM_USU AS nombre_usuario, u.EMAIL_USU AS correo_usuario
            FROM REGISTROS_VOTOS rv
            JOIN USUARIOS u ON rv.ID_USU_RES = u.ID_USU
            JOIN VOTOS v ON rv.ID_VOT_RES = v.ID_VOT
            WHERE v.ID_PAR_VOT = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $idPartido);
    $stmt->execute();
    $result = $stmt->get_result();

    $detalles = [];
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }

    return $detalles;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="EstilosResultados.css">
    <title>Resultados Admin</title>
</head>

<body>
    <header id="main-header">
        <div class="logo">
            <img src="Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024 - Administrador</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/Candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

    <div class="container">
        <h2>Resultados Detallados</h2>
        <div class="results">
            <div class="candidate">
                <h2><span
                        style="color: #a30280; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                        <?php echo htmlspecialchars($nombrePartido1); ?></span>
                </h2>
                <img src="Img/mari2.jpg" alt="Nombre del candidato 1">
                <div class="percentage">
                    Porcentaje: <?php echo isset($votosPorPartido[1]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[1], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[1]) ? $votosPorPartido[1] : '0'; ?>
                </div>
                <h4>Detalles de los votantes:</h4>
                <ul>
                    <?php
                    $detallesVotantes = obtenerDetallesVotantes(1);
                    foreach ($detallesVotantes as $votante) {
                        echo "<li>" . htmlspecialchars($votante['nombre_usuario']) . " (" . htmlspecialchars($votante['correo_usuario']) . ")</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="candidate">
                <h2><span
                        style="color: blue; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                        <?php echo htmlspecialchars($nombrePartido2); ?></span>
                </h2>
                <img src="Img/CANDIDATA2.jpg" alt="Nombre del candidato 2">
                <div class="percentage">
                    Porcentaje: <?php echo isset($votosPorPartido[2]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[2], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : '0'; ?>
                </div>
                <h4>Detalles de los votantes:</h4>
                <ul>
                    <?php
                    $detallesVotantes = obtenerDetallesVotantes(2);
                    foreach ($detallesVotantes as $votante) {
                        echo "<li>" . htmlspecialchars($votante['nombre_usuario']) . " (" . htmlspecialchars($votante['correo_usuario']) . ")</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-rights">
        Derechos reservados Team Sangre 2024
    </div>
</body>

</html>
