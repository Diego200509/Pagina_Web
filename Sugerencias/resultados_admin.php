<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../Login/Login.php");
    exit;
}
// Obtener el rol del usuario
$user_role = $_SESSION['user_role'];

// Determinar la URL del dashboard según el rol del usuario
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/superadmin_dasboard.php' : '../Login/admin_dashboard.php';
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
        
        @keyframes animatedBackground {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.logout {
            color: #ffc107;
        }

        .toggle-button {
            background-color: #ff1493;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s ease-in-out;
        }

        .toggle-button:hover {
            background-color: #00bfff;
        }
        #image-update-container {
            display: none; /* Oculto inicialmente */
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 15px;
        }

        #image-update-container h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
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
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    border-radius: 20px;
    padding: 30px;
    width: 500px;
    max-width: 90%;
    box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.image-modal-content h2 {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #00bfff;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.dynamic-container {
    background-color: rgba(245, 245, 245, 0.95); /* Fondo claro */
    padding: 20px; /* Espaciado interno */
    margin: 15px auto; /* Espaciado externo, centrado horizontal */
    border-radius: 12px; /* Bordes redondeados */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Sombra sutil */
    width: auto; /* Ajuste dinámico según el contenido */
    max-width: 85%; /* Limitar tamaño máximo */
    transition: all 0.3s ease-in-out; /* Transiciones suaves */
    overflow: hidden; /* Evitar desbordamiento */
}

.dynamic-container h2 {
    font-size: 26px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #333; /* Color de texto */
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

.dynamic-container .row {
    display: flex;
    flex-wrap: wrap; /* Ajuste dinámico en varias filas */
    gap: 20px; /* Espaciado entre los elementos */
    justify-content: center; /* Centrar el contenido */
}

.dynamic-container .col-md-4 {
    flex: 1 1 calc(30% - 20px); /* Ajustar a un tercio del espacio disponible */
    max-width: 300px; /* Limitar ancho máximo */
    text-align: center; /* Centrar texto */
}

.dynamic-container .col-md-4 img {
    width: 100%; /* Ajustar al contenedor */
    border-radius: 10px; /* Bordes redondeados */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); /* Sombra */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Efecto de hover */
}

.dynamic-container .col-md-4 img:hover {
    transform: scale(1.05); /* Ampliar ligeramente */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Intensificar sombra */
}

.dynamic-container .col-md-4 p {
    margin-top: 10px;
    font-size: 14px;
    color: #555; /* Color de texto gris */
}

.dynamic-container .button-container {
    text-align: center;
    margin-top: 20px;
}

.dynamic-container .button-container button {
    background-color: #0044cc; /* Azul */
    color: white; /* Texto blanco */
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.dynamic-container .button-container button:hover {
    background-color: #0033a1; /* Azul oscuro */
    transform: scale(1.05); /* Ampliar ligeramente */
}

label {
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 10px 20px; /* Más ancho */
    cursor: pointer;
    user-select: none;
    border-radius: 10px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    color: black;
    transition: background-color 0.3s ease; /* Suavizar el fondo */
    width: 200px; /* Ancho fijo para mejor visibilidad */
    height: 50px; /* Altura consistente */
}
input {
    display: none;
}
input[type="checkbox"] {
    display: none; /* Ocultar el checkbox */
}

label svg {
    transition: transform 0.3s ease, stroke 0.3s ease;
}
input:checked + label svg {
    transform: rotate(360deg); /* Animación de giro */
    stroke:rgb(0, 0, 0); /* Cambio de color del ícono */
}
input:checked + label {
    background-color: white; /* No cambia el fondo completo */
}
@keyframes gearButton {
    0% { transform: rotate(0); }
    50% { transform: rotate(180deg); }
    100% { transform: rotate(360deg); }
}

input + label .action {
    position: relative;
    overflow: hidden;
    display: grid;
}

input + label .action span {
    grid-column-start: 1;
    grid-column-end: 1;
    grid-row-start: 1;
    grid-row-end: 1;
    transition: all .5s;
}

input + label .action span.option-1 {
    transform: translate(0px,0%);
    opacity: 1;
}

input:checked + label .action span.option-1 {
    transform: translate(0px,-100%);
    opacity: 0;
}

input + label .action span.option-2 {
    transform: translate(0px,100%);
    opacity: 0;
}

input:checked + label .action span.option-2 {
    transform: translate(0px,0%);
    opacity: 1;
}

.form-label {
            font-weight: bold;
            color: #555;
        }


        .form-control,
        .form-select {
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 10px;
        }
.image-modal .form-label {
    font-weight: bold;
    color: #555;
    font-size: 16px;
}

.image-modal .form-control, 
.image-modal .form-select {
    padding: 12px;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 10px;
    transition: all 0.3s ease-in-out;
}
.image-modal .form-control:focus, 
.image-modal .form-select:focus {
    border-color: #0044cc;
    box-shadow: 0 0 8px rgba(0, 68, 204, 0.4);
    outline: none;
}
.btn-primary {
            background-color: #0044cc;
            border-color: #0044cc;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #0033a1;
        }
.image-modal .btn-primary {
    background-color: #0044cc;
    border-color: #0044cc;
    padding: 12px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%;
    transition: all 0.3s ease-in-out;
}

.image-modal .btn-primary:hover {
    background-color: #0033a1;
    border-color: #0033a1;
    transform: translateY(-2px);
}

.image-modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #888;
    transition: color 0.3s ease-in-out;
}



.image-modal-close:hover {
    color: #cc0000;
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

        
        .col-md-4 img {
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.col-md-4 img:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
}

.col-md-4 h3 {
    font-size: 20px;
    font-weight: bold;
    margin-top: 10px;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
}

.container h2.section-title {
    font-size: 26px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #0044cc;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
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
        <div class="text-center">
                <i class="fa-solid fa-user-shield fa-2x"></i>
                <h6 class="mt-2"><?php echo $user_role === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?></h6>
            </div>
        <img src="/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            <li><a href="../Login/Administracion.php"><i class="fa-solid fa-cogs"></i> <span>Administración</span></a></li>
            <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
            
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
   
    <!-- Botón para mostrar/ocultar la sección Administrar Imágenes -->
    <h2 class="section-title">Administrar Imágenes</h2>
    <input type="checkbox" id="toggle-administrar-imagenes" />
    <label for="toggle-administrar-imagenes">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2h-1.2a2 2 0 0 1-2-2v-.2a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2v-1.2a2 2 0 0 1 2-2h.2a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06c.31.31.7.48 1.13.53a1.65 1.65 0 0 0 1.82-.33V3a2 2 0 0 1 2-2h1.2a2 2 0 0 1 2 2v.2c.3.12.58.31.82.55s.43.52.55.82h.2a2 2 0 0 1 2 2v1.2a2 2 0 0 1-2 2h-.2a1.65 1.65 0 0 0-1.51 1z"></path>
        </svg>
        <div class="action">
            <span class="option-1">Mostrar</span>
            <span class="option-2">Ocultar</span>
        </div>
    </label>

    <!-- Contenedor Principal -->
    <div id="administrar-imagenes-container" style="display: none;">
        
        <!-- Sección de Votos -->
        <h3 class="section-subtitle">Votos</h3>
        <button class="toggle-button" onclick="toggleContainer('votos-container')">Mostrar/Ocultar Votos</button>
        <div id="votos-container" style="display: none;">
            <div class="row">
                <?php foreach (array_slice($imagenesActuales, 0, 3) as $posicion => $ruta): ?>
                    <div class="col-md-4 text-center">
                        <h4>
                            <?php 
                                if ($posicion == 2) {
                                    echo "Fondo";
                                } else {
                                    echo "Candidato " . ($posicion + 1);
                                }
                            ?>
                        </h4>
                        <img src="<?php echo htmlspecialchars($ruta); ?>" class="img-fluid mb-3" alt="Imagen Posición <?php echo $posicion + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sección de Resultados -->
        <h3 class="section-subtitle">Resultados</h3>
        <button class="toggle-button" onclick="toggleContainer('resultados-container')">Mostrar/Ocultar Resultados</button>
        <div id="resultados-container" style="display: none;">
            <div class="row">
                <?php foreach (array_slice($imagenesActuales, 3, 3) as $posicion => $ruta): ?>
                    <div class="col-md-4 text-center">
                        <h4>
                            <?php 
                                if ($posicion == 2) {
                                    echo "Fondo";
                                } else {
                                    echo "Candidato " . ($posicion + 1);
                                }
                            ?>
                        </h4>
                        <img src="<?php echo htmlspecialchars($ruta); ?>" class="img-fluid mb-3" alt="Imagen Posición <?php echo $posicion + 4; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>

document.getElementById('toggle-administrar-imagenes').addEventListener('change', function () {
    const container = document.getElementById('administrar-imagenes-container');
    container.style.display = this.checked ? 'block' : 'none';
});

    // Función para alternar la visibilidad de un contenedor
    function toggleContainer(containerId) {
    const container = document.getElementById(containerId);
    if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}


</script>

        



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