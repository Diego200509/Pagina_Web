<?php
include('../config/config.php');

// Consulta para obtener las propuestas favoritas que estén visibles, limitando a 8 resultados
$sql = "SELECT * FROM PROPUESTAS WHERE ES_FAVORITA = 'Sí' AND ESTADO = 'Visible' LIMIT 8";
$result = mysqli_query($connection, $sql);

// Mostrar las propuestas favoritas
echo "<div id='propuestas'>";
while ($row = mysqli_fetch_assoc($result)) {
    $desc = $row['DESC_PRO'];
    $isLongText = strlen($desc) > 277; // Verifica si el texto es largo
    $shortDesc = $isLongText ? substr($desc, 0, 277) . '...' : $desc; // Texto truncado si es necesario
    $imageURL = $row['IMAGEN_URL'] ?? ''; // URL de la imagen, maneja el caso de que sea NULL

    echo "<div class='propuesta'>";
    echo "<div class='propuesta-header'>{$row['TIT_PRO']}</div>"; // Título
    echo "<div class='propuesta-body'>";
    echo "<p class='propuesta-text'>{$shortDesc}</p>"; // Mostrar descripción truncada
        echo "<button class='ver-mas-btn' 
                  data-title='{$row['TIT_PRO']}'
                  data-description='{$row['DESC_PRO']}'
                  data-category='{$row['CAT_PRO']}'
                  data-image='{$imageURL}'>Ver más</button>";

    echo "</div>";
    echo "<div class='propuesta-categoria'><span>Categoría:</span> {$row['CAT_PRO']}</div>"; // Categoría
    echo "<div class='propuesta-footer'>";
    echo "<img src='../Login/Img/logoMariCruz.png' alt='Logo 1'>"; // Logo 1
    echo "<img src='../Home/Img/logoMano.png' alt='Logo 2'>"; // Logo 2
    echo "</div>";
    echo "</div>";
}
echo "</div>";
?>
