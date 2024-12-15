<?php
session_start();

// Verificar si el usuario tiene un rol válido
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['SUPERADMIN', 'ADMIN'])) {
    header("Location: ../Login/Login.php");
    exit;
}

include('../config/config.php');
$eventos_noticias = include('../src/resultado_queries.php');

$nombrePartido1 = obtenerNombrePartido(1);
$nombrePartido2 = obtenerNombrePartido(2);
$votosPorPartido = obtenerVotosPorPartido();

// Sumar los votos para calcular el total
$totalVotos = array_sum($votosPorPartido);

// Función para calcular el porcentaje
function calcularPorcentaje($votos, $total)
{
    return $total > 0 ? ($votos / $total) * 100 : 0; // Previene la división por cero
}

// Función para obtener los detalles de votantes por partido
function obtenerDetallesVotantes($idPartido)
{
    global $connection;

    $sql = "SELECT u.NOM_USU AS nombre_usuario, u.EMAIL_USU AS correo_usuario
            FROM REGISTROS_VOTOS rv
            JOIN USUARIOS u ON rv.ID_USU_RES = u.ID_USU
            JOIN VOTOS v ON rv.ID_VOT_RES = v.ID_VOT
            WHERE v.ID_PAR_VOT = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $idPartido);
    $stmt->execute();
    $result = $stmt->get_result();

    $detalles = [];
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }

    return $detalles;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="EstilosResultados.css">
    <title>Resultados Admin</title>
    <style>
        
        /* Navbar Styles */
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
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
        }

        .btn-submit:hover {
            background-color: rgb(236, 231, 232);
        }
    </style>
    <script>
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
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
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

    <div class="container">
        <h3>Resultados Detallados</h3>
        <div class="results">
            <div class="candidate">
                <h2><span
                        style="color: #a30280; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                        <?php echo htmlspecialchars($nombrePartido1); ?></span>
                </h2>
                <img src="Img/mari2.jpg" alt="Nombre del candidato 1">
                <div class="percentage">
                    Porcentaje: <?php echo isset($votosPorPartido[1]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[1], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[1]) ? $votosPorPartido[1] : '0'; ?>
                </div>
                <h4>Detalles de los votantes:</h4>
                <ul>
                    <?php
                    $detallesVotantes = obtenerDetallesVotantes(1);
                    foreach ($detallesVotantes as $votante) {
                        echo "<li>" . htmlspecialchars($votante['nombre_usuario']) . " (" . htmlspecialchars($votante['correo_usuario']) . ")</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="candidate">
                <h2><span
                        style="color: blue; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                        <?php echo htmlspecialchars($nombrePartido2); ?></span>
                </h2>
                <img src="Img/CANDIDATA2.jpg" alt="Nombre del candidato 2">
                <div class="percentage">
                    Porcentaje: <?php echo isset($votosPorPartido[2]) && $totalVotos > 0 ? number_format(calcularPorcentaje($votosPorPartido[2], $totalVotos), 2) . '%' : '0%'; ?>
                </div>
                <div class="votes">
                    Votos: <?php echo isset($votosPorPartido[2]) ? $votosPorPartido[2] : '0'; ?>
                </div>
                <h4>Detalles de los votantes:</h4>
                <ul>
                    <?php
                    $detallesVotantes = obtenerDetallesVotantes(2);
                    foreach ($detallesVotantes as $votante) {
                        echo "<li>" . htmlspecialchars($votante['nombre_usuario']) . " (" . htmlspecialchars($votante['correo_usuario']) . ")</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>


</body>
<footer class="footer-rights">
    Derechos reservados Team Sangre 2024
</footer>
</html>
