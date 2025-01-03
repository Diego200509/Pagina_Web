<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/config.php');
include('../src/gestionarPropuestas_queries.php');

// Configuración de sesión y redirección según el rol
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['ADMIN', 'SUPERADMIN'])) {
    $_SESSION['error'] = 'Acceso denegado. Por favor inicia sesión.';
    header('Location: ../Login/Login.php');
    exit();
}

// Configuración de paginación
$propuestasPorPagina = 10; // Número de propuestas por página
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $propuestasPorPagina;

// Validar que $propuestasPorPagina no sea cero
if ($propuestasPorPagina <= 0) {
    die("Error: El número de propuestas por página debe ser mayor que 0.");
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'agregar') {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $partido = $_POST['partido'];
        $estado = $_POST['estado'];

        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido) || empty($estado)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        try {
            agregarPropuestaYColaboracion($connection, $titulo, $descripcion, $categoria, $partido, $estado);
            header('Location: gestionarPropuestas.php?status=added');
            exit();
        } catch (Exception $e) {
            die("Error al agregar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'eliminar') {
        $id = $_POST['id'];

        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para eliminar.");
        }

        try {
            eliminarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=deleted');
            exit();
        } catch (Exception $e) {
            die("Error al eliminar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'editar') {
        $id = $_POST['id'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $partido = $_POST['partido'] ?? '';
        $estado = $_POST['estado'] ?? ''; // Corregido

        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        try {
            actualizarPropuesta($connection, $id, $titulo, $descripcion, $categoria, $partido, $estado);
            header('Location: gestionarPropuestas.php?status=edited');
            exit();
        } catch (Exception $e) {
            die("Error al editar propuesta: " . $e->getMessage());
        }
    }





    if ($accion === 'ocultar') {
        $id = $_POST['id'];

        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para ocultar.");
        }

        try {
            ocultarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=hidden');
            exit();
        } catch (Exception $e) {
            die("Error al ocultar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'mostrar') {
        $id = $_POST['id'];

        // Verificar que se recibió el ID
        if (empty($id)) {
            die("Error: No se recibió el ID de la propuesta para mostrar.");
        }

        try {
            // Llamar a la función de mostrar propuesta
            mostrarPropuesta($connection, $id);
            header('Location: gestionarPropuestas.php?status=success');
            exit();
        } catch (Exception $e) {
            die("Error al mostrar propuesta: " . $e->getMessage());
        }
    }

    if ($accion === 'cambiarEstado') {
        $id = $_POST['id'];
        $nuevoEstado = $_POST['nuevoEstado'];

        if (empty($id) || empty($nuevoEstado)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos para cambiar el estado.']);
            exit();
        }

        $query = "UPDATE PROPUESTAS SET ESTADO = ? WHERE ID_PRO = ?";
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $connection->error]);
            exit();
        }
        $stmt->bind_param('si', $nuevoEstado, $id);
        $stmt->execute();

        // Devuelve éxito incluso si no se afectan filas (estado ya está configurado)
        echo json_encode(['success' => true]);
        $stmt->close();
        exit();
    }

    if ($accion === 'cambiarFavorito') {
        $id = $_POST['id'];
        $esFavorita = $_POST['esFavorita']; // 'Sí' o 'No'
    
        if (empty($id) || empty($esFavorita)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos para actualizar favorito.']);
            exit();
        }
    
        $query = "UPDATE PROPUESTAS SET ES_FAVORITA = ? WHERE ID_PRO = ?";
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $connection->error]);
            exit();
        }
        $stmt->bind_param('si', $esFavorita, $id);
        $stmt->execute();
    
        echo json_encode(['success' => true]);
        $stmt->close();
        exit();
    }
    
}

// Obtener el total de propuestas en la base de datos (sin duplicados)
$totalPropuestasResult = $connection->query("SELECT COUNT(*) AS total FROM PROPUESTAS");
$totalPropuestas = $totalPropuestasResult->fetch_assoc()['total'];
$totalPaginas = ceil($totalPropuestas / $propuestasPorPagina);

// Consulta para obtener los estados
$queryEstados = "
    SELECT 'Visible' AS ESTADO
    UNION
    SELECT 'Oculta' AS ESTADO
    UNION
    SELECT DISTINCT ESTADO FROM PROPUESTAS
";
$estadosResult = $connection->query($queryEstados);
if (!$estadosResult) {
    die("Error al obtener los estados: " . $connection->error);
}



// Depuración de variables
//echo "<pre>";
//echo "Página actual: " . $paginaActual . "\n";
//echo "Offset: " . $offset . "\n";
//echo "Total de propuestas en BD: " . $totalPropuestas . "\n";
//echo "Total de páginas: " . $totalPaginas . "\n";
//echo "</pre>";

// Consulta simplificada para ver si la paginación funciona sin GROUP_CONCAT
$query = "
    SELECT 
        PROPUESTAS.ID_PRO, 
        PROPUESTAS.TIT_PRO, 
        PROPUESTAS.DESC_PRO, 
        PROPUESTAS.CAT_PRO, 
        PROPUESTAS.ESTADO, 
        PROPUESTAS.ES_FAVORITA, -- Incluye el campo favorito
        GROUP_CONCAT(PARTIDOS_POLITICOS.NOM_PAR SEPARATOR ', ') AS PARTIDOS,
        PARTIDOS_POLITICOS.ID_PAR AS ID_PAR
    FROM PROPUESTAS
    LEFT JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
    LEFT JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
    GROUP BY PROPUESTAS.ID_PRO
    ORDER BY PROPUESTAS.ID_PRO ASC
    LIMIT ? OFFSET ?";


$stmt = $connection->prepare($query);

if (!$stmt) {
    die("Error al preparar la consulta: " . $connection->error);
}

$stmt->bind_param("ii", $propuestasPorPagina, $offset);

if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$result = $stmt->get_result();



$partidos = obtenerPartidos($connection);

// Función para mostrar la descripción con formato
function mostrarDescripcionConFormato($descripcion)
{
    return nl2br(htmlspecialchars($descripcion));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propuestas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="estilosGestionarPropuestas.css">
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

        <!-- Menú principal -->
        <div class="navbar-menu-container">
            <ul class="navbar-menu">
                <li><a href="../Candidatos/candidatos_admin.php"><i class="fa-solid fa-users"></i> <span>Candidatos</span></a></li>
                <li><a href="../Eventos_Noticias/eventos_noticias_admin.php"><i class="fa-solid fa-calendar-alt"></i> <span>Eventos y Noticias</span></a></li>
                <li><a href="../Propuestas/gestionarPropuestas.php"><i class="fa-solid fa-lightbulb"></i> <span>Propuestas</span></a></li>
                <li><a href="../Sugerencias/sugerencias_admin.php"><i class="fa-solid fa-comment-dots"></i> <span>Sugerencias</span></a></li>
                <li><a href="../Sugerencias/resultados_admin.php"><i class="fas fa-vote-yea"></i> Votos</a></li>
                <li><a href="../Login/Administracion.php"><i class="fa-solid fa-cogs"></i> <span>Administración</span></a></li>
                <li><a href="../Login/Login.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> <span>Cerrar Sesión</span></a></li>
            </ul>
        </div>
    </nav>


    <div class="container">
        <h2>Propuestas</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'success'): ?>
                <p class="success">Operación realizada con éxito.</p>
            <?php elseif ($_GET['status'] == 'hidden'): ?>
                <p class="success">Propuesta ocultada con éxito.</p>
            <?php elseif ($_GET['status'] == 'visible'): ?>
                <p class="success">Propuesta visible con éxito.</p>
            <?php elseif ($_GET['status'] == 'deleted'): ?>
                <p class="success">Propuesta eliminada con éxito.</p>
            <?php elseif ($_GET['status'] == 'added'): ?>
                <p class="success">Propuesta agregada con éxito.</p>
            <?php elseif ($_GET['status'] == 'edited'): ?>
                <p class="success">Propuesta editada con éxito.</p>
            <?php elseif ($_GET['status'] == 'no_changes'): ?>
                <p class="info">No se realizaron cambios en la propuesta.</p>
            <?php endif; ?>
        <?php endif; ?>



        <div style="text-align: left; margin-bottom: 15px;">
            <button id="btnAgregarPropuesta" class="btn-agregar">
                <i class="bi bi-plus"></i> Agregar Propuesta
            </button>
        </div>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Partido Político</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Categorías</th>
                    <th>Estado</th>
                    <th>Favorito</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID_PRO']) ?></td>
                            <td><?= htmlspecialchars($row['PARTIDOS']) ?></td>
                            <td><?= htmlspecialchars($row['TIT_PRO']) ?></td>
                            <td><?= htmlspecialchars($row['DESC_PRO']) ?></td>
                            <td><?= htmlspecialchars($row['CAT_PRO']) ?></td>
                            <td id="estado-<?= $row['ID_PRO'] ?>"><?= htmlspecialchars($row['ESTADO']) ?></td>
                            <td>
                                <i
                                    class="fa-star <?= $row['ES_FAVORITA'] === 'Sí' ? 'fas' : 'far' ?>"
                                    style="cursor: pointer; color: gold;"
                                    onclick="toggleFavorito(<?= $row['ID_PRO'] ?>, '<?= $row['ES_FAVORITA'] === 'Sí' ? 'No' : 'Sí' ?>')">
                                </i>
                            </td>
                            <td>
                                <div class="dropdown-container">
                                    <!-- Botón principal -->
                                    <button class="action-btn" onclick="toggleDropdown(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Menú desplegable -->
                                    <div class="custom-dropdown">
                                        <a role="button" class="btn btn-primary"
                                            onclick="abrirModalEditar(
        '<?= htmlspecialchars($row['ID_PRO'], ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['TIT_PRO'], ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['DESC_PRO'], ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['CAT_PRO'], ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['ID_PAR'], ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['ESTADO'], ENT_QUOTES, 'UTF-8') ?>'
    ); event.preventDefault();">
                                            Editar
                                        </a>






                                        <a role="button" class="text-danger" onclick="eliminarPropuesta(<?= $row['ID_PRO'] ?>); event.preventDefault();">Eliminar</a>
                                        <a role="button" id="estado-opcion-<?= $row['ID_PRO'] ?>" class="text-warning"
                                            onclick="cambiarEstado(<?= $row['ID_PRO'] ?>, '<?= $row['ESTADO'] ?>'); event.preventDefault();">
                                            <?= $row['ESTADO'] === 'Visible' ? 'Ocultar' : 'Visible' ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay propuestas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>


        </table>

        <script>
            // Ocultar los mensajes de estado después de 5 segundos
            setTimeout(() => {
                const messages = document.querySelectorAll('.success, .info');
                messages.forEach(message => {
                    message.style.display = 'none';
                });
            }, 5000); // 5000 ms = 5 segundos
        </script>


        <!-- Barra de navegación para la paginación -->
        <div class="pagination">
            <?php if ($paginaActual > 1): ?>
                <a href="?pagina=1">Primera</a>
                <a href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" <?= $i === $paginaActual ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                <a href="?pagina=<?= $totalPaginas ?>">Última</a>
            <?php endif; ?>
        </div>


    </div>
    </div>

    <div id="modalPropuesta" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="cerrarModal()">&times;</span>
            <h2>Agregar Nueva Propuesta</h2>
            <form method="POST" action="gestionarPropuestas.php">
                <input type="hidden" name="accion" value="agregar">

                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>

                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria" class="form-select" required>
                    <option value="">Seleccionar Facultad o Interés</option>
                    <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                    <option value="Ciencia e Ingeniería en Alimentos">Ciencia e Ingeniería en Alimentos</option>
                    <option value="Jurisprudencia y Ciencias Sociales">Jurisprudencia y Ciencias Sociales</option>
                    <option value="Contabilidad y Auditoría">Contabilidad y Auditoría</option>
                    <option value="Ciencias Humanas y de la Educación">Ciencias Humanas y de la Educación</option>
                    <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                    <option value="Ingeniería Civil y Mecánica">Ingeniería Civil y Mecánica</option>
                    <option value="Ingeniería en Sistemas, Electrónica e Industrial">Ingeniería en Sistemas, Electrónica e Industrial</option>
                    <option value="Infraestructura">Infraestructura</option>
                    <option value="Deportes">Deportes</option>
                    <option value="Cultura">Cultura</option>
                    <option value="Investigación">Investigación</option>
                    <option value="Vinculación con la Sociedad">Vinculación con la Sociedad</option>
                </select>

                <label for="partido">Partido Político:</label>
                <select name="partido" id="partido" class="form-select" required>
                    <?php while ($partido = $partidos->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($partido['ID_PAR']) ?>">
                            <?= htmlspecialchars($partido['NOM_PAR']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-select" required>
                    <?php if ($estadosResult->num_rows > 0): ?>
                        <?php while ($estado = $estadosResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($estado['ESTADO']) ?>">
                                <?= htmlspecialchars($estado['ESTADO']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No hay estados disponibles</option>
                    <?php endif; ?>
                </select>





                <button type="submit" class="btn btn-danger">Guardar Propuesta</button>
            </form>
        </div>
    </div>

    <div id="modalEditarPropuesta" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="cerrarModalEditar()">&times;</span>
            <h2>Editar Propuesta</h2>
            <form id="formEditarPropuesta" method="POST" action="gestionarPropuestas.php">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" id="idEditarPropuesta">

                <!-- Título -->
                <label for="tituloEditar">Título:</label>
                <input type="text" id="tituloEditar" name="titulo" class="form-control" required>

                <!-- Descripción -->
                <label for="descripcionEditar">Descripción:</label>
                <textarea id="descripcionEditar" name="descripcion" class="form-control" required></textarea>

                <!-- Categoría -->
                <label for="categoriaEditar">Categoría:</label>
                <select id="categoriaEditar" name="categoria" class="form-select" required>
                    <?php
                    $categorias = [
                        "Ciencias Administrativas",
                        "Ciencia e Ingeniería en Alimentos",
                        "Jurisprudencia y Ciencias Sociales",
                        "Contabilidad y Auditoría",
                        "Ciencias Humanas y de la Educación",
                        "Ciencias de la Salud",
                        "Ingeniería Civil y Mecánica",
                        "Ingeniería en Sistemas, Electrónica e Industrial",
                        "Infraestructura",
                        "Deportes",
                        "Cultura",
                        "Investigación",
                        "Vinculación con la Sociedad"
                    ];
                    $categoriaSeleccionada = isset($row['CAT_PRO']) && $row['CAT_PRO'] !== null ? $row['CAT_PRO'] : '';
                    foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $categoria == $categoriaSeleccionada ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Partido Político -->
                <label for="partidoEditar">Partido Político:</label>
                <select id="partidoEditar" name="partido" class="form-select" required>
                    <?php mysqli_data_seek($partidos, 0); ?>
                    <?php while ($partido = $partidos->fetch_assoc()): ?>
                        <?php
                        $idPar = isset($partido['ID_PAR']) ? $partido['ID_PAR'] : '';
                        $nomPar = isset($partido['NOM_PAR']) ? $partido['NOM_PAR'] : '';
                        ?>
                        <option value="<?= htmlspecialchars($idPar, ENT_QUOTES, 'UTF-8') ?>"
                            <?= isset($row['ID_PAR']) && $row['ID_PAR'] === $idPar ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nomPar, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Estado -->
                <label for="estadoEditar">Estado:</label>
                <select id="estadoEditar" name="estado" class="form-select" required>
                    <option value="Visible" <?= isset($row['ESTADO']) && $row['ESTADO'] === 'Visible' ? 'selected' : '' ?>>Visible</option>
                    <option value="Oculta" <?= isset($row['ESTADO']) && $row['ESTADO'] === 'Oculta' ? 'selected' : '' ?>>Oculta</option>
                </select>

                <!-- Botón para actualizar -->
                <button type="submit" class="btn btn-danger">Actualizar Propuesta</button>
            </form>
        </div>
    </div>







    <script>
        // Función para abrir el modal de agregar propuesta
        function abrirModal() {
            const modal = document.getElementById("modalPropuesta");
            if (modal) {
                modal.style.display = "flex";
            } else {
                console.error("No se encontró el modal con ID 'modalPropuesta'.");
            }
        }

        // Función para cerrar el modal de agregar propuesta
        function cerrarModal() {
            const modal = document.getElementById("modalPropuesta");
            if (modal) {
                modal.style.display = "none";
            } else {
                console.error("No se encontró el modal con ID 'modalPropuesta'.");
            }
        }

        // Función para abrir el modal de editar propuesta
        function abrirModalEditar(id, titulo, descripcion, categoria, partido, estado) {
            document.getElementById('idEditarPropuesta').value = id;
            document.getElementById('tituloEditar').value = titulo;
            document.getElementById('descripcionEditar').value = descripcion;
            document.getElementById('categoriaEditar').value = categoria;

            // Asignar el ID del partido político
            const partidoSelect = document.getElementById('partidoEditar');
            if (partidoSelect) {
                partidoSelect.value = partido;
            } else {
                console.error("No se encontró el select para partidos.");
            }

            // Asignar el estado de la propuesta
            const estadoSelect = document.getElementById('estadoEditar');
            if (estadoSelect) {
                estadoSelect.value = estado;
            } else {
                console.error("No se encontró el select para estado.");
            }

            document.getElementById("modalEditarPropuesta").style.display = 'flex';
        }











        // Función para cerrar el modal de edición
        function cerrarModalEditar() {
            const modalEditar = document.getElementById("modalEditarPropuesta");
            if (modalEditar) {
                modalEditar.style.display = "none"; // Cierra el modal
            } else {
                console.error("No se encontró el modal con ID 'modalEditarPropuesta'.");
            }
        }

        // Esta parte garantiza que el código se ejecute cuando el DOM esté completamente cargado
        document.addEventListener("DOMContentLoaded", () => {
            // Configurar evento para abrir el modal al hacer clic en el botón "Agregar Propuesta"
            const btnAgregarPropuesta = document.getElementById("btnAgregarPropuesta");
            if (btnAgregarPropuesta) {
                btnAgregarPropuesta.addEventListener("click", abrirModal);
            } else {
                console.error("No se encontró el botón con ID 'btnAgregarPropuesta'.");
            }

            // Configurar el evento en los botones de cierre del modal (la "X") para ambos modales
            const closeButton = document.querySelector(".close-button");
            if (closeButton) {
                closeButton.addEventListener("click", cerrarModal);
            }

            // Configurar el evento en los botones de cierre del modal de edición (la "X")
            const closeButtonEditar = document.querySelectorAll(".close-button");
            if (closeButtonEditar) {
                closeButtonEditar.forEach(btn => btn.addEventListener("click", cerrarModalEditar));
            }

            // Configurar evento para cerrar el modal al hacer clic afuera del modal
            window.onclick = function(event) {
                const modalPropuesta = document.getElementById("modalPropuesta");
                const modalEditar = document.getElementById("modalEditarPropuesta");
                if (event.target === modalPropuesta) {
                    cerrarModal();
                } else if (event.target === modalEditar) { // Si el clic fue en el fondo (fuera del contenido del modal)
                    cerrarModalEditar(); // Cierra el modal de edición
                }
            };
        });

        // Función para manejar los dropdowns (acciones de los botones)
        function toggleDropdown(button) {
            const container = button.closest('.dropdown-container');
            container.classList.toggle('active');
        }

        document.addEventListener('click', (event) => {
            const dropdowns = document.querySelectorAll('.dropdown-container');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });

        // Función para cambiar el estado de la propuesta (visible u oculta)
        function cambiarEstado(id, estadoActual) {
            const nuevoEstado = estadoActual === 'Visible' ? 'Oculta' : 'Visible';
            const formData = new FormData();
            formData.append('accion', 'cambiarEstado');
            formData.append('id', id);
            formData.append('nuevoEstado', nuevoEstado);

            fetch('gestionarPropuestas.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualiza el texto del estado en la tabla
                        const estadoElemento = document.querySelector(`#estado-${id}`);
                        const opcionElemento = document.querySelector(`#estado-opcion-${id}`);
                        if (estadoElemento && opcionElemento) {
                            estadoElemento.textContent = nuevoEstado; // Actualiza el estado mostrado
                            opcionElemento.textContent = nuevoEstado === 'Visible' ? 'Ocultar' : 'Visible'; // Actualiza el texto de la opción
                            opcionElemento.setAttribute('onclick', `cambiarEstado(${id}, '${nuevoEstado}')`); // Actualiza la lógica del clic
                        }
                    } else {
                        alert('Error al cambiar el estado: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function toggleFavorito(id, nuevoEstado) {
            const formData = new FormData();
            formData.append('accion', 'cambiarFavorito');
            formData.append('id', id);
            formData.append('esFavorita', nuevoEstado);

            fetch('gestionarPropuestas.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cambia el icono de la estrella en la tabla
                        const star = document.querySelector(`i[onclick="toggleFavorito(${id}, '${nuevoEstado}')"]`);
                        if (star) {
                            star.classList.toggle('fas');
                            star.classList.toggle('far');
                            star.setAttribute('onclick', `toggleFavorito(${id}, '${nuevoEstado === 'Sí' ? 'No' : 'Sí'}')`);
                        }
                    } else {
                        alert('Error al cambiar el favorito: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php

// Función para ocultar propuesta
function ocultarPropuesta($connection, $id)
{
    $query = "UPDATE PROPUESTAS SET ESTADO = 'Oculta' WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar consulta ocultar: " . $connection->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Propuesta oculta con éxito.";
    } else {
        die("No se ocultó ninguna fila en PROPUESTAS.");
    }
    $stmt->close();
}

// Función para mostrar propuesta
function mostrarPropuesta($connection, $id)
{
    $query = "UPDATE PROPUESTAS SET ESTADO = 'Visible' WHERE ID_PRO = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Error al preparar consulta mostrar: " . $connection->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Propuesta visible con éxito.";
    } else {
        die("No se pudo cambiar el estado de la propuesta a visible.");
    }
    $stmt->close();
}


?>