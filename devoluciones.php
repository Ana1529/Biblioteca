<?php
session_start(); // Iniciar la sesión

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$nombre_bd = "biblioteca";

$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Manejar la lógica de la devolución (marcar como devuelto)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['marcar_devuelto'])) {
    $id_prestamo = $_POST['id_prestamo'];
    $fecha_devolucion = date('Y-m-d'); // Fecha actual
    $estado = $_POST['estado'];

    // Insertar la devolución en la base de datos
    $query_insertar_devolucion = "INSERT INTO devoluciones (id_prestamo, fecha_devolucion, estado) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query_insertar_devolucion);
    $stmt->bind_param('iss', $id_prestamo, $fecha_devolucion, $estado);
    
    if ($stmt->execute()) {
        // Eliminar el préstamo de la tabla prestamos
        $query_eliminar_prestamo = "DELETE FROM prestamos WHERE id_prestamo = ?";
        $stmt_eliminar = $conexion->prepare($query_eliminar_prestamo);
        $stmt_eliminar->bind_param('i', $id_prestamo);
        $stmt_eliminar->execute();
        
        $_SESSION['mensaje'] = "Devolución registrada correctamente y préstamo eliminado.";
    } else {
        $_SESSION['mensaje'] = "Error al registrar la devolución: " . $stmt->error;
    }
    $stmt->close();
    $stmt_eliminar->close();
}

// Manejar la eliminación de registros del historial de devoluciones
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_devolucion'])) {
    $id_devolucion = $_POST['id_devolucion'];

    // Eliminar el registro de la tabla devoluciones
    $query_eliminar_devolucion = "DELETE FROM devoluciones WHERE id_devolucion = ?";
    $stmt = $conexion->prepare($query_eliminar_devolucion);
    $stmt->bind_param('i', $id_devolucion);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Devolución eliminada del historial.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la devolución: " . $stmt->error;
    }
    $stmt->close();
}

// Consultar préstamos activos
$query_prestamos = "SELECT p.id_prestamo, u.nombre AS nombre_usuario, b.titulo AS titulo_libro, 
                    b.autor, p.fecha_prestamo, p.fecha_devolucion 
                    FROM prestamos p 
                    JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    JOIN libros b ON p.id_libro = b.id_libro 
                    WHERE p.devuelto = 0"; // Solo préstamos no devueltos

$resultado_prestamos = $conexion->query($query_prestamos);

if ($resultado_prestamos === FALSE) {
    die("Error en la consulta: " . $conexion->error);
}

// Consultar historial de devoluciones
$query_historial = "SELECT d.id_devolucion, p.id_prestamo, u.nombre AS nombre_usuario, b.titulo AS titulo_libro, 
                    d.fecha_devolucion, d.estado 
                    FROM devoluciones d 
                    JOIN prestamos p ON d.id_prestamo = p.id_prestamo 
                    JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    JOIN libros b ON p.id_libro = b.id_libro 
                    ORDER BY d.fecha_devolucion DESC"; // Historial ordenado por fecha

$resultado_historial = $conexion->query($query_historial);

if ($resultado_historial === FALSE) {
    die("Error en la consulta del historial: " . $conexion->error);
}

// Mostrar mensaje de sesión
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']); // Limpiar mensaje después de mostrarlo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Devoluciones</title>
    <link rel="stylesheet" href="css/return.css">
</head>
<body>
    <div class="conteiner">
        <header>
            <h1>Registrar Devoluciones</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
        </header>

        <div class="wrapper">
        
            <?php if ($mensaje != ""): ?>
                <p><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="buscar_usuario">Buscar Usuario (Nombre o ID):</label>
                <input type="text" name="buscar_usuario" id="buscar_usuario" required>

                <label for="buscar_libro">Buscar Libro (Título o ISBN):</label>
                <input type="text" name="buscar_libro" id="buscar_libro" required>

                <label for="fecha_devolucion">Fecha de Devolución:</label>
                <input type="date" name="fecha_devolucion" value="<?php echo date('Y-m-d'); ?>" readonly>

                <input type="hidden" name="id_prestamo" id="id_prestamo">

                <label for="estado">Estado de la Devolución:</label>
                <select name="estado" id="estado" required>
                    <option value="devuelto">Devuelto</option>
                    <option value="deteriorado">Deteriorado</option>
                    <option value="perdido">Perdido</option>
                </select>
                <br>

                <div class="button-group">
                    <button type="submit" name="registrar_devolucion">Registrar Devolución</button>
                    <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
                </div>

            <h2>Lista de Préstamos Activos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Préstamo</th>
                        <th>Nombre Usuario</th>
                        <th>Título del Libro</th>
                        <th>Autor</th>
                        <th>Fecha de Préstamo</th>
                        <th>Fecha de Devolución Original</th>
                        <th>Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prestamo = $resultado_prestamos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $prestamo['id_prestamo']; ?></td>
                            <td><?php echo htmlspecialchars($prestamo['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['titulo_libro']); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['autor']); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id_prestamo']; ?>">
                                    <input type="hidden" name="estado" value="devuelto"> <!-- Estado predeterminado -->
                                    <button type="submit" name="marcar_devuelto">Marcar como devuelto</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2>Historial de Devoluciones</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Devolución</th>
                        <th>ID Préstamo</th>
                        <th>Nombre Usuario</th>
                        <th>Título del Libro</th>
                        <th>Fecha de Devolución</th>
                        <th>Estado</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($historial = $resultado_historial->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $historial['id_devolucion']; ?></td>
                            <td><?php echo $historial['id_prestamo']; ?></td>
                            <td><?php echo htmlspecialchars($historial['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($historial['titulo_libro']); ?></td>
                            <td><?php echo htmlspecialchars($historial['fecha_devolucion']); ?></td>
                            <td><?php echo htmlspecialchars($historial['estado']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="id_devolucion" value="<?php echo $historial['id_devolucion']; ?>">
                                    <button type="submit" name="eliminar_devolucion">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>
