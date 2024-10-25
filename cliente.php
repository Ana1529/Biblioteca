<?php
session_start(); 

// Verificar que el usuario esté autenticado y sea un cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_roles'] != 3) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Consulta para obtener los libros disponibles
$sql = "SELECT titulo, autor FROM libros";
$result = $conn->query($sql);

if (!$result) {
    echo "Error al obtener los libros: " . $conn->error;
    exit();
}

// Mostrar un mensaje de bienvenida al cliente
echo "Bienvenido, Cliente " . htmlspecialchars($_SESSION['usuario']['nombre']) . "!";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página del Cliente</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Agrega tu archivo CSS aquí -->
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>!</h1>
    <p>Aquí puedes ver los libros disponibles:</p>

    <ul>
        <?php while ($libro = $result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($libro['titulo']) . " - " . htmlspecialchars($libro['autor']); ?></li>
        <?php endwhile; ?>
    </ul>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
