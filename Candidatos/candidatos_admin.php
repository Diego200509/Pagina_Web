<?php
// Manejo de la lógica del backend aquí, si es necesario
// Esto puede incluir validaciones o configuraciones específicas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos</title>
    <link rel="stylesheet" href="candidatos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo"> <!-- Cambié la ruta para que sea relativa -->
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
        <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php"><i class="fas fa-calendar-alt"></i> Eventos y
                Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

<body>
<div class="content">
    <div class="heart">&#10084;</div>
    <div class="text">
        <span class="pink">SUEÑA,</span><br>
        <span class="blue">CREA,</span><br>
        <span class="pink">INNOVA.</span>
    </div>
</div>
<!-- Botón para agregar candidatos -->
<button id="addCandidateBtn" class="add-candidate-btn">Agregar Candidato</button>

<!-- Modal para acciones CRUD -->
<div id="crudActionModal" class="modal">
    <div class="modal-content">
        <h2>¿Qué deseas hacer?</h2>
        <div class="button-group">
            <button id="createActionBtn" class="action-btn create">Crear Candidato</button>
            <button id="readActionBtn" class="action-btn read">Ver Candidatos</button>
            <button id="updateActionBtn" class="action-btn update">Actualizar Candidato</button>
            <button id="deleteActionBtn" class="action-btn delete">Eliminar Candidato</button>
        </div>
        <button id="closeCrudModal" class="close-btn">Cerrar</button>
    </div>
</div>
<div id="addCandidateModal" class="modal">
    <div class="modal-content">
        <h2>Agregar Candidato</h2>
        <form id="addCandidateForm" enctype="multipart/form-data">
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" placeholder="Ingrese el nombre del candidato" required>

            <label for="bio">Biografía:</label>
            <textarea name="bio" id="bio" placeholder="Escriba una breve biografía" required></textarea>

            <label for="experience">Experiencia:</label>
            <textarea name="experience" id="experience" placeholder="Describa la experiencia del candidato"></textarea>

            <label for="vision">Visión:</label>
            <textarea name="vision" id="vision" placeholder="Visión del candidato"></textarea>

            <label for="achievements">Logros:</label>
            <textarea name="achievements" id="achievements" placeholder="Logros principales"></textarea>

            <label for="party_id">Partido:</label>
            <select name="party_id" id="party_id" required>
                <option value="" disabled selected>Seleccione un partido</option>
                <!-- Aquí se llenarán dinámicamente las opciones de los partidos -->
            </select>

            <label for="image">Imagen del Candidato:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit" id="submitCandidateBtn">Crear Candidato</button>
        </form>
        <button id="closeAddCandidateModal" class="close-btn">Cerrar</button>
    </div>
</div>
    <!-- Indicador de carga -->
    <div id="loadingIndicator" class="loading" style="display: none;">Cargando...</div>

    <script src="candidatos_admin.js"></script>
</body>
</html>
