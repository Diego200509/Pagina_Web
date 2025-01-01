<?php
// Conexión a la base de datos
include('../config/config.php');

$message = "";
$success = 0; // 1 para éxito, 0 para error

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset']) && $_POST['reset'] === 'logoNavbar') {
        // Restablecer la imagen del Navbar a su valor predeterminado
        $default_path = "../Login/Img/logoMariCruz.png"; // Ruta predeterminada

        // Actualizar la base de datos
        $stmt_reset = $connection->prepare("UPDATE imagenes_Inicio_Logo SET image_path = ?, updated_at = NOW() WHERE section_name = 'logoNavbar'");
        $stmt_reset->bind_param("s", $default_path);
        if ($stmt_reset->execute()) {
            $message = "El logo del Navbar se ha restablecido correctamente.";
            $success = 1;
        } else {
            $message = "Error al restablecer el logo del Navbar.";
        }
        $stmt_reset->close();

        // Redirigir con mensaje
        header("Location: ../Login/administracion.php?success=$success&message=" . urlencode($message));
        exit;
    }

    $section_name = 'logoNavbar'; // Identificador único para esta sección
    $logo = $_FILES['logoNavbar'];

    if ($logo['error'] === 0) {
        // Definir el directorio de subida y el nuevo nombre del archivo
        $upload_dir = "../Login/Img/";
        $image_name = uniqid() . "-" . basename($logo['name']);
        $upload_path = $upload_dir . $image_name;

        // Mover la nueva imagen al servidor
        if (move_uploaded_file($logo['tmp_name'], $upload_path)) {
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
                    $message = "Imagen del Navbar actualizada correctamente.";
                    $success = 1;
                } else {
                    $message = "Error al actualizar la imagen del Navbar en la base de datos.";
                }
                $stmt_update->close();
            } else {
                // Insertar un nuevo registro
                $stmt_insert = $connection->prepare("INSERT INTO imagenes_Inicio_Logo (section_name, image_path) VALUES (?, ?)");
                $stmt_insert->bind_param("ss", $section_name, $upload_path);

                if ($stmt_insert->execute()) {
                    $message = "Imagen del Navbar actualizada correctamente.";
                    $success = 1;
                } else {
                    $message = "Error al actualizar la imagen del Navbar en la base de datos.";
                }
                $stmt_insert->close();
            }
            $stmt_check->close();
        } else {
            $message = "Error al mover la imagen al servidor.";
        }
    } else {
        $message = "Error al subir la imagen: " . $logo['error'];
    }
}

$connection->close();

// Redirigir con los parámetros de éxito y mensaje
header("Location: ../Login/administracion.php?success=$success&message=" . urlencode($message));
exit;
?>
