<?php
// Archivo: cambiar_colores.php

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $configFileEventos = "../Login/PaginaEventos.json"; // Ruta al archivo JSON
    
        // Crear el archivo si no existe
        if (!file_exists($configFileEventos)) {
            $defaultConfig = ["paginaBgColor" => "#33FF58"];
            file_put_contents($configFileEventos, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }
    
    
        // Restablecer colores
        if (isset($_POST['reset-pagina-eventos-noticias']) && $_POST['reset-pagina-eventos-noticias'] == "1") {
            $defaultConfig = ["paginaBgColor" => "#33FF58"];
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $configFile = "../Login/PaginaPropuestas.json"; // Ruta al archivo JSON de configuración
    
        // Crear el archivo si no existe
        if (!file_exists($configFile)) {
            $defaultConfig = ["paginaBgColor" => "#ffffff"]; // Color blanco por defecto
            file_put_contents($configFile, json_encode($defaultConfig, JSON_PRETTY_PRINT));
        }
    
        // Manejo de Restablecer Colores de la Página Propuestas
        if (isset($_POST['reset-pagina-propuestas']) && $_POST['reset-pagina-propuestas'] == "1") {
            $defaultConfig = ["paginaBgColor" => "#ffffff"]; // Color blanco por defecto
            guardarConfiguracion(
                $configFile,
                $defaultConfig,
                "El color de fondo de la Página Propuestas ha sido restablecido a su valor original.",
                "No se pudo restablecer el color de fondo de la Página Propuestas."
            );
        }
    
        // Manejo de Actualización de Colores de la Página Propuestas
        if (isset($_POST['colorPagPropuestas'])) {
            $config = json_decode(file_get_contents($configFile), true);
            $config['paginaBgColor'] = $_POST['colorPagPropuestas']; // Nuevo color seleccionado
            guardarConfiguracion(
                $configFile,
                $config,
                "El color de fondo de la Página Propuestas se ha actualizado correctamente.",
                "No se pudo actualizar el color de fondo de la Página Propuestas."
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

    // Manejo de Actualización de Colores del Navbar
    if (isset($_POST['colorNavbar'])) {
        $navbarConfig = [
            "navbarBgColor" => $_POST['colorNavbar']
        ];
        guardarConfiguracion("navbar_config.json", $navbarConfig, 
            "El color del Navbar se ha actualizado correctamente.", 
            "No se pudieron actualizar los colores del Navbar.");
    }

    // Manejo de Restablecer Colores de Candidatos
    if (isset($_POST['resetCandidatos']) && $_POST['resetCandidatos'] == "1") {
        $defaultConfig = [
            "candidatosBgColor" => "#00bfff" // Azul por defecto
        ];
        guardarConfiguracion("../Login/candidatos_config.json", $defaultConfig, 
            "Los colores de Candidatos han sido restablecidos a los valores originales.", 
            "No se pudieron restablecer los colores de Candidatos.");
    }

    // Manejo de Actualización de Colores de Candidatos
    if (isset($_POST['colorCandidatos'])) {
        $config = [
            "candidatosBgColor" => $_POST['colorCandidatos']
        ];
        guardarConfiguracion("../Login/candidatos_config.json", $config, 
            "El color de Candidatos se ha actualizado correctamente.", 
            "No se pudo actualizar el color de Candidatos.");
    }
}


    // Manejo de Restablecer Colores de Candidatos
    if (isset($_POST['resetCandidatos']) && $_POST['resetCandidatos'] == "1") {
        $defaultConfig = [
            "candidatosBgColor" => "#00bfff" // Azul por defecto
        ];
        guardarConfiguracion("../Login/candidatos_config.json", $defaultConfig, 
            "Los colores de Candidatos han sido restablecidos a los valores originales.", 
            "No se pudieron restablecer los colores de Candidatos.");
    }

    // Manejo de Actualización de Colores de Candidatos
    if (isset($_POST['colorCandidatos'])) {
        $config = [
            "candidatosBgColor" => $_POST['colorCandidatos']
        ];
        guardarConfiguracion("../Login/candidatos_config.json", $config, 
            "El color de Candidatos se ha actualizado correctamente.", 
            "No se pudo actualizar el color de Candidatos.");
    }




    

?>
