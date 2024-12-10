<?php
// Simular roles para pruebas
$rol = 'admin'; // Cambiar a 'superadmin' o null según lo que quieras probar

if ($rol !== 'admin' && $rol !== 'superadmin') {
    echo "Acceso denegado. Redirigiendo...";
    header('Location: ../Home/inicio.php'); // Redirige a la página principal
    exit();
}

// Simular conexión a la base de datos
$propuestas = [
    ['id' => 1, 'nombre_partido' => 'Partido A', 'descripcion' => 'Propuesta A', 'facultad' => 'Ingeniería', 'estado' => 'visible'],
    ['id' => 2, 'nombre_partido' => 'Partido B', 'descripcion' => 'Propuesta B', 'facultad' => 'Ciencias Sociales', 'estado' => 'visible']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id = $_POST['id'] ?? null;

    if ($accion === 'ocultar') {
        // Simular actualización en la base de datos
        foreach ($propuestas as &$propuesta) {
            if ($propuesta['id'] == $id) {
                $propuesta['estado'] = 'oculto';
                break;
            }
        }
    }
    if ($accion === 'eliminar') {
        // Simular eliminación
        $propuestas = array_filter($propuestas, fn($propuesta) => $propuesta['id'] != $id);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propuestas</title>
    <link rel="stylesheet" href="estilosGestionarPropuestas.css">
</head>
<body>
    <header>
        <h1>Gestión de Propuestas - Proceso de Elecciones UTA 2024</h1>
        <nav>
            <a href="../Home/inicio.php">Inicio</a>
            <a href="../Candidatos/candidatos.php">Candidatos</a>
            <a href="../Eventos_Noticias/eventos_noticias.php">Eventos y Noticias</a>
        </nav>
    </header>

    <div class="container">
        <h2>Administrar Propuestas</h2>

        <?php if ($rol === 'admin' || $rol === 'superadmin'): ?>
        <!-- Formulario para agregar propuesta -->
        <form method="POST" action="gestionarPropuestas.php">
            <h3>Agregar Nueva Propuesta</h3>
            <input type="text" name="nombrePartido" placeholder="Nombre del Partido" required>
            <textarea name="descripcionPropuesta" placeholder="Descripción de la propuesta" required></textarea>
            <select name="facultad" required>
                <option value="">Seleccionar Facultad o Interés</option>
                <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                <option value="Ciencia e Ingeniería en Alimentos">Ciencia e Ingeniería en Alimentos</option>
                <!-- Más opciones -->
            </select>
            <button type="submit" name="accion" value="agregar">Agregar Propuesta</button>
        </form>
        <?php endif; ?>

        <!-- Listado de propuestas -->
        <h3>Propuestas Existentes</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Partido</th>
                    <th>Descripción</th>
                    <th>Facultad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propuestas as $propuesta): ?>
                    <?php if ($rol === 'admin' || $rol === 'superadmin'): ?>
                        <tr>
                            <td><?= $propuesta['nombre_partido'] ?></td>
                            <td><?= $propuesta['descripcion'] ?></td>
                            <td><?= $propuesta['facultad'] ?></td>
                            <td><?= $propuesta['estado'] ?></td>
                            <td>
                                <form method="POST" action="gestionarPropuestas.php">
                                    <input type="hidden" name="id" value="<?= $propuesta['id'] ?>">
                                    <button type="submit" name="accion" value="editar">Editar</button>
                                    <button type="submit" name="accion" value="eliminar">Eliminar</button>
                                    <button type="submit" name="accion" value="ocultar" class="ocultar">Ocultar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
