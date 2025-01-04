<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = 'eventos_config.json';
    $defaultColor = '#f4f4f4';

    function guardarConfiguracion($archivo, $datos, $mensajeExito, $mensajeError) {
        if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT))) {
            header("Location: administracion.php?success=1&message=" . urlencode($mensajeExito));
        } else {
            header("Location: administracion.php?success=0&message=" . urlencode($mensajeError));
        }
        exit;
    }

    if (isset($_POST['resetEventosNoticias']) && $_POST['resetEventosNoticias'] === "1") {
        $datos = ["bgColor" => $defaultColor];
        guardarConfiguracion($archivo, $datos, "El color de Eventos y Noticias ha sido restablecido.", "Error al restablecer el color de Eventos y Noticias.");
    }

    if (isset($_POST['colorEventosNoticias'])) {
        $datos = ["bgColor" => $_POST['colorEventosNoticias']];
        guardarConfiguracion($archivo, $datos, "El color de Eventos y Noticias ha sido actualizado.", "Error al actualizar el color de Eventos y Noticias.");
    }
}
?>
