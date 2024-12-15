<?php
include("../config/config.php");

header('Content-Type: application/json');

// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar conexión con la base de datos
if (!$connection) {
    echo json_encode(["error" => "Conexión fallida: " . mysqli_connect_error()]);
    exit;
}

// Limpiar posibles salidas previas para evitar errores de formato JSON
ob_clean();

$request_method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($request_method) {
        case 'POST': // Crear o editar candidato
            $candidateId = isset($_POST['candidateId']) ? intval($_POST['candidateId']) : null;
            $name = mysqli_real_escape_string($connection, $_POST['name'] ?? '');
            $bio = mysqli_real_escape_string($connection, $_POST['bio'] ?? '');
            $experience = mysqli_real_escape_string($connection, $_POST['experience'] ?? '');
            $vision = mysqli_real_escape_string($connection, $_POST['vision'] ?? '');
            $achievements = mysqli_real_escape_string($connection, $_POST['achievements'] ?? '');
            $party_id = isset($_POST['party_id']) ? intval($_POST['party_id']) : null;
            $estado = isset($_POST['estado']) ? mysqli_real_escape_string($connection, $_POST['estado']) : 'Activo'; // Valor por defecto
            $image_url = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
                $imageName = uniqid() . '-' . basename($image['name']);
                $imageDir = "../Candidatos/Img/";
                $imagePath = $imageDir . $imageName;

                if (!file_exists($imageDir)) {
                    mkdir($imageDir, 0777, true);
                }

                if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                    echo json_encode(["error" => "Error al mover la imagen al directorio."]);
                    exit;
                }

                $image_url = "Candidatos/Img/" . $imageName;
            }

            if ($candidateId) {
                // Actualizar candidato
                $query = "UPDATE CANDIDATOS 
                          SET NOM_CAN = '$name', BIOGRAFIA_CAN = '$bio', EXPERIENCIA_CAN = '$experience', 
                              VISION_CAN = '$vision', LOGROS_CAN = '$achievements', ID_PAR_CAN = $party_id, 
                              ESTADO_CAN = '$estado'" . 
                              ($image_url ? ", IMG_CAN = '$image_url'" : "") . 
                          " WHERE ID_CAN = $candidateId";

                if (!mysqli_query($connection, $query)) {
                    echo json_encode(["error" => "Error al actualizar el candidato: " . mysqli_error($connection)]);
                    exit;
                }

                echo json_encode(["message" => "Candidato actualizado exitosamente."]);
            } else {
                // Crear nuevo candidato
                $query = "INSERT INTO CANDIDATOS (NOM_CAN, BIOGRAFIA_CAN, EXPERIENCIA_CAN, VISION_CAN, LOGROS_CAN, ID_PAR_CAN, IMG_CAN, ESTADO_CAN)
                          VALUES ('$name', '$bio', '$experience', '$vision', '$achievements', $party_id, '$image_url', '$estado')";

                if (!mysqli_query($connection, $query)) {
                    echo json_encode(["error" => "Error al insertar el candidato: " . mysqli_error($connection)]);
                    exit;
                }

                echo json_encode(["message" => "Candidato agregado exitosamente."]);
            }
            break;

        case 'GET': // Obtener datos
            if (isset($_GET['fetch']) && $_GET['fetch'] === 'parties') {
                $query = "SELECT ID_PAR, NOM_PAR FROM partidos_politicos";
                $result = mysqli_query($connection, $query);

                if ($result) {
                    $parties = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $parties[] = $row;
                    }
                    echo json_encode($parties);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error al obtener los partidos: " . mysqli_error($connection)]);
                }
                break;
            }

            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $query = "SELECT ID_CAN, NOM_CAN, BIOGRAFIA_CAN, EXPERIENCIA_CAN, VISION_CAN, LOGROS_CAN, ID_PAR_CAN, IMG_CAN, ESTADO_CAN 
                          FROM CANDIDATOS WHERE ID_CAN = $id";
                $result = mysqli_query($connection, $query);

                if ($result) {
                    $candidate = mysqli_fetch_assoc($result);
                    if ($candidate) {
                        echo json_encode($candidate);
                    } else {
                        http_response_code(404);
                        echo json_encode(["error" => "Candidato no encontrado."]);
                    }
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error al obtener el candidato: " . mysqli_error($connection)]);
                }
                break;
            }

            $query = "SELECT c.ID_CAN, c.NOM_CAN, c.BIOGRAFIA_CAN, c.EXPERIENCIA_CAN, c.VISION_CAN, c.LOGROS_CAN, 
                 c.IMG_CAN, c.ESTADO_CAN, c.ID_PAR_CAN, p.NOM_PAR 
          FROM CANDIDATOS c
          LEFT JOIN partidos_politicos p ON c.ID_PAR_CAN = p.ID_PAR";

            $result = mysqli_query($connection, $query);

            if ($result) {
                $candidates = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $candidates[] = $row;
                }
                echo json_encode($candidates);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al obtener los candidatos: " . mysqli_error($connection)]);
            }
            break;

        case 'PATCH': // Cambiar estado del candidato
            $input = json_decode(file_get_contents("php://input"), true);

            if (isset($input['id'], $input['estado'])) {
                $id = intval($input['id']);
                $estado = mysqli_real_escape_string($connection, $input['estado']);

                $query = "UPDATE CANDIDATOS SET ESTADO_CAN = '$estado' WHERE ID_CAN = $id";

                if (mysqli_query($connection, $query)) {
                    echo json_encode(["message" => "Estado del candidato actualizado a $estado."]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error al actualizar el estado: " . mysqli_error($connection)]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID o estado no especificados."]);
            }
            break;

        case 'DELETE': // Eliminar candidato
            $input = json_decode(file_get_contents("php://input"), true);

            if (isset($input['id'])) {
                $id = intval($input['id']);
                $query = "DELETE FROM CANDIDATOS WHERE ID_CAN = $id";

                if (mysqli_query($connection, $query)) {
                    echo json_encode(["message" => "Candidato eliminado correctamente."]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error al eliminar candidato: " . mysqli_error($connection)]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID del candidato no especificado."]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método HTTP no permitido."]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

mysqli_close($connection);
?>
