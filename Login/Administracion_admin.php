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
function cargarEstilo($archivo, $default)
{
    if (file_exists($archivo)) {
        $config = json_decode(file_get_contents($archivo), true);
        return $config['bgColor'] ?? $default;
    }
    return $default;
}

$candidatosColor = cargarEstilo('../Login/candidatos_config.json', '#000000');
$propuestasColor = cargarEstilo('../Login/propuestas_config.json', '#4d0a0a');
$eventosColor = cargarEstilo('../Login/eventos_config.json', '#FF9800');

// Leer la configuración del Navbar
$navbarConfig = json_decode(file_get_contents($navbarConfigPath), true);
$navbarBgColor = $navbarConfig['navbarBgColor'] ?? '#00bfff';

$config = json_decode(file_get_contents("styles_config.json"), true);
$gradientStartLogin = $config['gradientStartLogin'] ?? "#FF007B";
$gradientEndLogin = $config['gradientEndLogin'] ?? "#1C9FFF";




$configFileEventos = "../Login/PaginaEventos.json";

if (file_exists($configFileEventos)) {
    $config = json_decode(file_get_contents($configFileEventos), true);
    $paginaEventosBgColor = $config['paginaEventosBgColor'] ?? "#f4f4f4";
} else {
    $paginaEventosBgColor = "#f4f4f4";
    
}

$configFileCandidatos = "../Login/PaginaCandidatos.json";

if (file_exists($configFileCandidatos)) {
    $config = json_decode(file_get_contents($configFileCandidatos), true);
    $paginaCandidatosBgColor = $config['paginaCandidatosBgColor'] ?? "#f4f4f4";
} else {
    $paginaCandidatosBgColor = "#f4f4f4";
}


// Ruta al archivo JSON de configuración de colores
$configFile = "../Login/PaginaPropuestas.json";

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    $paginaPropuestasBgColor = $config['paginaPropuestasBgColor'] ?? "#000000"; // Color blanco por defecto
} else {
    $paginaPropuestasBgColor = "#000000"; // Color blanco por defecto si no existe el archivo
}


$configFileSugerencias = "../Login/PaginaSugerencias.json";
if (file_exists($configFileSugerencias)) {
    $config = json_decode(file_get_contents($configFileSugerencias), true);
    $paginaSugerenciasBgColor = $config['paginaSugerenciasBgColor'] ?? "#a1c4fd";
} else {
    $paginaSugerenciasBgColor = "#a1c4fd";
}


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
        :root {
        --navbar-bg-color: <?php echo $navbarBgColor; ?>;
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
                <h6 class="mt-2">Admin</h6>
            </div>
            <!-- Logo existente -->
            <img src="<?php echo htmlspecialchars($logo_path); ?>"  width="200px" style="margin-right: 20px;">



        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
            <li><a href="../Login/Administracion_admin.php"><i class="fa-solid fa-cogs"></i> <span>Administración</span></a></li>
            <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
        </ul>
    </nav>

    <div class="container mt-5">
        <div class="accordion" id="adminAccordion">
            <!-- Crear Admin -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#adminAccordion">
                        <div class="accordion-body">
                            <div class="mb-3">
                            </div>
                            <div class="mb-3">
                            </div>
                            <div class="mb-3">
                            </div>
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
                        <form action="cambiar_colores_admin.php" method="POST">
                            <!-- Distribución en dos columnas -->
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <!-- Cambiar colores Navbar -->
                                    <div class="color-section mb-4">
                                        <h5 class="text-uppercase" style="color: #00BFFF;">Colores Generales</h5>
                                        <p class="subtitle" style="color: #FF69B4;">Seleccionar color:</p>
                                        <form action="cambiar_colores_admin.php" method="POST" id="formNavbar">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorNavbar" name="colorNavbar" value="<?php echo htmlspecialchars($navbarBgColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorNavbar" name="hexColorNavbar" value="<?php echo htmlspecialchars($navbarBgColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_color_candidatos_admin.php" method="POST" id="formInicioCandidatos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorCandidatos" name="colorCandidatos" value="<?php echo htmlspecialchars($candidatosColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorCandidatos" name="hexColorCandidatos" value="<?php echo htmlspecialchars($candidatosColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_color_propuestas_admin.php" method="POST" id="formInicioPropuestas">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPropuestas" name="colorPropuestas" value="<?php echo htmlspecialchars($propuestasColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPropuestas" name="hexColorPropuestas" value="<?php echo htmlspecialchars($propuestasColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_color_eventos_admin.php" method="POST" id="formInicioEventos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorEventosNoticias" name="colorEventosNoticias" value="<?php echo htmlspecialchars($eventosColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorEventosNoticias" name="hexColorEventosNoticias" value="<?php echo htmlspecialchars($eventosColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_colores_admin.php" method="POST" id="formLogin">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center me-4">
                                                    <label for="gradientStartLogin" class="form-label me-2" style="white-space: nowrap;">Color Inicial:</label>
                                                    <input type="color" class="form-control form-control-color me-2" id="gradientStartLogin" name="gradientStartLogin" value="<?php echo htmlspecialchars($gradientStartLogin); ?>" style="width: 50px; height: 40px;">
                                                    <input type="text" class="form-control form-control-hex" id="hexGradientStartLogin" name="hexGradientStartLogin" value="<?php echo htmlspecialchars($gradientStartLogin); ?>" maxlength="7" style="width: 80px;">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label for="gradientEndLogin" class="form-label me-2" style="white-space: nowrap;">Color Final:</label>
                                                    <input type="color" class="form-control form-control-color me-2" id="gradientEndLogin" name="gradientEndLogin" value="<?php echo htmlspecialchars($gradientEndLogin); ?>" style="width: 50px; height: 40px;">
                                                    <input type="text" class="form-control form-control-hex" id="hexGradientEndLogin" name="hexGradientEndLogin" value="<?php echo htmlspecialchars($gradientEndLogin); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_colores_admin.php" method="POST" id="formInicioCandidatos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagCandidatos" name="colorPagCandidatos" value="<?php echo htmlspecialchars($paginaCandidatosBgColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagCandidatos" name="hexColorCandidatos" value="<?php echo htmlspecialchars($paginaCandidatosBgColor); ?>" maxlength="7" style="width: 80px;">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn" style="background-color: #00BFFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: transform 0.3s;">
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="reset-pagina-candidatos" value="1" class="btn" style="background-color: #FF69B4; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; transition: transform 0.3s;">
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
                                        <form action="cambiar_colores_admin.php" method="POST" id="formInicioEventos">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagEventos" name="colorPagEventos" value="<?php echo htmlspecialchars($paginaEventosBgColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagEventos" name="hexColorPagEventos" value="<?php echo htmlspecialchars($paginaEventosBgColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_colores_admin.php" method="POST" id="formInicioPropuestas">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagPropuestas" name="colorPagPropuestas" value="<?php echo htmlspecialchars($paginaPropuestasBgColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagPropuestas" name="hexColorPagPropuestas" value="<?php echo htmlspecialchars($paginaPropuestasBgColor); ?>" maxlength="7" style="width: 80px;">
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
                                        <form action="cambiar_colores_admin.php" method="POST" id="formInicioSugerencias">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <input type="color" class="form-control form-control-color me-3" id="colorPagSugerencias" name="colorPagSugerencias" value="<?php echo htmlspecialchars($paginaSugerenciasBgColor); ?>">
                                                <input type="text" class="form-control form-control-hex me-3" id="hexColorPagSugerencias" name="hexColorPagSugerencias" value="<?php echo htmlspecialchars($paginaSugerenciasBgColor); ?>" maxlength="7" style="width: 80px;">
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
                        Cambiar Imágenes Inicio
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#adminAccordion">
                    <div class="accordion-body">
                        <form action="subir_imagen_admin.php" method="POST" enctype="multipart/form-data">
                            <!-- Logo Navbar -->
                            <div class="mb-3">
                                <label for="logoNavbar" class="form-label header-text">Logo Navbar:</label>
                                <input type="file" class="form-control" id="logoNavbar" name="logoNavbar">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-aceptar">Aceptar</button>
                                <button type="submit" name="reset" value="logoNavbar" class="btn btn-warning" style="background-color: #00BFFF; color: white; border: 2px solid #FF69B4;">Restablecer</button>
                            </div>
                        </form>

                        <!-- Formulario para actualizar Slide 1 -->
                        <form action="imagenes_slides_admin.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="slide1" class="form-label header-text">Actualizar Imagen Slide 1:</label>
                                <input type="file" class="form-control" id="slide1" name="slideImage">
                                <input type="hidden" name="section_name" value="slide1">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-aceptar">Aceptar</button>
                                <button type="submit" name="reset" value="slide1" class="btn btn-warning" style="background-color: #00BFFF; color: white; border: 2px solid #FF69B4;">Restablecer</button>
                            </div>
                        </form>

                        <hr>

                        <!-- Formulario para actualizar Slide 5 -->
                        <form action="imagenes_slides_admin.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="slide5" class="form-label header-text">Actualizar Imagen Slide 2:</label>
                                <input type="file" class="form-control" id="slide5" name="slideImage">
                                <input type="hidden" name="section_name" value="slide5">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-aceptar">Aceptar</button>
                                <button type="submit" name="reset" value="slide5" class="btn btn-warning" style="background-color: #00BFFF; color: white; border: 2px solid #FF69B4;">Restablecer</button>
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


        document.addEventListener("DOMContentLoaded", function() {
            const colorInputCandidatos = document.getElementById("colorCandidatos");
            const hexInputCandidatos = document.getElementById("hexColorCandidatos");
            const defaultColorCandidatos = "#000000";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputCandidatos.addEventListener("input", function() {
                hexInputCandidatos.value = colorInputCandidatos.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputCandidatos.addEventListener("input", function() {
                const value = hexInputCandidatos.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputCandidatos.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioCandidatos = document.getElementById("formInicioCandidatos");
            formInicioCandidatos.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "resetCandidatos" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputCandidatos.value = defaultColorCandidatos;
                    hexInputCandidatos.value = defaultColorCandidatos;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("candidatosColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("candidatosColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("candidatosColorUpdated");
                }, 1000);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPropuestas = document.getElementById("colorPropuestas");
            const hexInputPropuestas = document.getElementById("hexColorPropuestas");
            const defaultColorPropuestas = "#4d0a0a";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPropuestas.addEventListener("input", function() {
                hexInputPropuestas.value = colorInputPropuestas.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPropuestas.addEventListener("input", function() {
                const value = hexInputPropuestas.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPropuestas.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPropuestas = document.getElementById("formInicioPropuestas");
            formInicioPropuestas.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "resetPropuestas" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPropuestas.value = defaultColorPropuestas;
                    hexInputPropuestas.value = defaultColorPropuestas;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("propuestasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("propuestasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("propuestasColorUpdated");
                }, 1000);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const colorInputEventosNoticias = document.getElementById("colorEventosNoticias");
            const hexInputEventosNoticias = document.getElementById("hexColorEventosNoticias");
            const defaultColorEventosNoticias = "#FF9800";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputEventosNoticias.addEventListener("input", function() {
                hexInputEventosNoticias.value = colorInputEventosNoticias.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputEventosNoticias.addEventListener("input", function() {
                const value = hexInputEventosNoticias.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputEventosNoticias.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioEventos = document.getElementById("formInicioEventos");
            formInicioEventos.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "resetEventosNoticias" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputEventosNoticias.value = defaultColorEventosNoticias;
                    hexInputEventosNoticias.value = defaultColorEventosNoticias;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("eventosNoticiasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("eventosNoticiasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("eventosNoticiasColorUpdated");
                }, 1000);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPagSugerencias = document.getElementById("colorPagSugerencias");
            const hexInputPagSugerencias = document.getElementById("hexColorPagSugerencias");
            const defaultColorPagSugerencias = "#FF33A1";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPagSugerencias.addEventListener("input", function() {
                hexInputPagSugerencias.value = colorInputPagSugerencias.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPagSugerencias.addEventListener("input", function() {
                const value = hexInputPagSugerencias.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPagSugerencias.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPagSugerencias = document.getElementById("formInicioSugerencias");
            formInicioPagSugerencias.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "reset-pagina-sugerencias" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPagSugerencias.value = defaultColorPagSugerencias;
                    hexInputPagSugerencias.value = defaultColorPagSugerencias;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("paginaSugerenciasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("paginaSugerenciasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("paginaSugerenciasColorUpdated");
                }, 1000);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPagPropuestas = document.getElementById("colorPagPropuestas");
            const hexInputPagPropuestas = document.getElementById("hexColorPagPropuestas");
            const defaultColorPagPropuestas = "#337BFF";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPagPropuestas.addEventListener("input", function() {
                hexInputPagPropuestas.value = colorInputPagPropuestas.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPagPropuestas.addEventListener("input", function() {
                const value = hexInputPagPropuestas.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPagPropuestas.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPagPropuestas = document.getElementById("formInicioPropuestas");
            formInicioPagPropuestas.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "reset-pagina-propuestas" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPagPropuestas.value = defaultColorPagPropuestas;
                    hexInputPagPropuestas.value = defaultColorPagPropuestas;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("paginaPropuestasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("paginaPropuestasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("paginaPropuestasColorUpdated");
                }, 1000);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPagEventos = document.getElementById("colorPagEventos");
            const hexInputPagEventos = document.getElementById("hexColorPagEventos");
            const defaultColorPagEventos = "#33FF58";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPagEventos.addEventListener("input", function() {
                hexInputPagEventos.value = colorInputPagEventos.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPagEventos.addEventListener("input", function() {
                const value = hexInputPagEventos.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPagEventos.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPagEventos = document.getElementById("formInicioEventos");
            formInicioPagEventos.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "reset-pagina-eventos-noticias" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPagEventos.value = defaultColorPagEventos;
                    hexInputPagEventos.value = defaultColorPagEventos;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("paginaEventosNoticiasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("paginaEventosNoticiasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("paginaEventosNoticiasColorUpdated");
                }, 1000);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPagCandidatos = document.getElementById("colorPagCandidatos");
            const hexInputPagCandidatos = document.getElementById("hexColorPagCandidatos");
            const defaultColorPagCandidatos = "#FF5733";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPagCandidatos.addEventListener("input", function() {
                hexInputPagCandidatos.value = colorInputPagCandidatos.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPagCandidatos.addEventListener("input", function() {
                const value = hexInputPagCandidatos.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPagCandidatos.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPagCandidatos = document.getElementById("formInicioCandidatos");
            formInicioPagCandidatos.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "reset-pagina-candidatos" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPagCandidatos.value = defaultColorPagCandidatos;
                    hexInputPagCandidatos.value = defaultColorPagCandidatos;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("paginaCandidatosColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("paginaCandidatosColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("paginaCandidatosColorUpdated");
                }, 1000);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const colorInputPropuestas = document.getElementById("colorPropuestas");
            const hexInputPropuestas = document.getElementById("hexColorPropuestas");
            const defaultColorPropuestas = "#4d0a0a";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputPropuestas.addEventListener("input", function() {
                hexInputPropuestas.value = colorInputPropuestas.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputPropuestas.addEventListener("input", function() {
                const value = hexInputPropuestas.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputPropuestas.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioPropuestas = document.getElementById("formInicioPropuestas");
            formInicioPropuestas.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "resetPropuestas" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputPropuestas.value = defaultColorPropuestas;
                    hexInputPropuestas.value = defaultColorPropuestas;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("propuestasColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("propuestasColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("propuestasColorUpdated");
                }, 1000);
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const colorInputCandidatos = document.getElementById("colorCandidatos");
            const hexInputCandidatos = document.getElementById("hexColorCandidatos");
            const defaultColorCandidatos = "#000000";

            // Sincronizar el campo de texto hexadecimal con el selector de color
            colorInputCandidatos.addEventListener("input", function() {
                hexInputCandidatos.value = colorInputCandidatos.value;
            });

            // Sincronizar el selector de color con el campo de texto hexadecimal
            hexInputCandidatos.addEventListener("input", function() {
                const value = hexInputCandidatos.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    colorInputCandidatos.value = value;
                }
            });

            // Manejar el evento del formulario
            const formInicioCandidatos = document.getElementById("formInicioCandidatos");
            formInicioCandidatos.addEventListener("submit", function(event) {
                const submitter = event.submitter;

                if (submitter.name === "resetCandidatos" && submitter.value === "1") {
                    // Restablecer el color al valor por defecto
                    colorInputCandidatos.value = defaultColorCandidatos;
                    hexInputCandidatos.value = defaultColorCandidatos;

                    // Guardar en localStorage que se ha restablecido
                    localStorage.setItem("candidatosColorUpdated", "reset");
                } else {
                    // Guardar que el color ha sido actualizado
                    localStorage.setItem("candidatosColorUpdated", "true");
                }

                // Limpiar el estado en el localStorage después de 1 segundo
                setTimeout(() => {
                    localStorage.removeItem("candidatosColorUpdated");
                }, 1000);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const colorInput = document.getElementById("colorNavbar");
            const hexInput = document.getElementById("hexColorNavbar");

            // Actualizar el input hexadecimal cuando cambie el cuadro de color
            colorInput.addEventListener("input", function() {
                hexInput.value = colorInput.value;
            });

            // Actualizar el cuadro de color cuando cambie el input hexadecimal
            hexInput.addEventListener("input", function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(hexInput.value)) {
                    colorInput.value = hexInput.value;
                }
            });
        });
    </script>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
```