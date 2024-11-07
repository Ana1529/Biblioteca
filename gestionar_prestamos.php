<?php  //holllaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
session_start(); // Iniciar la sesión

include 'conexion.php';

$servidor = "localhost";
$usuario = "root"; 
$contrasena = "";
$nombre_bd = 'biblioteca';

$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Mostrar mensaje de sesión
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']); // Limpiar mensaje después de mostrarlo

// Registrar un nuevo préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_prestamo'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_libro = $_POST['id_libro'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    // Insertar el nuevo préstamo
    $stmt = $conexion->prepare("INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Préstamo registrado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "Error al registrar el préstamo: " . $stmt->error;
    }
    $stmt->close();
}

// Manejar la acción de prestar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prestar'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_libro = $_POST['id_libro'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    // Insertar el nuevo préstamo
    $stmt = $conexion->prepare("INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion);

    if ($stmt->execute()) {
        // El préstamo se registró con éxito, ahora eliminar el pedido
        $stmt_delete = $conexion->prepare("DELETE FROM pedidos WHERE id_usuario = ? AND id_libro = ?");
        $stmt_delete->bind_param("ii", $id_usuario, $id_libro);
        
        if ($stmt_delete->execute()) {
            $_SESSION['mensaje'] = "Prestado con éxito y pedido eliminado.";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el pedido: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    } else {
        $_SESSION['mensaje'] = "Error al prestar: " . $stmt->error;
    }
    $stmt->close();
}


// Consultar los préstamos
$query_prestamos = "
SELECT p.id_prestamo, u.nombre AS nombre_usuario, u.email AS email_usuario,
       b.titulo AS titulo_libro, b.autor AS autor_libro, 
       b.editorial AS editorial_libro, b.isbn AS isbn_libro,
       p.fecha_prestamo, p.fecha_devolucion
FROM prestamos p
JOIN usuarios u ON p.id_usuario = u.id_usuario
JOIN libros b ON p.id_libro = b.id_libro"; 

$resultado_prestamos = $conexion->query($query_prestamos);

if ($resultado_prestamos === FALSE) {
    die("Error en la consulta: " . $conexion->error);
}

// Consultar los pedidos de préstamos
$query_pedidos = "
SELECT p.id_pedido, u.nombre AS nombre_usuario, 
       b.titulo AS titulo_libro, p.fecha_pedido,
       p.id_usuario, p.id_libro 
FROM pedidos p
JOIN usuarios u ON p.id_usuario = u.id_usuario
JOIN libros b ON p.id_libro = b.id_libro"; 

$resultado_pedidos = $conexion->query($query_pedidos);

if ($resultado_pedidos === FALSE) {
    die("Error en la consulta: " . $conexion->error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Préstamos</title>
    <link rel="stylesheet" href="CSS/gestionar_prestamo.css"> 
</head>
<body>

    <div class="container">
        
        <header>
            <h1>Gestionar Préstamos</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
        </header>
        
        <?php if ($mensaje != ""): ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        
        <!-- Formulario para registrar un nuevo préstamo -->
        <form method="POST" action="">
            <label for="id_usuario">ID Usuario:</label>
            <input type="number" name="id_usuario" required>
            
            <label for="id_libro">ID Libro:</label>
            <input type="number" name="id_libro" required>
            
            <label for="fecha_prestamo">Fecha de Préstamo:</label>
            <input type="date" name="fecha_prestamo" required>
            
            <label for="fecha_devolucion">Fecha de Devolución:</label>
            <input type="date" name="fecha_devolucion" required>
            
            <button type="submit" name="registrar_prestamo">Registrar Préstamo</button>
            <button type="button" onclick="window.location.href='administrador.php'">Volver</button>

        </form>

        <!-- Tabla de préstamos registrados -->
        <h2>Préstamos Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Préstamo</th>
                    <th>Nombre Usuario</th>
                    <th>Email Usuario</th>
                    <th>Título del Libro</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>ISBN</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($prestamo = $resultado_prestamos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $prestamo['id_prestamo']; ?></td>
                        <td><?php echo htmlspecialchars($prestamo['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['email_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['titulo_libro']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['autor_libro']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['editorial_libro']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['isbn_libro']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['fecha_devolucion']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tabla de pedidos de préstamos -->
        <h2>Pedidos de Préstamos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Nombre Usuario</th>
                    <th>Título del Libro</th>
                    <th>Fecha de Solicitud</th>
                    <th>Acciones</th> <!-- Nueva columna para acciones -->
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $resultado_pedidos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $pedido['id_pedido']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['titulo_libro']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id_usuario" value="<?php echo $pedido['id_usuario']; ?>">
                                <input type="hidden" name="id_libro" value="<?php echo $pedido['id_libro']; ?>">
                                <input type="hidden" name="fecha_prestamo" value="<?php echo date('Y-m-d'); ?>">
                                <input type="hidden" name="fecha_devolucion" value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>"> <!-- Ejemplo de 15 días para la devolución -->
                                <button type="submit" name="prestar">Prestar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    <?php
    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>
