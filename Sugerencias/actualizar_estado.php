<?php
include('../Config/config.php');

header('Content-Type: application/json');

// Leer datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_sugerencia']) && isset($data['estado'])) {
    $id_sugerencia = $data['id_sugerencia'];
    $estado = $data['estado'];

    // Actualizar estado en la base de datos
    $sql = "UPDATE SUGERENCIAS SET ESTADO_SUG = ? WHERE ID_SUG = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('si', $estado, $id_sugerencia);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos insuficientes']);
}

$connection->close();
?>
