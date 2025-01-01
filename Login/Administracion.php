<?php
session_start();
$navbarConfigPath = "navbar_config.json";

// Crear el archivo si no existe
if (!file_exists($navbarConfigPath)) {
    $defaultNavbarConfig = [
        "navbarBgColor" => "#00bfff"
    ];
    file_put_contents($navbarConfigPath, json_encode($defaultNavbarConfig, JSON_PRETTY_PRINT));
}

// Leer la configuración del Navbar
$navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
$navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff';
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
    <script src="../Login/administracion.js"></script>

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
            background-color: #FF66CC;
            /* Rosa vibrante */
            color: white;
        }

        .accordion-button:not(.collapsed) {
            background-color: #00BFFF;
            /* Azul brillante */
            color: white;
        }

        .accordion-body {
            background-color: #ffffff;
            /* Fondo blanco para contenido */
            color: #333333;
            /* Texto gris oscuro */
        }

        .btn-dark {
            background-color: #FF66CC;
            /* Rosa vibrante */
            border: none;
        }

        .btn-dark:hover {
            background-color: #FF1493;
            /* Rosa intenso */

        }

        :root {
            --navbar-bg-color: <?php echo $navbarBgColor; ?>;
        }

        /* Colores tomados de la imagen */
        .header-text {
            color: #00CFFF;
            /* Azul claro */
            font-weight: bold;
        }

        .secondary-text {
            color: #FF00FF;
            /* Rosa */
        }

        /* Estilo para el botón "Aceptar" */
        .btn-aceptar {
            background-color: #FF00FF;
            /* Rosa */
            color: white;
            border: none;
        }

        .btn-aceptar:hover {
            background-color: #e600e6;
            /* Tonalidad más oscura de rosa */
            color: white;
        }

        /* Estilo para el botón "Cancelar" */
        .btn-cancelar {
            background-color: #00CFFF;
            /* Celeste */
            color: white;
            border: none;
        }

        .btn-cancelar:hover {
            background-color: #009ac7;
            /* Tonalidad más oscura de celeste */
            color: white;
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
                            <!-- Distribución en dos columnas -->
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <!-- Cambiar colores Navbar -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Navbar General</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formNavbar">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorNavbar" name="colorNavbar" value="#00bfff">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorNavbar" name="hexColorNavbar" value="#00bfff" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="resetNavbar" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Cambiar colores Candidatos -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Sección Candidatos</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_color_candidatos.php" method="POST" id="formInicioCandidatos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorCandidatos" name="colorCandidatos" value="#000000">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorCandidatos" name="hexColorCandidatos" placeholder="#000000" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="resetCandidatos" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Cambiar colores Propuestas -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Sección Propuestas</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_color_propuestas.php" method="POST" id="formInicioPropuestas">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPropuestas" name="colorPropuestas" value="#4d0a0a">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPropuestas" name="hexColorPropuestas" placeholder="#4d0a0a" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="resetPropuestas" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Cambiar colores Eventos y Noticias -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Sección Eventos y Noticias</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_color_eventos.php" method="POST" id="formInicioEventos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorEventosNoticias" name="colorEventosNoticias" value="#FF9800">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorEventosNoticias" name="hexColorEventosNoticias" placeholder="#FF9800" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="resetEventosNoticias" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Página Login -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Login</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar degradado:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formLogin">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center me-4">
                                                    <label for="gradientStartLogin" class="form-label me-2" style="white-space: nowrap;">Color Inicial:</label>
                                                    <input type="color" class="form-control form-control-color me-2" id="gradientStartLogin" name="gradientStartLogin" value="#FF007B" style="width: 50px; height: 40px;">
                                                    <input type="text" class="form-control form-control-hex" id="hexGradientStartLogin" name="hexGradientStartLogin" placeholder="#FF007B" maxlength="7" style="width: 80px;">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label for="gradientEndLogin" class="form-label me-2" style="white-space: nowrap;">Color Final:</label>
                                                    <input type="color" class="form-control form-control-color me-2" id="gradientEndLogin" name="gradientEndLogin" value="#1C9FFF" style="width: 50px; height: 40px;">
                                                    <input type="text" class="form-control form-control-hex" id="hexGradientEndLogin" name="hexGradientEndLogin" placeholder="#1C9FFF" maxlength="7" style="width: 80px;">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center mt-3">
                                                <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                    Aceptar
                                                </button>
                                                <button type="submit" name="reset" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                    Restablecer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <!-- Página Candidatos -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Página Candidatos</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formInicioCandidatos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagCandidatos" name="colorCandidatos" value="#FF5733">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagCandidatos" name="hexColorCandidatos" placeholder="#FF5733" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="resetCandidatos" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Página Eventos y Noticias -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Página Eventos y Noticias</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formInicioEventos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagEventos" name="colorPagEventos" value="#33FF58">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagEventos" name="hexColorPagEventos" placeholder="#33FF58" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="reset-pagina-eventos-noticias" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Página Propuestas -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Página Propuestas</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formInicioPropuestas">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagPropuestas" name="colorPagPropuestas" value="#337BFF">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagPropuestas" name="hexColorPagPropuestas" placeholder="#337BFF" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="reset-pagina-propuestas" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Página Sugerencias -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Página Sugerencias</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formInicioSugerencias">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagSugerencias" name="colorPagSugerencias" value="#FF33A1">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagSugerencias" name="hexColorPagSugerencias" placeholder="#FF33A1" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="reset-pagina-sugerencias" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Página Votos -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Página Votos</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores.php" method="POST" id="formInicioVotos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagVotos" name="colorPagVotos" value="#FF9A33">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagVotos" name="colorPagVotos" placeholder="#FF9A33" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="reset-pagina-votos" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-left: 10px; transition: transform 0.3s;">
                                                        Restablecer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    <!-- Logo Navbar -->
    <div class="mb-3">
        <label for="logoNavbar" class="form-label header-text">Logo Navbar:</label>
        <input type="file" class="form-control" id="logoNavbar" name="logoNavbar">
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-aceptar">Aceptar</button>
        <button type="submit" name="reset" value="logoNavbar" class="btn btn-warning">Restablecer</button>
    </div>
</form>

<!-- Formulario para actualizar Slide 1 -->
<form action="imagenes_slides.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="slide1" class="form-label header-text">Actualizar Imagen Slide 1:</label>
        <input type="file" class="form-control" id="slide1" name="slideImage">
        <input type="hidden" name="section_name" value="slide1">
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-aceptar">Aceptar</button>
        <button type="submit" name="reset" value="slide1" class="btn btn-warning">Restablecer</button>
    </div>
</form>

<hr>

<!-- Formulario para actualizar Slide 5 -->
<form action="imagenes_slides.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="slide5" class="form-label header-text">Actualizar Imagen Slide 2:</label>
        <input type="file" class="form-control" id="slide5" name="slideImage">
        <input type="hidden" name="section_name" value="slide5">
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-aceptar">Aceptar</button>
        <button type="submit" name="reset" value="slide5" class="btn btn-warning">Restablecer</button>
    </div>
</form>

</div>

</div>
</form>
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
            <script>
                document.addEventListener("DOMContentLoaded", function() {
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
```