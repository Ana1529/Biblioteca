<?php
// Conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$nombre_bd = 'biblioteca';

$conn = new mysqli($host, $usuario, $contraseña, $nombre_bd);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nombre_libro = $_POST['nombre_libro'] ?? null;
    $isbn = $_POST['isbn'] ?? null;
    $fecha_prestamo = $_POST['fecha_prestamo'] ?? date('Y-m-d');
    $fecha_devolucion = $_POST['fecha_devolucion'] ?? null;

    // Preparar la consulta para buscar el libro
    $stmt = $conn->prepare("SELECT id_libro, titulo FROM libros WHERE titulo = ? AND isbn = ?");
    $stmt->bind_param("ss", $nombre_libro, $isbn);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // El libro existe, proceder a insertar el préstamo
            $row = $result->fetch_assoc();
            $id_libro = $row['id_libro'];
            $titulo_libro = $row['titulo'];

            // Preparar y ejecutar la consulta para insertar el préstamo
            $stmt = $conn->prepare("INSERT INTO prestamos (id_usuario, id_libro, titulo, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $id_usuario, $id_libro, $titulo_libro, $fecha_prestamo, $fecha_devolucion);

            if ($stmt->execute()) {
                echo "<div class='success-message'>Préstamo añadido exitosamente.</div>";
            } else {
                echo "<div class='error-message'>Error al añadir el préstamo: " . $stmt->error . "</div>";
            }
        } else {
            echo "<div class='error-message'>Libro no encontrado. Verifica el nombre y el ISBN.</div>";
        }
    } else {
        echo "<div class='error-message'>Error en la consulta: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos de Libros</title>
    <link rel="stylesheet" href="CSS/prestamo.css"> 
</head>
<body>
    <div class="conteiner">
        <header>
            <h1>Registrar Préstamo de Libro</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="IMG/usuario.png" alt="Usuario" class="icon" width="30" height="30">
            </div>
        </header>

        <form action="prestamos.php" method="post">
            <label for="id_usuario">ID de Usuario:</label>
            <input type="number" id="id_usuario" name="id_usuario" required>

            <label for="nombre_libro">Nombre del Libro:</label>
            <input type="text" id="nombre_libro" name="nombre_libro" required>

            <label for="isbn">ISBN del Libro:</label>
            <input type="text" id="isbn" name="isbn" required>

            <label for="fecha_prestamo">Fecha de Préstamo:</label>
            <input type="date" id="fecha_prestamo" name="fecha_prestamo" value="<?php echo date('Y-m-d'); ?>" required>

            <label for="fecha_devolucion">Fecha de Devolución (opcional):</label>
            <input type="date" id="fecha_devolucion" name="fecha_devolucion">

            <input type="submit" value="Registrar Préstamo">
        </form>

        <div class="button-group">
            <button onclick="location.href='prestamos.php';">
                Registrar Nuevo Préstamo
            </button>
            <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
        </div>

        <!-- Aquí puedes mostrar mensajes de éxito o error -->
        <?php if (isset($message)): ?>
            <div class="message <?= $messageType; ?>">
                <?= $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
