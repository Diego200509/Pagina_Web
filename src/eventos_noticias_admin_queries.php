<?php
include '../config/config.php';

// Función para obtener todos los eventos y noticias con paginación
function obtenerEventosNoticias($offset = 0, $limit = 6)
{
    global $connection;

    // Validación de parámetros
    $offset = intval($offset);
    $limit = intval($limit);

    $query = "
        SELECT 
            en.ID_EVT_NOT,
            en.TIT_EVT_NOT,
            en.DESC_EVT_NOT,
            en.FECHA_EVT_NOT,
            en.TIPO_REG_EVT_NOT,
            en.UBICACION_EVT_NOT,
            en.ESTADO_EVT_NOT,
            en.IMAGEN_EVT_NOT,
            p.NOM_PAR AS NOMBRE_PARTIDO
        FROM EVENTOS_NOTICIAS en
        LEFT JOIN PARTIDOS_POLITICOS p ON en.ID_PAR_EVT_NOT = p.ID_PAR
        LIMIT $offset, $limit
    ";

    $result = mysqli_query($connection, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Función para obtener el número total de registros
function obtenerTotalEventosNoticias()
{
    global $connection;
    $query = "SELECT COUNT(*) AS total FROM EVENTOS_NOTICIAS";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

// Función para crear un nuevo evento o noticia
function crearEventoNoticia($titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagen)
{
    global $connection;
    $query = "INSERT INTO EVENTOS_NOTICIAS 
                (TIT_EVT_NOT, DESC_EVT_NOT, FECHA_EVT_NOT, TIPO_REG_EVT_NOT, UBICACION_EVT_NOT, ID_PAR_EVT_NOT, ESTADO_EVT_NOT, IMAGEN_EVT_NOT)
              VALUES 
                ('$titulo', '$descripcion', '$fecha', '$tipo', '$ubicacion', $partido, '$estado', '$imagen')";
    return mysqli_query($connection, $query);
}

// Función para actualizar un evento o noticia existente
function actualizarEventoNoticia($id, $titulo, $descripcion, $fecha, $tipo, $ubicacion, $partido, $estado, $imagen)
{
    global $connection;
    $query = "UPDATE EVENTOS_NOTICIAS SET 
                TIT_EVT_NOT='$titulo',
                DESC_EVT_NOT='$descripcion',
                FECHA_EVT_NOT='$fecha',
                TIPO_REG_EVT_NOT='$tipo',
                UBICACION_EVT_NOT='$ubicacion',
                ID_PAR_EVT_NOT=$partido,
                ESTADO_EVT_NOT='$estado',
                IMAGEN_EVT_NOT='$imagen'
              WHERE ID_EVT_NOT=$id";
    return mysqli_query($connection, $query);
}

// Función para eliminar un evento o noticia
function eliminarEventoNoticia($id)
{
    global $connection;
    $query = "DELETE FROM EVENTOS_NOTICIAS WHERE ID_EVT_NOT=$id";
    return mysqli_query($connection, $query);
}

// Función para obtener un evento por ID
function obtenerEventoNoticiaPorID($id)
{
    global $connection;
    $query = "SELECT * FROM EVENTOS_NOTICIAS WHERE ID_EVT_NOT = $id";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($result);
}

// Manejar solicitudes AJAX
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if (eliminarEventoNoticia($id)) {
            echo json_encode(['success' => true, 'message' => 'El registro se eliminó correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el registro.']);
        }
        exit;
    }

    if ($_GET['action'] === 'fetch') {
        $paginaActual = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $registrosPorPagina = 6;
        $offset = ($paginaActual - 1) * $registrosPorPagina;

        $eventosNoticias = obtenerEventosNoticias($offset, $registrosPorPagina);
        $totalRegistros = obtenerTotalEventosNoticias();
        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

        echo json_encode([
            'success' => true,
            'data' => $eventosNoticias,
            'totalPaginas' => $totalPaginas,
            'paginaActual' => $paginaActual,
        ]);
        exit;
    }

    if ($_GET['action'] === 'fetchById' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $evento = obtenerEventoNoticiaPorID($id);

        if ($evento) {
            echo json_encode(['success' => true, 'data' => $evento]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Evento no encontrado.']);
        }
        exit;
    }

    if ($_GET['action'] === 'changeState' && isset($_GET['id']) && isset($_GET['newState'])) {
        $id = intval($_GET['id']);
        $nuevoEstado = $_GET['newState'];

        $query = "UPDATE EVENTOS_NOTICIAS SET ESTADO_EVT_NOT = '$nuevoEstado' WHERE ID_EVT_NOT = $id";
        $result = mysqli_query($connection, $query);

        if ($result) {
            echo json_encode(['success' => true, 'message' => "El estado se actualizó a '$nuevoEstado'."]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
        }
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
    exit;
}