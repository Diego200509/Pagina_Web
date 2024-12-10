<?php
include('../config/config.php');

header('Content-Type: application/json');

$events = [];
$news = [];

// Consulta para obtener eventos y noticias
$query = "SELECT ID_EVT_NOT, TIT_EVT_NOT, DESC_EVT_NOT, FECHA_EVT_NOT, TIPO_REG_EVT_NOT, UBICACION_EVT_NOT, NOM_PAR, IMAGEN_EVT_NOT
          FROM EVENTOS_NOTICIAS 
          JOIN PARTIDOS_POLITICOS ON EVENTOS_NOTICIAS.ID_PAR_EVT_NOT = PARTIDOS_POLITICOS.ID_PAR 
          WHERE EVENTOS_NOTICIAS.ESTADO_EVT_NOT = 'Activo' 
          ORDER BY FECHA_EVT_NOT DESC";


$result = $connection->query($query);

// Comprobar si la consulta devuelve resultados
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['TIPO_REG_EVT_NOT'] == 'EVENTO') {
            $events[] = $row;
        } else if ($row['TIPO_REG_EVT_NOT'] == 'NOTICIA') {
            $news[] = $row;
        }
    }
}

// Crear un arreglo de respuesta con los eventos y noticias
$response = [
    'events' => $events,
    'news' => $news
];

// Devolver el JSON
echo json_encode($response);

// Cerrar la conexión
$connection->close();
?>