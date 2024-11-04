<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

$mensaje = '';

// Comprobar si se recibió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $id_libro = $_POST['id_libro'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];

    // Consultar el título del libro
    $queryLibro = "SELECT titulo FROM libros WHERE id_libro = ?";
    $stmtLibro = $conexion->prepare($queryLibro);
    $stmtLibro->bind_param("i", $id_libro);
    $stmtLibro->execute();
    $resultadoLibro = $stmtLibro->get_result();

    if ($resultadoLibro->num_rows > 0) {
        // Obtener el título del libro
        $filaLibro = $resultadoLibro->fetch_assoc();
        $titulo_libro = $filaLibro['titulo'];

        // Preparar la consulta para agregar un nuevo préstamo
        $query = "INSERT INTO prestamos (id_usuario, id_libro, titulo, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        
        // Enlazar parámetros
        $stmt->bind_param("iisss", $id_usuario, $id_libro, $titulo_libro, $fecha_prestamo, $fecha_devolucion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = "Préstamo registrado con éxito";
        } else {
            $mensaje = "Error al registrar el préstamo: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $mensaje = "El libro no se encontró en la base de datos.";
    }

    // Cerrar la conexión
    $stmtLibro->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Préstamo</title>
    <link rel="stylesheet" href="CSS/agregar_prestamo.css"> 
</head>
<body>
    <div class="container">
        <h1>Registrar Préstamo de Libro</h1>

        <?php if ($mensaje): ?>
            <div class="mensaje-exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label for="id_usuario">ID de Usuario:</label>
            <input type="number" id="id_usuario" name="id_usuario" required>

            <label for="id_libro">ID de Libro:</label>
            <input type="number" id="id_libro" name="id_libro" required>

            <label for="fecha_prestamo">Fecha de Préstamo:</label>
            <input type="date" id="fecha_prestamo" name="fecha_prestamo" value="<?php echo date('Y-m-d'); ?>" required>

            <label for="fecha_devolucion">Fecha de Devolución (opcional):</label>
            <input type="date" id="fecha_devolucion" name="fecha_devolucion">

            <button type="submit">Registrar Préstamo</button>
        </form>

        <button onclick="window.location.href='gestionar_prestamos.php'">Volver</button>
    </div>
</body>
</html>
