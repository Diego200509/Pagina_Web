<?php
include('../config/config.php');

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo']; // 'EVENTO' o 'NOTICIA'

    // Consulta para obtener los eventos o noticias activos, limitados a los 4 más recientes
    $query = "SELECT * FROM EVENTOS_NOTICIAS 
              WHERE TIPO_REG_EVT_NOT = ? AND ESTADO_EVT_NOT = 'Activo' 
              ORDER BY FECHA_EVT_NOT ASC LIMIT 4"; // Cambiado el orden a ASC para fecha más próxima
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generar el HTML para mostrar los resultados
    echo "<div id='propuestas-eventos'>"; // Clase principal
    while ($row = $result->fetch_assoc()) {
        echo "<div class='propuesta-eventos'>"; // Tarjeta de propuesta

        // Título del evento/noticia
        echo "<div class='propuesta-header-eventos'>" . htmlspecialchars($row['TIT_EVT_NOT']) . "</div>";

        // Imagen y descripción en el cuerpo
        echo "<div class='propuesta-body-eventos'>";
        echo "<img src='" . htmlspecialchars($row['IMAGEN_EVT_NOT']) . "' alt='" . htmlspecialchars($row['TIT_EVT_NOT']) . "' style='width: 250px; height: 300px;'>"; // Imagen más pequeña
        echo "<p>" . htmlspecialchars($row['DESC_EVT_NOT']) . "</p>"; // Descripción
        echo "</div>";

        // Fecha y ubicación en la categoría
        echo "<div class='propuesta-categoria-eventos'>";
        echo "<span><strong>Fecha:</strong></span> " . htmlspecialchars($row['FECHA_EVT_NOT']) . "<br>"; // Fecha
        if ($tipo === 'EVENTO' && !empty($row['UBICACION_EVT_NOT'])) {
            echo "<span><strong>Ubicación:</strong></span> " . htmlspecialchars($row['UBICACION_EVT_NOT']) . ""; // Ubicación (solo para eventos)
        }
        echo "</div>";

        // Footer con logos
        echo "<div class='propuesta-footer-eventos'>";
        echo "<img src='../Login/Img/logoMariCruz.png' alt='Logo 1'>"; // Logo 1
        echo "<img src='../Home/Img/logoMano.png' alt='Logo 2'>"; // Logo 2
        echo "</div>";

        echo "</div>"; // Cierra tarjeta
    }
    echo "</div>"; // Cierra contenedor principal

    $stmt->close();
    exit;
}
?>
