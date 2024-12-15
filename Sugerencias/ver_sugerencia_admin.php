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
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #eef2f3;
        }

        header {
            background-color: #00796b;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .formulario {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .formulario label {
            font-weight: bold;
            display: block;
            margin: 15px 0 5px;
            font-size: 0.9em;
            color: #333;
        }

        .formulario p {
            margin: 0;
            padding: 10px 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #555;
        }

        .acciones {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1em;
            font-weight: bold;
            background-color: #00796b;
            border: none;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .btn:hover {
            background-color: #005f56;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-regresar {
            background-color: #d32f2f;
        }

        .btn-regresar:hover {
            background-color: #9a2222;
        }

        @media (max-width: 768px) {
            .formulario {
                padding: 15px;
            }

            .btn {
                font-size: 0.9em;
            }
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

            <!-- Contenedor de botones -->
            <div class="acciones">
                <a href="sugerencias_admin.php" class="btn btn-regresar">Regresar</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
