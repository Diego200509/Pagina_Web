<?php
// Incluir archivo de consultas y configuración
include('../src/sugerencias_queries.php');
include('../Config/config.php');

// Obtener todas las sugerencias desde la base de datos
$sugerencias = obtenerTodasSugerencias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Sugerencias</title>
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

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #b22222;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            cursor: pointer;
            border: none;
        }

        .btn-revisar {
            background-color: #2b7a78;
        }

        .btn-revisar:hover {
            background-color: #19595a;
        }

        .btn-cancelar {
            background-color: #e63946;
        }

        .btn-cancelar:hover {
            background-color: #d62828;
        }

        .mensaje {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-top: 20px;
        }
    </style>
    <script>
        async function actualizarEstado(id, estado) {
            const data = { id_sugerencia: id, estado: estado };
            try {
                const response = await fetch('actualizar_estado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        const estadoElemento = document.getElementById(`estado-${id}`);
                        estadoElemento.textContent = estado === 'Revisado' ? 'Revisado' : 'Pendiente';
                    } else {
                        alert('Error al actualizar el estado.');
                    }
                } else {
                    alert('Error en la solicitud.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al comunicarse con el servidor.');
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Administrar Sugerencias</h1>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Candidato</th>
            <th>Sugerencia</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($sugerencias)): ?>
            <?php foreach ($sugerencias as $sugerencia): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sugerencia['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($sugerencia['correo_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($sugerencia['nombre_candidato']); ?></td>
                    <td><?php echo htmlspecialchars($sugerencia['sugerencia']); ?></td>
                    <td id="estado-<?php echo $sugerencia['id_sugerencia']; ?>">
                        <?php echo isset($sugerencia['estado']) && $sugerencia['estado'] == 'Revisado' ? 'Revisado' : 'Pendiente'; ?>
                    </td>
                    <td>
                        <?php 
                        echo isset($sugerencia['created_at']) ? 
                            htmlspecialchars($sugerencia['created_at']) : 
                            'Fecha no disponible'; 
                        ?>
                    </td>
                    <td>
    <button 
        class="btn btn-revisar" 
        onclick="actualizarEstado(<?php echo $sugerencia['id_sugerencia']; ?>, 'Revisado')">
        Revisar
    </button>
    <button 
        class="btn btn-cancelar" 
        onclick="actualizarEstado(<?php echo $sugerencia['id_sugerencia']; ?>, 'Pendiente')">
        Cancelar
    </button>
    <a href="ver_sugerencia_admin.php?id=<?php echo $sugerencia['id_sugerencia']; ?>" class="btn btn-revisar">
        Ver Detalles
    </a>
</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="mensaje">No hay sugerencias registradas.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
