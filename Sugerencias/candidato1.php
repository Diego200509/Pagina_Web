<?php
// Incluir el archivo de consultas
include_once('../src/partido1_sugerencias_queries.php');
include('../config/config.php');


$navbarConfigPath = "../Login/navbar_config.json"; // Ruta al archivo de configuración del Navbar

// Verificar si el archivo existe y cargar el color del Navbar
if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; // Azul por defecto
} else {
    $navbarBgColor = '#00bfff'; // Azul por defecto si no existe el archivo
}


$eventos_noticias = include('../src/partido1_sugerencias_queries.php');
include('../Config/config.php');


$nombrePartido = obtenerNombrePartido(1);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
        }
    </style>
    <title>Sobre nuestros estudiantes</title>
    <style>
        
@keyframes animatedBackground {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
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

/* Mantener los demás estilos intactos */
input[type="email"] {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #CCC;
    border-radius: 5px;
    margin-bottom: 20px;
}

textarea {
    width: 100%;
}

.container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    padding: 20px;
}

.card {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    width: 100%;
    height: auto;
    background-color: #F7F7F7;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

.card .img {
    flex: 0 0 40%; /* Ajusta el ancho de la tarjeta de imagen al 30% */
    max-width: 100%; /* Garantiza que no exceda el 30% del contenedor */
    height: auto;
}
.card img {
    width: 100%; /* Reducir el espacio ocupado por la imagen */
    object-fit: cover;
    height: 100%;

}    


.content {
    flex: 1; /* El formulario ocupa el espacio restante */
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.content h1 {
    color: #2B4657;
    font-size: 1.5em; /* Ajustar el tamaño del título */
    margin-bottom: 15px;
    text-align: center;
}

.content p {
    color: #7A7A7A;
    line-height: 1.6;
}

.content a {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px;
    background-color: #2B4657;
    color: #FFF;
    text-decoration: none;
    border-radius: 5px;
}

.content a:hover {
    background-color: #435A6A;
}

.navbar {
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

textarea {
    resize: none;
    height: 80px; /* Más altura para el textarea */
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
    position: relative;
    bottom: 0;
    width: 100%;
    margin-top: 0px;
}

.form-section {
    margin-top: 20px;
}

.form-section label {
    display: block;
    margin-bottom: 10px;
    font-size: 1.2em;
    color: #2B4657;
}

.form-section textarea {
    width: 100%;
    height: 80px;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #CCC;
    border-radius: 5px;
    margin-bottom: 20px;
    resize: none;
}

.buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 15px;
}
/* Estilo común para ambos botones */
.buttons button, .buttons a {
    padding: 12px 20px;
    font-size: 1.1em;
    text-align: center;
    border-radius: 8px;  /* Bordes redondeados */
    cursor: pointer;
    transition: all 0.3s ease;  /* Transición suave para el hover */
    width: 48%;  /* Hace que ambos botones tengan el mismo tamaño */
    box-sizing: border-box; /* Para evitar que el padding afecte el ancho total */
}

/* Estilo específico para el botón de "Enviar Sugerencias" */
.btn1-enviar {
    background-color: #6cace4; /* Azul suave */
    color: white;
    border: none;
}

.btn1-enviar:hover {
    background-color: #56a5d7; /* Azul más oscuro al pasar el mouse */
}

/* Estilo específico para el botón de "Regresar" */
.btn-regresar {
    background-color: #dcdcdc; /* Gris suave */
    color: #2B4657; /* Texto oscuro para mayor contraste */
    border: none;
}

.btn-regresar:hover {
    background-color: #b0b0b0; /* Gris más oscuro al pasar el mouse */
}

/* Asegura que los botones se agranden ligeramente al pasar el mouse */
.buttons a:hover, .buttons button:hover {
    transform: scale(1.05);  /* Efecto de agrandamiento sutil */
}

/* Asegura que los botones se vean bien en dispositivos pequeños */
@media (max-width: 768px) {
    .card {
        flex-direction: column; /* Cambiar a diseño vertical en pantallas pequeñas */
        max-width: 90%; /* Usar casi todo el ancho disponible */
    }

    .card img {
        width: 100%; /* Imagen ocupa todo el ancho */
    }

    .content {
        padding: 20px;
    }
}

.content h1 {
    text-align: center;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    font-size: 1em;
    color: #555;
    margin-bottom: 5px;
    display: block; /* Asegurar que el label esté sobre el campo */
}

.input-group input, .input-group textarea {
    width: 100%;
    padding: 8px;
    font-size: 0.9em;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

.container { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { background-color: #F7F7F7; width: 60%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 10px; overflow: hidden; 
            height: auto;
            align-items: stretch;
            flex-direction: row;
            display: flex;
        }
        .card .img {
    flex: 0 0 40%; /* Ajusta el ancho de la tarjeta de imagen al 30% */
    max-width: 40%; /* Garantiza que no exceda el 30% del contenedor */
    height: auto;
}
        .card img {
    flex-shrink: 0; /* Evita que la imagen se reduzca demasiado */
    width: 100%; /* Ajusta el ancho de la imagen al 50% */
    object-fit: cover; /* Recorta la imagen para ajustarse al contenedor */
    height: auto; /* Mantén las proporciones */
}  
.content {
    flex: 1; /* El formulario ocupa el espacio restante */
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

        .form-section { margin-top: 90px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 10px; font-size: 1.2em; color: #2B4657; }
        .input-group input, .input-group textarea {
            width: 100%; padding: 10px; font-size: 1em; border: 1px solid #CCC; border-radius: 5px;
        }
        .buttons { text-align: center; margin-top: 20px; }
        .buttons button {
            padding: 10px 15px; font-size: 0.9em; text-align: center; border-radius: 5px; cursor: pointer;
            background-color: #6cace4; color: white; border: none; transition: background-color 0.3s ease;
        }
        .buttons button:hover { background-color: #56a5d7; }
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
<img src="Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">

</div>



    </div>
    <ul class="navbar-menu"> 
    <li><a href="../Home/inicio.php"><i class="fa-solid fa-house"></i> <span>Inicio</span></a></li>
        <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
        <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
        <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
        <li><a href="../Sugerencias/index.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
        <li><a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
    </ul>
</nav>


</navbar>
    <div class="container">
    <div class="card">
        <!-- Imagen en el lado izquierdo -->
        <div class="card img">
            <img src="Img/mari2.jpg" alt="Imagen de Mary Cruz">
        </div>
        <div class="content">
            <h1 style="text-align: center; color: #2B4657;"><?php echo htmlspecialchars($nombrePartido); ?></h1>
            <form id="suggestionForm">
                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
                </div>
                <div class="input-group">
                    <label for="email">Correo electrónico (opcional):</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico">
                </div>
                <div class="input-group">
                    <label for="sugerencias">Sugerencia:</label>
                    <textarea id="sugerencias" name="sugerencias" placeholder="Escribe tu sugerencia aquí..." required></textarea>
                </div>
                <input type="hidden" name="id_partido" value="1">
                <div class="buttons">
                    <button type="submit">Enviar Sugerencia</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    $('#suggestionForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../src/partido1_sugerencias_queries.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Sugerencia Enviada!',
                        text: response.message,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#388e3c',
                        backdrop: true
                    });
                    $('#suggestionForm')[0].reset(); // Limpiar formulario tras el éxito
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#d33',
                        backdrop: true
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al procesar la solicitud. Intenta nuevamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#d33',
                    backdrop: true
                });
            }
        });
    });
});
</script>

</body>
</html>