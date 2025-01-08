<?php
include('../config/config.php');



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
    $logo_path = "../Login/Img/logoMariCruz.png"; // Imagen por defecto
}


function cargarEstilo($archivo, $default) {
    if (file_exists($archivo)) {
        $config = json_decode(file_get_contents($archivo), true);
        return $config['bgColor'] ?? $default;
    }
    return $default;
}

$candidatosColor = cargarEstilo('../Login/candidatos_config.json', '#000000');
$propuestasColor = cargarEstilo('../Login/propuestas_config.json', '#4d0a0a');
$eventosColor = cargarEstilo('../Login/eventos_config.json', '#FF9800');

// Definir rutas por defecto
$slide1_path = "../Home/Img/FONDOMARI.jpg";
$slide5_path = "../Home/Img/FONDOMARI2.jpg";

// Consultar las rutas desde la base de datos
$stmt = $connection->prepare("SELECT section_name, image_path FROM imagenes_Inicio_Logo WHERE section_name IN ('slide1', 'slide5')");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['section_name'] === 'slide1' && file_exists($row['image_path'])) {
        $slide1_path = $row['image_path'];
    } elseif ($row['section_name'] === 'slide5' && file_exists($row['image_path'])) {
        $slide5_path = $row['image_path'];
    }
}




$stmt->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos a Rector</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWM3FAa+UP7B7aXQFJ59Y3RF53p50eA88LvNCwD5zZoOMMDzBtF1UeJ0cEtCU" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Estilo.css">
    <style>
    :root {
        --navbar-bg-color: <?php echo $navbarBgColor; ?>;
    }
    .slide1 {
            background: url('<?php echo $slide1_path; ?>') no-repeat center center/cover;
            height: 300px;
        }

        .slide5 {
            background: url('<?php echo $slide5_path; ?>') no-repeat center center/cover;
            height: 300px;
        }
    #candidatos {
        background-color: <?= $candidatosColor ?>;
    }
    #propuestas-section {
        background-color: <?= $propuestasColor ?>;
    }
    #eventos {
        background-color: <?= $eventosColor ?>;
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
    <img src="<?php echo htmlspecialchars($logo_path); ?>" width="200px" style="margin-right: 20px;">

</div>



        </div>
        <ul class="navbar-menu"> 
            <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/index.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
        </ul>
    </nav>


</navbar>

<section class="slider">
    <div class="fade"></div>
    <div class="slides">
        <div class="slide slide1 active">
            <div class="content">

            </div>
        </div>
        <div class="slide slide5">
            <div class="content">

            </div>
        </div>
    </div>
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
</section>


<section id="candidatos">
<h1 class="propuestas-title-candidatos">Conoce a nuestros Candidatos</h1>
<?php include('../src/candidatos_inicio_queries.php'); ?>
</section>




<section id="propuestas-section">
    <h1 class="propuestas-title">PROPUESTAS</h1>
    <div id="propuestas">
        <?php include('../src/propuestas_favoritas_queries.php'); ?>
    </div>
</section>
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title"></h2>
            <button class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Imagen de la propuesta -->
            <img id="modal-image" src="" alt="Imagen de la propuesta">
            <!-- Descripción de la propuesta -->
            <p id="modal-description"></p>
        </div>
        <div class="modal-category" id="modal-category"></div>
        <div class="modal-footer">
            <img src="../Login/Img/logoMariCruz.png" alt="Logo 1">
            <img src="../Home/Img/logoMano.png" alt="Logo 2">
        </div>
    </div>
</div>






<section id="eventos">
<h1 class="propuestas-title-eventos">Eventos y Noticias</h1>

    <div class="botones-eventos">
        <button class="btn-eventos" id="mostrarEventos">Mostrar Eventos</button>
        <button class="btn-eventos" id="mostrarNoticias">Mostrar Noticias</button>
    </div>
    <div id="contenidoEventosNoticias" class="contenido-eventos">
        <!-- Aquí se mostrarán los eventos o noticias -->
    </div>
</section>



<footer class="footer-rights">
    <p>Todos los derechos reservados Team Sangre © 2024</p>
</footer>


<script src="Scripts.js"></script> <!-- Enlace al archivo JavaScript -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Escuchar cambios en el almacenamiento local
    window.addEventListener("storage", function (event) {
        if (event.key === "navbarColorUpdated" && (event.newValue === "true" || event.newValue === "reset")) {
            // Recargar la página cuando se detecte un cambio o restablecimiento
            window.location.reload();
        }
    });
});

document.getElementById('mostrarFavoritas').addEventListener('click', function () {
    fetch('../src/propuestas_favoritas_queries.php', { method: 'POST' })
        .then(response => response.text())
        .then(data => {
            document.getElementById('contenedorPropuestas').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
});





</script>


<script>
document.getElementById("mostrarEventos").addEventListener("click", function() {
    cargarContenido("EVENTO");
});

document.getElementById("mostrarNoticias").addEventListener("click", function() {
    cargarContenido("NOTICIA");
});

function cargarContenido(tipo) {
    fetch('../src/eventos_noticias_inicio_queries.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tipo=' + tipo
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("contenidoEventosNoticias").innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modal-title');
        const modalDescription = document.getElementById('modal-description');
        const modalCategory = document.getElementById('modal-category');
        const modalImage = document.getElementById('modal-image');
        const closeModalBtn = document.querySelector('.close-btn');

        // Agregar evento a los botones "Ver más"
        document.querySelectorAll('.ver-mas-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Extraer datos del botón
                const title = button.getAttribute('data-title');
                const description = button.getAttribute('data-description');
                const category = button.getAttribute('data-category');
                const imageURL = button.getAttribute('data-image');

                // Pasar datos al modal
                modalTitle.textContent = title;
                modalDescription.textContent = description;
                modalCategory.textContent = `Categoría: ${category}`;
                if (imageURL) {
                    modalImage.src = imageURL;
                    modalImage.style.display = 'block'; // Mostrar la imagen si existe
                } else {
                    modalImage.style.display = 'none'; // Ocultar la imagen si no hay URL
                }

                // Mostrar el modal
                modal.style.display = 'flex';
            });
        });

        // Cerrar el modal al hacer clic en la "X"
        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Cerrar el modal al hacer clic fuera del contenido
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>



</body>
</html>
