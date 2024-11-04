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
    $correo = $_POST['correo'] ?? '';
    $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';
    $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET contra = ? WHERE email = ?");
    $stmt->bind_param("ss", $nueva_contrasena_hash, $correo);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Contraseña cambiada exitosamente.</div>";
    } else {
        echo "<div class='error-message'>Error al cambiar la contraseña: " . $stmt->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="CSS/cambiar_contra.css">
</head>
<body>
    <div class="container">
        <h1>Cambiar Contraseña</h1>
        <form action="cambiar_contrasena.php" method="post">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>

            <input type="submit" value="Cambiar Contraseña">
        </form>
    </div>
</body>
</html>
