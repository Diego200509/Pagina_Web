<?php
session_start();
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['SUPERADMIN', 'ADMIN'])) {
    header("Location: ../Login/Login.php");
    exit;
}
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen']) && isset($_POST['posicion'])) {
    $imagen = $_FILES['imagen'];
    $posicion = (int)$_POST['posicion']; // Validar la posición seleccionada

    if ($imagen['error'] === UPLOAD_ERR_OK) {
        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '-' . basename($imagen['name']);

        // Ruta completa para guardar la imagen en el servidor
        $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/';
        $rutaDestino = $directorioDestino . $nombreImagen;

        // Ruta relativa para guardar en la base de datos
        $rutaBaseDatos = '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/' . $nombreImagen;

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true)) {
                error_log("Error: No se pudo crear el directorio destino: $directorioDestino");
                exit;
            }
        }

        // Mover el archivo subido al destino
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            // Guardar la ruta de la imagen en la base de datos
            if (actualizarImagenResultados($rutaBaseDatos, $posicion)) {
                $_SESSION['mensaje'] = "Imagen actualizada correctamente en la posición $posicion.";
            } else {
                $_SESSION['mensaje'] = "Error al registrar la imagen en la base de datos.";
            }
        } else {
            $_SESSION['mensaje'] = "Error al mover la imagen al servidor.";
        }
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen. Código de error: " . $imagen['error'];
    }
    header("Location: resultados_admin.php");
    exit;
}

$imagenesActuales = obtenerImagenesResultados();



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
        
        @keyframes animatedBackground {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.logout {
            color: #ffc107;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background: white;
            color: #333;
            border-radius: 15px;
            padding: 25px;
            width: 450px;
            max-width: 90%;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content h2 {
            color: rgb(122, 3, 23);
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: #666;
            transition: color 0.2s ease;
        }

        .close-btn:hover {
            color: red;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }

        .form-group input, .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            width: 100%;
        }

        .btn-submit {
            padding: 12px;
            background-color: rgb(122, 3, 23);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: rgb(236, 231, 232);
        }

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 50%, #f5a5e0 100%); /* Degradado pastel suave entre azul, lila y rosa */
    background-size: 400% 400%;
    animation: animatedBackground 12s ease infinite; /* Animación suave */
    overflow-x: hidden;
}
.image-modal {
    display: none; /* Oculto inicialmente */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
    justify-content: center;
    align-items: center;
    z-index: 10000;
}

.image-modal-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.image-modal-content h2 {
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.image-modal-close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.image-modal-close:hover {
    color: red;
}

.image-modal .btn-submit {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 15px;
}

.image-modal .btn-submit:hover {
    background-color: #388e3c;
}

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

<script>
        const showModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'flex';
        };

        const closeModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'none';
        };

        window.onclick = function(event) {
            const modal = document.getElementById('modal-crear-usuario');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
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
            <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
                <li><a href="#" onclick="showModal()"><i class="fas fa-user-plus"></i> Crear Admin</a></li>
            <?php endif; ?>
            <li><a href="../Login/Login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </nav>

    <!-- Modal para Crear Admin -->
    <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
        <div id="modal-crear-usuario" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Crear Nuevo Admin</h2>
                <form action="../src/crear_usuario_queries.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Ingrese una contraseña" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select id="rol" name="rol" required>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Crear Admin</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

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



        <div class="container">
    <h2 class="section-title">Votos</h2>
    <div class="row">
        <?php foreach (array_slice($imagenesActuales, 0, 3) as $posicion => $ruta): ?>
            <div class="col-md-4 text-center">
                <h3>
                    <?php 
                        if ($posicion == 2) {
                            echo "Fondo";
                        } else {
                            echo "Candidato " . ($posicion + 1);
                        }
                    ?>
                </h3>
                <img src="<?php echo htmlspecialchars($ruta); ?>" class="img-fluid mb-3" alt="Imagen Posición <?php echo $posicion + 1; ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>

        <h2 class="section-title">Resultados</h2>
        <div class="row">
            <?php foreach (array_slice($imagenesActuales, 3, 3) as $posicion => $ruta): ?>
                <div class="col-md-4 text-center">
                    <h3>                    <?php 
                        if ($posicion == 2) {
                            echo "Fondo";
                        } else {
                            echo "Candidato " . ($posicion + 1);
                        }
                    ?></h3>
                    <img src="<?php echo htmlspecialchars($ruta); ?>" class="img-fluid mb-3" alt="Imagen Posición <?php echo $posicion + 4; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        
    <button class="btn-config" onclick="openImageModal()">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" class="svg-icon">
    <g stroke-width="1.5" stroke-linecap="round" stroke="#5d41de">
      <circle r="2.5" cy="10" cx="10"></circle>
      <path fill-rule="evenodd" d="M8.39 2.8c.54-1.51 2.68-1.51 3.22 0 .34.95 1.43 1.4 2.34.97 1.45-.69 2.97.82 2.28 2.28-.43.91.02 2 .97 2.34 1.51.54 1.51 2.68 0 3.22-.95.34-1.4 1.43-.97 2.34.69 1.45-.82 2.97-2.28 2.28-.91-.43-2 .02-2.34.97-.54 1.51-2.68 1.51-3.22 0-.34-.95-1.43-1.4-2.34-.97-1.45.69-2.97-.82-2.28-2.28.43-.91-.02-2-.97-2.34-1.51-.54-1.51-2.68 0-3.22.95-.34 1.4-1.43.97-2.34-.69-1.45.82-2.97 2.28-2.28.91.43 2-.02 2.34-.97z" clip-rule="evenodd"></path>
    </g>
  </svg>
  <span class="lable">Actualizar Imagen</span>
</button>


        <div id="image-update-modal" class="image-modal">
            <div class="image-modal-content">
                <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
                <h2>Actualizar Imagen</h2>
                <form method="POST" enctype="multipart/form-data" action="resultados_admin.php">
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir nueva imagen:</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="posicion" class="form-label">Seleccionar posición:</label>
                        <select name="posicion" id="posicion" class="form-select" required>
                            <option value="1">Votos - Candidato 1</option>
                            <option value="2">Votos - Candidato 2</option>
                            <option value="3">Votos - Fondo</option>
                            <option value="4">Resultados - Candidato 1</option>
                            <option value="5">Resultados - Candidato 2</option>
                            <option value="6">Resultados - Fondo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Imagen</button>
                </form>
            </div>
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




        // Define la función para abrir el modal
function openImageModal() {
    const modal = document.getElementById('image-update-modal');
    modal.style.display = 'flex';
}

// Define la función para cerrar el modal
function closeImageModal() {
    const modal = document.getElementById('image-update-modal');
    modal.style.display = 'none';
}

// Cerrar el modal al hacer clic fuera de su contenido
window.addEventListener('click', function (event) {
    const modal = document.getElementById('image-update-modal');
    if (event.target === modal) {
        closeImageModal();
    }
});


document.addEventListener('DOMContentLoaded', function () {
    // Define la función para abrir el modal
    window.openImageModal = function () {
        const modal = document.getElementById('image-update-modal');
        modal.style.display = 'flex';
    };

    // Define la función para cerrar el modal
    window.closeImageModal = function () {
        const modal = document.getElementById('image-update-modal');
        modal.style.display = 'none';
    };

    // Cerrar el modal al hacer clic fuera del contenido
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('image-update-modal');
        if (event.target === modal) {
            window.closeImageModal();
        }
    });
});




    </script>
</body>

</html>