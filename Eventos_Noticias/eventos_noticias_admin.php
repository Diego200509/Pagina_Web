<?php
include '../src/eventos_noticias_admin_queries.php';

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

    if ($id) {
        // Actualizar un registro existente
        $resultado = actualizarEventoNoticia($id, $titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagen);
    } else {
        // Crear un nuevo registro
        $resultado = crearEventoNoticia($titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagen);
    }

    if ($resultado) {
        move_uploaded_file($_FILES['imagen']['tmp_name'], "img/$imagen");
        header('Location: eventos_noticias_admin.php');
    } else {
        echo "Error en la operación.";
    }
}

// Manejar eliminación
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    eliminarEventoNoticia($id);
    header('Location: eventos_noticias_admin.php');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Eventos y Noticias</title>
    <link rel="stylesheet" href="styleEventsAdmin.css">
</head>
<body>
    <h1>Administrar Eventos y Noticias</h1>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>

        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required>
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

        <button type="submit">Guardar</button>
    </form>

    <h2>Lista de Eventos y Noticias</h2>
    <table>
        <thead>
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
                <tr>
                    <td><?php echo $evento['ID_EVT_NOT']; ?></td>
                    <td><?php echo $evento['TIT_EVT_NOT']; ?></td>
                    <td><?php echo $evento['FECHA_EVT_NOT']; ?></td>
                    <td><?php echo $evento['TIPO_REG_EVT_NOT']; ?></td>
                    <td><?php echo $evento['ESTADO_EVT_NOT']; ?></td>
                    <td>
                        <a href="eventos_noticias_admin.php?edit=<?php echo $evento['ID_EVT_NOT']; ?>">Editar</a>
                        <a href="eventos_noticias_admin.php?delete=<?php echo $evento['ID_EVT_NOT']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
