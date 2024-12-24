<?php
session_start();

// Verificar si el usuario está autenticado y tiene un rol válido
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['SUPERADMIN', 'ADMIN'])) {
    header("Location: ../Login/Login.php");
    exit;
}

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
    padding: 20px;
    min-width: 100px;
    border: 1px solid #ddd;
}

th {
    background-color: #8a2b2b;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
}

td {
    background-color: #ffffff;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

tr:nth-child(even) td {
    background-color: #f9f9f9;
}

tr:hover td {
    background-color: #f5f5f5;
}

.acciones {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
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
    width: 150px;
    box-sizing: border-box;
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

/* Navbar */
.navbar {
    background-color: rgb(122, 3, 23);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
}

.navbar-logo img {
    width: 50px;
    margin-right: 10px;
}

.navbar-logo h2 {
    font-size: 1.5rem;
    margin: 0;
}

.navbar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
}

.navbar-menu li {
    list-style: none;
}

.navbar-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s;
}

.navbar-menu li a:hover {
    color: #ffc107;
}

.logout {
    color: #ffc107;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background: white;
    color: #333;
    border-radius: 15px;
    padding: 25px;
    width: 450px;
    max-width: 90%;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.modal-content h2 {
    color: rgb(122, 3, 23);
    text-align: center;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: bold;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #666;
    transition: color 0.2s ease;
}

.close-btn:hover {
    color: red;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
    color: #333;
}

.form-group input, .form-group select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    color: #333;
    width: 100%;
    box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus, .form-group select:focus {
    border-color: #4A90E2;
    outline: none;
    box-shadow: 0px 0px 8px rgba(74, 144, 226, 0.5);
}

.form-group input::placeholder {
    color: #aaa;
    font-style: italic;
}

.btn-submit {
    padding: 12px;
    background-color: rgb(122, 3, 23);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    transition: background 0.3s ease, transform 0.2s ease;
}

.btn-submit:hover {
    background-color: rgb(142, 20, 36);
    transform: scale(1.05);
}

/* Animación */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
    <script>
        // Modal handling
        const showModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'flex';
        };

        const closeModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'none';
        };

        window.onclick = function(event) {
            const modal = document.getElementById('modal-crear-usuario');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        // Actualizar el estado de una sugerencia
        async function actualizarEstado(id, estado) {
            const data = { id_sugerencia: id, estado: estado };
            try {
                const response = await fetch('actualizar_estado.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        document.getElementById(`estado-${id}`).textContent = estado;
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
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h2><?php echo $_SESSION['user_role'] === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?></h2>
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fas fa-users"></i> Candidatos</a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fas fa-lightbulb"></i> Propuestas</a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fas fa-comment-dots"></i> Sugerencias</a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li> <!-- Nuevo campo -->

            <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
                <li><a href="#" onclick="showModal()"><i class="fas fa-user-plus"></i> Crear Admin</a></li>
            <?php endif; ?>
            <li><a href="../Login/Login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </nav>

      <!-- Modal para Crear Admin -->
      <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
        <div id="modal-crear-usuario" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Crear Nuevo Admin</h2>
                <form action="../src/crear_usuario_queries.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Ingrese una contraseña" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select id="rol" name="rol" required>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Crear Admin</button>
                </form>
            </div>
        </div>
    <?php endif; ?>


    <!-- Contenido principal -->
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
                                <?php echo htmlspecialchars($sugerencia['estado']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($sugerencia['created_at']); ?></td>
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
