<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = 'propuestas_config.json';
    $defaultColor = '#ffffff';

    function guardarConfiguracion($archivo, $datos, $mensajeExito, $mensajeError) {
        if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT))) {
            header("Location: administracion.php?success=1&message=" . urlencode($mensajeExito));
        } else {
            header("Location: administracion.php?success=0&message=" . urlencode($mensajeError));
        }
        exit;
    }

    if (isset($_POST['resetPropuestas']) && $_POST['resetPropuestas'] === "1") {
        $datos = ["bgColor" => $defaultColor];
        guardarConfiguracion($archivo, $datos, "El color de la sección Propuestas ha sido restablecido.", "Error al restablecer el color de Propuestas.");
    }

    if (isset($_POST['colorPropuestas'])) {
        $datos = ["bgColor" => $_POST['colorPropuestas']];
        guardarConfiguracion($archivo, $datos, "El color de la sección Propuestas ha sido actualizado.", "Error al actualizar el color de Propuestas.");
    }
}
?>
