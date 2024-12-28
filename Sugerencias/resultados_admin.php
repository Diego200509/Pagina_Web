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

include('../config/config.php');


$navbarConfigPath = "../Login/navbar_config.json"; // Ruta al archivo de configuración del Navbar

// Verificar si el archivo existe y cargar el color del Navbar
if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; // Azul por defecto
} else {
    $navbarBgColor = '#00bfff'; // Azul por defecto si no existe el archivo
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="EstilosResultados.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
        }
</style>
    <style>


        .container {
            background-color: rgba(252, 252, 252, 0.85);
            padding: 2px;
            max-width: 900px;
            margin: 2px auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        .results {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate {
            text-align: center;
            max-width: 250px;
        }

        .candidate img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .candidate h2 {
            font-size: 1.5em;
            color: #000;
            margin-bottom: 10px;
        }

        .candidate .percentage {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
        }

        .candidate .votes {
            font-size: 1em;
            color: #555;
        }

        .chart-container {
            text-align: center;
            background-color: rgba(252, 252, 252, 0.85);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
        }

        .chart-container h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: rgb(0, 0, 0);
        }

        .chart-container canvas {
            width: 100%;
            max-height: 300px;
        }
    </style>
    <title>Resultados Presidenciales Ecuador 2023</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <img src="Img/logoMariCruz.png" alt="Logo" width="200">
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fas fa-users"></i> Candidatos</a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fas fa-lightbulb"></i> Propuestas</a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fas fa-comment-dots"></i> Sugerencias</a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li> <!-- Nuevo campo -->
        </ul>
    </nav>

    <div class="container">
        <!-- Resultados -->
        <div class="results">
            <div class="candidate">
                <h2><?php echo htmlspecialchars($nombrePartido1); ?></h2>
                <img src="Img/mari2.jpg" alt="Candidato 1">
                <div class="percentage">
                    <?php echo isset($votosPorPartido[1]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[1], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">Votos: <?php echo $votosPorPartido[1] ?? 0; ?></div>
            </div>
            <div class="candidate">
                <h2><?php echo htmlspecialchars($nombrePartido2); ?></h2>
                <img src="Img/CANDIDATA2.jpg" alt="Candidato 2">
                <div class="percentage">
                    <?php echo isset($votosPorPartido[2]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[2], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">Votos: <?php echo $votosPorPartido[2] ?? 0; ?></div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="chart-container">
            <h2>Distribución de Votos</h2>
            <canvas id="adminChart"></canvas>
        </div>
    </div>

    <script>
        // Datos para el gráfico
        const votosPartido1 = <?php echo isset($votosPorPartido[1]) ? $votosPorPartido[1] : 0; ?>;
        const votosPartido2 = <?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : 0; ?>;
        const nombrePartido1 = "<?php echo htmlspecialchars($nombrePartido1); ?>";
        const nombrePartido2 = "<?php echo htmlspecialchars($nombrePartido2); ?>";

        const ctx = document.getElementById('adminChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [nombrePartido1, nombrePartido2],
                datasets: [{
                    data: [votosPartido1, votosPartido2],
                    backgroundColor: ['#a30280', '#0044cc'],
                    hoverBackgroundColor: ['#d62891', '#3366ff'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const total = votosPartido1 + votosPartido2;
                                const porcentaje = total > 0 ? ((tooltipItem.raw / total) * 100).toFixed(2) : 0;
                                return `${tooltipItem.label}: ${tooltipItem.raw} votos (${porcentaje}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
