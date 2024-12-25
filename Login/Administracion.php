<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="administrador_estilos.css">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(160deg, #ffffff, #1C9FFF);
            background-size: cover;
            transition: background 0.5s ease-in-out;
        }
        .accordion-button {
            background-color: #FF66CC; /* Rosa vibrante */
            color: white;
        }

        .accordion-button:not(.collapsed) {
            background-color: #00BFFF; /* Azul brillante */
            color: white;
        }

        .accordion-body {
            background-color: #ffffff; /* Fondo blanco para contenido */
            color: #333333; /* Texto gris oscuro */
        }

        .btn-dark {
            background-color: #FF66CC; /* Rosa vibrante */
            border: none;
        }

        .btn-dark:hover {
            background-color: #FF1493; /* Rosa intenso */
        
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
    <div class="navbar-logo">
    <div class="text-center">
        <!-- Icono SuperAdmin existente -->
        <i class="fa-solid fa-user-shield fa-2x"></i>
        <h6 class="mt-2">SuperAdmin</h6>
    </div>
    <!-- Logo existente -->
    <img src="../Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">
        <!-- Nuevo icono de Inicio -->
        <div class="text-center" style="cursor: pointer;" onclick="window.location.href='../Login/superadmin_dasboard.php'">
        <i class="fa-solid fa-house fa-2x house-icon"></i>
    </div>
</div>



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

    <div class="container mt-5">
        <div class="accordion" id="adminAccordion">
            <!-- Crear Admin -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Crear Admin
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <form action="../src/crear_usuario_queries.php" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del nuevo admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@ejemplo.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                            </div>
                            <button type="submit" class="btn btn-dark">Crear Admin</button>
                        </form>
                        
                    </div>
                </div>
                
            </div>

            <!-- Cambiar Colores -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Cambiar Colores
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <form action="cambiar_colores.php" method="POST">
                            <div class="mb-3">
                                <label for="colorPrimario" class="form-label">Color Primario</label>
                                <input type="color" class="form-control form-control-color" id="colorPrimario" name="colorPrimario" value="#800000">
                            </div>
                            <div class="mb-3">
                                <label for="colorSecundario" class="form-label">Color Secundario</label>
                                <input type="color" class="form-control form-control-color" id="colorSecundario" name="colorSecundario" value="#B00000">
                            </div>
                            <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Subir Imágenes -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Subir Imágenes
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <form action="subir_imagen.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" required>
                            </div>
                            <button type="submit" class="btn btn-dark">Subir Imagen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- MODAL MENSAJES -->
                <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i id="messageIcon" class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <h4 class="modal-title mb-2" id="messageModalLabel"></h4>
                <p id="messageText"></p>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>



    <!-- JavaScript para cambiar fondo -->
    <!-- JavaScript para cambiar fondo -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const adminAccordion = document.getElementById("adminAccordion");
            const body = document.body;
            const originalBackground = "linear-gradient(160deg, #ffffff, #1C9FFF)";

            adminAccordion.addEventListener("click", (event) => {
                if (event.target.matches(".accordion-button")) {
                    const button = event.target;

                    // Cambia el fondo según la sección seleccionada
                    if (button.innerText.includes("Crear Admin")) {
                        body.style.background = "linear-gradient(160deg, #00BFFF, #87CEFA)";

                    } else if (button.innerText.includes("Cambiar Colores")) {
                        body.style.background = "linear-gradient(160deg, #FF66CC, #FF1493)";
                    } else if (button.innerText.includes("Subir Imágenes")) {
                        body.style.background = "linear-gradient(355deg, #FF66CC,rgb(255, 255, 255))";
                    }

                    // Si todas las secciones están contraídas, vuelve al fondo original
                    const allCollapsed = [...document.querySelectorAll(".accordion-button")].every((btn) =>
                        btn.classList.contains("collapsed")
                    );

                    if (allCollapsed) {
                        body.style.background = originalBackground;
                    }
                }
            });
        });

    document.addEventListener("DOMContentLoaded", function () {
        const sessionMessage = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
        const sessionMessageType = "<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : ''; ?>";

        if (sessionMessage) {
            const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            const messageIcon = document.getElementById('messageIcon');
            const messageModalLabel = document.getElementById('messageModalLabel');
            const messageText = document.getElementById('messageText');

            if (sessionMessageType === 'success') {
                messageIcon.classList.add('text-success', 'fa-check-circle');
                messageModalLabel.textContent = '¡Éxito!';
            } else if (sessionMessageType === 'error') {
                messageIcon.classList.add('text-danger', 'fa-times-circle');
                messageModalLabel.textContent = '¡Error!';
            }

            messageText.textContent = sessionMessage;
            messageModal.show();

            <?php unset($_SESSION['message']); ?>
            <?php unset($_SESSION['message_type']); ?>
        }
    });
</script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
