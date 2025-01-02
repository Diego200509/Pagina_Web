<?php

// Función para obtener las propuestas con paginación
function obtenerPropuestasConPartidos($connection, $propuestasPorPagina, $offset) {
    // Consulta mejorada para obtener las propuestas sin duplicados
    $query = "
    SELECT 
        PROPUESTAS.ID_PRO, PROPUESTAS.TIT_PRO, PROPUESTAS.DESC_PRO, PROPUESTAS.CAT_PRO,
        GROUP_CONCAT(PARTIDOS_POLITICOS.NOM_PAR SEPARATOR ', ') AS PARTIDOS
    FROM PROPUESTAS
    INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
    INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
    GROUP BY PROPUESTAS.ID_PRO
    ORDER BY PROPUESTAS.ID_PRO ASC
    LIMIT ? OFFSET ?";

echo "Consulta: " . $query . "<br>";  // Ver la consulta para asegurarte de que es correcta

$stmt = $connection->prepare($query);
if (!$stmt) {
    echo "<script>console.error('Error al preparar consulta: " . $connection->error . "');</script>";
    die("Error al preparar consulta.");
}

$stmt->bind_param("ii", $propuestasPorPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

echo "Número de filas en el resultado: " . $result->num_rows . "<br>";  // Verifica cuántos resultados trae

return $result;

}

// Función para obtener todos los partidos
function obtenerPartidos($connection) {
    $query = "SELECT ID_PAR, NOM_PAR FROM PARTIDOS_POLITICOS";
    $result = $connection->query($query);
    if (!$result) {
        die("Error al obtener partidos: " . $connection->error);
    }
    return $result;
}


// Función para actualizar una propuesta
function actualizarPropuesta($connection, $id, $titulo, $descripcion, $categoria, $partido) {
    // Actualizar los datos de la propuesta en la tabla PROPUESTAS
    $query = "UPDATE PROPUESTAS SET TIT_PRO = ?, DESC_PRO = ?, CAT_PRO = ? WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar la consulta de actualización: " . $connection->error);
    }
    $stmt->bind_param("sssi", $titulo, $descripcion, $categoria, $id);
    $stmt->execute();

    // Verificar si se actualizó alguna fila
    $propuestaActualizada = $stmt->affected_rows > 0;

    // Actualizar la colaboración entre la propuesta y el partido político
    $queryColaboracion = "UPDATE COLABORACIONES SET ID_PAR_COL = ? WHERE ID_PRO_COL = ?";
    $stmtColaboracion = $connection->prepare($queryColaboracion);
    if (!$stmtColaboracion) {
        die("Error al preparar la consulta de actualización de colaboración: " . $connection->error);
    }
    $stmtColaboracion->bind_param("ii", $partido, $id);
    $stmtColaboracion->execute();

    // Verificar si se actualizó alguna fila en la tabla COLABORACIONES
    $colaboracionActualizada = $stmtColaboracion->affected_rows > 0;

    $stmt->close();
    $stmtColaboracion->close();

    // Retornar verdadero si alguna de las actualizaciones fue exitosa
    return $propuestaActualizada || $colaboracionActualizada;
}



// Función para agregar propuesta y colaboración
function agregarPropuestaYColaboracion($connection, $titulo, $descripcion, $categoria, $idPartido, $estado) {
    // Insertar en la tabla PROPUESTAS
    $queryPropuesta = "INSERT INTO PROPUESTAS (TIT_PRO, DESC_PRO, CAT_PRO, ESTADO) VALUES (?, ?, ?, ?)";
    $stmtPropuesta = $connection->prepare($queryPropuesta);
    if (!$stmtPropuesta) {
        die("Error al preparar consulta propuesta: " . $connection->error);
    }
    $stmtPropuesta->bind_param("ssss", $titulo, $descripcion, $categoria, $estado);
    $stmtPropuesta->execute();

    if ($stmtPropuesta->affected_rows > 0) {
        echo "Propuesta insertada con éxito.";
    } else {
        die("No se insertó ninguna fila en PROPUESTAS.");
    }

    // Obtener el ID de la propuesta insertada
    $idPropuesta = $connection->insert_id;

    // Insertar en la tabla COLABORACIONES
    $queryColaboracion = "INSERT INTO COLABORACIONES (ID_PAR_COL, ID_PRO_COL) VALUES (?, ?)";
    $stmtColaboracion = $connection->prepare($queryColaboracion);
    if (!$stmtColaboracion) {
        die("Error al preparar consulta colaboración: " . $connection->error);
    }
    $stmtColaboracion->bind_param("ii", $idPartido, $idPropuesta);
    $stmtColaboracion->execute();

    if ($stmtColaboracion->affected_rows > 0) {
        echo "Colaboración insertada con éxito.";
    } else {
        die("No se insertó ninguna fila en COLABORACIONES.");
    }

    $stmtPropuesta->close();
    $stmtColaboracion->close();
}


// Función para eliminar propuesta
// Función para eliminar propuesta
// Función para eliminar propuesta
function eliminarPropuesta($connection, $id) {
    // Primero, eliminar las colaboraciones asociadas a la propuesta
    $queryColaboraciones = "DELETE FROM COLABORACIONES WHERE ID_PRO_COL = ?";
    $stmtColaboraciones = $connection->prepare($queryColaboraciones);
    if (!$stmtColaboraciones) {
        die("Error al preparar consulta de eliminación de colaboraciones: " . $connection->error);
    }
    $stmtColaboraciones->bind_param("i", $id);
    $stmtColaboraciones->execute();

    // Verificar si se eliminaron las colaboraciones correctamente
    if ($stmtColaboraciones->affected_rows > 0) {
        echo "Colaboraciones eliminadas con éxito.<br>";
    } else {
        echo "No se encontraron colaboraciones para eliminar.<br>";
    }
    $stmtColaboraciones->close();

    // Ahora eliminar la propuesta
    $queryPropuesta = "DELETE FROM PROPUESTAS WHERE ID_PRO = ?";
    $stmtPropuesta = $connection->prepare($queryPropuesta);
    if (!$stmtPropuesta) {
        die("Error al preparar consulta de eliminación de propuesta: " . $connection->error);
    }
    $stmtPropuesta->bind_param("i", $id);
    $stmtPropuesta->execute();

    // Verificar si se eliminó la propuesta correctamente
    if ($stmtPropuesta->affected_rows > 0) {
        echo "Propuesta eliminada con éxito.";
    } else {
        die("No se eliminó ninguna fila en PROPUESTAS.");
    }

    $stmtPropuesta->close();
}


?>
