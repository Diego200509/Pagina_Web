
<?php
include('../config/config.php');

session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Protege contra la inyección de SQL
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

// Consulta para verificar las credenciales del usuario
$sql = "SELECT ID_USU, PASSWORD_USU, ROL_USU FROM USUARIOS WHERE EMAIL_USU = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verifica la contraseña (considera usar password_hash() y password_verify() en un entorno real)
    if ($password === $row['PASSWORD_USU']) {
        // Asignar sesión basada en el rol del usuario
        $_SESSION['rol'] = $row['ROL_USU'];
        $_SESSION['usuario'] = $email;

        if ($row['ROL_USU'] === 'SUPERADMIN') {
            header('Location: dashboard_superadmin.php'); // Redirige al dashboard del superadministrador
        } else {
            header('Location: dashboard_usuario.php'); // Redirige al dashboard del usuario normal
        }
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
$conn->close();
?>