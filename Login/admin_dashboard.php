<?php
session_start();

// Verificar si el usuario tiene el rol de SUPERADMIN
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ADMIN') {
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
    <title>Dashboard Admin</title>
    <!-- Font Awesome actualizado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-jLKHWM3FAa+UP7B7aXQFJ59Y3RF53p50eA88LvNCwD5zZoOMMDzBtF1UeJ0cEtCU" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin_estilos.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <div class="navbar-logo">
    <div class="text-center">
        <!-- Icono SuperAdmin existente -->
        <i class="fa-solid fa-user-shield fa-2x"></i>
        <h6 class="mt-2">Admin</h6>
    </div>
    <!-- Logo existente -->
    <img src="../Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">

</div>



        </div>
        <ul class="navbar-menu"> 
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
        </ul>
    </nav>


    <!-- Contenido principal -->
    <div class="container">
        <h1>Bienvenido, Admin</h1>
        <p class="subtitle">Gestiona y navega por las secciones principales desde aquí.</p>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'éxito') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
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
