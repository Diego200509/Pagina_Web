<?php
// Incluir el archivo de consultas
include('../src/sugerencias_queries.php');
include('../config/config.php');

$nombrePartido1 = obtenerNombrePartido(1);
$nombrePartido2 = obtenerNombrePartido(2);

if (!$nombrePartido1) {
    $nombrePartido1 = "Partido no encontrado";
}

if (!$nombrePartido2) {
    $nombrePartido2 = "Partido no encontrado";
}

$votosPorPartido = obtenerVotosPorPartido();

if (isset($_GET['mensaje'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['mensaje']) . "');</script>";
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Votación</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('Img/voto.JPG');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
            animation: slideIn 0.5s ease;
            margin-top: 100px;
            /* Mover el formulario más abajo */
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            color: #b22222;
            /* Cambiado al color del header */
            margin-bottom: 20px;
        }

        .formulario {
            margin-bottom: 20px;
            text-align: center;
        }

        .formulario input {
            padding: 10px;
            margin: 5px 0;
            border: 2px solid #b22222;
            /* Cambiado al color del header */
            border-radius: 5px;
            width: calc(100% - 20px);
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .formulario input:focus {
            border-color: #7a1b1b;
            /* Un rojo más oscuro para el enfoque */
        }

        .candidatos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .candidato {
            border: 2px solid #b22222;
            /* Cambiado al color del header */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(178, 34, 34, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .candidato:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(178, 34, 34, 0.4);
        }

        .candidato img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            transition: transform 0.3s;
            border-bottom: 2px solid #b22222;
            /* Cambiado al color del header */
        }

        .candidato img:hover {
            transform: scale(1.05);
        }

        .candidato div {
            padding: 15px;
            text-align: center;
            flex-grow: 1;
        }

        .candidato h2 {
            margin: 10px 0;
            color: #343a40;
        }

        .botones {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button {
            padding: 10px 15px;
            border: 2px solid #b22222;
            /* Cambiado al color del header */
            border-radius: 5px;
            cursor: pointer;
            background-color: #b22222;
            /* Fondo rojo similar al header */
            color: white;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
            flex: 1;
            margin: 0 5px;
        }

        button:hover {
            background-color: #d62828;
            /* Rojo más oscuro al pasar el mouse */
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(1px);
        }

        .votos-section {
            margin-top: 30px;
            display: none;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .voto-candidato {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border: 2px solid #b22222;
            /* Cambiado al color del header */
            border-radius: 10px;
            overflow: hidden;
            padding: 10px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .voto-candidato img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid #b22222;
            /* Cambiado al color del header */
        }

        .voto-candidato div {
            flex-grow: 1;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background-color: #b22222;
            width: 100%;
            box-sizing: border-box;
            margin: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        header.hidden {
            transform: translateY(-100%);
        }

        header:not(.hidden) {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            width: 50px;
            margin-right: 10px;
        }

        header .logo h1 {
            color: #ffffff;
            font-size: 1.5em;
        }

        header nav {
            display: flex;
            align-items: center;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1em;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        header nav a i {
            margin-right: 8px;
        }

        header nav a:hover {
            color: #2f2929;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeInModal 0.3s ease;
            max-width: 500px;
            width: 80%;
        }

        .modal-content p {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <header id="main-header">
        <div class="logo">
            <img src="Img\logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <!-- Modal -->
        <div id="modalAviso" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="modalTexto"></p>
            </div>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/Candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y
                Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
            <a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a> <!-- Nuevo campo -->

        </nav>
    </header>

    <div class="container">
        <h1>SELECCIONAR CANDIDATO PARA EL VOTO</h1>

        <form action="votos.php" method="POST" onsubmit="return validarFormulario();">
            <div class="formulario">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
            </div>
            <div class="candidatos">
                <div class="candidato">
                    <img src="Img/BANNERVOTOMARI.jpg" alt="Candidato 1">
                    <div>
                        <h2><?php echo htmlspecialchars($nombrePartido1); ?></h2>
                        <label>
                            <input type="radio" name="candidato" value="1"> Seleccionar
                        </label>
                    </div>
                </div>
                <div class="candidato">
                    <img src="Img/BANNERVOTOSARA.jpg" alt="Candidato 2">
                    <div>
                        <h2><?php echo htmlspecialchars($nombrePartido2); ?></h2>
                        <label>
                            <input type="radio" name="candidato" value="2"> Seleccionar
                        </label>
                    </div>
                </div>
            </div>
            <div class="botones">
                <button type="button" onclick="location.href='index.php'">Regresar</button>
                <button type="submit">Votar</button>
            </div>
        </form>

        <div class="votos-section" id="votosSection">
            <h2>Resultados de Votos</h2>
            <div class="voto-candidato">
                <img src="Img/BANNERVOTOMARI.jpg" alt="Candidato 1">
                <div>
                    <h3><?php echo htmlspecialchars($nombrePartido1); ?></h3>
                    <p>Cantidad de votos:
                        <strong><?php echo isset($votosPorPartido[1]) ? $votosPorPartido[1] : 0; ?></strong></p>
                </div>
            </div>
            <div class="voto-candidato">
                <img src="Img/BANNERVOTOSARA.jpg" alt="Candidato 2">
                <div>
                    <h3><?php echo htmlspecialchars($nombrePartido2); ?></h3>
                    <p>Cantidad de votos:
                        <strong><?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : 0; ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>

        function mostrarModal(mensaje) {
            const modal = document.getElementById('modalAviso');
            const modalTexto = document.getElementById('modalTexto');
            const cerrar = document.getElementsByClassName('close')[0];

            // Mostrar mensaje
            modalTexto.innerText = mensaje;
            modal.style.display = "flex";

            // Cerrar modal
            cerrar.onclick = function () {
                modal.style.display = "none";
            }

            // Cerrar modal al hacer clic fuera de él
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
        function validarFormulario() {
            const nombre = document.getElementById('nombre').value;
            const correo = document.getElementById('correo').value;
            const candidatoSeleccionado = document.querySelector('input[name="candidato"]:checked');

            if (!nombre || !correo || !candidatoSeleccionado) {
                alert('Por favor, complete todos los campos antes de votar.');
                return false;
            } else {
                alert(`Gracias por votar, ${nombre}!`);
                return true;
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const mensaje = urlParams.get('mensaje');
            if (mensaje) {
                mostrarModal(mensaje);
            }
        });
    </script>
</body>

</html>