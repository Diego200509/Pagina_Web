<?php
header('Content-Type: application/json');

include('../config/config.php');

$categoria = isset($_POST['category']) ? $_POST['category'] : 'all';

if ($categoria === 'all') {
    $sql = "SELECT PARTIDOS_POLITICOS.NOM_PAR, PROPUESTAS.TIT_PRO, PROPUESTAS.DESC_PRO, PROPUESTAS.CAT_PRO 
            FROM PROPUESTAS
            INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
            INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR";
    $result = $connection->query($sql); 
} else {
    $stmt = $connection->prepare("SELECT PARTIDOS_POLITICOS.NOM_PAR, PROPUESTAS.TIT_PRO, PROPUESTAS.DESC_PRO, PROPUESTAS.CAT_PRO 
                                  FROM PROPUESTAS
                                  INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
                                  INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
                                  WHERE PROPUESTAS.CAT_PRO = ?");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $result = $stmt->get_result();
}

if (!$result) {
    die('Error en la consulta: ' . $connection->error);
}

$propuestas = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $propuestas[] = array(
            'partido' => $row['NOM_PAR'],  
            'titulo' => $row['TIT_PRO'],
            'descripcion' => $row['DESC_PRO'],
            'categoria' => $row['CAT_PRO']
        );
    }
}

echo json_encode($propuestas);
?>