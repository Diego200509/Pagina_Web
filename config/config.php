<?php

$host = "localhost";  
$user = "root";  
$password = "";  
$database = "elecciones2024";  

$connection = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}


?>
