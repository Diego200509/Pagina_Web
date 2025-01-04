<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../Login/Login.php");
    exit;
}

// Obtener el rol del usuario
$user_role = $_SESSION['user_role'];

// Determinar la URL del dashboard según el rol del usuario
$dashboard_url = $user_role === 'SUPERADMIN' ? '../Login/superadmin_dasboard.php' : '../Login/admin_dashboard.php';
include('../src/sugerencias_queries.php');

include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $imagen = $_FILES['imagen'];

    if ($imagen['error'] === UPLOAD_ERR_OK) {
        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '-' . basename($imagen['name']);

        // Ruta completa para guardar la imagen en el servidor
        $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/Pagina_Web/Pagina_Web/Sugerencias/Img/';
        $rutaDestino = $directorioDestino . $nombreImagen;

        // Ruta relativa para guardar en la base de datos
        $rutaBaseDatos = '/Pagina_Web/Pagina_Web/Sugerencias/Img/' . $nombreImagen;

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true)) {
                error_log("Error: No se pudo crear el directorio destino: $directorioDestino");
                exit;
            }
        }

        // Mover el archivo subido al destino
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
 

            // Guardar la ruta de la imagen en la base de datos
            if (actualizarImagenConfiguracion($rutaBaseDatos)) {
            } else {

            }
        } else {
        }
    } else {

    }
}



// Verificar si el usuario está autenticado y tiene un rol válido


// Incluir archivo de consultas y configuración


// Obtener todas las sugerencias desde la base de datos
$sugerencias = obtenerTodasSugerencias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<style>

</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Sugerencias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .fa-user-shield {
    font-size: 17px; /* Ajusta según tus necesidades */
}
        .navbar .fa-user-shield {
    font-size: 1.9rem; /* Ajusta según lo necesario */
}

/* General */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom, #ffffff, #f0f0f0);
}

/* Header */
header {
    background: linear-gradient(90deg, #ff1493, #ff69b4); /* Tono rosa intenso con gradiente suave */
    color: white;
    text-align: center;
    padding: 20px 15px;
    font-size: 1.8em;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    margin: 20px auto;
    max-width: 95%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Efectos hover */
header:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

/* Iconos dentro del header */
header i {
    font-size: 1.5em;
    margin-right: 10px;
    vertical-align: middle;
}

/* Responsividad para dispositivos más pequeños */
@media (max-width: 768px) {
    header {
        font-size: 1.5em;
        padding: 15px 10px;
    }

    header i {
        font-size: 1.2em;
        margin-right: 8px;
    }
}
/* Contenedor de la Tabla */
.table-container {
    width: 95%;
    max-width: 100%; /* Ajusta el ancho máximo según tus necesidades */
    margin: 20px auto; /* Centra la tabla horizontalmente con un margen */
    border-collapse: collapse;
    margin-top: 20px;
    overflow: hidden; /* Asegura que el contenido respete los bordes redondeados */
    font-size: 16px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow-x: auto; /* Permite el scroll si el contenido es demasiado ancho */
    padding: 20px; /* Opcional: Añade un espacio interior si lo deseas */

}

.sort-icon {
    margin-left: 8px;
    font-size: 14px;
    transition: transform 0.3s ease;
    vertical-align: middle;
}

th[data-sort="asc"] .sort-icon {
    transform: rotate(180deg); /* Flecha hacia arriba */
    color: #ffecb3;
}

th[data-sort="desc"] .sort-icon {
    transform: rotate(0deg); /* Flecha hacia abajo */
    color: #ffecb3;
}

.table-container:hover {
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2); /* Sombra aumentada */
}

/* Tabla */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 16px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}
.table thead {
    background-color: #00bfff; /* Azul vibrante */
    color: white;
    font-weight: bold;
    text-align: left;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1); /* Sombra destacada en encabezado */
    border-top-left-radius: 12px; /* Bordes superiores redondeados */
    border-top-right-radius: 12px;
}
@media (max-width: 768px) {
    .table-container {
        overflow-x: scroll;
    }
}


.table th {
    cursor: pointer;
    padding: 15px;
    background-color: #00bfff;
    color: white;
    user-select: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.table th:hover {
    background-color: #008ccd;
}
.table td.acciones {
    text-align: center;
    width: 180px; /* Asegúrate de que los botones tengan espacio suficiente */
    text-align: center; /* Centra el contenido en la celda */
    white-space: nowrap; /* Evita que el texto en los botones haga un salto de línea */
    width: auto; /* Permite que se ajuste automáticamente */
}

.table th, 
.table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    min-width: 8px; /* Ajusta según las necesidades */
    word-wrap: break-word;

}

.table tbody tr {
    background-color: #f9f9f9;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}
.table tbody tr:nth-child(odd) {
    background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), white); /* Degradado rosa claro */
}

.table tbody tr:nth-child(even) {
    background: linear-gradient(135deg, rgba(0, 191, 255, 0.1), white); /* Degradado azul claro */
}

.table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05); /* Fondo al pasar el mouse */
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Sombra dinámica al hover */
}
.acciones {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 0.9em;
    font-weight: bold;
    color: white;
    cursor: pointer;
    border: none;
    text-align: center;
    transition: all 0.3s ease;
    width: 150px;
    min-width: 150px; /* Ancho mínimo fijo */
    box-sizing: border-box;
}
/* Botón Estilo */
.btn-revisar {
    position: relative;
    overflow: hidden;
    border: 1px solid #18181a;
    color: #18181a;
    display: inline-block;
    font-size: 15px;
    line-height: 15px;
    padding: 18px 18px 17px;
    text-decoration: none;
    cursor: pointer;
    background: #fff;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    min-width: 50px; /* Asegura que los botones tengan un ancho mínimo */
    white-space: nowrap; /* Evita que el texto dentro del botón se divida */
    text-overflow: ellipsis; /* Añade "..." si el texto es demasiado largo */

}
.image-modal {
    display: none; /* Oculto inicialmente */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
    justify-content: center;
    align-items: center;
    z-index: 10000;
}

.image-modal-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.image-modal-content h2 {
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.image-modal-close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.image-modal-close:hover {
    color: red;
}

.image-modal .btn-submit {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 15px;
}

.image-modal .btn-submit:hover {
    background-color: #388e3c;
}
.btn-revisar span:first-child {
    position: relative;
    transition: color 600ms cubic-bezier(0.48, 0, 0.12, 1);
    z-index: 10;
}

.btn-revisar span:last-child {
    color: white;
    display: block;
    position: absolute;
    bottom: 0;
    transition: all 500ms cubic-bezier(0.48, 0, 0.12, 1);
    z-index: 100;
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translateY(225%) translateX(-50%);
    height: 14px;
    line-height: 13px;
}

.btn-revisar:after {
    content: "";
    position: absolute;
    bottom: -50%;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    transform-origin: bottom center;
    transition: transform 600ms cubic-bezier(0.48, 0, 0.12, 1);
    transform: skewY(9.3deg) scaleY(0);
    z-index: 50;
}

.btn-revisar:hover:after {
    transform-origin: bottom center;
    transform: skewY(9.3deg) scaleY(2);
}

.btn-revisar:hover span:last-child {
    transform: translateX(-50%) translateY(-50%);
    opacity: 1;
    transition: all 900ms cubic-bezier(0.48, 0, 0.12, 1);
}


.btn-cancelar {
    background: #b33a3a;
}

.btn-cancelar:hover {
    background: #992f2f;
}



.mensaje {
    text-align: center;
    font-size: 1.2em;
    color: #555;
    margin-top: 20px;
}

/* Navbar */
.navbar {
    background-color: var(--navbar-bg-color, #00bfff);
    display: flex;
    align-items: center;
    padding: 10px 20px; 
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    gap: 20px; /* Espacio entre logo y menú */
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    flex-shrink: 0; /* Mantener el tamaño fijo del logo */
}

.navbar-logo i {
    font-size: 20px; /* Ajusta según la necesidad */
    margin-right: 10px; /* Espaciado con el texto */
    color: white;

}
.navbar-logo img {
    width: 170px; /* Reduce el ancho de la imagen */
    height: auto; /* Mantén la proporción de aspecto */
    margin-right: 10px; /* Ajusta el espacio entre el logo y el texto si es necesario */
}

.navbar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px; /* Espacio entre elementos del menú */
    flex-grow: 1; /* Ocupa todo el espacio disponible */
    justify-content: flex-end; /* Alinear los botones a la derecha */
    padding-right: 20px; /* Asegura espacio entre el último botón y el borde derecho */
}

.navbar-menu li {
    list-style: none;
}

.navbar-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
}

.navbar-menu li a:hover {
    color: #ff0050;
}
/* From Uiverse.io by sihamjardi */ 
.delete-btn {
  position: relative;
  border-radius: 6px;
  width: 150px;
  height: 40px;
  cursor: pointer;
  display: flex;
  align-items: center;
  border: 1px solid #cc0000;
  background-color: #e50000;
  overflow: hidden;
  min-width: 20px; /* Asegura que los botones tengan un ancho mínimo */
  white-space: nowrap; /* Evita que el texto dentro del botón se divida */

}

.delete-btn,
.delete-btn__icon,
.delete-btn__text {
  transition: all 0.3s;
}

.delete-btn .delete-btn__text {
  transform: translateX(35px);
  color: #fff;
  font-weight: 600;
}

.delete-btn .delete-btn__icon {
  position: absolute;
  transform: translateX(109px);
  height: 100%;
  width: 39px;
  background-color: #cc0000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.delete-btn .svg {
  width: 20px;
}

.delete-btn:hover {
  background: #cc0000;
}

.delete-btn:hover .delete-btn__text {
  color: transparent;
}

.delete-btn:hover .delete-btn__icon {
  width: 148px;
  transform: translateX(0);
}

.delete-btn:active .delete-btn__icon {
  background-color: #b20000;
}

.delete-btn:active {
  border: 1px solid #b20000;
}




.logout {
    color: #ffc107;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background: white;
    color: #333;
    border-radius: 15px;
    padding: 25px;
    width: 450px;
    max-width: 90%;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.modal-content h2 {
    color: rgb(122, 3, 23);
    text-align: center;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: bold;
}
.btn-config {
    display: flex;
  justify-content: center;
  align-items: center;
  padding: 6px 12px;
  gap: 8px;
  height: 36px;
  width: 180px;
  border: none;
  background: #5e41de33;
  border-radius: 20px;
  cursor: pointer;
}

.btn-config:hover {
    background: #5e41de4d;
}

.btn-config:hover .svg-icon{
    animation: spin 2s linear infinite;

}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
.lable {
  line-height: 20px;
  font-size: 14px;
  color: #5D41DE;
  font-family: sans-serif;
  letter-spacing: 1px;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #666;
    transition: color 0.2s ease;
}

.close-btn:hover {
    color: red;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
    color: #333;
}

.form-group input, .form-group select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    color: #333;
    width: 100%;
    box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus, .form-group select:focus {
    border-color: #4A90E2;
    outline: none;
    box-shadow: 0px 0px 8px rgba(74, 144, 226, 0.5);
}
/* Base del estilo para el botón del estado */
.estado-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: bold;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: default; /* Cursor desactivado */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Estilo adicional para hover */
.estado-btn:hover {
    text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
    transform: scale(1.05);
}

/* Efecto visual del diseño */
.estado-btn::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 5%;
    height: 1px;
    width: 1px;
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    transform: translateY(100%);
    transition: all 0.7s ease;
}

.estado-btn:hover::after {
    transform: scale(300);
    transition: all 0.7s ease;
}

/* Estados de sugerencias */
.estado-pendiente {
    background-color: #f59e0b; /* Naranja */
}

.estado-pendiente:hover {
    background-color: #d97706;
}

.estado-revisado {
    background-color: #10b981; /* Verde */
}

.estado-revisado:hover {
    background-color: #059669;
}

.estado-eliminado {
    background-color: #ef4444; /* Rojo */
}

.estado-eliminado:hover {
    background-color: #dc2626;
}


.form-group input::placeholder {
    color: #aaa;
    font-style: italic;
}
/* Estilo para el correo no proporcionado */
.correo-no-proporcionado {
    color: rgba(100, 100, 100, 0.6); /* Letras opacas */
    font-style: italic; /* Fuente en cursiva */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Sombra suave */
}

.btn-submit {
    padding: 12px;
    background-color: rgb(122, 3, 23);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    transition: background 0.3s ease, transform 0.2s ease;
}

.btn-submit:hover {
    background-color: rgb(142, 20, 36);
    transform: scale(1.05);
}
/* Modal para confirmación */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-in-out;
}

.modal-content {
    background: white;
    color: #333;
    border-radius: 15px;
    padding: 30px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
    text-align: center;
    animation: slideIn 0.3s ease-out;
}

.modal-content h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: rgb(122, 3, 23);
}

.modal-content p {
    font-size: 16px;
    margin-bottom: 20px;
    color: #555;
}

.modal-actions {
    display: flex;
    justify-content: space-around;
}

.btn-confirm {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}

.btn-confirm:hover {
    background-color: #c0392b;
}

.btn-cancel {
    background-color: #95a5a6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}

.btn-cancel:hover {
    background-color: #7f8c8d;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
/* Notificaciones */
.notification-container {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 300px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-radius: 8px;
    font-size: 14px;
    color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-in 3s forwards;
}

.notification.success {
    background-color: #4caf50;
}

.notification.error {
    background-color: #f44336;
}

.notification.warning {
    background-color: #ff9800;
}

.notification.info {
    background-color: #2196f3;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
@media (max-width: 768px) {
    table {
        font-size: 12px;
    }
    td, th {
        padding: 10px;
    }
}

/* Animación */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.navbar-role {
    margin-bottom: 9px;
    padding-left: 5px;
    padding-right: 5px;
}

    </style>


    <script>

const showModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'flex';
        };

        const closeModal = () => {
            document.getElementById('modal-crear-usuario').style.display = 'none';
        };

        window.onclick = function(event) {
            const modal = document.getElementById('modal-crear-usuario');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
       
        // Actualizar el estado de una sugerencia
        async function actualizarEstado(id, nuevoEstado) {
    const estadoCelda = document.getElementById(`estado-${id}`);

    try {
        const response = await fetch('actualizar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_sugerencia: id, estado: nuevoEstado }),
        });

        if (!response.ok) throw new Error('Error en la solicitud');

        const result = await response.json();
        if (result.success) {
            // Actualizar el texto y los estilos dinámicamente
            estadoCelda.textContent = nuevoEstado;
            estadoCelda.className = ''; // Limpia clases previas
            if (nuevoEstado === "Pendiente") {
                estadoCelda.classList.add('estado-btn', 'estado-pendiente');
            } else if (nuevoEstado === "Revisado") {
                estadoCelda.classList.add('estado-btn', 'estado-revisado');
            } else if (nuevoEstado === "Eliminado") {
                estadoCelda.classList.add('estado-btn', 'estado-eliminado');
            }
        } else {
            throw new Error(result.message || 'No se pudo actualizar el estado');
        }
    } catch (error) {
        console.error(error);
        alert('Error al actualizar el estado. Intenta nuevamente.');
    }
}

    </script>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar">
    <div class="navbar-logo">
        <div class="text-center">
                <!-- Icono SuperAdmin existente -->
                <i class="fa-solid fa-user-shield fa-2x"></i>
                <h6 class="mt-2">SuperAdmin</h6>
            </div>
            <!-- Logo existente -->
            <img src="/Pagina_Web/Pagina_Web/Login/Img/logoMariCruz.png" width="200px" style="margin-right: 20px;">
            </div>
    </div>
    <ul class="navbar-menu">
        <li><a href="../Home/inicio.php"><i class="fa-solid fa-house"></i> <span>Inicio</span></a></li>
        <li><a href="../Candidatos/candidatos.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
        <li><a href="../Eventos_Noticias/eventos_noticias.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
        <li><a href="../Propuestas/Propuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
        <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
        <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
        <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
                <li><a href="#" onclick="showModal()"><i class="fas fa-user-plus"></i> Crear Admin</a></li>
            <?php endif; ?>
            <li><a href="../Login/Login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
    </ul>
</nav>


 
             <!-- Modal para Crear Admin -->
             <?php if ($_SESSION['user_role'] === 'SUPERADMIN'): ?>
        <div id="modal-crear-usuario" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Crear Nuevo Admin</h2>
                <form action="../src/crear_usuario_queries.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Ingrese una contraseña" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol:</label>
                        <select id="rol" name="rol" required>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Crear Admin</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <header>Administrar Sugerencias</header>
    <main>
    <button class="btn-config" onclick="openImageModal()">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" class="svg-icon">
    <g stroke-width="1.5" stroke-linecap="round" stroke="#5d41de">
      <circle r="2.5" cy="10" cx="10"></circle>
      <path fill-rule="evenodd" d="M8.39 2.8c.54-1.51 2.68-1.51 3.22 0 .34.95 1.43 1.4 2.34.97 1.45-.69 2.97.82 2.28 2.28-.43.91.02 2 .97 2.34 1.51.54 1.51 2.68 0 3.22-.95.34-1.4 1.43-.97 2.34.69 1.45-.82 2.97-2.28 2.28-.91-.43-2 .02-2.34.97-.54 1.51-2.68 1.51-3.22 0-.34-.95-1.43-1.4-2.34-.97-1.45.69-2.97-.82-2.28-2.28.43-.91-.02-2-.97-2.34-1.51-.54-1.51-2.68 0-3.22.95-.34 1.4-1.43.97-2.34-.69-1.45.82-2.97 2.28-2.28.91.43 2-.02 2.34-.97z" clip-rule="evenodd"></path>
    </g>
  </svg>
  <span class="lable">Actualizar Imagen</span>
</button>



<div id="image-update-modal" class="image-modal">
    <div class="image-modal-content">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <h2>Actualizar Imagen</h2>
        <form method="POST" enctype="multipart/form-data" action="sugerencias_admin.php">
    <input type="hidden" name="accion" value="actualizar_imagen">
    <div class="mb-3">
        <label for="imagen" class="form-label">Subir nueva imagen para la página de sugerencias:</label>
        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Imagen</button>
</form>
    </div>
</div>




        <div class="table-container">
            <table class="table">
            <thead>
    <tr>
        <th data-column="nombre_usuario" onclick="sortTable('nombre_usuario')">
            Usuario <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th data-column="correo_usuario" onclick="sortTable('correo_usuario')">
            Correo <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th data-column="nombre_candidato" onclick="sortTable('nombre_candidato')">
            Candidato <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th data-column="sugerencia" onclick="sortTable('sugerencia')">
            Sugerencia <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th data-column="estado" onclick="sortTable('estado')">
            Estado <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th data-column="created_at" onclick="sortTable('created_at')">
            Fecha <i class="fas fa-arrow-down-wide-short sort-icon"></i>
        </th>
        <th>Acciones</th>
    </tr>
</thead>



                <tbody>
                    <?php if (!empty($sugerencias)): ?>
                        <?php foreach ($sugerencias as $sugerencia): ?>
                            <tr id="fila-<?php echo $sugerencia['id_sugerencia']; ?>">
                            <td data-column="nombre_usuario"><?php echo htmlspecialchars($sugerencia['nombre_usuario']); ?></td>
                                <td data-column="correo_usuario" class="<?php echo empty($sugerencia['correo_usuario']) ? 'correo-no-proporcionado' : ''; ?>">
    <?php echo empty($sugerencia['correo_usuario']) ? "Correo no proporcionado" : htmlspecialchars($sugerencia['correo_usuario']); ?>
</td>
                                <td data-column="nombre_candidato"><?php echo htmlspecialchars($sugerencia['nombre_candidato']); ?></td>
                                <td data-column="sugerencia"><?php echo htmlspecialchars($sugerencia['sugerencia']); ?></td>
                                <td data-column="estado" id="estado-<?php echo $sugerencia['id_sugerencia']; ?>">
    <button 
        class="estado-btn <?php echo $sugerencia['estado'] === 'Pendiente' ? 'estado-pendiente' : ($sugerencia['estado'] === 'Revisado' ? 'estado-revisado' : 'estado-eliminado'); ?>" 
        disabled>
        <?php echo htmlspecialchars($sugerencia['estado']); ?>
    </button>
</td>

                                <td data-column="created_at"><?php echo htmlspecialchars($sugerencia['created_at']); ?></td>
                                <td class="acciones">
    <button 
        class="btn-revisar"
        id="btn-<?php echo $sugerencia['id_sugerencia']; ?>" 
        onclick="actualizarEstado(<?php echo $sugerencia['id_sugerencia']; ?>)" 
        <?php echo $sugerencia['estado'] === 'Revisado' ? 'disabled' : ''; ?>>
        <?php echo $sugerencia['estado'] === 'Revisado' ? 'Revisado' : 'Revisar'; ?>
        <span class="check-symbol">✔</span>
    </button >

     <!-- Botón Eliminar -->
     <button 
    class="delete-btn" 
    type="button" 
    onclick="showConfirmModal(<?php echo $sugerencia['id_sugerencia']; ?>)">
    <span class="delete-btn__text">Eliminar</span>
    <span class="delete-btn__icon">
        <svg class="svg" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="M112,112l20,320c.95,18.49,14.4,32,32,32H348c17.67,0,30.87-13.51,32-32l20-320" style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
            <line style="stroke:#fff;stroke-linecap:round;stroke-miterlimit:10;stroke-width:32px" x1="80" x2="432" y1="112" y2="112"></line>
            <path d="M192,112V72h0a23.93,23.93,0,0,1,24-24h80a23.93,23.93,0,0,1,24,24h0v40" style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></path>
            <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="256" x2="256" y1="176" y2="400"></line>
            <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="184" x2="192" y1="176" y2="400"></line>
            <line style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" x1="328" x2="320" y1="176" y2="400"></line>
        </svg>
    </span>
</button>

</td>


                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="mensaje">No hay sugerencias registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="confirm-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeConfirmModal()">&times;</span>
        <h2>¿Estás seguro?</h2>
        <p>¿Deseas eliminar esta sugerencia? Esta acción no se puede deshacer.</p>
        <div class="modal-actions">
            <button class="btn-confirm" onclick="confirmDelete()">Eliminar</button>
            <button class="btn-cancel" onclick="closeConfirmModal()">Cancelar</button>
        </div>
    </div>
</div>
            <div class="notification-container" id="notification-container"></div>

        </div>
    </main>
    <script>


        let suggestionToDelete = null; // Variable para almacenar el ID de la sugerencia

function showConfirmModal(id) {
    suggestionToDelete = id; // Almacena el ID de la sugerencia
    document.getElementById('confirm-modal').style.display = 'flex';
}

function closeConfirmModal() {
    suggestionToDelete = null; // Resetea el ID de la sugerencia
    document.getElementById('confirm-modal').style.display = 'none';
}

async function confirmDelete() {
    if (!suggestionToDelete) return;

    try {
        const response = await fetch('actualizar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_sugerencia: suggestionToDelete, estado: 'Eliminado' }),
        });

        if (!response.ok) throw new Error('Error en la solicitud');

        const result = await response.json();
        if (result.success) {
            // Elimina la fila correspondiente
            const fila = document.getElementById(`fila-${suggestionToDelete}`);
            if (fila) fila.remove();

            // Muestra notificación de éxito
            showNotification('Sugerencia eliminada correctamente.', 'success');
        } else {
            throw new Error(result.error || 'No se pudo eliminar la sugerencia.');
        }
    } catch (error) {
        console.error(error);
        showNotification('Error al intentar eliminar la sugerencia.', 'error');
    } finally {
        closeConfirmModal(); // Cierra el modal
    }
}

        function showNotification(message, type = 'info') {
    const container = document.getElementById('notification-container');

    // Crear el elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Añadir la notificación al contenedor
    container.appendChild(notification);

    // Eliminar la notificación después de 3.5 segundos
    setTimeout(() => {
        notification.remove();
    }, 3500);
}

async function actualizarEstado(id) {
    const button = document.getElementById(`btn-${id}`);
    const estadoCelda = document.getElementById(`estado-${id}`).querySelector('button');

    button.disabled = true;

    try {
        const response = await fetch('actualizar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_sugerencia: id, estado: 'Revisado' }),
        });

        if (!response.ok) throw new Error('Error en la solicitud');

        const result = await response.json();
        if (result.success) {
            estadoCelda.textContent = 'Revisado';
            estadoCelda.className = '';
            estadoCelda.classList.add('estado-btn', 'estado-revisado');

            button.textContent = 'Revisado';
            button.disabled = true;
            // Mostrar notificación de éxito
            showNotification('Estado actualizado a Revisado.', 'success');
        } else {
            throw new Error(result.message || 'No se pudo actualizar el estado');
        }
    } catch (error) {
        console.error(error);
        showNotification('Error al actualizar el estado.', 'error');
    } finally {
        button.disabled = false;
    }
}


async function eliminarSugerencia(id) {
    // Confirmación con notificación personalizada

    try {
        const response = await fetch('actualizar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_sugerencia: id, estado: 'Eliminado' }),
        });

        if (!response.ok) throw new Error('Error en la solicitud');

        const result = await response.json();
        if (result.success) {
            // Buscar y eliminar la fila de la tabla
            const fila = document.getElementById(`fila-${id}`);
            if (fila) fila.remove();

            // Mostrar notificación de éxito
            showNotification('Sugerencia eliminada correctamente.', 'success');
        } else {
            throw new Error(result.error || 'No se pudo eliminar la sugerencia.');
        }
    } catch (error) {
        console.error(error);
        showNotification('Error al intentar eliminar la sugerencia.', 'error');
    }
}
function sortTable(column) {
    const table = document.querySelector("table tbody");
    const rows = Array.from(table.rows);

    // Obtener encabezado de la columna y alternar dirección
    const header = document.querySelector(`th[data-column="${column}"]`);
    let sortDirection = header.getAttribute("data-sort") === "asc" ? "desc" : "asc";
    header.setAttribute("data-sort", sortDirection);

    // Limpiar estados previos de otros encabezados
    document.querySelectorAll("th").forEach(th => {
        if (th !== header) th.removeAttribute("data-sort");
    });

    // Determinar tipo de dato
    const isDate = column === "created_at";

    rows.sort((a, b) => {
        const cellA = a.querySelector(`[data-column="${column}"]`).textContent.trim();
        const cellB = b.querySelector(`[data-column="${column}"]`).textContent.trim();

        if (isDate) {
            const dateA = new Date(cellA);
            const dateB = new Date(cellB);
            return sortDirection === "asc" ? dateA - dateB : dateB - dateA;
        }

        return sortDirection === "asc"
            ? cellA.localeCompare(cellB, undefined, { numeric: true })
            : cellB.localeCompare(cellA, undefined, { numeric: true });
    });

    // Reasignar filas ordenadas y aplicar animación
    rows.forEach(row => {
        row.style.animation = "reOrder 0.5s ease";
        table.appendChild(row);
    });

    // Actualizar íconos
    document.querySelectorAll(".sort-icon").forEach(icon => {
        icon.className = "fas fa-sort sort-icon";
    });
    const icon = header.querySelector(".sort-icon");
    icon.className = sortDirection === "asc" ? "fas fa-sort-up sort-icon" : "fas fa-sort-down sort-icon";

}

// Define la función para abrir el modal
function openImageModal() {
    const modal = document.getElementById('image-update-modal');
    modal.style.display = 'flex';
}

// Define la función para cerrar el modal
function closeImageModal() {
    const modal = document.getElementById('image-update-modal');
    modal.style.display = 'none';
}

// Cerrar el modal al hacer clic fuera de su contenido
window.addEventListener('click', function (event) {
    const modal = document.getElementById('image-update-modal');
    if (event.target === modal) {
        closeImageModal();
    }
});


document.addEventListener('DOMContentLoaded', function () {
    // Define la función para abrir el modal
    window.openImageModal = function () {
        const modal = document.getElementById('image-update-modal');
        modal.style.display = 'flex';
    };

    // Define la función para cerrar el modal
    window.closeImageModal = function () {
        const modal = document.getElementById('image-update-modal');
        modal.style.display = 'none';
    };

    // Cerrar el modal al hacer clic fuera del contenido
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('image-update-modal');
        if (event.target === modal) {
            window.closeImageModal();
        }
    });
});




    </script>
</body>
</html>