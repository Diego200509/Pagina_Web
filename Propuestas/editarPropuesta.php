<?php
// Incluir los archivos de configuración y funciones
include('../config/config.php');
include('../src/gestionarPropuestas_queries.php'); // Asegúrate de incluir el archivo de funciones

// Obtener el ID de la propuesta a editar
if (isset($_GET['id'])) {
    $idPropuesta = $_GET['id'];

    // Obtener los datos de la propuesta
    $query = "SELECT * FROM PROPUESTAS WHERE ID_PRO = ?";
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
</head>

<body>
    <header>
        <h1>Editar Propuesta - Proceso de Elecciones UTA 2024</h1>
        <nav>
            <a href="../Home/inicio.php">Inicio</a>
            <a href="../Candidatos/candidatos.php">Candidatos</a>
            <a href="../Eventos_Noticias/eventos_noticias.php">Eventos y Noticias</a>
        </nav>
    </header>

    <div class="container">
        <h2>Editar Propuesta</h2>

        <form method="POST" action="gestionarPropuestas.php">
    <h3>Agregar Nueva Propuesta</h3>
    
    <label for="titulo">Título</label>
    <input type="text" id="titulo" name="titulo" placeholder="Título de la Propuesta" required>
    
    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" placeholder="Descripción de la Propuesta" required></textarea>
    
    <label for="categoria">Categoría</label>
    <select id="categoria" name="categoria" required>
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
    
    <label for="partido">Partido Político</label>
    <select id="partido" name="partido" required>
        <option value="">Seleccionar Partido Político</option>
        <?php while ($partido = $partidos->fetch_assoc()): ?>
            <option value="<?= $partido['ID_PAR'] ?>"><?= $partido['NOM_PAR'] ?></option>
        <?php endwhile; ?>
    </select>
    
    <button type="submit" name="accion" value="agregar">Agregar Propuesta</button>
</form>

    </div>

</body>

</html>