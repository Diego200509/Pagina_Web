<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/config.php');
include('../src/gestionarPropuestas_queries.php'); // Asegúrate de incluir el archivo de funciones

// Configuración de paginación
$propuestasPorPagina = 10;  // Número de propuestas por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $propuestasPorPagina;

echo "Página actual: " . $paginaActual . "<br>";
echo "Offset: " . $offset . "<br>";

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
}

// Obtener el total de propuestas sin duplicados
$totalPropuestasResult = $connection->query("SELECT COUNT(PROPUESTAS.ID_PRO) AS total FROM PROPUESTAS");
if (!$totalPropuestasResult) {
    die("Error al obtener el total de propuestas: " . $connection->error);
}

$totalPropuestas = $totalPropuestasResult->fetch_assoc()['total'];
echo "Total de propuestas en BD: " . $totalPropuestas . "<br>";

$totalPaginas = ceil($totalPropuestas / $propuestasPorPagina);
echo "Total de páginas: " . $totalPaginas . "<br>";

// Obtener las propuestas con paginación y sin duplicados
$result = obtenerPropuestasConPartidos($connection, $propuestasPorPagina, $offset);  // Llamamos a la función de consultas
echo "Resultado de la consulta: <br>";
var_dump($result);

$partidos = obtenerPartidos($connection);
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

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p class="success">Operación realizada con éxito.</p>
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
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Partido Político</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['ID_PRO'] ?></td>
                            <td><?= $row['TIT_PRO'] ?></td>
                            <td><?= $row['DESC_PRO'] ?></td>
                            <td><?= $row['CAT_PRO'] ?></td>
                            <td><?= $row['PARTIDOS'] ?></td>
                            <td>
                                <form method="POST" action="gestionarPropuestas.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID_PRO'] ?>">
                                    <button type="submit" name="accion" value="eliminar">Eliminar</button>
                                </form>
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
