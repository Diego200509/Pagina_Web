<?php
include('../config/config.php'); // Incluye la conexión a la base de datos
include('../src/gestionarPropuestas_queries.php'); // Incluye las funciones de consulta

// Simular roles para pruebas
$rol = 'admin'; // Cambiar a 'superadmin' o null según lo que quieras probar

// Verificar el acceso
if ($rol !== 'admin' && $rol !== 'superadmin') {
    echo "Acceso denegado. Redirigiendo...";
    header('Location: ../Home/inicio.php'); // Redirige a la página principal
    exit();
}

// Procesar acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'agregar') {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];

        // Insertar propuesta en la base de datos
        agregarPropuesta($connection, $titulo, $descripcion, $categoria);

        header('Location: gestionarPropuestas.php?status=success');
        exit();
    }

    if ($accion === 'eliminar') {
        $id = $_POST['id'];

        // Eliminar propuesta de la base de datos
        eliminarPropuesta($connection, $id);

        header('Location: gestionarPropuestas.php?status=success');
        exit();
    }
}

// Obtener todas las propuestas para mostrar en la tabla
$result = obtenerPropuestas($connection);
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

        <!-- Mostrar mensaje de éxito si existe -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p class="success">Operación realizada con éxito.</p>
        <?php endif; ?>

        <!-- Formulario para agregar propuesta -->
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
            <button type="submit" name="accion" value="agregar">Agregar Propuesta</button>
        </form>

        <!-- Tabla de propuestas existentes -->
        <h3>Propuestas Existentes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
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
                            <td><?= $row['TIT_PRO'] ?></td>
                            <td><?= $row['DESC_PRO'] ?></td>
                            <td><?= $row['CAT_PRO'] ?></td>
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
                        <td colspan="5">No hay propuestas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>