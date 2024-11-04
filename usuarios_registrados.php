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
    <link rel="stylesheet" href="CSS/usuario_registrado.css">

</head>

<body>

    <div class="wrapper">
        <aside class="sidebar">
                <nav>
                    <center><h1>Mi Biblioteca</h1></center>
                    <ul>
                        <li><a href="gestionar_prestamos.php">Préstamos</a></li>
                        <li><a href="libros_por_categoria.php">Libros</a></li>
                        <li><a href="usuarios_registrados.php">Personas Registradas</a></li>
                        <br>
                        <li>
                            <a href="administrador.php">
                                <img src="img/retorno.png" alt="Volver" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
                                Volver
                            </a>
                        </li>
                    </ul>
                </nav>
                <br>
                <br>
                <br>
                <br><br><br><br>
                <img src="imagenes/logoazul.jpg" alt="Usuario" style="width: 200px; height: 200px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); object-fit: cover;">
        </aside>
    
    
        <div class="conteiner">
            
            <header>
                <h1>Usuarios Registrados</h1>
                <div class="user-notification">
                    <span>Administrador</span>
                    <img src="IMG/usuario.png" alt="Usuario" class="icon" width="30" height="30">
                </div>
            </header>
                
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
    
    </div>

</body>

</html>

<?php
$conn->close();
?>
