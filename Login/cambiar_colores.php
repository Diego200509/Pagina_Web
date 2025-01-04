<?php
include_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Función para guardar configuración en un archivo JSON
    function guardarConfiguracion($archivo, $datos, $mensajeExito, $mensajeError) {
        if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT))) {
            header("Location: ../Login/Administracion.php?success=1&message=" . urlencode($mensajeExito));
        } else {
            header("Location: ../Login/Administracion.php?success=0&message=" . urlencode($mensajeError));
        }
        exit;
    }

    // Manejo de Colores del Login
    $configFileLogin = "../Login/styles_config.json";
    if (!file_exists($configFileLogin)) {
        file_put_contents($configFileLogin, json_encode([
            "gradientStartLogin" => "#FF007B",
            "gradientEndLogin" => "#1C9FFF"
        ], JSON_PRETTY_PRINT));
    }

    // Restablecer colores del Login
    if (isset($_POST['reset']) && $_POST['reset'] == "1") {
        $defaultConfig = [
            "gradientStartLogin" => "#FF007B",
            "gradientEndLogin" => "#1C9FFF"
        ];
        guardarConfiguracion(
            $configFileLogin,
            $defaultConfig,
            "Los colores del login han sido restablecidos a los valores originales.",
            "No se pudieron restablecer los colores del login."
        );
    }

    // Actualizar colores del Login
    if (isset($_POST['gradientStartLogin']) && isset($_POST['gradientEndLogin'])) {
        $config = json_decode(file_get_contents($configFileLogin), true);
        $config['gradientStartLogin'] = $_POST['gradientStartLogin'];
        $config['gradientEndLogin'] = $_POST['gradientEndLogin'];
        guardarConfiguracion(
            $configFileLogin,
            $config,
            "Los colores del login se han actualizado correctamente.",
            "No se pudieron actualizar los colores del login."
        );
    }

    // Manejo del Navbar
    if (isset($_POST['resetNavbar']) && $_POST['resetNavbar'] == "1") {
        $defaultNavbarConfig = [
            "navbarBgColor" => "#00bfff"
        ];
        guardarConfiguracion(
            "navbar_config.json",
            $defaultNavbarConfig,
            "El color del Navbar ha sido restablecido a su valor original.",
            "No se pudieron restablecer los colores del Navbar."
        );
    }

    if (isset($_POST['colorNavbar'])) {
        $configFileNavbar = "navbar_config.json";

        if (!file_exists($configFileNavbar)) {
            $defaultConfig = ["navbarBgColor" => "#00bfff"];
            file_put_contents($configFileNavbar, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }

        $config = json_decode(file_get_contents($configFileNavbar), true);
        $config['navbarBgColor'] = $_POST['colorNavbar'];
        guardarConfiguracion(
            $configFileNavbar,
            $config,
            "El color del Navbar se ha actualizado correctamente.",
            "No se pudo actualizar el color del Navbar."
        );
    }

    // Página Eventos y Noticias
    $configFileEventos = "../Login/PaginaEventos.json";
    if (!file_exists($configFileEventos)) {
        file_put_contents($configFileEventos, json_encode(["paginaBgColor" => "#33FF58"], JSON_PRETTY_PRINT));
    }

    if (isset($_POST['reset-pagina-eventos-noticias']) && $_POST['reset-pagina-eventos-noticias'] == "1") {
        $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
        guardarConfiguracion(
            $configFileEventos,
            $defaultConfig,
            "El color de fondo de la Página Eventos y Noticias ha sido restablecido.",
            "No se pudo restablecer el color de fondo de la Página Eventos y Noticias."
        );
    }

    if (isset($_POST['colorPagEventos'])) {
        $config = json_decode(file_get_contents($configFileEventos), true);
        $config['paginaBgColor'] = $_POST['colorPagEventos'];
        guardarConfiguracion(
            $configFileEventos,
            $config,
            "El color de fondo de la Página Eventos y Noticias se ha actualizado correctamente.",
            "No se pudo actualizar el color de fondo de la Página Eventos y Noticias."
        );
    }

    // Página Candidatos
    $configFileCandidatos = "../Login/PaginaCandidatos.json";
    if (!file_exists($configFileCandidatos)) {
        file_put_contents($configFileCandidatos, json_encode(["paginaBgColor" => "#33FF58"], JSON_PRETTY_PRINT));
    }

    if (isset($_POST['reset-pagina-candidatos']) && $_POST['reset-pagina-candidatos'] == "1") {
        $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
        guardarConfiguracion(
            $configFileCandidatos,
            $defaultConfig,
            "El color de fondo de la Página Candidatos ha sido restablecido.",
            "No se pudo restablecer el color de fondo de la Página Candidatos."
        );
    }

    if (isset($_POST['colorPagCandidatos'])) {
        $config = json_decode(file_get_contents($configFileCandidatos), true);
        $config['paginaBgColor'] = $_POST['colorPagCandidatos'];
        guardarConfiguracion(
            $configFileCandidatos,
            $config,
            "El color de fondo de la Página Candidatos se ha actualizado correctamente.",
            "No se pudo actualizar el color de fondo de la Página Candidatos."
        );
    }

    // Página Propuestas
    $configFilePropuestas = "../Login/PaginaPropuestas.json";
    if (!file_exists($configFilePropuestas)) {
        file_put_contents($configFilePropuestas, json_encode(["paginaBgColor" => "#337BFF"], JSON_PRETTY_PRINT));
    }

    if (isset($_POST['reset-pagina-propuestas']) && $_POST['reset-pagina-propuestas'] == "1") {
        $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
        guardarConfiguracion(
            $configFilePropuestas,
            $defaultConfig,
            "El color de fondo de la Página Propuestas ha sido restablecido.",
            "No se pudo restablecer el color de fondo de la Página Propuestas."
        );
    }

    if (isset($_POST['colorPagPropuestas'])) {
        $config = json_decode(file_get_contents($configFilePropuestas), true);
        $config['paginaBgColor'] = $_POST['colorPagPropuestas'];
        guardarConfiguracion(
            $configFilePropuestas,
            $config,
            "El color de fondo de la Página Propuestas se ha actualizado correctamente.",
            "No se pudo actualizar el color de fondo de la Página Propuestas."
        );
    }

    // Página Sugerencias
    $configFileSugerencias = "../Login/PaginaSugerencias.json";
    if (!file_exists($configFileSugerencias)) {
        file_put_contents($configFileSugerencias, json_encode(["paginaBgColor" => "#FF33A1"], JSON_PRETTY_PRINT));
    }

    if (isset($_POST['reset-pagina-sugerencias']) && $_POST['reset-pagina-sugerencias'] == "1") {
        $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
        guardarConfiguracion(
            $configFileSugerencias,
            $defaultConfig,
            "El color de fondo de la Página Sugerencias ha sido restablecido.",
            "No se pudo restablecer el color de fondo de la Página Sugerencias."
        );
    }

    if (isset($_POST['colorPagSugerencias'])) {
        $config = json_decode(file_get_contents($configFileSugerencias), true);
        $config['paginaBgColor'] = $_POST['colorPagSugerencias'];
        guardarConfiguracion(
            $configFileSugerencias,
            $config,
            "El color de fondo de la Página Sugerencias se ha actualizado correctamente.",
            "No se pudo actualizar el color de fondo de la Página Sugerencias."
        );
    }
}
?>
