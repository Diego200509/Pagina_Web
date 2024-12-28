<?php
session_start();

// Verificar si el usuario está autenticado y tiene un rol válido
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['SUPERADMIN', 'ADMIN'])) {
    header("Location: ../Login/Login.php");
    exit;
}

// Incluir archivo de consultas y configuración
include('../src/sugerencias_queries.php');
include('../Config/config.php');

// Obtener todas las sugerencias desde la base de datos
$sugerencias = obtenerTodasSugerencias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Sugerencias</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* General */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom, #ffffff, #f0f0f0);
}

header {
    background-color: #8a2b2b;
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 1.5em;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
/* Contenedor de la Tabla */
.table-container {
    width: 95%;
    margin: 30px auto;
    border-radius: 12px;
    overflow-x: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Sombra profesional */
    background-color: white;
    animation: fadeIn 1s ease;
    transition: box-shadow 0.3s ease;
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
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Sombra adicional */
}

thead {
    background-color: #8a2b2b;
    color: white;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1); /* Sombra destacada en encabezado */
}

th {
    cursor: pointer;
    position: relative;
    text-align: left;
    padding: 15px;
    background-color: #8a2b2b;
    color: white;
    user-select: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

th:hover {
    background-color: #d94a4a;
}
th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e6e6e6;
}

tbody tr {
    background-color: #f9f9f9;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

tbody tr:nth-child(even) {
    background-color: #f0f0f5;
}

tbody tr:hover {
    background-color: #e4e8f0;
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
    background-color: rgb(122, 3, 23);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
}

.navbar-logo img {
    width: 50px;
    margin-right: 10px;
}

.navbar-logo h2 {
    font-size: 1.5rem;
    margin: 0;
}

.navbar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
}

.navbar-menu li {
    list-style: none;
}

.navbar-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s;
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


.navbar-menu li a:hover {
    color: #ffc107;
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

    </style>
    <script>
        // Modal handling
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
            <img src="../Home/Img/logo.png" alt="UTA Logo">
            <h2><?php echo $_SESSION['user_role'] === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?></h2>
        </div>
        <ul class="navbar-menu">
            <li><a href="../Candidatos/candidatos_admin.php"><i class="fas fa-users"></i> Candidatos</a></li>
            <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fas fa-calendar-alt"></i> Eventos y Noticias</a></li>
            <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fas fa-lightbulb"></i> Propuestas</a></li>
            <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fas fa-comment-dots"></i> Sugerencias</a></li>
            <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li> <!-- Nuevo campo -->

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


    <body>
    <header>Administrar Sugerencias</header>
    <main>
        <div class="table-container">
            <table>
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



    </script>
</body>
</html>
