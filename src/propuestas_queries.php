<?php
header('Content-Type: application/json');

include('../config/config.php');

$categoria = isset($_POST['category']) ? $_POST['category'] : 'all';

// Si la categoría es 'all', obtenemos todas las propuestas con estado "Visible"
if ($categoria === 'all') {
    $sql = "SELECT 
                PARTIDOS_POLITICOS.NOM_PAR, 
                PROPUESTAS.TIT_PRO, 
                PROPUESTAS.DESC_PRO, 
                PROPUESTAS.CAT_PRO, 
                PROPUESTAS.IMAGEN_URL
            FROM PROPUESTAS
            INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
            INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
            WHERE PROPUESTAS.ESTADO = 'Visible'";  
    $result = $connection->query($sql); 
} else {
    $stmt = $connection->prepare("
        SELECT 
            PARTIDOS_POLITICOS.NOM_PAR, 
            PROPUESTAS.TIT_PRO, 
            PROPUESTAS.DESC_PRO, 
            PROPUESTAS.CAT_PRO, 
            PROPUESTAS.IMAGEN_URL
        FROM PROPUESTAS
        INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
        INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
        WHERE PROPUESTAS.CAT_PRO = ? 
        AND PROPUESTAS.ESTADO = 'Visible'");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $result = $stmt->get_result();
}

if (!$result) {
    die(json_encode(['error' => 'Error en la consulta: ' . $connection->error]));  
}

$propuestas = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagen_url = !empty($row['IMAGEN_URL']) ? 'http://localhost' . trim($row['IMAGEN_URL']) : "https://via.placeholder.com/300x200?text=Sin+Imagen";

        $propuestas[] = array(
            'partido' => $row['NOM_PAR'],  
            'titulo' => $row['TIT_PRO'],
            'descripcion' => $row['DESC_PRO'],
            'categoria' => $row['CAT_PRO'],
            'imagen_url' => $imagen_url // ✅ Corregimos la ruta de la imagen
        );
    }
}

// Asegúrate de que se envíe el JSON correctamente
echo json_encode($propuestas);
?>
