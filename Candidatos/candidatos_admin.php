
<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../Login/Login.php");
    exit;
}

// Obtener el rol del usuario
$user_role = $_SESSION['user_role'];

// Determinar la URL del dashboard según el rol del usuario
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/superadmin_dasboard.php' : '../Login/admin_dashboard.php';
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
    <header>
        <nav class="navbar">
        <div class="navbar-logo">
            
            <img src="../Home/Img/logo.png" width="50px" margin-right="10px">
            <h1>Gestión de Candidatos</h1>
           
        </div>
    </nav>
    </header>
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="<?php echo $dashboard_url; ?>" class="btn btn-danger btn-lg">
                <i class="bi bi-arrow-left-circle me-2"></i> Regresar
            </a>
        </div>
    
    <main>
        <h2>Lista de Candidatos</h2>
        <button id="addCandidateBtn" class="btn">Agregar Candidato</button>
        <table id="candidatesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Biografía</th>
                    <th>Experiencia</th>
                    <th>Visión</th>
                    <th>Logros</th>
                    <th>Partido</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="candidateList">
                <!-- Se llenará dinámicamente -->
            </tbody>
        </table>
    </main>

    <!-- Modal para Crear/Editar Candidato -->
    <div id="addCandidateModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="modalTitle">Agregar Candidato</h2>
            <form id="candidateForm">
                <input type="hidden" id="candidateId" name="candidateId">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>

                <label for="party_id">Partido:</label>
                <select id="party_id" name="party_id" required>
                    <!-- Opciones llenadas dinámicamente -->
                </select>

                <label for="bio">Biografía:</label>
                <textarea id="bio" name="bio" required></textarea>

                <label for="experience">Experiencia:</label>
                <textarea id="experience" name="experience" required></textarea>

                <label for="vision">Visión:</label>
                <textarea id="vision" name="vision" required></textarea>

                <label for="achievements">Logros:</label>
                <textarea id="achievements" name="achievements" required></textarea>

                <label for="image">Imagen:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Guardar</button>
                <button type="button" id="closeModal" class="btn">Cancelar</button>
            </form>
        </div>
    </div>
    <div id="customAlert" class="alert hidden">
    <span id="alertMessage"></span>
</div>

    <script src="candidatos_admin.js"></script>
</body>
</html>
