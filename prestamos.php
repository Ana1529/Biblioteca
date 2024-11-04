<?php
session_start(); // Iniciar la sesión

$host = 'localhost';
$usuario = 'root'; // Usuario de XAMPP (comúnmente 'root')
$contraseña = ''; // Contraseña, suele estar vacía en XAMPP
$nombre_base_datos = 'biblioteca'; // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Variable para almacenar mensajes de estado
$mensaje = "";

// Registrar préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_prestamo'])) {
    $nombre_usuario = $_POST['userName'];
    $titulo_libro = $_POST['bookTitle'];

    // Obtener id_usuario
    $query_usuario = "SELECT id_usuario FROM usuarios WHERE nombre = '$nombre_usuario' LIMIT 1";
    $resultado_usuario = $conexion->query($query_usuario);
    $usuario = $resultado_usuario->fetch_assoc();
    $id_usuario = $usuario['id_usuario'] ?? null;

    // Obtener id_libro
    $query_libro = "SELECT id_libro FROM libros WHERE titulo = '$titulo_libro' LIMIT 1";
    $resultado_libro = $conexion->query($query_libro);
    $libro = $resultado_libro->fetch_assoc();
    $id_libro = $libro['id_libro'] ?? null;

    // Verificar si se encontraron el usuario y el libro
    if ($id_usuario && $id_libro) {
        // Cambiar la consulta para insertar solo los campos válidos
        $sql = "INSERT INTO pedidos (id_usuario, id_libro, nombre, correo) VALUES ('$id_usuario', '$id_libro', '$nombre_usuario', 'correo@example.com')";

        if ($conexion->query($sql) === TRUE) {
            $mensaje = "Su solicitud ya fue enviada.";
        } else {
            $mensaje = "Error: " . $conexion->error;
        }
    } else {
        $mensaje = "Usuario o libro no encontrados.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Préstamos de Libros</title>
    <link rel="stylesheet" href="prestamos.css">
</head>
<body>
    <header>
        <h1>Solicitud de Préstamos de Libros</h1>
    </header>

    <!-- Mostrar el mensaje de estado aquí -->
    <?php if ($mensaje != ""): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="userName">Nombre del Usuario:</label>
        <input type="text" id="userName" name="userName" required>
        
        <label for="bookTitle">Título del Libro:</label>
        <input type="text" id="bookTitle" name="bookTitle" required>
        
        <button type="submit" name="registrar_prestamo">Solicitar Préstamo</button>
    </form>

</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>
