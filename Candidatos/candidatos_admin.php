<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../Login/Login.php");
    exit;
}

// Obtener el rol del usuario
$user_role = $_SESSION['user_role'];

// Determinar la URL del dashboard según el rol del usuario
<<<<<<< HEAD
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/superadmin_dasboard.php' : '../Login/admin_dashboard.php';

=======
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/administración.php' : '../Login/administracion_admin.php';
>>>>>>> feature/PW-4-rediseño-de-candidatos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Candidatos</title>
    <link rel="stylesheet" href="candidatos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <div class="text-center">
                <i class="fa-solid fa-user-shield fa-2x"></i>
                <h6 class="mt-2 navbar-role"><?php echo $user_role === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?></h6>
            </div>
            <img src="../Login/Img/logoMariCruz.png" width="200px" margin-right="20px">
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            <li>
                <a href="<?php echo ($user_role === 'SUPERADMIN') ? '../Login/Administracion.php' : '../Login/Administracion_admin.php'; ?>">
                    <i class="fa-solid fa-cogs"></i> <span>Administración</span>
                </a>
            </li>
            <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
                
        </ul>
    </nav>
    
        <main>
            <h2>Lista de Candidatos</h2>
            <button id="addCandidateBtn" class="btn">
    <i class="fa fa-plus"></i> Agregar Candidato
</button>

    <table id="candidatesTable" class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Cargo</th>
                        <th>Educación</th>
                        <th>Experiencia</th>
                        <th>Estado</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="candidateList">
                    <!-- Se llenará dinámicamente -->
                </tbody>
            </table>
        </main>

      <div id="addCandidateModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2 id="modalTitle">Agregar Candidato</h2>
                <form id="candidateForm">
                    <input type="hidden" id="candidateId" name="candidateId">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="surname">Apellido:</label>
                    <input type="text" id="surname" name="surname" required>

                    <label for="birth_date">Fecha de Nacimiento:</label>
                    <input type="date" id="birth_date" name="birth_date" required>

                    <label for="position">Cargo:</label>
                    <select id="position" name="position" required>
                        <option value="Rectora">Rectora</option>
                        <option value="Vicerrector de Investigación, Innovación Vinculación con la Sociedad">Vicerrector de Investigación, Innovación Vinculación con la Sociedad</option>
                        <option value="Vicerrectora Administrativa">Vicerrectora Administrativa</option>
                        <option value="Vicerrector Académico">Vicerrector Académico</option>
                    </select>

                    <label for="education">Educación:</label>
                    <textarea id="education" name="education" required></textarea>

                    <label for="experience">Experiencia:</label>
                    <textarea id="experience" name="experience" required></textarea>

                    <label for="image">Imagen:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>

                    <button type="submit">Guardar</button>
                    <button type="button" id="closeModal" class="btn">Cancelar</button>
                </form>

               
            </div>
        </div>
    <script src="candidatos_admin.js"></script>
</body>
</html>
