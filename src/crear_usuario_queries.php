<?php
session_start();
include('../config/config.php');

// Obtener datos del formulario
$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$rol = trim($_POST['rol']);

// Validar campos vacíos
if (!$nombre || !$email || !$password || !$rol) {
    $_SESSION['message'] = 'Por favor, completa todos los campos.';
    header("Location: ../Login/superadmin_dasboard.php");
    exit;
}

// Crear hash de la contraseña
$password_hashed = hash('sha256', $password);

try {
    // Preparar e insertar en la base de datos
    $sql = "INSERT INTO USUARIOS (NOM_USU, EMAIL_USU, PASSWORD_USU, ROL_USU) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssss', $nombre, $email, $password_hashed, $rol);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Usuario creado con éxito.';
    }
} catch (mysqli_sql_exception $e) {
    // Manejar errores específicos de MySQL
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'NOM_USU') !== false) {
            $_SESSION['message'] = 'El nombre de usuario ya existe. Por favor, elige otro.';
        } elseif (strpos($e->getMessage(), 'EMAIL_USU') !== false) {
            $_SESSION['message'] = 'El correo electrónico ya está registrado. Usa uno diferente.';
        } else {
            $_SESSION['message'] = 'Error: Dato duplicado.';
        }
    } else {
        $_SESSION['message'] = 'Error al crear el usuario: ' . $e->getMessage();
    }
}

// Cerrar la conexión
$stmt->close();
$connection->close();

// Redirigir al dashboard
header("Location: ../Login/superadmin_dasboard.php");
exit;
?>
