<?php

$host = "localhost";  
$user = "root";  
$password = "";  
$database = "elecciones2024";  

// config.php
$connection = new mysqli($host, $user, $password, $database);
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}
echo "<script>console.log('Conectado a la base de datos: " . $database . "');</script>";



?>
