<?php
// Archivo: cambiar_colores.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Función para guardar configuración en un archivo JSON
    function guardarConfiguracion($archivo, $datos, $mensajeExito, $mensajeError) {
        if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT))) {
            header("Location: /Pagina_Web/Pagina_Web/Login/Administracion.php?success=1&message=" . urlencode($mensajeExito));
        } else {
            header("Location: /Pagina_Web/Pagina_Web/Login/Administracion.php?success=0&message=" . urlencode($mensajeError));
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $configFileEventos = "../Login/PaginaEventos.json"; // Ruta al archivo JSON
    
        // Crear el archivo si no existe
        if (!file_exists($configFileEventos)) {
            $defaultConfig = ["paginaBgColor" => "#33FF58"];
            file_put_contents($configFileEventos, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }
    
    
        // Restablecer colores
        if (isset($_POST['reset-pagina-eventos-noticias']) && $_POST['reset-pagina-eventos-noticias'] == "1") {
            $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
            guardarConfiguracion(
                $configFileEventos,
                $defaultConfig,
                "El color de fondo de la Página Eventos y Noticias ha sido restablecido.",
                "No se pudo restablecer el color de fondo de la Página Eventos y Noticias."
            );
        }
    
        // Actualizar colores
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
    }

    
    // Manejo de Restablecer Colores del Login
    if (isset($_POST['reset']) && $_POST['reset'] == "1") {
        $defaultConfig = [
            "gradientStartLogin" => "#FF007B",
            "gradientEndLogin" => "#1C9FFF"
        ];
        guardarConfiguracion("styles_config.json", $defaultConfig, 
            "Los colores del login han sido restablecidos a los valores originales.", 
            "No se pudieron restablecer los colores del login.");
    }

    // Manejo de Actualización de Colores del Login
    if (isset($_POST['gradientStartLogin']) && isset($_POST['gradientEndLogin'])) {
        $config = [
            "gradientStartLogin" => $_POST['gradientStartLogin'],
            "gradientEndLogin" => $_POST['gradientEndLogin']
        ];
        guardarConfiguracion("styles_config.json", $config, 
            "Los colores del login se han actualizado correctamente.", 
            "No se pudieron actualizar los colores del login.");
    }

    // Manejo de Restablecer Colores del Navbar
    if (isset($_POST['resetNavbar']) && $_POST['resetNavbar'] == "1") {
        $defaultNavbarConfig = [
            "navbarBgColor" => "#00bfff"
        ];
        guardarConfiguracion("navbar_config.json", $defaultNavbarConfig, 
            "El color del Navbar ha sido restablecido a su valor original.", 
            "No se pudieron restablecer los colores del Navbar.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $configFileCandidatos = "../Login/PaginaCandidatos.json"; // Ruta al archivo JSON
    
        // Crear el archivo si no existe
        if (!file_exists($configFileCandidatos)) {
            $defaultConfig = ["paginaBgColor" => "#33FF58"];
            file_put_contents($configFileCandidatos, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }
    
        // Restablecer colores
        if (isset($_POST['reset-pagina-candidatos']) && $_POST['reset-pagina-candidatos'] == "1") {
            $defaultConfig = ["paginaBgColor" => "#f4f4f4"];
            guardarConfiguracion(
                $configFileCandidatos,
                $defaultConfig,
                "El color de fondo de la Página Candidatos ha sido restablecido.",
                "No se pudo restablecer el color de fondo de la Página Candidatos."
            );
        }
    
        // Actualizar colores
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
    }

//PAGINA PROPUESTAS















    //PAGINA SUGERENCIAS
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $configFileSugerencias = "../Login/PaginaSugerencias.json";
    
        if (!file_exists($configFileSugerencias)) {
            $defaultConfig = ["paginaBgColor" => "#a1c4fd"];
            file_put_contents($configFileSugerencias, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }
    
        if (isset($_POST['reset-pagina-sugerencias']) && $_POST['reset-pagina-sugerencias'] == "1") {
            $defaultConfig = ["paginaBgColor" => "#a1c4fd"];
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











}


    

?>
