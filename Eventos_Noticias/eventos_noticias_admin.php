<?php
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
    $ubicacion = $_POST['ubicacion'];
    $partido = $_POST['partido'];
    $estado = $_POST['estado'];
    $imagen = $_FILES['imagen']['name'];
    $imagenConRuta = $rutaBaseImagenes . $imagen;

    if ($id) {
        $resultado = actualizarEventoNoticia($id, $titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagenConRuta);
    } else {
        $resultado = crearEventoNoticia($titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagenConRuta);
    }

    if ($resultado) {
        header('Location: eventos_noticias_admin.php');
    } else {
        echo "Error en la operación.";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <h1>Administrar Eventos y Noticias</h1>

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
        <input type="file" name="imagen" id="imagen" required>

        <button type="submit" class="btn btn-danger">Guardar</button>
    </form>

    <h2>Lista de Eventos y Noticias</h2>
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
                        <button class="btn btn-warning btn-sm">Editar</button>
                        <button class="btn btn-danger btn-sm"
                            onclick="eliminarEvento(<?php echo $evento['ID_EVT_NOT']; ?>)">Eliminar</button>
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

    <script src="scriptEventsAdmin.js"></script>
</body>

</html>