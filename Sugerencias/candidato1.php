<?php
// Incluir el archivo de consultas
include_once('../src/partido1_sugerencias_queries.php');

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    height: calc(100vh - 50px); /* Ajusta la altura para que deje espacio */
    margin-top: 50px; /* Desplaza la tarjeta hacia abajo */
    color: #FFF;
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    max-width: 100vw;
    overflow: hidden;
}

.card {
    display: flex;
    background-color: #F7F7F7;
    width: 70%;
    max-width: 4000px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card img {
    width: 10%;
    height: auto;
    object-fit: cover;
}

.content {
    padding: 40px;
    flex: 1;
}

.content h1 {
    color: #2B4657;
    font-size: 2em;
    margin-bottom: 20px;
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
    justify-content: space-between; /* Distribuye los botones con espacio entre ellos */
    margin-top: 20px;
    gap: 20px; /* Espaciado entre los botones */
    width: 100%; /* Asegura que los botones ocupen el 100% del ancho disponible */
    box-sizing: border-box; /* Para asegurarse de que el padding no afecte el tamaño total */
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
    .buttons {
        flex-direction: column; /* Cambia los botones a una columna en pantallas pequeñas */
        align-items: center;
    }

    .buttons button, .buttons a {
        width: 100%;  /* Los botones ocuparán el 100% del ancho disponible */
        margin-bottom: 10px; /* Espaciado entre botones */
    }
}

.content h1 {
    text-align: center;
}

.input-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.input-group label {
    margin-right: 10px;
    flex-basis: 30%;
}

.input-group input {
    flex-basis: 65%;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #CCC;
    border-radius: 5px;
}


.container { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { background-color: #F7F7F7; width: 60%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 10px; overflow: hidden; }
        .card img { width: 100%; object-fit: cover; }
        .content { padding: 20px; }
        .form-section { margin-top: 20px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 10px; font-size: 1.2em; color: #2B4657; }
        .input-group input, .input-group textarea {
            width: 100%; padding: 10px; font-size: 1em; border: 1px solid #CCC; border-radius: 5px;
        }
        .buttons { text-align: center; margin-top: 20px; }
        .buttons button {
            padding: 12px 20px; font-size: 1.1em; text-align: center; border-radius: 8px; cursor: pointer;
            background-color: #6cace4; color: white; border: none; transition: background-color 0.3s ease;
        }
        .buttons button:hover { background-color: #56a5d7; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/Candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
            <a href="../Sugerencias/votos.php"><i class="fas fa-vote-yea"></i> Votos</a><!-- Nuevo campo -->

        </nav>
    </header>

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