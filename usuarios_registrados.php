<?php
// Conexión a la base de datos
$host = 'localhost';
$usuario = 'root'; // Cambia esto según tus configuraciones
$contraseña = ''; // Cambia esto si tienes una contraseña
$nombre_bd = 'biblioteca';

$conn = new mysqli($host, $usuario, $contraseña, $nombre_bd);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener usuarios registrados
$result = $conn->query("SELECT * FROM usuarios");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="CSS/usuarios.css">

</head>

<body>
    <h1>Usuarios Registrados</h1>
    
    <div class="table-container">
        <table>
            <thead>
                <tr class="table-header">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Contraseña</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Mostrar datos de cada usuario
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id_usuario']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['apellido']}</td>
                            <td>{$row['email']}</td>
                            <td>" . substr($row['contra'], 0, 3) . str_repeat('*', strlen($row['contra']) - 3) . "</td>
                            <td>{$row['telefono']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-users'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php
$conn->close();
?>
