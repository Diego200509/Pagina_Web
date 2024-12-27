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
}


    

if (isset($_POST['candidato']) && !empty($_POST['candidato'])) {
    $candidato = (int) $_POST['candidato'];
    // Registrar el voto en sugerencias_queries.php
    registrarVoto($candidato); // Llamada a función centralizada
}

if (isset($_COOKIE['ya_voto'])) {
    header("Location: resultados.php");
    exit;
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
        }
    </style>
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
            gap: 31px;
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
            width: 110%;
            height: 174px;
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


/* Navbar */
.navbar {
    width: 100%;
    position: fixed;
    top: 0;
    z-index: 1000;
    background-color: var(--navbar-bg-color, #00bfff);
    display: flex;
    align-items: center;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    gap: 20px; /* Espacio entre logo y menú */
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    flex-shrink: 0; /* Mantener el tamaño fijo del logo */
}

.navbar-logo i {
    font-size: 24px;
}

.navbar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px; /* Espacio entre elementos del menú */
    flex-grow: 1; /* Ocupa todo el espacio disponible */
    justify-content: flex-end; /* Alinear los botones a la derecha */
    padding-right: 20px; /* Asegura espacio entre el último botón y el borde derecho */
}

.navbar-menu li {
    list-style: none;
}

.navbar-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
}

.navbar-menu li a:hover {
    color: #ff0050;
}

        footer {
            text-align: center;
            padding: 20px;
            background-color: #b22222;
            color: white;
            margin-top: 50px;
        }

        .footer-rights {
            background-color: #b22222;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            position: fixed; /* Lo fija en la parte inferior */
            bottom: 0;
            left: 0;
            z-index: 10; /* Asegura que quede sobre otros elementos */
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
            text-align: center;
            animation: fadeIn 0.3s ease;
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




    <div class="container">
    <h1>Seleccionar candidato para el voto</h1>

        <form action="../src/sugerencias_queries.php" method="POST" onsubmit="return validarFormulario();">
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
    const candidatoSeleccionado = document.querySelector('input[name="candidato"]:checked');
    if (!candidatoSeleccionado) {
        alert('Por favor, selecciona un candidato antes de votar.');
        return false;
    }
    return true;
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