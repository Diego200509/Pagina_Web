<?php
session_start();
include('../config/config.php');

// Obtener datos del formulario
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validar campos vacíos
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Por favor, completa todos los campos.';
    header("Location: ../Login/Login.php");
    exit;
}

// Consultar en la base de datos
$sql = "SELECT ID_USU, NOM_USU, PASSWORD_USU, ROL_USU FROM USUARIOS WHERE EMAIL_USU = ?";
$stmt = $connection->prepare($sql); // Cambiado a $connection
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar contraseña
    if (hash('sha256', $password) === $user['PASSWORD_USU']) {
        $_SESSION['user_id'] = $user['ID_USU'];
        $_SESSION['user_name'] = $user['NOM_USU'];
        $_SESSION['user_role'] = $user['ROL_USU'];

        // Elimina esta línea si ya confirmaste que el rol es correcto
        // echo "Rol detectado: " . $user['ROL_USU'];
        // exit;

        // Redirigir según el rol
        if ($user['ROL_USU'] === 'SUPERADMIN') {
            header("Location: ../Login/superadmin_dasboard.php");
        } elseif ($user['ROL_USU'] === 'ADMIN') {
            header("Location:  ../Login/admin_dashboard.php");
        } else {
            $_SESSION['error'] = 'Rol no reconocido.';
            header("Location: ../Login/Login.php");
        }
        exit;
    } else {
        $_SESSION['error'] = 'Contraseña incorrecta.';
        header("Location: ../Login/Login.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'El correo electrónico no existe.';
    header("Location: ../Login/Login.php");
    exit;
}


// Cerrar conexión
$stmt->close();
$connection->close(); // Cambiado a $connection
?>
