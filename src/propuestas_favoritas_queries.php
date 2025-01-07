<?php
include('../config/config.php');

// Consulta para obtener las propuestas favoritas que estén visibles, limitando a 8 resultados
$sql = "SELECT * FROM PROPUESTAS WHERE ES_FAVORITA = 'Sí' AND ESTADO = 'Visible' LIMIT 8";
$result = mysqli_query($connection, $sql);

// Mostrar las propuestas favoritas
echo "<div id='propuestas'>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='propuesta'>";
    echo "<div class='propuesta-header'>{$row['TIT_PRO']}</div>"; // Título
    echo "<div class='propuesta-body'>";
    echo "<p><strong></strong> {$row['DESC_PRO']}</p>"; // Descripción
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
