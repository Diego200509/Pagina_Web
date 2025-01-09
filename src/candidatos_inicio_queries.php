<?php
include('../config/config.php');

// Consulta para obtener los candidatos activos
$sql = "SELECT NOM_CAN, APE_CAN, IMG_CAN, CARGO_CAN FROM CANDIDATOS WHERE ESTADO_CAN = 'Activo'";
$result = mysqli_query($connection, $sql);

// Mostrar los candidatos
echo "<div class='candidatos-container'>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='candidato'>";
    // Mostrar la imagen del candidato
    if (!empty($row['IMG_CAN'])) {
        echo "<img src='../{$row['IMG_CAN']}' alt='{$row['NOM_CAN']} {$row['APE_CAN']}'>"; // Ruta de la imagen directamente desde la base de datos
    } else {
        // Imagen por defecto si no hay imagen en la base de datos
        echo "<img src='../Candidatos/Img/default-candidate.png' alt='Imagen no disponible'>";
    }
    // Mostrar el nombre completo del candidato
    echo "<p>{$row['NOM_CAN']} {$row['APE_CAN']}</p>";
    // Mostrar el cargo del candidato
    echo "<p><strong>{$row['CARGO_CAN']}</strong></p>";
    echo "</div>";
}
echo "</div>";
?>
