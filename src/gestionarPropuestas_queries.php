<?php
// Obtener todas las propuestas
function obtenerPropuestas($connection) {
    $query = "SELECT * FROM PROPUESTAS";
    $result = $connection->query($query);
    if (!$result) {
        die("Error en la consulta: " . $connection->error);
    }
    return $result;
}

// Insertar nueva propuesta
function agregarPropuesta($connection, $titulo, $descripcion, $categoria) {
    $query = "INSERT INTO PROPUESTAS (TIT_PRO, DESC_PRO, CAT_PRO) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error en la preparación: " . $connection->error);
    }
    $stmt->bind_param("sss", $titulo, $descripcion, $categoria);
    $stmt->execute();
    $stmt->close();
}

// Eliminar propuesta
function eliminarPropuesta($connection, $id) {
    $query = "DELETE FROM PROPUESTAS WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error en la preparación: " . $connection->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>