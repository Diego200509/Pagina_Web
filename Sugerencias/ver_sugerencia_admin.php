<?php
// Incluir archivo de consultas y configuración
include('../src/sugerencias_queries.php');
include('../Config/config.php');

// Obtener el ID de la sugerencia desde la URL
$id_sugerencia = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar si el ID es válido
if ($id_sugerencia <= 0) {
    // Redirigir a la página de administración si no se encuentra el ID o es inválido
    header('Location: sugerencias_admin.php');
    exit;
}

// Obtener los detalles de la sugerencia desde la base de datos
$sugerencia = obtenerSugerenciaPorId($id_sugerencia);

// Verificar si se obtuvo la sugerencia correctamente
if (!$sugerencia || !is_array($sugerencia)) {
    // Mostrar mensaje de error si no se encontró la sugerencia
    echo "Sugerencia no encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Sugerencia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        header {
            background-color: #b22222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .formulario {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .formulario label {
            font-weight: bold;
            margin-top: 10px;
        }

        .formulario p {
            margin: 10px 0;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            background-color: #2b7a78;
            border: none;
        }

        .btn:hover {
            background-color: #19595a;
        }

        .btn-regresar {
            background-color: #b22222;
        }

        .btn-regresar:hover {
            background-color: #8b1a1a;
        }
    </style>
</head>
<body>
<header>
    <h1>Detalles de la Sugerencia</h1>
</header>
<main>
    <div class="formulario">
        <form>
        <label for="usuario">Usuario:</label>
<p id="usuario"><?php echo htmlspecialchars($sugerencia['nombre_usuario'] ?? 'No disponible'); ?></p>

<label for="correo">Correo:</label>
<p id="correo"><?php echo htmlspecialchars($sugerencia['correo_usuario'] ?? 'No disponible'); ?></p>

<label for="candidato">Candidato:</label>
<p id="candidato"><?php echo htmlspecialchars($sugerencia['nombre_candidato'] ?? 'No disponible'); ?></p>

<label for="sugerencia">Sugerencia:</label>
<p id="sugerencia"><?php echo htmlspecialchars($sugerencia['sugerencia'] ?? 'No disponible'); ?></p>

<label for="propuesta">Propuesta:</label>
<p id="propuesta"><?php echo htmlspecialchars($sugerencia['propuesta'] ?? 'No disponible'); ?></p>

<label for="comentarios">Comentarios:</label>
<p id="comentarios"><?php echo htmlspecialchars($sugerencia['comentarios'] ?? 'No disponible'); ?></p>

<label for="estado">Estado:</label>
<p id="estado"><?php echo htmlspecialchars($sugerencia['estado_sug'] ?? 'No disponible'); ?></p>

<label for="fecha">Fecha:</label>
<p id="fecha"><?php echo htmlspecialchars($sugerencia['created_at'] ?? 'No disponible'); ?></p>


            <a href="sugerencias_admin.php" class="btn btn-regresar">Regresar</a>
        </form>
    </div>
</main>
</body>
</html>
