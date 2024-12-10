<?php
include '../config/config.php';

// Función para obtener todos los eventos y noticias
function obtenerEventosNoticias()
{
    global $connection;
    $query = "SELECT * FROM EVENTOS_NOTICIAS";
    $result = mysqli_query($connection, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
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
        $eventosNoticias = obtenerEventosNoticias();
        echo json_encode(['success' => true, 'data' => $eventosNoticias]);
        exit;
    }
}

function obtenerEventoNoticiaPorID($id)
{
    global $connection;
    $query = "SELECT * FROM EVENTOS_NOTICIAS WHERE ID_EVT_NOT = $id";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($result);
}

if (isset($_GET['action']) && $_GET['action'] === 'changeState' && isset($_GET['id']) && isset($_GET['newState'])) {
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


?>