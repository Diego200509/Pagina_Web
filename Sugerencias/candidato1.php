<?php
// Incluir el archivo de consultas
include_once('../config/config.php');
include_once('../src/sugerencias_queries.php'); // Cambia esta línea si la ruta es diferente


$imagenConfiguracion = obtenerImagenConfiguracion();

if ($imagenConfiguracion && file_exists($_SERVER['DOCUMENT_ROOT'] . $imagenConfiguracion)) {
    $imagenRuta = $imagenConfiguracion;
} else {
    $imagenRuta = '../Sugerencias/Img/default.jpg'; // Ruta completa para la imagen por defecto
}




$navbarConfigPath = "../Login/navbar_config.json"; // Ruta al archivo de configuración del Navbar

// Verificar si el archivo existe y cargar el color del Navbar
if (file_exists($navbarConfigPath)) {
    $navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
    $navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff'; // Azul por defecto
} else {
    $navbarBgColor = '#00bfff'; // Azul por defecto si no existe el archivo
}

// Obtener la ruta de la imagen para la sección 'logoNavbar'
$section_name = 'logoNavbar';
$stmt = $connection->prepare("SELECT image_path FROM imagenes_Inicio_Logo WHERE section_name = ?");
$stmt->bind_param("s", $section_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logo_path = $row['image_path'];
} else {
    $logo_path = "/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png"; // Imagen por defecto
}


$configFileSugerencias = "../Login/PaginaSugerencias.json";
if (file_exists($configFileSugerencias)) {
    $config = json_decode(file_get_contents($configFileSugerencias), true);
    $paginaSugerenciasBgColor = $config['paginaSugerenciasBgColor'] ?? "#a1c4fd";
} else {
    $paginaSugerenciasBgColor = "#a1c4fd";
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
        --pagina-bg-color: <?php echo $paginaSugerenciasBgColor; ?>;
    }

    body {
        background-color: var(--pagina-bg-color);
        background-size: 400% 400%;
        animation: animatedBackground 12s ease infinite; /* Animación suave */
        overflow-x: hidden;
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
  justify-content: center;
  align-items: center;
  height: 100vh;
  perspective: 1000px; /* Perspectiva para efecto 3D */
}


.card {
  width: 17em;
  height: 22.5em;
  background: #171717;
  transition: 1s ease-in-out;
  clip-path: polygon(30px 0%, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%, 0% 30px);
  border-top-right-radius: 20px;
  border-bottom-left-radius: 20px;
  display: flex;
  flex-direction: column;
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
    background-color: var(--navbar-bg-color, #00bfff);
    color: white;
    margin-top: 50px;
}

.footer-rights {
    background-color: var(--navbar-bg-color, #00bfff);
    color: white; 
    text-align: center;
    padding: 10px;
    position: relative;
    bottom: 0;
    width: 100%;
    margin-top: 0px; 
}


/* Contenedor del formulario */
.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  padding: 20px;
}

/* Estilo general del formulario */
.form {
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 400px;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.8s ease-in-out; /* Animación más suave */

}

/* Frente del formulario */
.form .form_front {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  position: absolute;
  backface-visibility: hidden;
  padding: 30px;
  border-radius: 15px;
  background-color: #fff; /* Fondo blanco */
  box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 0.1), /* Sombra sutil */
              inset -1px -1px 5px rgba(255, 255, 255, 0.6);
}

/* Parte trasera del formulario */
.form .form_back {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  position: absolute;
  backface-visibility: hidden;
  transform: rotateY(180deg);
  padding: 65px 45px;
  border-radius: 15px;
  background-color: #fff; /* Fondo blanco */
  box-shadow: inset 2px 2px 10px rgba(0, 0, 0, 0.1),
              inset -1px -1px 5px rgba(255, 255, 255, 0.6);
}

/* Títulos del formulario */
.form_details {
  font-size: 25px;
  font-weight: 600;
  padding-bottom: 10px;
  color: #2B4657; /* Texto gris oscuro */
}

/* Campos de entrada */
.input {
    width: 245px;
  min-height: 45px;
  color: #333; /* Color oscuro */
  outline: none;
  transition: 0.35s ease-in-out; /* Transición suave */
  padding: 5px 10px;
  background-color: #f5f5f5; /* Fondo claro */
  border-radius: 6px;
  border: 2px solid #ddd; /* Borde gris claro */
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  inset 1px 1px 4px rgba(0, 0, 0, 0.15); /* Sombras suaves */

}

.input-container input{
  margin-bottom: 20px;
}

.input-container label {
  display: block;
  margin-bottom: 8px;
  font-size: 16px;
  color: #2B4657;
  font-weight: bold;
}
/* Placeholder de los campos de entrada */
.input::placeholder {
  color: #999; /* Placeholder gris claro */
}

/* Placeholder cuando el campo está enfocado */
.input:focus.input::placeholder {
  transition: 0.3s;
  opacity: 0;
}

/* Efecto al enfocar un campo */
.input:focus {
    transform: scale(1.05);
  box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.1),
    1px 1px 10px rgba(255, 255, 255, 0.6),
    inset 2px 2px 10px rgba(0, 0, 0, 0.1),
    inset -1px -1px 5px rgba(255, 255, 255, 0.6);
}

.button {
    display: flex; /* Usamos flexbox para centrar */
    justify-content: center; /* Centrar horizontalmente */
    align-items: center; /* Centrar verticalmente si es necesario */
    margin-top: 20px; /* Espaciado superior */
}

/* Estilo del botón "Enviar Sugerencia" */
button {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;
    background-color: var(--navbar-bg-color, #00bfff);
    font-family: "Montserrat", sans-serif;
    box-shadow: 0px 6px 24px 0px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    cursor: pointer;
    border: none;
    padding: 0px 0px; /* Tamaño más pequeño */
    font-size: 1em; /* Ajustar el tamaño del texto */
}

button:after {
    content: " ";
    width: 0%;
    height: 100%;
    background: #ff1493;
    position: absolute;
  transition: all 0.4s ease-in-out;
  right: 0;
  z-index: 1; /* Asegura que el fondo esté detrás del texto */

}

button:hover::after {
    right: auto;
    left: 0;
    width: 100%;
}

button span {
    text-align: center;
    text-decoration: none;
    width: 100%;
    padding: 18px 25px;
    color: #fff;
    font-size: 0.8em; /* Asegura tamaño adecuado para el texto */
    font-weight: 700;
    letter-spacing: 0.3em;
    z-index: 2;
    transition: all 0.3s ease-in-out;
}

button:hover span {
    color: #fff;
    animation: scaleUp 0.3s ease-in-out;
}


@keyframes scaleUp {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(0.95);
    }

    100% {
        transform: scale(1);
    }
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
.form .switch {
  font-size: 13px;
  color:rgb(10, 15, 19);
}

.form .switch .signup_tog {
  font-weight: 700;
  cursor: pointer;
  text-decoration: underline;
}

.container #signup_toggle {
  display: none;
}

.container #signup_toggle:checked + .form {
  transform: rotateY(180deg);
}
.content {
    flex: 1; /* El formulario ocupa el espacio restante */
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.content h1 {
  text-align: center;
  color: #2b4657;
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: bold;
}


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
<img src="<?php echo htmlspecialchars($logo_path); ?>"  width="200px" style="margin-right: 20px;">

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
<div class="form-container">
    <div class="card">
        <!-- Imagen en el lado izquierdo -->
        <div class="card img">
        <img src="<?php echo htmlspecialchars($imagenRuta); ?>" alt="Imagen configurada">
        </div>
        <div class="content">
            <h1 style="text-align: center; color: #2B4657;"><?php echo htmlspecialchars($nombrePartido); ?></h1>
            <form id="suggestionForm"class="form">
                <div class="input-container">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="input"placeholder="Nombre de usuario" required>
                </div>
                <div class="input-container">
                    <label for="email">Correo electrónico (opcional):</label>
                    <input type="email" id="email" name="email" class="input"placeholder="Correo electrónico">
                </div>
                <div class="input-container">
                    <label for="sugerencias">Sugerencia:</label>
                    <textarea id="sugerencias" name="sugerencias" class="input"placeholder="Escribe tu sugerencia aquí..." required></textarea>
                </div>
                <input type="hidden" name="id_partido" value="1">
                <div class="button">
                <button type="submit"><span>Enviar Sugerencia</span></button>
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


<footer class="footer-rights">
    <p>Todos los derechos reservados Team Sangre © 2024</p>
</footer>
</html>