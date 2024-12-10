<?php
include('../config/config.php');

// Obtener todas las propuestas
function obtenerPropuestas($conexion) {
    $query = "SELECT * FROM PROPUESTAS";
    $result = $conexion->query($query);
    return $result;
}

// Insertar nueva propuesta
function agregarPropuesta($conexion, $titulo, $descripcion, $categoria) {
    $query = "INSERT INTO PROPUESTAS (TIT_PRO, DESC_PRO, CAT_PRO) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sss", $titulo, $descripcion, $categoria);
    $stmt->execute();
    $stmt->close();
}

// Eliminar propuesta
function eliminarPropuesta($conexion, $id) {
    $query = "DELETE FROM PROPUESTAS WHERE ID_PRO = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>
