<?php
// Manejo de la lógica del backend aquí, si es necesario
// Esto puede incluir validaciones o configuraciones específicas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Candidatos</title>
    <link rel="stylesheet" href="candidatos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h1>Gestión de Candidatos</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php">Inicio</a>
            <a href="../Candidatos/candidatos.php">Candidatos</a>
            <a href="../Propuestas/Propuestas.php">Propuestas</a>
            <a href="../Eventos_Noticias/eventos_noticias.php">Eventos y Noticias</a>
            <a href="../Sugerencias/index.php">Sugerencias</a>
        </nav>
    </header>
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

    <script src="candidatos_admin.js"></script>
</body>
</html>
