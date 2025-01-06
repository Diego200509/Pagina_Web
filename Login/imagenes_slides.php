<?php
include('../config/config.php'); // Conexión a la base de datos

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        // Restablecer la imagen correspondiente
        $section_name = $_POST['reset']; // slide1 o slide5
        $default_path = ($section_name === 'slide1') ? "../Home/Img/FONDOMARI.jpg" : "../Home/Img/FONDOMARI2.jpg";

        // Actualizar la ruta predeterminada en la base de datos
        $stmt_reset = $connection->prepare("UPDATE imagenes_Inicio_Logo SET image_path = ?, updated_at = NOW() WHERE section_name = ?");
        $stmt_reset->bind_param("ss", $default_path, $section_name);
        $stmt_reset->execute();
        $stmt_reset->close();

        // Redirigir con mensaje de éxito
        header("Location: administracion.php?success=1&message=" . urlencode("La imagen de $section_name ha sido restablecida a su valor predeterminado."));
        exit;
    }

    $section_name = $_POST['section_name']; // slide1 o slide5
    $image = $_FILES['slideImage'];

    if ($image['error'] === 0) {
        // Definir el directorio de subida y el nuevo nombre del archivo
        $upload_dir = "../Login/Img/";
        $image_name = uniqid() . "-" . basename($image['name']);
        $upload_path = $upload_dir . $image_name;

        // Mover la nueva imagen al servidor
        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            // Verificar si ya existe un registro para esta sección
            $stmt_check = $connection->prepare("SELECT image_path FROM imagenes_Inicio_Logo WHERE section_name = ?");
            $stmt_check->bind_param("s", $section_name);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // Actualizar el registro existente
                $row = $result->fetch_assoc();
                $old_image_path = $row['image_path'];

                $stmt_update = $connection->prepare("UPDATE imagenes_Inicio_Logo SET image_path = ?, updated_at = NOW() WHERE section_name = ?");
                $stmt_update->bind_param("ss", $upload_path, $section_name);

                if ($stmt_update->execute()) {
                    // Eliminar la imagen anterior del servidor
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                    $message = "Imagen de $section_name actualizada correctamente.";
                    $success = 1;
                } else {
                    $message = "Error al actualizar la imagen de $section_name en la base de datos.";
                    $success = 0;
                }
                $stmt_update->close();
            } else {
                // Insertar un nuevo registro
                $stmt_insert = $connection->prepare("INSERT INTO imagenes_Inicio_Logo (section_name, image_path) VALUES (?, ?)");
                $stmt_insert->bind_param("ss", $section_name, $upload_path);

                if ($stmt_insert->execute()) {
                    $message = "Imagen de $section_name subida correctamente.";
                    $success = 1;
                } else {
                    $message = "Error al guardar la imagen de $section_name en la base de datos.";
                    $success = 0;
                }
                $stmt_insert->close();
            }
            $stmt_check->close();
        } else {
            $message = "Error al mover la imagen al servidor.";
            $success = 0;
        }
    } else {
        $message = "Error al subir la imagen: " . $image['error'];
        $success = 0;
    }

    // Redirigir con mensaje
    header("Location: administracion.php?success=$success&message=" . urlencode($message));
    exit;
}

// Obtener las rutas de las imágenes dinámicas
$slide1_path = "../Home/Img/FONDOMARI.jpg"; // Valor por defecto
$slide5_path = "../Home/Img/FONDOMARI2.jpg"; // Valor por defecto

$stmt = $connection->prepare("SELECT section_name, image_path FROM imagenes_Inicio_Logo WHERE section_name IN ('slide1', 'slide5')");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['section_name'] === 'slide1') {
        $slide1_path = $row['image_path'];
    } elseif ($row['section_name'] === 'slide5') {
        $slide5_path = $row['image_path'];
    }
}

$stmt->close();
?>
