<?php
session_start();
include('../config/config.php');

// Obtener datos del formulario
$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validar campos vacíos
if (!$nombre || !$email || !$password) {
    $_SESSION['message'] = 'Por favor, completa todos los campos.';
    $_SESSION['message_type'] = 'error';
    header("Location: ../Login/Administracion.php");
    exit;
}

// Validar contraseña
if (strlen($password) < 6) {
    $_SESSION['message'] = 'La contraseña debe tener al menos 6 caracteres.';
    $_SESSION['message_type'] = 'error';
    header("Location: ../Login/Administracion.php");
    exit;
}

// Validar si el correo ya existe
$sql_check_email = "SELECT EMAIL_USU FROM USUARIOS WHERE EMAIL_USU = ?";
$stmt_check = $connection->prepare($sql_check_email);
$stmt_check->bind_param('s', $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    $_SESSION['message'] = 'El correo electrónico ya está registrado. Usa uno diferente.';
    $_SESSION['message_type'] = 'error';
    $stmt_check->close();
    $connection->close();
    header("Location: ../Login/Administracion.php");
    exit;
}

// Crear hash de la contraseña
$password_hashed = hash('sha256', $password);

// Establecer el rol directamente como ADMIN
$rol = 'ADMIN';

try {
    // Preparar e insertar en la base de datos
    $sql = "INSERT INTO USUARIOS (NOM_USU, EMAIL_USU, PASSWORD_USU, ROL_USU) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssss', $nombre, $email, $password_hashed, $rol);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Usuario creado con éxito.';
        $_SESSION['message_type'] = 'success';
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['message'] = 'Error al crear el usuario: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
}

// Cerrar la conexión
$stmt->close();
$connection->close();

// Redirigir al dashboard
header("Location: ../Login/Administracion.php");
exit;
?>
