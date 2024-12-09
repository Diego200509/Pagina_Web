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
        /* General */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #ffffff, #f0f0f0);
        }

        header {
            background-color: #8a2b2b;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5em;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        table {
    width: 90%;
    margin: 20px auto;
    border-collapse: separate;
    border-spacing: 0;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 10px;
}
td, th {
    padding: 20px; /* Más espacio interno */
    min-width: 100px; /* Evitar compresión */
    border: 1px solid #ddd; /* Separación clara */
}

        th {
            background-color: #8a2b2b;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        td {
            background-color: #ffffff;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Sombra interna sutil */
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        tr:hover td {
    background-color: #f5f5f5; /* Destaca fila activa */
}
.acciones {
        display: flex;
        flex-direction: column; /* Alineación vertical */
        align-items: center; /* Centrado horizontal */
        gap: 10px; /* Espaciado uniforme entre los botones */
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 0.9em;
        font-weight: bold;
        color: white;
        cursor: pointer;
        border: none;
        text-align: center;
        transition: all 0.3s ease;
        width: 150px; /* Ancho fijo uniforme */
        box-sizing: border-box; /* Incluye el padding en el tamaño del botón */
    }

        .btn-revisar {
            background: #357a38;
        }

        .btn-revisar:hover {
            background: #2c6330;
        }

        .btn-cancelar {
            background: #b33a3a;
        }

        .btn-cancelar:hover {
            background: #992f2f;
        }

        .btn-detalles {
        background: #3b5998;
        margin-top: 5px; /* Espacio adicional debajo de los otros botones */
    }
        .btn-detalles:hover {
            background: #334b7f;
        }

        .mensaje {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
        .btn {
            width: 100%; /* Botones ocupan todo el ancho disponible */
        }
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
                    <td class="acciones">
    <button class="btn btn-revisar" onclick="actualizarEstado(<?php echo $sugerencia['id_sugerencia']; ?>, 'Revisado')">Revisar</button>
    <button class="btn btn-cancelar" onclick="actualizarEstado(<?php echo $sugerencia['id_sugerencia']; ?>, 'Pendiente')">Cancelar</button>
    <a href="ver_sugerencia_admin.php?id=<?php echo $sugerencia['id_sugerencia']; ?>" class="btn btn-detalles">Ver Detalles</a>
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
