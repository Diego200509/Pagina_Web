<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerencias y Votación para Candidato a Rector</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
        }
        h1, h2 {
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="C:\xamp2\htdocs\Pagina_Web\Pagina_Web\Home\Main\Img\logo.png" alt="UTA Logo"> 
            <h1>Proceso de Elecciones UTA 2024</h1>
        </div>
        <nav>
            <a href="#"><i class="fas fa-home"></i> Inicio</a>
            <a href="#"><i class="fas fa-user"></i> Candidatos</a>
            <a href="#"><i class="fas fa-bullhorn"></i> Propuestas</a>
            <a href="#"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a>
            <a href="#"><i class="fas fa-comment-dots"></i> Sugerencias</a>
        </nav>
    </header>

    <div class="container">
        <h1>Sugerencias y Votación para Candidato a Rector</h1>
        
        <h2>Instrucciones:</h2>
        <p>Por favor, proporcione sus sugerencias o comentarios sobre los candidatos a rector. También puede votar por su candidato preferido a continuación.</p>
        
        <!-- Sugerencias Generales -->
        <h2>Sugerencias Generales</h2>
        <textarea class="input-field textarea" placeholder="Escriba aquí sus sugerencias generales..."></textarea>
        
        <!-- Comentarios por Candidato -->
         <!-- Comentarios por Candidato -->
<h2>Comentarios por Candidato</h2>

<div class="candidate-comments">
    <div>
        <label for="candidate1">Candidato 1: [Nombre del candidato]</label>
        <textarea id="candidate1" class="input-field textarea" placeholder="Escriba sus comentarios sobre este candidato..."></textarea>
    </div>
    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg" alt="Foto Candidato 1">
</div>

<div class="candidate-comments">
    <div>
        <label for="candidate2">Candidato 2: [Nombre del candidato]</label>
        <textarea id="candidate2" class="input-field textarea" placeholder="Escriba sus comentarios sobre este candidato..."></textarea>
    </div>
    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg" alt="Foto Candidato 2">
</div>
<div class="candidate-comments">
    <div>
        <label for="candidate3">Candidato 3: [Nombre del candidato]</label>
        <textarea id="candidate3" class="input-field textarea" placeholder="Escriba sus comentarios sobre este candidato..."></textarea>
    </div>
    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg" alt="Foto Candidato 2">
</div>

    <!-- Propuestas de mejora -->
    <h2>Propuestas de Mejora</h2>
    <textarea class="input-field textarea" placeholder="¿Tiene alguna sugerencia adicional para mejorar el proceso de selección o los criterios para elegir al próximo rector?"></textarea>

    <!-- Botón de Enviar Sugerencias -->
    <div class="form-row">
        <button class="button" onclick="submitSuggestions()">Enviar Sugerencias</button>
    </div>
          <!-- Botón para Mostrar Opciones de Voto -->
          <button class="button" onclick="toggleVoteOptions()">Votar por un candidato</button>
        </div>
    
        <!-- Sección de Votación -->
        <div id="vote-container" class="vote-container">
            <!-- Datos del Votante -->
            <h2>Datos del Votante</h2>
            <div class="form-row">
                <input type="text" id="username" class="input-field small-input" placeholder="Nombre de usuario">
                <input type="email" id="email" class="input-field small-input" placeholder="Correo electrónico">
            </div>
    
            <!-- Sección de votación con imágenes -->
            <h2>Votar por un Candidato</h2>
            <p>Seleccione su candidato preferido:</p>
    
            <div class="form-row">
                <div class="candidate-option">
                    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg" alt="Foto Candidato 1">
                    <input type="radio" id="vote1" name="vote" value="Candidato 1">
                    <P><label for="vote1">[Nombre del candidato]</label></P>
                </div>
                <div class="candidate-option">
                    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg" alt="Foto Candidato 2">
                    <input type="radio" id="vote2" name="vote" value="Candidato 2">
                <p><label for="vote2">[Nombre del candidato]</label></p>
                </div>
                <div class="candidate-option">
                    <img src="C:\Users\ACER_2023\Downloads\perfil.jpg"   alt="Foto Candidato 3">
                    <input type="radio" id="vote3" name="vote" value="Candidato 3">
                <p><label for="vote3">[Nombre del candidato]</label></p>
                </div>
            </div>
<script>
    function confirmarVoto() {
        var votoSeleccionado = document.querySelector('input[name="voto"]:checked');
        if (votoSeleccionado) {
            alert("Has votado por: " + votoSeleccionado.value);
            return true;
        } else {
            alert("Por favor, selecciona un candidato antes de votar.");
            return false;
        }
    }
</script>
    </div>
</body>
</html>
