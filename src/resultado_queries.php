<?php
// Incluir archivo de configuración para la conexión a la base de datos
include('../Config/config.php');

// Función para obtener el nombre del partido político según su ID_PAR
function obtenerNombrePartidoResultados($idPartido) {
    global $connection;  // Hacer disponible la variable de conexión en la función

    // Consulta SQL para obtener el nombre del partido político
    $sql = "SELECT NOM_PAR FROM PARTIDOS_POLITICOS WHERE ID_PAR = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $idPartido);  // Vincular el ID del partido como parámetro
    $stmt->execute();
    $result = $stmt->get_result();
    $nombrePartido = $result->fetch_assoc();
    return $nombrePartido['NOM_PAR'];
}
// Función para obtener la cantidad de votos por cada partido político
function obtenerVotosPorPartidoResultados() {
    global $connection;

    // Consulta SQL para contar los votos por partido
    $sql = "SELECT ID_PAR_VOT, COUNT(*) AS cantidad_votos 
            FROM VOTOS 
            GROUP BY ID_PAR_VOT";
    $result = $connection->query($sql);

    $votosPorPartido = [];
    while ($row = $result->fetch_assoc()) {
        $votosPorPartido[$row['ID_PAR_VOT']] = $row['cantidad_votos'];
    }

    return $votosPorPartido;
}



function obtenerImagenesResultados() {
    global $connection;

    $sql = "SELECT posicion, ruta_imagen FROM configuracion_resultados ORDER BY posicion ASC";
    $result = $connection->query($sql);

    $imagenes = array_fill(0, 6, '/Pagina_Web/Pagina_Web/Sugerencias/Img_Res/default.jpg'); // Default

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posicion = (int)$row['posicion'] - 1;
            if (isset($imagenes[$posicion])) {
                $imagenes[$posicion] = $row['ruta_imagen'];
            }
        }
    }

    return $imagenes;
}


// Función para actualizar imagen según la posición
function actualizarImagenResultados($rutaImagen, $posicion) {
    global $connection;

    $sql = "INSERT INTO configuracion_resultados (posicion, ruta_imagen) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE ruta_imagen = VALUES(ruta_imagen)";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        error_log("Error al preparar la consulta: " . $connection->error);
        return false;
    }

    $stmt->bind_param("is", $posicion, $rutaImagen);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Error al ejecutar la consulta: " . $stmt->error);
        $stmt->close();
        return false;
    }
}


?>