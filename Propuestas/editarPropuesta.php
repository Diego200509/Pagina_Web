<?php
// Incluir los archivos de configuración y funciones
include('../config/config.php');
include('../src/gestionarPropuestas_queries.php');

// Verificar si el ID está presente en la URL
if (isset($_GET['id'])) {
    $idPropuesta = $_GET['id'];

    // Consultar los detalles de la propuesta con el ID proporcionado
    $query = "SELECT PROPUESTAS.*, COLABORACIONES.ID_PAR_COL AS ID_PAR FROM PROPUESTAS
              INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
              WHERE PROPUESTAS.ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $connection->error);
    }
    $stmt->bind_param("i", $idPropuesta);
    $stmt->execute();
    $result = $stmt->get_result();
    $propuesta = $result->fetch_assoc();

    if (!$propuesta) {
        die("Propuesta no encontrada.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización de la propuesta
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $partido = $_POST['partido'];

    // Verificar que los datos no estén vacíos
    if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido)) {
        die("Error: Faltan datos en el formulario. Verifique los campos.");
    }

    try {
        // Llamar a la función de actualizar propuesta
        $actualizado = actualizarPropuesta($connection, $id, $titulo, $descripcion, $categoria, $partido, $estado);

        if ($actualizado) {
            header('Location: gestionarPropuestas.php?status=success');
        } else {
            header('Location: gestionarPropuestas.php?status=no_changes');
        }
        exit();
    } catch (Exception $e) {
        die("Error al editar propuesta: " . $e->getMessage());
    }
} else {
    die("ID de propuesta no proporcionado.");
}

// Obtener todos los partidos para el selector
$partidos = obtenerPartidos($connection);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propuesta</title>
    <link rel="stylesheet" href="estilosGestionarPropuestas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilo para la flecha de regreso */
        .back-arrow {
            position: absolute;
            top: 100px;
            right: 60px;
            text-decoration: none;
            font-size: 20px;
            color: #333;
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 50%;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
        }

        .back-arrow:hover {
            background-color: #ddd;
            color: #000;
        }
    </style>


</head>

<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <i class="fa-solid fa-user-shield"></i>
            <img src="../Home/Img/logo.png" width="50px" margin-right="10px">
            <h2>Gestión de Propuestas</h2>
        </div>
    </nav>

    <div style="text-align: right; margin-bottom: 1px; margin-top: 30px; margin-left: -20px;">
        <a href="gestionarPropuestas.php" class="btn btn-danger btn-lg">
            <i class="bi bi-arrow-left-circle me-2"></i> Regresar
        </a>
    </div>


    <div class="container">
        <h2>Editar Propuesta</h2>

        <form method="POST" action="editarPropuesta.php">
            <input type="hidden" name="id" value="<?= $propuesta['ID_PRO'] ?>">

            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="<?= $propuesta['TIT_PRO'] ?>" required>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required><?= $propuesta['DESC_PRO'] ?></textarea>

            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria" required>
                <option value="Ciencias Administrativas" <?= $propuesta['CAT_PRO'] == 'Ciencias Administrativas' ? 'selected' : '' ?>>Ciencias Administrativas</option>
                <option value="Ciencia e Ingeniería en Alimentos" <?= $propuesta['CAT_PRO'] == 'Ciencia e Ingeniería en Alimentos' ? 'selected' : '' ?>>Ciencia e Ingeniería en Alimentos</option>
                <option value="Jurisprudencia y Ciencias Sociales" <?= $propuesta['CAT_PRO'] == 'Jurisprudencia y Ciencias Sociales' ? 'selected' : '' ?>>Jurisprudencia y Ciencias Sociales</option>
                <option value="Contabilidad y Auditoría" <?= $propuesta['CAT_PRO'] == 'Contabilidad y Auditoría' ? 'selected' : '' ?>>Contabilidad y Auditoría</option>
                <option value="Ciencias Humanas y de la Educación" <?= $propuesta['CAT_PRO'] == 'Ciencias Humanas y de la Educación' ? 'selected' : '' ?>>Ciencias Humanas y de la Educación</option>
                <option value="Ciencias de la Salud" <?= $propuesta['CAT_PRO'] == 'Ciencias de la Salud' ? 'selected' : '' ?>>Ciencias de la Salud</option>
                <option value="Ingeniería Civil y Mecánica" <?= $propuesta['CAT_PRO'] == 'Ingeniería Civil y Mecánica' ? 'selected' : '' ?>>Ingeniería Civil y Mecánica</option>
                <option value="Ingeniería en Sistemas, Electrónica e Industrial" <?= $propuesta['CAT_PRO'] == 'Ingeniería en Sistemas, Electrónica e Industrial' ? 'selected' : '' ?>>Ingeniería en Sistemas, Electrónica e Industrial</option>
                <option value="Infraestructura" <?= $propuesta['CAT_PRO'] == 'Infraestructura' ? 'selected' : '' ?>>Infraestructura</option>
                <option value="Deportes" <?= $propuesta['CAT_PRO'] == 'Deportes' ? 'selected' : '' ?>>Deportes</option>
                <option value="Cultura" <?= $propuesta['CAT_PRO'] == 'Cultura' ? 'selected' : '' ?>>Cultura</option>
                <option value="Investigación" <?= $propuesta['CAT_PRO'] == 'Investigación' ? 'selected' : '' ?>>Investigación</option>
                <option value="Vinculación con la Sociedad" <?= $propuesta['CAT_PRO'] == 'Vinculación con la Sociedad' ? 'selected' : '' ?>>Vinculación con la Sociedad</option>
            </select>

            <label for="partido">Partido Político</label>
            <select id="partido" name="partido" required>
                <?php while ($partido = $partidos->fetch_assoc()): ?>
                    <option value="<?= $partido['ID_PAR'] ?>" <?= $partido['ID_PAR'] == $propuesta['ID_PAR'] ? 'selected' : '' ?>><?= $partido['NOM_PAR'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Actualizar Propuesta</button>
        </form>
    </div>

</body>

</html>