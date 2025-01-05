<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = 'candidatos_config.json';
    $defaultColor = '#00bfff';

    function guardarConfiguracion($archivo, $datos, $mensajeExito, $mensajeError) {
        if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT))) {
            header("Location: administracion_admin.php?success=1&message=" . urlencode($mensajeExito));
        } else {
            header("Location: administracion_admin.php?success=0&message=" . urlencode($mensajeError));
        }
        exit;
    }

    if (isset($_POST['resetCandidatos']) && $_POST['resetCandidatos'] === "1") {
        $datos = ["bgColor" => $defaultColor];
        guardarConfiguracion($archivo, $datos, "El color de la sección Candidatos ha sido restablecido.", "Error al restablecer el color de Candidatos.");
    }

    if (isset($_POST['colorCandidatos'])) {
        $datos = ["bgColor" => $_POST['colorCandidatos']];
        guardarConfiguracion($archivo, $datos, "El color de la sección  Candidatos ha sido actualizado.", "Error al actualizar el color de Candidatos.");
    }
}
?>
