<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/config.php');
include('../src/gestionarPropuestas_queries.php'); // Asegúrate de incluir el archivo de funciones

// Configuración de paginación
$propuestasPorPagina = 10;  // Número de propuestas por página
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $propuestasPorPagina;

$rol = 'admin'; // Cambiar a 'superadmin' según el rol actual

if ($rol !== 'admin' && $rol !== 'superadmin') {
    header('Location: ../Home/inicio.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'agregar') {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $partido = $_POST['partido'];

        // Verificar que los datos del formulario se recibieron correctamente
        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        try {
            // Llamar a la función de agregar propuesta
            agregarPropuestaYColaboracion($connection, $titulo, $descripcion, $categoria, $partido);
            header('Location: gestionarPropuestas.php?status=success');
            exit();
        } catch (Exception $e) {
            die("Error al agregar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'eliminar') {
        $id = $_POST['id'];

        // Verificar que se recibió el ID
        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para eliminar.");
        }

        try {
            // Llamar a la función de eliminar propuesta
            eliminarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=success');
            exit();
        } catch (Exception $e) {
            die("Error al eliminar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'editar') {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $partido = $_POST['partido'];

        // Verifica que los datos no estén vacíos
        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        try {
            // Llamar a la función de actualizar propuesta
            actualizarPropuesta($connection, $id, $titulo, $descripcion, $categoria, $partido);
            header('Location: gestionarPropuestas.php?status=success');
            exit();
        } catch (Exception $e) {
            die("Error al editar propuesta: " . $e->getMessage());
        }
    }



    if ($accion === 'ocultar') {
        $id = $_POST['id'];

        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para ocultar.");
        }

        try {
            ocultarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=hidden');
            exit();
        } catch (Exception $e) {
            die("Error al ocultar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'mostrar') {
        $id = $_POST['id'];

        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para mostrar.");
        }

        try {
            mostrarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=visible');
            exit();
        } catch (Exception $e) {
            die("Error al mostrar propuesta: " . $e->getMessage());
        }
    }


    if ($accion === 'mostrar') {
        $id = $_POST['id'];

        // Verificar que se recibió el ID
        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para mostrar.");
        }

        try {
            // Llamar a la función de mostrar propuesta
            mostrarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=success');
            exit();
        } catch (Exception $e) {
            die("Error al mostrar propuesta: " . $e->getMessage());
        }
    }
}

// Obtener el total de propuestas en la base de datos (sin duplicados)
$totalPropuestasResult = $connection->query("SELECT COUNT(*) AS total FROM PROPUESTAS");
$totalPropuestas = $totalPropuestasResult->fetch_assoc()['total'];
$totalPaginas = ceil($totalPropuestas / $propuestasPorPagina);

// Depuración de variables
echo "<pre>";
echo "Página actual: " . $paginaActual . "\n";
echo "Offset: " . $offset . "\n";
echo "Total de propuestas en BD: " . $totalPropuestas . "\n";
echo "Total de páginas: " . $totalPaginas . "\n";
echo "</pre>";

// Consulta simplificada para ver si la paginación funciona sin GROUP_CONCAT
$query = "
    SELECT 
        PROPUESTAS.ID_PRO, PROPUESTAS.TIT_PRO, PROPUESTAS.DESC_PRO, PROPUESTAS.CAT_PRO, PROPUESTAS.ESTADO,
        GROUP_CONCAT(PARTIDOS_POLITICOS.NOM_PAR SEPARATOR ', ') AS PARTIDOS
    FROM PROPUESTAS
    INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
    INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
    GROUP BY PROPUESTAS.ID_PRO
    ORDER BY PROPUESTAS.ID_PRO ASC
    LIMIT ? OFFSET ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ii", $propuestasPorPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

$partidos = obtenerPartidos($connection);

// Función para mostrar la descripción con formato
function mostrarDescripcionConFormato($descripcion)
{
    return nl2br(htmlspecialchars($descripcion));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propuestas</title>
    <link rel="stylesheet" href="estilosGestionarPropuestas.css">
</head>

<body>
    <header>
        <h1>Gestión de Propuestas - Proceso de Elecciones UTA 2024</h1>
        <nav>
            <a href="../Home/inicio.php">Inicio</a>
            <a href="../Candidatos/candidatos.php">Candidatos</a>
            <a href="../Eventos_Noticias/eventos_noticias.php">Eventos y Noticias</a>
        </nav>
    </header>

    <div class="container">
        <h2>Administrar Propuestas</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'success'): ?>
                <p class="success">Operación realizada con éxito.</p>
            <?php elseif ($_GET['status'] == 'hidden'): ?>
                <p class="success">Propuesta ocultada con éxito.</p>
            <?php elseif ($_GET['status'] == 'visible'): ?>
                <p class="success">Propuesta visible con éxito.</p>
            <?php elseif ($_GET['status'] == 'deleted'): ?>
                <p class="success">Propuesta eliminada con éxito.</p>
            <?php elseif ($_GET['status'] == 'no_changes'): ?>
                <p class="info">No se realizaron cambios en la propuesta.</p>
            <?php endif; ?>
        <?php endif; ?>


        <form method="POST" action="gestionarPropuestas.php">
            <h3>Agregar Nueva Propuesta</h3>
            <input type="text" name="titulo" placeholder="Título de la Propuesta" required>
            <textarea name="descripcion" placeholder="Descripción de la Propuesta" required></textarea>
            <select name="categoria" required>
                <option value="">Seleccionar Facultad o Interés</option>
                <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                <option value="Ciencia e Ingeniería en Alimentos">Ciencia e Ingeniería en Alimentos</option>
                <option value="Jurisprudencia y Ciencias Sociales">Jurisprudencia y Ciencias Sociales</option>
                <option value="Contabilidad y Auditoría">Contabilidad y Auditoría</option>
                <option value="Ciencias Humanas y de la Educación">Ciencias Humanas y de la Educación</option>
                <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                <option value="Ingeniería Civil y Mecánica">Ingeniería Civil y Mecánica</option>
                <option value="Ingeniería en Sistemas, Electrónica e Industrial">Ingeniería en Sistemas, Electrónica e Industrial</option>
                <option value="Infraestructura">Infraestructura</option>
                <option value="Deportes">Deportes</option>
                <option value="Cultura">Cultura</option>
                <option value="Investigación">Investigación</option>
                <option value="Vinculación con la Sociedad">Vinculación con la Sociedad</option>
            </select>
            <select name="partido" required>
                <option value="">Seleccionar Partido Político</option>
                <?php while ($partido = $partidos->fetch_assoc()): ?>
                    <option value="<?= $partido['ID_PAR'] ?>"><?= $partido['NOM_PAR'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="accion" value="agregar">Agregar Propuesta</button>
        </form>

        <h3>Propuestas Existentes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Partido Político</th> <!-- Partido Político después del ID -->
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['ID_PRO'] ?></td>
                            <td><?= $row['PARTIDOS'] ?></td> <!-- Mostrar el partido político -->
                            <td><?= $row['TIT_PRO'] ?></td>
                            <td><?= mostrarDescripcionConFormato($row['DESC_PRO']) ?></td>
                            <td><?= $row['CAT_PRO'] ?></td>
                            <td>
                                <form method="POST" action="gestionarPropuestas.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID_PRO'] ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                                </form>
                                <form method="GET" action="editarPropuesta.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID_PRO'] ?>">
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                </form>
                                <form method="POST" action="gestionarPropuestas.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID_PRO'] ?>">
                                    <button type="submit" name="accion" value="ocultar" class="btn btn-warning">Ocultar</button>
                                </form>

                                <!-- Agregar el botón "Mostrar" solo si la propuesta está oculta -->
                                <?php if ($row['ESTADO'] === 'Oculta'): ?>
                                    <form method="POST" action="gestionarPropuestas.php" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['ID_PRO'] ?>">
                                        <button type="submit" name="accion" value="mostrar" class="btn btn-success">Mostrar</button>
                                    </form>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay propuestas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Barra de navegación para la paginación -->
        <div class="pagination">
            <?php if ($paginaActual > 1): ?>
                <a href="?pagina=1">Primera</a>
                <a href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" <?= $i === $paginaActual ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                <a href="?pagina=<?= $totalPaginas ?>">Última</a>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>

<?php

// Función para ocultar propuesta
function ocultarPropuesta($connection, $id)
{
    $query = "UPDATE PROPUESTAS SET ESTADO = 'Oculta' WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar consulta ocultar: " . $connection->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Propuesta oculta con éxito.";
    } else {
        die("No se ocultó ninguna fila en PROPUESTAS.");
    }
    $stmt->close();
}

// Función para mostrar propuesta
function mostrarPropuesta($connection, $id)
{
    $query = "UPDATE PROPUESTAS SET ESTADO = 'Visible' WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar consulta mostrar: " . $connection->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Propuesta visible con éxito.";
    } else {
        die("No se pudo cambiar el estado de la propuesta a visible.");
    }
    $stmt->close();
}
?>