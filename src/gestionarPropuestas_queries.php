<?php
function obtenerPropuestasConPartidos($connection) {
    $query = "
        SELECT 
            PROPUESTAS.ID_PRO, PROPUESTAS.TIT_PRO, PROPUESTAS.DESC_PRO, PROPUESTAS.CAT_PRO,
            PARTIDOS_POLITICOS.NOM_PAR
        FROM PROPUESTAS
        INNER JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
        INNER JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR";
    $result = $connection->query($query);
    if (!$result) {
        echo "<script>console.error('Error al obtener propuestas: " . $connection->error . "');</script>";
        die("Error al obtener propuestas: " . $connection->error);
    }
    return $result;
}

function obtenerPartidos($connection) {
    $query = "SELECT ID_PAR, NOM_PAR FROM PARTIDOS_POLITICOS";
    $result = $connection->query($query);
    if (!$result) {
        echo "<script>console.error('Error al obtener partidos: " . $connection->error . "');</script>";
        die("Error al obtener partidos: " . $connection->error);
    }
    return $result;
}

function agregarPropuestaYColaboracion($connection, $titulo, $descripcion, $categoria, $idPartido) {
    // Insertar en la tabla PROPUESTAS
    $queryPropuesta = "INSERT INTO PROPUESTAS (TIT_PRO, DESC_PRO, CAT_PRO) VALUES (?, ?, ?)";
    $stmtPropuesta = $connection->prepare($queryPropuesta);
    if (!$stmtPropuesta) {
        echo "<script>console.error('Error al preparar consulta propuesta: " . $connection->error . "');</script>";
        die("Error al preparar consulta propuesta.");
    }
    $stmtPropuesta->bind_param("sss", $titulo, $descripcion, $categoria);
    if (!$stmtPropuesta->execute()) {
        echo "<script>console.error('Error al ejecutar consulta propuesta: " . $stmtPropuesta->error . "');</script>";
        die("Error al ejecutar consulta propuesta.");
    }

    // Verificar filas afectadas
    if ($stmtPropuesta->affected_rows > 0) {
        echo "<script>console.log('Propuesta insertada con éxito.');</script>";
    } else {
        echo "<script>console.error('No se insertó ninguna fila en PROPUESTAS.');</script>";
        die("Error: No se pudo insertar la propuesta.");
    }

    $idPropuesta = $connection->insert_id;
    $stmtPropuesta->close();

    // Insertar en la tabla COLABORACIONES
    $queryColaboracion = "INSERT INTO COLABORACIONES (ID_PAR_COL, ID_PRO_COL) VALUES (?, ?)";
    $stmtColaboracion = $connection->prepare($queryColaboracion);
    if (!$stmtColaboracion) {
        echo "<script>console.error('Error al preparar consulta colaboración: " . $connection->error . "');</script>";
        die("Error al preparar consulta colaboración.");
    }
    $stmtColaboracion->bind_param("ii", $idPartido, $idPropuesta);
    if (!$stmtColaboracion->execute()) {
        echo "<script>console.error('Error al ejecutar consulta colaboración: " . $stmtColaboracion->error . "');</script>";
        die("Error al ejecutar consulta colaboración.");
    }

    // Verificar filas afectadas
    if ($stmtColaboracion->affected_rows > 0) {
        echo "<script>console.log('Colaboración insertada con éxito.');</script>";
    } else {
        echo "<script>console.error('No se insertó ninguna fila en COLABORACIONES.');</script>";
        die("Error: No se pudo insertar la colaboración.");
    }

    $stmtColaboracion->close();
}

function eliminarPropuesta($connection, $id) {
    $query = "DELETE FROM PROPUESTAS WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        echo "<script>console.error('Error al preparar consulta eliminación: " . $connection->error . "');</script>";
        die("Error al preparar consulta eliminación.");
    }
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "<script>console.error('Error al ejecutar consulta eliminación: " . $stmt->error . "');</script>";
        die("Error al ejecutar consulta eliminación.");
    }

    // Verificar filas afectadas
    if ($stmt->affected_rows > 0) {
        echo "<script>console.log('Propuesta eliminada con éxito.');</script>";
    } else {
        echo "<script>console.error('No se eliminó ninguna fila en PROPUESTAS.');</script>";
        die("Error: No se pudo eliminar la propuesta.");
    }

    $stmt->close();
}
?>
