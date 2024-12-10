<?php
// Simular roles para pruebas
$rol = 'admin'; // Cambiar a 'superadmin' o null según lo que quieras probar

if ($rol !== 'admin' && $rol !== 'superadmin') {
    echo "Acceso denegado. Redirigiendo...";
    header('Location: ../Home/inicio.php'); // Redirige a la página principal
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propuestas</title>
    <link rel="stylesheet" href="estilosgestionarPropuestas.css">
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Simular conexión a la base de datos
                $propuestas = [
                    ['id' => 1, 'nombre_partido' => 'Partido A', 'descripcion' => 'Propuesta A', 'facultad' => 'Ingeniería'],
                    ['id' => 2, 'nombre_partido' => 'Partido B', 'descripcion' => 'Propuesta B', 'facultad' => 'Ciencias Sociales']
                ];

                foreach ($propuestas as $propuesta) {
                    echo "<tr>";
                    echo "<td>{$propuesta['nombre_partido']}</td>";
                    echo "<td>{$propuesta['descripcion']}</td>";
                    echo "<td>{$propuesta['facultad']}</td>";
                    if ($rol === 'admin' || $rol === 'superadmin') {
                        echo "<td>
                                <form method='POST' action='gestionarPropuestas.php' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$propuesta['id']}'>
                                    <button type='submit' name='accion' value='editar'>Editar</button>
                                    <button type='submit' name='accion' value='eliminar'>Eliminar</button>
                                </form>
                              </td>";
                    } else {
                        echo "<td>Sin acciones</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
