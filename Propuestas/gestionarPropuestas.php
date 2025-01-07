<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../config/config.php');
include('../src/gestionarPropuestas_queries.php');

// Obtener el nombre del partido con ID 1
$consultaPartido = "SELECT NOM_PAR FROM PARTIDOS_POLITICOS WHERE ID_PAR = 1";
$resultadoPartido = $connection->query($consultaPartido);

$nombrePartido = "Partido Desconocido"; // Valor por defecto en caso de error
if ($resultadoPartido && $resultadoPartido->num_rows > 0) {
    $filaPartido = $resultadoPartido->fetch_assoc();
    $nombrePartido = $filaPartido['NOM_PAR'];
}

// Configuración de sesión y redirección según el rol
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['ADMIN', 'SUPERADMIN'])) {
    $_SESSION['error'] = 'Acceso denegado. Por favor inicia sesión.';
    header('Location: ../Login/Login.php');
    exit();
}

// Configuración de paginación
$propuestasPorPagina = 6; // Número de propuestas por página
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $propuestasPorPagina;

// Validar que $propuestasPorPagina no sea cero
if ($propuestasPorPagina <= 0) {
    die("Error: El número de propuestas por página debe ser mayor que 0.");
}


function manejarSubidaImagen()
{
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $nombreImagen = basename($_FILES['imagen']['name']);
        $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);

        if (!in_array(strtolower($extension), $extensionesPermitidas)) {
            die("Error: Solo se permiten archivos JPG, JPEG, PNG o GIF.");
        }

        $nombreUnico = time() . "_" . uniqid() . "." . $extension;
        $rutaCarpeta = "../Propuestas/img/"; // Ruta donde se guardará la imagen
        $rutaDestino = $rutaCarpeta . $nombreUnico;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            // ✅ Guardamos la ruta correcta en la base de datos (sin '../')
            return "/Pagina_Web/Pagina_Web/Propuestas/img/" . $nombreUnico;
        } else {
            die("Error al mover la imagen al directorio.");
        }
    }
    return null;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'agregar') {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $partido = $_POST['partido'];
        $estado = $_POST['estado'];
        $imagenUrl = manejarSubidaImagen();

        if (!$imagenUrl) {
            die("Error: Debe seleccionar una imagen antes de guardar la propuesta.");
        }
        // ✅ Aquí se maneja la imagen correctamente

        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido) || empty($estado)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        try {
            agregarPropuestaYColaboracion($connection, $titulo, $descripcion, $categoria, $partido, $estado, $imagenUrl);
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
        $estado = $_POST['estado'] ?? '';
        $imagenUrl = manejarSubidaImagen(); // ✅ Primero intentamos subir la nueva imagen

        if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($partido) || empty($estado)) {
            die("Error: Faltan datos en el formulario. Verifique los campos.");
        }

        // **Si no se subió una nueva imagen, obtenemos la imagen actual de la BD**
        if (!$imagenUrl) {
            $queryImagenActual = "SELECT IMAGEN_URL FROM PROPUESTAS WHERE ID_PRO = ?";
            $stmtImagen = $connection->prepare($queryImagenActual);
            $stmtImagen->bind_param("i", $id);
            $stmtImagen->execute();
            $resultado = $stmtImagen->get_result();
            $propuesta = $resultado->fetch_assoc();
            $stmtImagen->close();

            $imagenUrl = !empty($propuesta['IMAGEN_URL']) ? $propuesta['IMAGEN_URL'] : ""; // Evita que sea null
        }

        try {
            actualizarPropuesta($connection, $id, $titulo, $descripcion, $categoria, $partido, $estado, $imagenUrl);
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
        PROPUESTAS.ES_FAVORITA, 
        PROPUESTAS.IMAGEN_URL,  -- 🔹 Asegura que esta columna está incluida
        GROUP_CONCAT(PARTIDOS_POLITICOS.NOM_PAR SEPARATOR ', ') AS PARTIDOS,
        PARTIDOS_POLITICOS.ID_PAR AS ID_PAR
    FROM PROPUESTAS
    LEFT JOIN COLABORACIONES ON PROPUESTAS.ID_PRO = COLABORACIONES.ID_PRO_COL
    LEFT JOIN PARTIDOS_POLITICOS ON COLABORACIONES.ID_PAR_COL = PARTIDOS_POLITICOS.ID_PAR
    GROUP BY PROPUESTAS.ID_PRO
    ORDER BY PROPUESTAS.ID_PRO ASC
    LIMIT ? OFFSET ?";


// Prepara la consulta
$stmt = $connection->prepare($query);

if (!$stmt) {
    die("Error al preparar la consulta: " . $connection->error);
}

// Vínculo de los parámetros
$stmt->bind_param("ii", $propuestasPorPagina, $offset);

// Ejecutar la consulta
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

// Obtener los resultados
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body>

    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <div class="text-center">
                <!-- Icono según el rol -->
                <i class="fa-solid fa-user-shield fa-2x"></i>
                <h6 class="mt-2 navbar-role">
                    <?php echo $_SESSION['user_role'] === 'SUPERADMIN' ? 'SuperAdmin' : 'Admin'; ?>
                </h6>
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
                <li>
                    <a href="<?php echo ($_SESSION['user_role'] === 'SUPERADMIN') ? '../Login/Administracion.php' : '../Login/Administracion_admin.php'; ?>">
                        <i class="fa-solid fa-cogs"></i> <span>Administración</span>
                    </a>
                </li>
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
                    <th>Imagen</th> <!-- 🔹 Nueva columna para la imagen -->
                    <th>Favorito</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['PARTIDOS'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['TIT_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['DESC_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['CAT_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td id="estado-<?= $row['ID_PRO'] ?>"><?= htmlspecialchars($row['ESTADO'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>


                            <td>
                                <?php if (!empty(trim($row['IMAGEN_URL']))) : ?>
                                    <img src="<?= 'http://localhost' . trim($row['IMAGEN_URL']) ?>" alt="Imagen de la propuesta" width="50">
                                <?php else : ?>
                                    <span>Sin imagen</span>
                                <?php endif; ?>
                            </td>























                            <td>
                                <div class="star-container">
                                    <i
                                        class="fa-star <?= $row['ES_FAVORITA'] === 'Sí' ? 'fas' : 'far' ?>"
                                        style="cursor: pointer; color: gold;"
                                        onclick="toggleFavorito(<?= $row['ID_PRO'] ?>, '<?= $row['ES_FAVORITA'] === 'Sí' ? 'No' : 'Sí' ?>')">
                                    </i>
                                </div>
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
        '<?= htmlspecialchars($row['ID_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['TIT_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['DESC_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['CAT_PRO'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['ID_PAR'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['ESTADO'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
        '<?= htmlspecialchars($row['IMAGEN_URL'] ?? '', ENT_QUOTES, 'UTF-8') ?>'
    ); event.preventDefault();">
                                            Editar
                                        </a>
                                        <a role="button" class="text-danger" onclick="eliminarPropuesta(<?= $row['ID_PRO'] ?>); event.preventDefault();">
                                            Eliminar
                                        </a>
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
                        <td colspan="9">No hay propuestas registradas.</td> <!-- Ajusté colspan a 9 por la nueva columna -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <div class="pagination-container text-center">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <script>
            // Ocultar los mensajes de estado después de 5 segundos
            setTimeout(() => {
                const messages = document.querySelectorAll('.success, .info');
                messages.forEach(message => {
                    message.style.display = 'none';
                });
            }, 5000); // 5000 ms = 5 segundos
        </script>

    </div>
    </div>

    <div id="modalPropuesta" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="cerrarModal()">&times;</span>
            <h2>Agregar Nueva Propuesta</h2>
            <form method="POST" action="gestionarPropuestas.php" enctype="multipart/form-data"> <!-- 🔹 Agregado enctype -->
                <input type="hidden" name="accion" value="agregar">

                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" maxlength="430" required oninput="contarCaracteres('descripcion', 'contadorDescripcion')"></textarea>
                <small id="contadorDescripcion">0/430 caracteres</small>

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

                <!-- Campo Partido Político: Siempre con ID 1 -->
                <label for="partido">Partido Político:</label>
                <select name="partido" id="partido" class="form-select" required disabled>
                    <option value="1" selected><?= htmlspecialchars($nombrePartido) ?></option>
                </select>
                <input type="hidden" name="partido" value="1">

                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="" disabled selected>Seleccione el Estado</option>
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

                <!-- 🔹 Campo para subir imagen -->
                <label for="imagen">Imagen:</label>
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">

                <button type="submit" class="btn btn-danger">Guardar Propuesta</button>
            </form>
        </div>
    </div>



    <div id="modalEditarPropuesta" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="cerrarModalEditar()">&times;</span>
            <h2>Editar Propuesta</h2>
            <form id="formEditarPropuesta" method="POST" action="gestionarPropuestas.php" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" id="idEditarPropuesta">

                <!-- Título -->
                <label for="tituloEditar">Título:</label>
                <input type="text" id="tituloEditar" name="titulo" class="form-control" required>

                <!-- Descripción -->
                <label for="descripcionEditar">Descripción:</label>
                <textarea id="descripcionEditar" name="descripcion" class="form-control" maxlength="430" required oninput="contarCaracteres('descripcionEditar', 'contadorDescripcionEditar')"></textarea>
                <small id="contadorDescripcionEditar">0/430 caracteres</small>


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
                <select id="partidoEditar" name="partido" class="form-select" required disabled>
                    <?php
                    // Restablecemos el puntero para recorrer nuevamente la consulta
                    mysqli_data_seek($partidos, 0);

                    while ($partido = $partidos->fetch_assoc()):
                        $idPar = isset($partido['ID_PAR']) ? $partido['ID_PAR'] : '';
                        $nomPar = isset($partido['NOM_PAR']) ? $partido['NOM_PAR'] : '';
                    ?>
                        <option value="<?= htmlspecialchars($idPar, ENT_QUOTES, 'UTF-8') ?>"
                            <?= isset($row['ID_PAR']) && $row['ID_PAR'] === $idPar ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nomPar, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Input oculto para enviar el ID del partido seleccionado -->
                <input type="hidden" name="partido" id="partidoEditarHidden">

                <!-- Estado -->
                <label for="estadoEditar">Estado:</label>
                <select id="estadoEditar" name="estado" class="form-select" required>
                    <option value="Visible" <?= isset($row['ESTADO']) && $row['ESTADO'] === 'Visible' ? 'selected' : '' ?>>Visible</option>
                    <option value="Oculta" <?= isset($row['ESTADO']) && $row['ESTADO'] === 'Oculta' ? 'selected' : '' ?>>Oculta</option>
                </select>

                <!-- 🔹 Imagen actual -->
                <label>Imagen actual:</label>
                <div>
                    <img id="previewImagenEditar" src="" width="100" alt="No hay imagen disponible">
                </div>

                <!-- 🔹 Campo para subir una nueva imagen -->
                <label for="imagenEditar">Cambiar Imagen:</label>
                <input type="file" name="imagen" id="imagenEditar" class="form-control" accept="image/*">

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
        function abrirModalEditar(id, titulo, descripcion, categoria, partido, estado, imagenUrl) {
            // Asegurar que los valores no sean null o undefined
            document.getElementById('idEditarPropuesta').value = id ?? '';
            document.getElementById('tituloEditar').value = titulo ?? '';
            document.getElementById('descripcionEditar').value = descripcion ?? '';
            document.getElementById('categoriaEditar').value = categoria ?? '';

            // Asignar el ID del partido político en el select (deshabilitado) y en el input hidden
            const partidoSelect = document.getElementById('partidoEditar');
            const partidoHidden = document.getElementById('partidoEditarHidden');

            if (partidoSelect) {
                if (partidoSelect.querySelector(`option[value="${partido}"]`)) {
                    partidoSelect.value = partido;
                } else {
                    console.warn(`⚠️ El partido con ID ${partido} no está en la lista de opciones.`);
                }
            } else {
                console.error("❌ No se encontró el select para partidos.");
            }

            if (partidoHidden) {
                partidoHidden.value = partido ?? ''; // Asegurar que el ID se envíe en el formulario
            } else {
                console.error("❌ No se encontró el input hidden para partidos.");
            }

            // Asignar el estado de la propuesta
            const estadoSelect = document.getElementById('estadoEditar');
            if (estadoSelect) {
                if (estadoSelect.querySelector(`option[value="${estado}"]`)) {
                    estadoSelect.value = estado;
                } else {
                    console.warn(`⚠️ El estado "${estado}" no está en la lista de opciones.`);
                }
            } else {
                console.error("❌ No se encontró el select para estado.");
            }

            // Asignar la imagen al campo de previsualización
            const previewImagen = document.getElementById('previewImagenEditar');
            if (previewImagen) {
                if (imagenUrl && imagenUrl.trim() !== "") {
                    previewImagen.src = imagenUrl;
                    previewImagen.style.display = "block"; // Mostrar imagen
                } else {
                    previewImagen.src = "";
                    previewImagen.style.display = "none"; // Ocultar si no hay imagen
                }
            } else {
                console.error("❌ No se encontró el elemento de previsualización de la imagen.");
            }

            // Mostrar el modal de edición
            document.getElementById("modalEditarPropuesta").style.display = 'flex';
        }



        function contarCaracteres(textareaId, contadorId) {
            var textarea = document.getElementById(textareaId);
            var contador = document.getElementById(contadorId);
            contador.textContent = textarea.value.length + "/430 caracteres";
        }


        function mostrarMensajeActualizado() {
            Swal.fire({
                title: '¡Actualizado!',
                text: 'El evento/noticia se actualizó correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
        }

        function mostrarMensajeEliminado() {
            Swal.fire({
                title: '¡Eliminado!',
                text: 'La propuesta se eliminó correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
        }

        function mostrarMensajeOcultar() {
            Swal.fire({
                title: '¡Ocultado!',
                text: 'La propuesta ahora está oculta.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
        }

        function mostrarMensajeVisible() {
            Swal.fire({
                title: '¡Visible!',
                text: 'La propuesta ahora es visible.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
        }

        function mostrarMensajeFavorito() {
            Swal.fire({
                title: '¡Favorito!',
                text: 'La propuesta se marcó como favorita.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
        }

        function mostrarMensajeNoFavorito() {
            Swal.fire({
                title: '¡No favorito!',
                text: 'La propuesta se desmarcó como favorita.',
                icon: 'info',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); // Recarga la página después de cerrar el mensaje
            });
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


        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'added') {
                Swal.fire({
                    title: '¡Agregado!',
                    text: 'La propuesta se agregó correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirigir a la misma página pero sin el parámetro 'status'
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'edited') {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: 'La propuesta se actualizó correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'deleted') {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: 'La propuesta se eliminó correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'hidden') {
                Swal.fire({
                    title: '¡Ocultado!',
                    text: 'La propuesta ahora está oculta.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'visible') {
                Swal.fire({
                    title: '¡Visible!',
                    text: 'La propuesta ahora es visible.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'favorited') {
                Swal.fire({
                    title: '¡Favorito!',
                    text: 'La propuesta se marcó como favorita.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            } else if (status === 'unfavorited') {
                Swal.fire({
                    title: '¡No favorito!',
                    text: 'La propuesta se desmarcó como favorita.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                    window.location.reload(); // Recarga la página después de redirigir sin el parámetro
                });
            }
        });


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

                        // Mostrar mensaje con SweetAlert
                        Swal.fire({
                            title: nuevoEstado === 'Visible' ? '¡Visible!' : '¡Ocultado!',
                            text: nuevoEstado === 'Visible' ? 'La propuesta ahora es visible.' : 'La propuesta ahora está oculta.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload(); // Recarga la página después de cerrar el mensaje
                        });

                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al cambiar el estado.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al cambiar el estado.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
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

                        // Mostrar el mensaje de éxito
                        Swal.fire({
                            title: nuevoEstado === 'Sí' ? '¡Favorito!' : '¡No favorito!',
                            text: nuevoEstado === 'Sí' ? 'La propuesta se marcó como favorita.' : 'La propuesta se desmarcó como favorita.',
                            icon: nuevoEstado === 'Sí' ? 'success' : 'info',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload(); // Recarga la página después de cerrar el mensaje
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al cambiar el favorito.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al cambiar el favorito.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }


        function eliminarPropuesta(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta propuesta?')) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id', id);

                fetch('gestionarPropuestas.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la solicitud de eliminación.');
                        }
                        return response.text();
                    })
                    .then(data => {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'La propuesta se eliminó correctamente.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Redirige a la misma página pero sin el parámetro 'status' en la URL
                            window.history.replaceState({}, document.title, window.location.pathname);
                            window.location.reload(); // Recarga la página después de cerrar el mensaje
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al eliminar la propuesta.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        }



        document.addEventListener("DOMContentLoaded", function() {
            const formAgregar = document.querySelector("#modalPropuesta form");

            if (formAgregar) {
                formAgregar.addEventListener("submit", function(event) {
                    const inputImagen = document.getElementById("imagen");

                    if (!inputImagen.files || inputImagen.files.length === 0) {
                        event.preventDefault(); // Detiene el envío del formulario

                        Swal.fire({
                            title: "Error",
                            text: "Debe seleccionar una imagen antes de guardar la propuesta.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });

                        return false;
                    }
                });
            }
        });
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