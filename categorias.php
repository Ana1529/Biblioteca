<?php
$host = 'localhost';
$usuario = 'root'; 
$contraseña = ''; 
$nombre_base_datos = 'biblioteca'; 

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Variable para almacenar mensajes de estado
$mensaje = "";

// Crear nueva categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_categoria'])) {
    $nombre_categoria = $_POST['nombre_categoria'];
    
    $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre_categoria')";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Categoría creada correctamente.";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id_categoria = $_GET['eliminar'];
    
    $sql = "DELETE FROM categorias WHERE id_categoria = $id_categoria";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Categoría eliminada correctamente.";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Consultar categorías
$query = "SELECT * FROM categorias";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <link rel="stylesheet" href="CSS/categorias.css"> 
</head>
<body>
    <header>
        <h1>Gestión de Categorías</h1>
        <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
    </header>

    <!-- Mostrar el mensaje de estado aquí -->
    <?php if ($mensaje != ""): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <!-- Formulario para crear categorías -->
    <form method="post">
        <label for="nombre_categoria">Nombre de la Categoría:</label>
        <input type="text" id="nombre_categoria" name="nombre_categoria" required>
        <button type="submit" name="crear_categoria">Crear Categoría</button>
        <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
    </form>

    <h2>Categorías Existentes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_categoria']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td>
                    <a href="?eliminar=<?php echo $row['id_categoria']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
</body>
</html>
