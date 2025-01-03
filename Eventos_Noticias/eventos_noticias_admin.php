<?php
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
$eventosNoticias = obtenerEventosNoticias();

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
    <!-- Solo incluir Bootstrap para la ventana emergente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <i class="fa-solid fa-user-shield"></i>
            <img src="../Home/Img/logo.png" width="50px" margin-right="10px">
            <h2>Gestión de eventos y noticia</h2>
        </div>
    </nav>
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="<?php echo $dashboard_url; ?>" class="btn btn-danger btn-lg">
                <i class="bi bi-arrow-left-circle me-2"></i> Regresar
            </a>
        </div>

    <h1>Crear Eventos/Noticias</h1>

    <form method="POST" enctype="multipart/form-data" id="form-eventos">
        <input type="hidden" name="id" id="id">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>

        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required onchange="habilitarUbicacion()">
            <option value="EVENTO">Evento</option>
            <option value="NOTICIA">Noticia</option>
        </select>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" name="ubicacion" id="ubicacion">

        <label for="partido">Partido:</label>
        <select name="partido" id="partido" required>
            <option value="1">Sueña, crea, innova</option>
            <option value="2">Juntos por el cambio</option>
        </select>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="Activo">Activo</option>
            <option value="Oculto">Oculto</option>
        </select>

        <label for="imagen">Imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/png, image/jpeg, image/jpg">
        <input type="hidden" name="imagen_actual" value="<?php echo $evento['IMAGEN_EVT_NOT'] ?? ''; ?>">

        <button type="submit" class="btn btn-danger">Guardar</button>
    </form>

    <h2>Lista de Eventos/Noticias</h2>
    <table class="table table-bordered" id="tabla-eventos">
        <thead class="table-danger">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventosNoticias as $evento): ?>
                <tr id="fila-<?php echo $evento['ID_EVT_NOT']; ?>">
                    <td><?php echo $evento['ID_EVT_NOT']; ?></td>
                    <td><?php echo $evento['TIT_EVT_NOT']; ?></td>
                    <td><?php echo $evento['FECHA_EVT_NOT']; ?></td>
                    <td><?php echo $evento['TIPO_REG_EVT_NOT']; ?></td>
                    <td><?php echo $evento['ESTADO_EVT_NOT']; ?></td>
                    <td>
                        <a href="eventos_noticias_admin_editar.php?id=<?php echo $evento['ID_EVT_NOT']; ?>"
                            class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm"
                            onclick="eliminarEvento(<?php echo $evento['ID_EVT_NOT']; ?>)">Eliminar</button>
                        <button class="btn btn-info btn-sm"
                            onclick="cambiarEstado(<?php echo $evento['ID_EVT_NOT']; ?>, '<?php echo $evento['ESTADO_EVT_NOT']; ?>')">
                            <?php echo $evento['ESTADO_EVT_NOT'] === 'Activo' ? 'Ocultar' : 'Activar'; ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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