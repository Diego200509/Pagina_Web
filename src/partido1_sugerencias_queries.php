<?php
// Incluir archivo de configuración para la conexión a la base de datos
include_once('../config/config.php');

// Verificar si la función ya existe antes de declararla
if (!function_exists('obtenerNombrePartido')) {
    // Función para obtener el nombre del partido político según su ID_PAR
    function obtenerNombrePartido($idPartido) {
        global $connection;

        $sql = "SELECT NOM_PAR FROM PARTIDOS_POLITICOS WHERE ID_PAR = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $idPartido);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $nombrePartido = $result->fetch_assoc();
            return $nombrePartido['NOM_PAR'];
        } else {
            return "Partido no encontrado";
        }
    }
}

// Este bloque solo se ejecuta si el archivo es accedido directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nombre = $_POST['nombre'];
        $email = !empty($_POST['email']) ? $_POST['email'] : null; // Manejar correo opcional
        $sugerencias = $_POST['sugerencias'];
        $id_partido = $_POST['id_partido'];

        try {
            // Verificar si el usuario ya existe
            if ($email) {
                $stmt = $connection->prepare("SELECT ID_USU FROM USUARIOS WHERE EMAIL_USU = ?");
                $stmt->bind_param("s", $email);
            } else {
                $stmt = $connection->prepare("SELECT ID_USU FROM USUARIOS WHERE NOM_USU = ?");
                $stmt->bind_param("s", $nombre);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                $id_usuario = $usuario['ID_USU'];
            } else {
                // Insertar nuevo usuario
                if ($email) {
                    $stmt = $connection->prepare("INSERT INTO USUARIOS (NOM_USU, EMAIL_USU) VALUES (?, ?)");
                    $stmt->bind_param("ss", $nombre, $email);
                } else {
                    $stmt = $connection->prepare("INSERT INTO USUARIOS (NOM_USU, EMAIL_USU) VALUES (?, NULL)");
                    $stmt->bind_param("s", $nombre);
                }

                $stmt->execute();
                $id_usuario = $stmt->insert_id;
            }

            // Insertar sugerencia
            $stmt = $connection->prepare("INSERT INTO SUGERENCIAS (ID_USU_PER, SUGERENCIAS_SUG, ID_PAR_SUG) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $id_usuario, $sugerencias, $id_partido);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = '¡Gracias por tu colaboración! Tu sugerencia ha sido enviada exitosamente.';
            } else {
                $response['message'] = 'Error al enviar la sugerencia.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Ocurrió un error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Método no permitido.';
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
    $connection->close();
}
?>