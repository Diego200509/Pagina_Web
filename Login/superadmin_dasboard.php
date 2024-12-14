<?php
session_start();

// Verificar si el usuario tiene el rol de SUPERADMIN
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'SUPERADMIN') {
    header("Location: ../Login/Login.php");
    exit;
}

// Mensaje de éxito o error
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Limpiar mensaje después de mostrarlo
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SuperAdmin</title>
    <!-- Font Awesome actualizado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWM3FAa+UP7B7aXQFJ59Y3RF53p50eA88LvNCwD5zZoOMMDzBtF1UeJ0cEtCU" crossorigin="anonymous">
    <link rel="stylesheet" href="superadmin_styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <div class="navbar-logo">
        <i class="fa-solid fa-user-shield"></i>
        <img src = "../Home/Img/logo.png" width="50px"  margin-right= "10px">
        <h2>SuperAdmin</h2>
    </div>
    <ul class="navbar-menu">
        <li><a href="candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
        <li><a href="eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
        <li><a href="propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
        <li><a href="sugerencias.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
        <li><a href="#" id="btn-crear-usuario"><i class="fa-solid fa-user-plus"></i> <span>Crear Admin</span></a></li>
        <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
    </ul>
</nav>


    <!-- Contenido principal -->
    <div class="container">
        <h1>Bienvenido, SuperAdmin</h1>
        <p class="subtitle">Gestiona usuarios y navega por las secciones principales desde aquí.</p>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'éxito') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para crear usuario -->
    <div id="modal-crear-usuario" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-modal">&times;</span>
            <h2>Crear Nuevo Admin</h2>
            <form id="user-form" action="../src/crear_usuario_queries.php" method="POST">
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
        </br>
                <button type="submit" class="btn-submit">Crear Admin</button>
            </form>
        </div>
    </div>

    <script>
        // Mostrar el modal
        const modal = document.getElementById('modal-crear-usuario');
        const btnAbrirModal = document.getElementById('btn-crear-usuario');
        const btnCerrarModal = document.getElementById('close-modal');

        btnAbrirModal.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });

        btnCerrarModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Cerrar modal si se hace clic fuera de él
        window.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
