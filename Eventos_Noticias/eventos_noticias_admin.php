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
    $logo_path = "/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png"; // Imagen por defecto
}


$configFileEventos = "../Login/PaginaEventos.json";

if (file_exists($configFileEventos)) {
    $config = json_decode(file_get_contents($configFileEventos), true);
    $paginaEventosBgColor = $config['paginaEventosBgColor'] ?? "#f4f4f4";
} else {
    $paginaEventosBgColor = "#f4f4f4";
    
}

session_start();

// Verificar si el usuario tiene sesión iniciada y rol asignado
if (!isset($_SESSION['user_role'])) {
    header("Location: ../Login/Login.php");
    exit;
}


// Obtener el rol del usuario
$user_role = $_SESSION['user_role'];

// Determinar la URL del dashboard según el rol del usuario
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/superadmin_dasboard.php' : '../Login/admin_dashboard.php';

include '../src/eventos_noticias_admin_queries.php';

// Ruta base para las imágenes
$rutaBaseImagenes = "/Pagina_Web/Pagina_Web/Eventos_Noticias/img/";

// Obtener todos los eventos y noticias
$totalRegistros = obtenerTotalEventosNoticias();
$registrosPorPagina = 6;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$paginaActual = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Obtener los registros para la página actual
$eventosNoticias = obtenerEventosNoticias($offset, $registrosPorPagina);

// Manejar creación y edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $ubicacion = !empty($_POST['ubicacion']) ? $_POST['ubicacion'] : null;
    $partido = $_POST['partido'];
    $estado = $_POST['estado'];

    // Verificar si se subió una imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = $_FILES['imagen']['name'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];
        $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . "/Pagina_Web/Pagina_Web/Eventos_Noticias/img/";

        // Mover la imagen al directorio de destino
        if (move_uploaded_file($rutaTemporal, $directorioDestino . $nombreImagen)) {
            $imagenConRuta = "/Pagina_Web/Pagina_Web/Eventos_Noticias/img/" . $nombreImagen;
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo subir la imagen.',
                    icon: 'error'
                });
            </script>";
            exit;
        }
    } else {
        // Si no se sube una nueva imagen, usar la imagen existente (en caso de edición)
        $imagenConRuta = $_POST['imagen_actual'] ?? null;
    }

    if ($id) {
        $resultado = actualizarEventoNoticia($id, $titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagenConRuta);
    } else {
        $resultado = crearEventoNoticia($titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagenConRuta);
    }

    if ($resultado) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Guardado!',
                    text: 'El evento/noticia se guardó correctamente.',
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'eventos_noticias_admin.php'; // Redirige después de cerrar el mensaje
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar el evento/noticia.',
                icon: 'error'
            });
        </script>";
    }
}

// Manejar eliminación
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    eliminarEventoNoticia($id);
    $mensajeEliminacion = "El registro se ha eliminado correctamente."; // Mensaje para la ventana emergente
    echo "<script>window.onload = function() { mostrarNotificacion('$mensajeEliminacion'); }</script>";
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Eventos y Noticias</title>
    <link rel="stylesheet" href="styleEventsAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --pagina-bg-color: <?php echo $paginaEventosBgColor; ?>;
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
            body {
    background-color: var(--pagina-bg-color);
}
    
        }
    </style>
</head>


<body>
    <!-- Navbar -->
<nav class="navbar">
    <div class="navbar-logo">
        <div class="text-center">
            <i class="fa-solid fa-user-shield fa-2x"></i>
            <h6 class="mt-2 navbar-role"><?php echo $user_role === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?></h6>
        </div>
        <img src="<?php echo htmlspecialchars($logo_path); ?>" width="200px" style="margin-right: 20px;">
    </div>

    <ul class="navbar-menu">
        <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
        <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
        <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
        <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
        <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
        <li>
            <a href="<?php echo ($user_role === 'SUPERADMIN') ? '../Login/Administracion.php' : '../Login/Administracion_admin.php'; ?>">
                <i class="fa-solid fa-cogs"></i> <span>Administración</span>
            </a>
        </li>
        <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
    </ul>
</nav>

    <div class="container">

        <h1>Eventos y Noticias</h1>
        <button id="addEventBtn" class="btn" onclick="openModal()">
            <i class="fa fa-plus"></i> Agregar Evento/Noticia
        </button>

        <!-- Modal para el formulario -->
        <div id="eventModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal()">&times;</span>
                <h2 id="modal-title">Agregar Evento/Noticia</h2>
                <form method="POST" enctype="multipart/form-data" id="form-eventos">
                    <input type="hidden" name="id" id="id">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" required>

                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" required></textarea>

                    

                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo" required onchange="habilitarUbicacion()">
                        <option value="EVENTO">Evento</option>
                        <option value="NOTICIA">Noticia</option>
                    </select>

                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" required>

                    <label for="ubicacion">Ubicación:</label>
                    <input type="text" name="ubicacion" id="ubicacion">

                    <label for="partido">Partido:</label>
                    <select name="partido" id="partido" required>
                        <option value="1">Sueña, crea, innova</option>
                    </select>

                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="Oculto">Oculto</option>
                    </select>

                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" id="imagen" accept="image/png, image/jpeg, image/jpg">
                    <input type="hidden" name="imagen_actual" id="imagen_actual">

                    <button type="submit" class="btn btn-danger">Guardar</button>
                </form>
            </div>
        </div>

        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Partido</th>
                        <th>Estado</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventosNoticias as $evento): ?>
                        <tr id="fila-<?php echo $evento['ID_EVT_NOT']; ?>">
                            <td><?php echo $evento['ID_EVT_NOT']; ?></td>
                            <td><?php echo $evento['TIT_EVT_NOT']; ?></td>
                            <td><?php echo $evento['DESC_EVT_NOT']; ?></td>
                            <td><?php echo $evento['FECHA_EVT_NOT']; ?></td>
                            <td><?php echo $evento['TIPO_REG_EVT_NOT']; ?></td>
                            <td><?php echo $evento['UBICACION_EVT_NOT'] ?: 'N/A'; ?></td>
                            <td><?php echo $evento['ID_PAR_EVT_NOT']; ?></td>
                            <td><?php echo $evento['ESTADO_EVT_NOT']; ?></td>
                            <td>
                                <img src="<?php echo $evento['IMAGEN_EVT_NOT']; ?>" alt="Imagen" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarEvento(<?php echo $evento['ID_EVT_NOT']; ?>)">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarEvento(<?php echo $evento['ID_EVT_NOT']; ?>)">Eliminar</button>
                                <button class="btn btn-info btn-sm" onclick="cambiarEstado(<?php echo $evento['ID_EVT_NOT']; ?>, '<?php echo $evento['ESTADO_EVT_NOT']; ?>')">
                                    <?php echo $evento['ESTADO_EVT_NOT'] === 'Activo' ? 'Ocultar' : 'Activar'; ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container text-center">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $paginaActual - 1; ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Ventana emergente -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="toastNotificacion" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <!-- Mensaje dinámico -->
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script src="scriptEventsAdmin.js?v=<?php echo time(); ?>"></script>
</body>

</html>