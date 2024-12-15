<?php
include '../src/eventos_noticias_admin_queries.php';

// Verificar si se proporcionó un ID
if (!isset($_GET['id'])) {
    die("No se proporcionó ningún ID para editar.");
}

$id = intval($_GET['id']);
$evento = obtenerEventoNoticiaPorID($id);

if (!$evento) {
    die("El evento/noticia con el ID especificado no existe.");
}

// Actualizar el evento/noticia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $ubicacion = $_POST['ubicacion'];
    $partido = $_POST['partido'];
    $estado = $_POST['estado'];

    // Verificar si se seleccionó una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        $imagenConRuta = "/Pagina_Web/Pagina_Web/Eventos_Noticias/img/" . $imagen;

        // Mover la nueva imagen al directorio correspondiente
        $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . "/Pagina_Web/Pagina_Web/Eventos_Noticias/img/";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorioDestino . $imagen);
    } else {
        // Si no se selecciona una nueva imagen, mantener la imagen actual
        $imagenConRuta = $evento['IMAGEN_EVT_NOT'];
    }

    // Actualizar el evento/noticia
    $resultado = actualizarEventoNoticia($id, $titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagenConRuta);

    if ($resultado) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: 'El evento/noticia se actualizó correctamente.',
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
                text: 'Hubo un problema al actualizar el evento/noticia.',
                icon: 'error'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento/Noticia</title>
    <link rel="stylesheet" href="styleEventsAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <i class="fa-solid fa-user-shield"></i>
            <img src="../Home/Img/logo.png" width="50px" margin-right="10px">
            <h2>Gestión de eventos y noticia</h2>
        </div>
    </nav>
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="eventos_noticias_admin.php" class="btn btn-danger btn-lg">
                <i class="bi bi-arrow-left-circle me-2"></i> Regresar
            </a>
        </div>


        <h1>Editar Evento/Noticia</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $evento['TIT_EVT_NOT']; ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required><?php echo $evento['DESC_EVT_NOT']; ?></textarea>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $evento['FECHA_EVT_NOT']; ?>" required>

            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="EVENTO" <?php echo $evento['TIPO_REG_EVT_NOT'] === 'EVENTO' ? 'selected' : ''; ?>>Evento</option>
                <option value="NOTICIA" <?php echo $evento['TIPO_REG_EVT_NOT'] === 'NOTICIA' ? 'selected' : ''; ?>>Noticia</option>
            </select>

            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" value="<?php echo $evento['UBICACION_EVT_NOT']; ?>">

            <label for="partido">Partido:</label>
            <select name="partido" id="partido" required>
                <option value="1" <?php echo $evento['ID_PAR_EVT_NOT'] == 1 ? 'selected' : ''; ?>>Sueña, crea, innova</option>
                <option value="2" <?php echo $evento['ID_PAR_EVT_NOT'] == 2 ? 'selected' : ''; ?>>Juntos por el cambio</option>
            </select>

            <label for="estado">Estado:</label>
            <select name="estado" id="estado" required>
                <option value="Activo" <?php echo $evento['ESTADO_EVT_NOT'] === 'Activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="Oculto" <?php echo $evento['ESTADO_EVT_NOT'] === 'Oculto' ? 'selected' : ''; ?>>Oculto</option>
            </select>

            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" id="imagen" accept="image/png, image/jpeg, image/jpg">
            <?php if ($evento['IMAGEN_EVT_NOT']): ?>
                <p>Imagen actual:</p>
                <img src="/Pagina_Web/Pagina_Web/Eventos_Noticias/img/<?php echo basename($evento['IMAGEN_EVT_NOT']); ?>" alt="Imagen actual" style="max-width: 200px; max-height: 150px;">

            <?php endif; ?>


            <button type="submit" class="btn btn-danger w-100">Actualizar</button>
        </form>
    </div>
    <script src="scriptEventsEditar.js"></script>
</body>

</html>