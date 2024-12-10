<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuestas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="estilosPropuestas.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="../Home/inicio.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="../Candidatos/candidatos.php"><i class="fas fa-user"></i> Candidatos</a>
            <a href="../Propuestas/Propuestas.php"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href='../Eventos_Noticias/eventos_noticias.php'><i class="fas fa-calendar-alt"></i> Eventos y
                Noticias</a>
            <a href="../Sugerencias/index.php"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

    <div class="container">
        <h2>Propuestas de los Partidos Políticos</h2>

        <div class="filter-box">
            <label for="faculty">Filtrar por Facultad o Interés:</label>
            <select id="faculty" onchange="filterProposals()">
                <!-- Facultades actualizadas -->
                <option value="all">Mostrar Todas</option>
                <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                <option value="Ciencia e Ingeniería en Alimentos">Ciencia e Ingeniería en Alimentos</option>
                <option value="Jurisprudencia y Ciencias Sociales">Jurisprudencia y Ciencias Sociales</option>
                <option value="Contabilidad y Auditoría">Contabilidad y Auditoría</option>
                <option value="Ciencias Humanas y de la Educación">Ciencias Humanas y de la Educación</option>
                <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                <option value="Ingeniería Civil y Mecánica">Ingeniería Civil y Mecánica</option>
                <option value="Ingeniería en Sistemas, Electrónica e Industrial">Ingeniería en Sistemas, Electrónica e
                    Industrial</option>
                <!-- Intereses adicionales -->
                <option value="infraestructura">Infraestructura</option>
                <option value="deportes">Deportes</option>
                <option value="cultura">Cultura</option>
                <option value="investigación">Investigación</option>
                <option value="vinculación">Vinculación con la Sociedad</option>
            </select>
        </div>

        <div class="proposals-grid" id="proposalsGrid">
            <!-- Propuesta Partido 1 -->
            <div class="proposal-card" id="proposalCandidato1">
                <h3 id="partido1Nombre"></h3> <!-- Aquí se mostrará el nombre del partido 1 -->
                <div id="candidato1Description"></div>
            </div>

            <!-- Propuesta Partido 2 -->
            <div class="proposal-card" id="proposalCandidato2">
                <h3 id="partido2Nombre"></h3> <!-- Aquí se mostrará el nombre del partido 2 -->
                <div id="candidato2Description"></div>
            </div>
        </div>
    </div>

    <footer>
        Derechos reservados UTA 2024
    </footer>

    <script src="scriptsPropuestas.js"></script>
</body>

</html>