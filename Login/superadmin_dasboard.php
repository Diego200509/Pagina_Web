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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="superadmin_estilo.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <div class="text-center">
                <i class="fa-solid fa-user-shield fa-2x"></i>
                <h6 class="mt-2">SuperAdmin</h6>
            </div>
            <img src="/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            <li><a href="../Login/Administracion.php"><i class="fa-solid fa-cogs"></i> <span>Administración</span></a></li>
            <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
        </ul>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <h1 class="text-center">Bienvenido, SuperAdmin</h1>
        <p class="text-center">Gestiona usuarios y navega por las secciones principales desde aquí.</p>

        <!-- Tabla para mostrar usuarios (ADMIN y USUARIO) -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h3 class="text-center">Administradores creados </h3>
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../config/config.php');
                        // Seleccionar usuarios con rol ADMIN o USUARIO
                        $sql = "SELECT NOM_USU AS nombre, EMAIL_USU AS correo, ROL_USU AS rol 
                                FROM USUARIOS 
                                WHERE ROL_USU IN ('ADMIN')";
                        $result = $connection->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No hay usuarios registrados con rol ADMIN o USUARIO.</td></tr>";
                        }

                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
