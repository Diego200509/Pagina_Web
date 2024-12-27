<?php
// Incluir archivo de configuración para la conexión a la base de datos
include('../config/config.php');
// En sugerencias_queries.php
function obtenerSugerenciaPorId($id_sugerencia) {
    global $connection;  // Asegúrate de que la conexión esté disponible

    $sql = "SELECT 
                s.ID_SUG AS id_sugerencia,
                u.NOM_USU AS nombre_usuario,
                u.EMAIL_USU AS correo_usuario,
                c.NOM_CAN AS nombre_candidato,
                s.SUGERENCIAS_SUG AS sugerencia,
                s.ESTADO_SUG AS estado,
                s.CREATED_AT AS created_at
            FROM SUGERENCIAS s
            JOIN USUARIOS u ON s.ID_USU_PER = u.ID_USU
            JOIN PARTIDOS_POLITICOS p ON s.ID_PAR_SUG = p.ID_PAR
            JOIN CANDIDATOS c ON c.ID_PAR_CAN = p.ID_PAR
            WHERE s.ID_SUG = ?
            ORDER BY s.ID_SUG DESC";

    if ($stmt = $connection->prepare($sql)) {
        $stmt->bind_param("i", $id_sugerencia);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    } else {
        return null;
    }
}



function obtenerTodasSugerencias() {
    global $connection;

    $sql = "SELECT 
                s.ID_SUG AS id_sugerencia,
                u.NOM_USU AS nombre_usuario,
                u.EMAIL_USU AS correo_usuario,
                c.NOM_CAN AS nombre_candidato,
                s.SUGERENCIAS_SUG AS sugerencia,
                s.ESTADO_SUG AS estado,
                s.CREATED_AT AS created_at
            FROM SUGERENCIAS s
            JOIN USUARIOS u ON s.ID_USU_PER = u.ID_USU
            JOIN PARTIDOS_POLITICOS p ON s.ID_PAR_SUG = p.ID_PAR
            JOIN CANDIDATOS c ON c.ID_PAR_CAN = p.ID_PAR
            ORDER BY s.ID_SUG DESC";

    $result = $connection->query($sql);

    $sugerencias = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sugerencias[] = $row;
        }
    }

    return $sugerencias;
}


// Función para obtener el nombre del partido político según su ID_PAR
function obtenerNombrePartido($idPartido)
{
    global $connection;  // Hacer disponible la variable de conexión en la función

    // Consulta SQL para obtener el nombre del partido político
    $sql = "SELECT NOM_PAR FROM PARTIDOS_POLITICOS WHERE ID_PAR = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $idPartido);  // Vincular el ID del partido como parámetro
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado antes de acceder a él
    if ($result && $result->num_rows > 0) {
        $nombrePartido = $result->fetch_assoc();
        return $nombrePartido['NOM_PAR'];
    } else {
        return "Partido no encontrado";  // Mensaje alternativo si no se encuentra el partido
    }
}

// Función para obtener la cantidad de votos por cada partido político
function obtenerVotosPorPartido()
{
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

// Inicializar mensaje de error
$mensajeError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_COOKIE['ya_voto'])) {
        // Si ya votó, redirigir directamente a la página de estadísticas
        header("Location: ../Sugerencias/resultados.php");
        exit;
    }

    if (isset($_POST['candidato']) && !empty($_POST['candidato'])) {
        $candidato = (int)$_POST['candidato'];

        // Intentar registrar el voto
        if (registrarVoto($candidato)) {
            // Establecer cookie para evitar votos múltiples
            setcookie("ya_voto", true, time() + 3600 * 24, "/");

            // Redirigir a la página de estadísticas después del voto exitoso
            header("Location: ../Sugerencias/resultados.php");
        } else {
            // Redirigir con mensaje de error
            header("Location: ../Sugerencias/votos.php?mensaje=error");
        }
    } else {
        // Redirigir con mensaje si no se selecciona un candidato
        header("Location: ../Sugerencias/votos.php?mensaje=no_candidato");
    }
    exit;
}

function registrarVoto($candidato) {
    global $connection;

    // Intentar registrar el voto en la base de datos
    $stmt_voto = $connection->prepare("INSERT INTO VOTOS (ID_PAR_VOT) VALUES (?)");
    $stmt_voto->bind_param("i", $candidato);

    if ($stmt_voto->execute()) {
        $stmt_voto->close();
        return true;
    } else {
        error_log("Error al registrar el voto: " . $stmt_voto->error);
        $stmt_voto->close();
        return false;
    }
}
$connection->close();
?>