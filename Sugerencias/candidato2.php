<?php
// Incluir el archivo de consultas
$eventos_noticias = include('../src/partido2_sugerencias_queries.php');
include('../Config/config.php');


$nombrePartido = obtenerNombrePartido(2);


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
    width: 60%;
    max-width: 4000px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card img {
    width: 50%;
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


    </style>
</head>
<body>
    <header id="main-header">
        <div class="logo">
        <img src="/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">
        <h1>Proceso de Elecciones UTA 2024</h1>
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
        <div class="card">
            <img src="Img/CANDIDATA2.jpg" alt="Imagen de un estudiante">
            <div class="content">
            <div class="form-section">
            <div class="form-section">
            <h1 style="text-align: center;"><span style="color: blue; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;"><?php echo htmlspecialchars($nombrePartido); ?></span></h1>
        <form method="POST" action="candidato2.php"> 
    <div class="input-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
    </div>
    <div class="input-group">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
        </div>

        <label for="sugerencias">Sugerencias:</label>
        <textarea id="sugerencias" name="sugerencias" placeholder="Escribe tus sugerencias aquí..." required></textarea>
        
        <label for="propuestas">Propuestas:</label>
        <textarea id="propuestas" name="propuestas" placeholder="Escribe tus propuestas aquí..." required></textarea>
        
        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios" placeholder="Escribe tus comentarios aquí..." required></textarea>
        
        <input type="hidden" name="id_partido" value="2"> <!-- Aquí se define el ID del partido -->
        
        <div class="buttons">
        <a href="index.php" class="btn-regresar">Regresar</a>

            <button type="submit" class="btn1-enviar">Enviar Sugerencias</button>
        </div>
    </form>
</div>

</div>
            </div>
        </div>
    </div>



<div class="footer-rights">
Derechos reservados UTA 2024-2025
</div>

</body>
</html>