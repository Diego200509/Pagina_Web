<?php
// Incluir el archivo de conexión
include("../config/config.php");

header('Content-Type: application/json');

// Verificar la conexión
if (!$connection) {
    echo json_encode(["error" => "Conexión fallida: " . mysqli_connect_error()]);
    exit;
}

// Manejo de las solicitudes según el método HTTP
$request_method = $_SERVER['REQUEST_METHOD'];

// Consultas para el manejo de candidatos
switch ($request_method) {

    case 'GET':
        // Si se proporciona un ID en la URL, obtener un solo candidato
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);  // Asegúrate de que 'id' sea un número entero

            // Consulta para obtener un candidato específico
            $query = "SELECT NOM_CAN, BIOGRAFIA_CAN, EXPERIENCIA_CAN, VISION_CAN, LOGROS_CAN, ID_PAR_CAN FROM CANDIDATOS WHERE ID_CAN = $id";
            $result = mysqli_query($connection, $query);

            if (!$result) {
                echo json_encode(["error" => "Error en la consulta: " . mysqli_error($connection)]);
                exit;
            }

            // Si se encuentra el candidato, devolver los datos en formato JSON
            if ($row = mysqli_fetch_assoc($result)) {
                echo json_encode([
                    'id' => $id,
                    'name' => $row['NOM_CAN'],
                    'bio' => $row['BIOGRAFIA_CAN'],
                    'experience' => $row['EXPERIENCIA_CAN'],
                    'vision' => $row['VISION_CAN'],
                    'achievements' => $row['LOGROS_CAN'],
                    'party_id' => $row['ID_PAR_CAN']
                ]);
            } else {
                echo json_encode(["error" => "No se encontró un candidato con ID = $id."]);
            }
        } else {
            // Si no se proporciona 'id', devolver todos los candidatos
            $query = "SELECT ID_CAN, NOM_CAN, BIOGRAFIA_CAN, EXPERIENCIA_CAN, VISION_CAN, LOGROS_CAN, ID_PAR_CAN FROM CANDIDATOS ORDER BY ID_PAR_CAN";
            $result = mysqli_query($connection, $query);

            if (!$result) {
                echo json_encode(["error" => "Error en la consulta: " . mysqli_error($connection)]);
                exit;
            }

            // Crear un arreglo para almacenar todos los candidatos
            $candidates = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $candidates[] = [
                    'id' => $row['ID_CAN'],
                    'name' => $row['NOM_CAN'],
                    'bio' => $row['BIOGRAFIA_CAN'],
                    'experience' => $row['EXPERIENCIA_CAN'],
                    'vision' => $row['VISION_CAN'],
                    'achievements' => $row['LOGROS_CAN'],
                    'party_id' => $row['ID_PAR_CAN']
                ];
            }

            // Devolver todos los candidatos en formato JSON
            echo json_encode($candidates);
        }
        break;

    case 'POST':
        // Crear un nuevo candidato
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar que los datos necesarios estén presentes
        if (!isset($data['name'], $data['bio'], $data['party_id'])) {
            echo json_encode(["error" => "Faltan datos necesarios (name, bio, party_id)."]);
            exit;
        }

        // Asignar valores de los datos recibidos
        $name = mysqli_real_escape_string($connection, $data['name']);
        $bio = mysqli_real_escape_string($connection, $data['bio']);
        $experience = isset($data['experience']) ? mysqli_real_escape_string($connection, $data['experience']) : '';
        $vision = isset($data['vision']) ? mysqli_real_escape_string($connection, $data['vision']) : '';
        $achievements = isset($data['achievements']) ? mysqli_real_escape_string($connection, $data['achievements']) : '';
        $party_id = intval($data['party_id']);

        // Consulta SQL para insertar un nuevo candidato
        $query = "INSERT INTO CANDIDATOS (NOM_CAN, BIOGRAFIA_CAN, EXPERIENCIA_CAN, VISION_CAN, LOGROS_CAN, ID_PAR_CAN)
                  VALUES ('$name', '$bio', '$experience', '$vision', '$achievements', $party_id)";

        if (mysqli_query($connection, $query)) {
            echo json_encode(["message" => "Candidato creado con éxito."]);
        } else {
            echo json_encode(["error" => "Error al crear el candidato: " . mysqli_error($connection)]);
        }
        break;

    case 'PUT':
        // Editar un candidato existente
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'], $data['name'], $data['bio'], $data['party_id'])) {
            echo json_encode(["error" => "Faltan datos necesarios (id, name, bio, party_id)."]);
            exit;
        }

        // Asignar valores de los datos recibidos
        $id = intval($data['id']);
        $name = mysqli_real_escape_string($connection, $data['name']);
        $bio = mysqli_real_escape_string($connection, $data['bio']);
        $experience = isset($data['experience']) ? mysqli_real_escape_string($connection, $data['experience']) : '';
        $vision = isset($data['vision']) ? mysqli_real_escape_string($connection, $data['vision']) : '';
        $achievements = isset($data['achievements']) ? mysqli_real_escape_string($connection, $data['achievements']) : '';
        $party_id = intval($data['party_id']);

        // Consulta SQL para actualizar el candidato
        $query = "UPDATE CANDIDATOS SET 
                  NOM_CAN = '$name', 
                  BIOGRAFIA_CAN = '$bio', 
                  EXPERIENCIA_CAN = '$experience', 
                  VISION_CAN = '$vision', 
                  LOGROS_CAN = '$achievements', 
                  ID_PAR_CAN = $party_id 
                  WHERE ID_CAN = $id";

        if (mysqli_query($connection, $query)) {
            echo json_encode(["message" => "Candidato actualizado con éxito."]);
        } else {
            echo json_encode(["error" => "Error al actualizar el candidato: " . mysqli_error($connection)]);
        }
        break;

    case 'DELETE':
        // Eliminar un candidato
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode(["error" => "Falta el ID del candidato a eliminar."]);
            exit;
        }

        $id = intval($data['id']);

        // Consulta SQL para eliminar el candidato
        $query = "DELETE FROM CANDIDATOS WHERE ID_CAN = $id";

        if (mysqli_query($connection, $query)) {
            echo json_encode(["message" => "Candidato eliminado con éxito."]);
        } else {
            echo json_encode(["error" => "Error al eliminar el candidato: " . mysqli_error($connection)]);
        }
        break;

    default:
        echo json_encode(["error" => "Método HTTP no permitido."]);
        break;
}

// Cerrar la conexión
mysqli_close($connection);
?>
